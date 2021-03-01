DROP TABLE IF EXISTS Person;
DROP TABLE IF EXISTS Organization;
DROP TABLE IF EXISTS Award;
DROP TABLE IF EXISTS Affiliation;
DROP TABLE IF EXISTS Affiliated;

CREATE TABLE Person(id varchar(5) PRIMARY KEY, givenName varchar(50), familyName varchar(50), gender varchar(10), birthDate date, city varchar(50), country varchar(50));
CREATE TABLE Organization(id varchar(5) PRIMARY KEY, orgName varchar(50), foundedDate date, foundedCity varchar(50), foundedCountry varchar(50));
CREATE TABLE Award(id int PRIMARY KEY, laureateID varchar(5), year varchar(4), category varchar(20), sortOrder varchar(2), portion varchar(5), dateAwarded date, prizeStatus varchar(10), motivation varchar(200), prizeAmount int);
CREATE TABLE Affiliation(id int PRIMARY KEY, name varchar(50), city varchar(50), country varchar(50));
CREATE TABLE Affiliated(affiliateID int, awardID int);

LOAD DATA LOCAL INFILE './person.del' INTO TABLE Person
FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './org.del' INTO TABLE Organization
FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './award.del' INTO TABLE Award
FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './affiliation.del' INTO TABLE Affiliation
FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';
LOAD DATA LOCAL INFILE './affiliated.del' INTO TABLE Affiliated
FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"';