create table news
(
    id int auto_increment
        primary key,
    title varchar(128) not null,
    created timestamp default CURRENT_TIMESTAMP null,
    content text not null,
    updated timestamp null
);

