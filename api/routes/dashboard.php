<?php

use Middlewares\JwtAuthMiddleware;
use Illuminate\Database\Capsule\Manager as DB;

$app->get('/dashboard/musics', function ($request, $response) {

    $pdo = DB::connection()->getPdo();

    // 1. Uso geral
    $generalUsage = $pdo->query("
        SELECT m.id, m.name, COUNT(ea.id) AS total_usage
        FROM musics m
        LEFT JOIN event_activities ea ON ea.music_id = m.id
        GROUP BY m.id, m.name
        ORDER BY total_usage DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Última vez usada
    $lastUsed = $pdo->query("
        SELECT m.id, m.name, MAX(e.start_at) AS last_used_at
        FROM musics m
        LEFT JOIN event_activities ea ON ea.music_id = m.id
        LEFT JOIN events e ON e.id = ea.event_id
        GROUP BY m.id, m.name
        ORDER BY last_used_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 3. Top últimos 8 eventos
    $topLast8 = $pdo->query("
        SELECT m.id, m.name, COUNT(ea.id) AS usage_last_events
        FROM event_activities ea
        JOIN (SELECT id FROM events ORDER BY start_at DESC LIMIT 8) AS last_events
            ON last_events.id = ea.event_id
        JOIN musics m ON m.id = ea.music_id
        GROUP BY m.id, m.name
        ORDER BY usage_last_events DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 4. Summary
    $uniqueUsed = $pdo->query("
        SELECT COUNT(DISTINCT music_id) AS unique_musics_used
        FROM event_activities
    ")->fetch(PDO::FETCH_ASSOC);

    $totalPlays = $pdo->query("
        SELECT COUNT(*) AS total_music_plays
        FROM event_activities
    ")->fetch(PDO::FETCH_ASSOC);

    $mostUsed = $pdo->query("
        SELECT m.name, COUNT(*) AS total_usage
        FROM event_activities ea
        JOIN musics m ON m.id = ea.music_id
        GROUP BY m.id
        ORDER BY total_usage DESC
        LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);

    // JSON final
    $payload = [
        "generalUsage" => $generalUsage,
        "lastUsed" => $lastUsed,
        "topLast8Events" => $topLast8,
        "summary" => [
            "unique_musics_used" => $uniqueUsed['unique_musics_used'],
            "total_music_plays" => $totalPlays['total_music_plays'],
            "most_used" => $mostUsed
        ]
    ];

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader("Content-Type", "application/json");

})->addMiddleware(new JwtAuthMiddleware());

$app->get('/dashboard/events', function ($request, $response) {

    $user = $request->getAttribute("user");
    //print_r($user->getId()); die();
    $userId = $user->getId();

    $pdo = DB::connection()->getPdo();

    $futureEvents = $pdo->prepare("
        SELECT 
            e.id, e.name, e.start_at, e.end_at,
            st.name AS scale_type,
            sm.role, sm.status
        FROM scales s
        JOIN scale_members sm ON sm.scale_id = s.id
        JOIN events e ON e.id = s.event_id
        LEFT JOIN scale_types st ON st.id = s.scale_type_id
        WHERE sm.user_id = :id
        AND e.start_at >= NOW()
        ORDER BY e.start_at ASC
        LIMIT 10
    ");
    $futureEvents->execute(['id' => $userId]);
    $future = $futureEvents->fetchAll(PDO::FETCH_ASSOC);

    $totalEvents = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM scales s
        JOIN scale_members sm ON sm.scale_id = s.id
        JOIN events e ON e.id = s.event_id
        WHERE sm.user_id = :id
        AND e.start_at >= NOW()
    ");
    $totalEvents->execute(['id' => $userId]);
    $totalAgendados = $totalEvents->fetch(PDO::FETCH_ASSOC)['total'];

    $eventsMonth = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM scales s
        JOIN scale_members sm ON sm.scale_id = s.id
        JOIN events e ON e.id = s.event_id
        WHERE sm.user_id = :id
        AND MONTH(e.start_at) = MONTH(CURRENT_DATE())
        AND YEAR(e.start_at) = YEAR(CURRENT_DATE())
    ");
    $eventsMonth->execute(['id' => $userId]);
    $eventsNesteMes = $eventsMonth->fetch(PDO::FETCH_ASSOC)['total'];

    $eventsWeek = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM scales s
        JOIN scale_members sm ON sm.scale_id = s.id
        JOIN events e ON e.id = s.event_id
        WHERE sm.user_id = :id
        AND YEARWEEK(e.start_at, 1) = YEARWEEK(CURDATE(), 1)
    ");
    $eventsWeek->execute(['id' => $userId]);
    $eventsNestaSemana = $eventsWeek->fetch(PDO::FETCH_ASSOC)['total'];

    $payload = [
        "summary" => [
            "agendados" => $totalAgendados,
            "mes_atual" => $eventsNesteMes,
            "semana_atual" => $eventsNestaSemana
        ],
        "nextEvents" => $future
    ];

    $response->getBody()->write(json_encode($payload));
    return $response->withHeader("Content-Type", "application/json");

})->addMiddleware(new JwtAuthMiddleware());
