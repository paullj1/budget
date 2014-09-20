drop table if exists budget;
create table budget(
  id       INTEGER PRIMARY KEY AUTOINCREMENT,
  month    INTEGER NOT NULL CHECK(month > 0) CHECK(month < 13),
  year     INTEGER NOT NULL CHECK(year > 2012),
  category INTEGER NOT NULL,
  amount   REAL NOT NULL CHECK(amount > 0), 
  note     CHAR(256)
);

drop table if exists categories;
create table categories(
  id	   INTEGER PRIMARY KEY AUTOINCREMENT,
  category CHAR(50) NOT NULL,
  goal     REAL DEFAULT 0.00 CHECK(goal > 0),
  visible  INTEGER NOT NULL CHECK(visible >= 0) CHECK(visible <= 1)
); 

drop table if exists users;
create table users(
  id       INTEGER PRIMARY KEY AUTOINCREMENT,
  username CHAR(50) NOT NULL,
  pass     CHAR(128) NOT NULL, -- 512 char hash
  salt     CHAR(128) NOT NULL -- 128 random hash
);

drop table if exists login_attempts;
create table login_attempts(
  user_id INTEGER NOT NULL,
  time   VARCHAR(30) NOT NULL
);
