create table OTAUpgrade
(
    id          int auto_increment,
    fromVersion bigint      not null,
    toVersion   bigint      not null,
    model       varchar(30) not null,
    commandValue varchar(100),
    constraint OTAUpgrade_id_uindex
        unique (id)
);

alter table OTAUpgrade
    add primary key (id);

CREATE TABLE OTAUpgradeUnit
(
    id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    unitId int NOT NULL,
    fromVersion int NOT NULL,
    toVersion int NOT NULL,
    otaUpgradeId int NOT NULL,
    commandId int NOT NULL,
    startedAt datetime DEFAULT now() NOT NULL,
    userId int NOT NULL,
    completed bool DEFAULT false  NOT NULL,
    completedAt datetime,
    deleted bool DEFAULT false NOT NULL
);

INSERT INTO OTAUpgrade (id, fromVersion, toVersion, model) VALUES (1, 710328, 710330, 'STX70');
INSERT INTO OTAUpgrade (id, fromVersion, toVersion, model) VALUES (2, 710328, 710330, 'STX71');
INSERT INTO OTAUpgrade (id, fromVersion, toVersion, model) VALUES (3, 710328, 710330, 'STX71F');


INSERT INTO HealthCheckReport (healthCheckReportId, vehicleId, unitId, diagnosticData, timestamp) VALUES (2166270, 5291, 36505, '050420010F99000C142828201115034403FF00027C020302310B22000101', now());
select * from OTAUpgrade;

update Unit set appId =  18710328 where appId !=18710328;



