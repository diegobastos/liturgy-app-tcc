create schema liturgy_platform;
use liturgy_platform;

create table if not exists users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash varchar(255) not null,
    salt VARCHAR(255) NOT NULL,
    active tinyint default 0,
    roles TEXT,
    timezone varchar(255)
);

create table if not exists events (
    id bigint not null auto_increment PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,    
    name varchar(255) not null,
    start_at datetime,
    end_at datetime
);

create table if not exists musics (
    id bigint not null auto_increment PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,    
    name varchar(255) not null,
    author varchar(255) not null,
    tone varchar(255) not null,
    time_signature varchar(10) not null,
    lyrics TEXT null,
    music_score TEXT null,
    references_links TEXT null
);

create table if not exists event_activities (
    id bigint auto_increment primary key,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,     
    event_id bigint not null,
    music_id bigint null,
    position int default 0,
    notes text,
    foreign key (event_id) references events(id) on delete cascade,
    foreign key (music_id) references musics(id) on delete cascade
);

create table if not exists scale_types (
    id bigint auto_increment primary key,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,      
    name varchar(100) not null unique,         -- nome legível (ex: Louvor, Ensaio, Projeto Social)
    slug varchar(50) not null unique,          -- identificador técnico (ex: WORSHIP, REHEARSAL)
    description text null
);

insert into scale_types (uuid, name, slug, description) values
((uuid()), 'Louvor', 'WORSHIP', 'Escala de músicos e ministros de louvor'),
((uuid()),'Aula Infantil', 'CHILDREN_CLASS', 'Escala de professores e ajudantes das classes infantis'),
((uuid()),'Cozinha', 'FOOD', 'Escala de voluntários responsáveis pela alimentação'),
((uuid()),'Visitação', 'VISITATION', 'Escala de visitas a membros e idosos'),
((uuid()),'Limpeza', 'CLEANING', 'Escala de manutenção e limpeza do templo'),
((uuid()),'Ensaio', 'REHEARSAL', 'Escala de ensaios de ministérios'),
((uuid()),'Projeto Social', 'SOCIAL_PROJECT', 'Escala de ações e projetos sociais'),
((uuid()),'Genérica', 'GENERIC', 'Escala de uso geral');

create table if not exists scales (
    id bigint auto_increment primary key,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    start_at DATETIME NULL,
    end_at DATETIME NULL,      
    event_id bigint null,
    scale_type_id bigint,
    notes text,    
    foreign key (event_id) references events(id) on delete cascade,
    foreign key (scale_type_id) references scale_types(id) on delete set null
);

create table if not exists scale_members (
    id bigint auto_increment primary key,
    uuid CHAR(36) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,    
    scale_id bigint not null,
    user_id bigint not null,
    role varchar(100), -- ex: vocal, teclado, bateria
    status enum('PENDING', 'CONFIRMED','REPLACED','ABSENT') default 'PENDING',
    foreign key (scale_id) references scales(id) on delete cascade,
    foreign key (user_id) references users(id) on delete cascade
);
