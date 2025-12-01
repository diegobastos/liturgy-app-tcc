<?php
namespace Src\Domain\Event;

use Illuminate\Database\Eloquent\Model;
use Src\Domain\Music\Music;

final class EventActivity extends Model
{
    protected $table = 'event_activities';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'event_id',
        'music_id',
        'position',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id');
    }
}
