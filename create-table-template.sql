CREATE TABLE `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminID` int(11) NOT NULL,
  `First_name` varchar(255) NOT NULL,
  `Last_name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Bio` varchar(255) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Friends` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Joined_date` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`AdminID`),
  UNIQUE KEY `Password` (`Password`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `movie` (
  `Movie_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT 'NULL',
  `Hours` int(11) DEFAULT NULL,
  `Minutes` int(11) DEFAULT NULL,
  `Realese_date` int(11) DEFAULT NULL,
  `Genre` varchar(255) DEFAULT 'NULL',
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Movie_ID`),
  UNIQUE KEY `Movie_ID` (`Movie_ID`)
);

CREATE TABLE `movie_crew` (
  `CrewID` int(11) NOT NULL AUTO_INCREMENT,
  `Movie_ID` int(11) NOT NULL,
  `M_crew_Fname` varchar(255) NOT NULL,
  `M_crew_Lname` varchar(255) NOT NULL,
  `Marketing` varchar(255) DEFAULT 'NULL',
  `Animation` varchar(255) DEFAULT 'NULL',
  `Writers` varchar(255) DEFAULT 'NULL',
  `Directors` varchar(255) DEFAULT 'NULL',
  `Actors` varchar(255) DEFAULT 'NULL',
  `TopActors` varchar(255) DEFAULT 'NULL',
  `Composer` varchar(255) DEFAULT 'NULL',
  `Editor` varchar(255) DEFAULT 'NULL',
  `Cinematographer` varchar(255) DEFAULT 'NULL',
  PRIMARY KEY (`CrewID`),
  KEY `fk_movie_crew_movie` (`Movie_ID`),
  CONSTRAINT `fk_movie_crew_movie` FOREIGN KEY (`Movie_ID`) REFERENCES `movie` (`Movie_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL
);

CREATE TABLE movie_ratings (
    Rating_ID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Movie_ID INT NOT NULL,
    Rating INT NOT NULL,
    UNIQUE(UserID, Movie_ID)
);

CREATE TABLE comments (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    MovieID INT NOT NULL,
    Comment_Text VARCHAR(500) NOT NULL,
    Comment_Time DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE friend_requests (
    RequestID INT AUTO_INCREMENT PRIMARY KEY,
    FromUserID INT NOT NULL,
    ToUserID INT NOT NULL,
    RequestDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(FromUserID, ToUserID)
);

CREATE TABLE IF NOT EXISTS friend_requests (
    RequestID INT AUTO_INCREMENT PRIMARY KEY,
    FromUserID INT NOT NULL,
    ToUserID INT NOT NULL,
    RequestDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_request (FromUserID, ToUserID),
    FOREIGN KEY (FromUserID) REFERENCES user(UserID) ON DELETE CASCADE,
    FOREIGN KEY (ToUserID) REFERENCES user(UserID) ON DELETE CASCADE
);

CREATE TABLE friends (
    UserID INT NOT NULL,
    FriendID INT NOT NULL,
    FriendSince TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID, FriendID),
    FOREIGN KEY (UserID) REFERENCES user(UserID),
    FOREIGN KEY (FriendID) REFERENCES user(UserID)
);

