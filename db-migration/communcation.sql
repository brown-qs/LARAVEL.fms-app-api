create table CommunicationType
(
    typeId int auto_increment,
    name varchar(55) null,
    active boolean default 1 null,
    hidden boolean default 0 null,
    created_at datetime default now(),
    updated_at datetime default now(),
    constraint CommunicationType_pk
        primary key (typeId)
);

create unique index CommunicationType_name_uindex
    on CommunicationType (name);

create table CommunicationPreferences
(
    preferencesId int auto_increment,
    userId int not null,
    typeId int not null,
    email boolean default 1 null,
    sms boolean default 1 null,
    push boolean default 1 null,
    created_at datetime default now(),
    updated_at datetime default now(),
    constraint CommunicationPreferences_pk
        primary key (preferencesId)
);