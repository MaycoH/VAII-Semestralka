-- auto-generated definition
create schema covidinfo collate utf8mb4_general_ci;

grant alter, alter routine, create, create routine, create temporary tables, create view, delete, drop, event, execute, index, insert, lock tables, references, select, show view, trigger, update on covidinfo.* to db_user;

USE covidinfo;
# Vytvorenie tabuľky "users"
create table if not exists users
(
    id       int auto_increment primary key,
    name     varchar(255)                null,
    password varchar(255)                null,
    role     varchar(255) default 'User' not null
);
# Vytvorenie tabuľky "actuality"
create table if not exists actuality
(
    id        int auto_increment primary key,
    title     varchar(255) null,
    imagePath varchar(255) null,
    perex     varchar(255) null,
    text      text         null,
    author_id int          null,
    constraint actuality_author_id__fk
        foreign key (author_id) references users (id)
);
# Vytvorenie tabuľky "comments"
create table if not exists comments
(
    id              int auto_increment primary key,
    comment         varchar(255) null,
    actuality_id    int null,
    author_id       int null,
    constraint comments_actuality_id__fk
        foreign key (actuality_id) references actuality (id),
    constraint comments_author_id__fk
        foreign key (author_id) references users (id)
);
# Vytvorenie tabuľky "events"
create table if not exists events
(
    id               int auto_increment primary key,
    startTime        datetime     null,
    endTime          datetime     null,
    place            varchar(255) null,
    eventDescription text         null
);
