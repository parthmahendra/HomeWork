create table Chores
(
    choreID integer
        primary key,
    userID integer,
    houseID integer,
    description Text,
    choreFrequency integer,
    firstDate integer,
    choreName text,
    generatedTill int
);

create table House
(
    houseID INTEGER
        primary key,
    name text,
    passwordHash text,
    generatedTill integer,
    dateCreated text
);

create table Members
(
    relationshipID integer
        primary key,
    userID integer,
    houseID integer
);

create table OneTimeTasks
(
    oneTimeTaskID integer
        primary key,
    userID integer,
    houseID integer,
    description Text,
    taskDate integer,
    taskName text,
    complete integer default 0
);

create table Task
(
    taskID integer
        primary key,
    houseID integer,
    choreID integer,
    dueDate integer,
    memberID integer,
    complete integer default 0
);

create table Users
(
    userID integer
        primary key,
    email text,
    username text,
    passwordHash text,
    dyslexiaMode int default 0
);



