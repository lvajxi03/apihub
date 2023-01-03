CREATE DATABASE apihub;

USE apihub;

CREATE TABLE credentials
(
	username CHAR(255) NOT NULL,
	password CHAR(255) NOT NULL,
	primary key pk(username)
);

CREATE TABLE auth_tokens
(
	data DATE NOT NULL,
	token char(255) NOT NULL,
	primary key pk(token)
);

CREATE TABLE temperatura
(
	data DATE not null,
	godzina TIME not null,
	pomieszczenie CHAR(255) not null,
	temperatura CHAR(255) not null
);

CREATE TABLE adresy
(
	data DATE not null,
	godzina TIME not null,
	hostname CHAR(255) not null,
	ip CHAR(255) not null
);
