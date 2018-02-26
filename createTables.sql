


create table users (
	UID varchar(10),
	userName varchar(50),
	pssword varchar(50),
	dateJoined date,
	isAdmin boolean,
    PRIMARY KEY (userName)
    )

create table projectsOwnership (
	projectName varchar(100) NOT NULL,
	projectDescription varchar(1000),
	startDate date NOT NULL,
	endDate date NOT NULL,
	projectID varchar(50),
	ownerName varchar(10)NOT NULL,
	targetAmount int NOT NULL CHECK (targetAmount > 0),
	progress int NOT NULL CHECK (progress >= 0 and progress <=1),
	category varchar(20),
	PRIMARY KEY (projectID, ownerName),
	FOREIGN KEY (ownerName) REFERENCES users(userName))

create table investments (
	amount int NOT NULL CHECK (amount > 0),
	dateInvested date NOT NULL,
	investmentID varchar(100),
	investorName varchar(50) NOT NULL,
	projectID varchar(50) NOT NULL,
	ownerName varchar(50) NOT NULL,
	PRIMARY KEY (investmentID),
	FOREIGN KEY (projectID, ownerName) REFERENCES projectOwnership(projectID, ownerName),
	FOREIGN KEY (investorName) REFERENCES users(userName))


INSERT INTO users(UID, userName, pssword, dateJoined, isAdmin)
values ('00001', 'Bob', '12345', date '2018-02-01', false);	

INSERT INTO users(UID, userName, pssword, dateJoined, isAdmin)
values ('00002', 'Alice', '12345', date '2018-02-02', false);	

INSERT INTO users(UID, userName, pssword, dateJoined, isAdmin)
values ('00003', 'Charles', '12345', date '2018-02-02', false);	

INSERT INTO users(UID, userName, pssword, dateJoined, isAdmin)
values ('00004', 'David', '12345', date '2018-02-03', true);	

INSERT INTO users(UID, userName, pssword, dateJoined, isAdmin)
values ('00005', 'Elton', '12345', date '2018-02-03', true);	


INSERT INTO projectsOwnership(projectName, projectDescription, startDate, endDate, projectID, ownerName, targetAmount, progress, category)
values ('Seeking funding for startup', 'new start up involved in property tech', date '2018-02-10', date '2018-03-10', '00001', 'Bob', 10000, 0, 'Start Ups Funding')

INSERT INTO projectsOwnership(projectName, projectDescription, startDate, endDate, projectID, ownerName, targetAmount, progress, category)
values ('Seeking funding for mobile app', 'mobile app called instagram', date '2018-02-15', date '2018-06-15', '00001', 'Alice', 50000, 0, 'Mobile App Funding');

INSERT INTO projectsOwnership(projectName, projectDescription, startDate, endDate, projectID, ownerName, targetAmount, progress, category)
values ('Seeking funding for building new hospital', 'TTS hospital', date '2018-02-20', date '2018-07-20', '00002', 'Bob', 1000000, 0, 'Others');

INSERT INTO investments(armount, dateInvested, investorName, projectID, ownerName)
values (500, date '2018-02-23', 'David', '00001', 'Bob')

INSERT INTO investments(armount, dateInvested, investorName, projectID, ownerName)
values (500, date '2018-02-23', 'Bob', '00001', 'Alice')
	