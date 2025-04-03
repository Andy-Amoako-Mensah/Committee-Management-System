create database CommitteeManagementSystem;
use CommitteeManagementSystem;
CREATE TABLE Committee (
    CommitteeID INT PRIMARY KEY AUTO_INCREMENT,
    CommitteeName VARCHAR(100) UNIQUE NOT NULL,
    CommitteeChair VARCHAR(100) NOT NULL
);

CREATE TABLE Committee_members (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    memberName VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    affiliation VARCHAR(100),
    role VARCHAR(50) NOT NULL,
    CommitteeID INT,
    FOREIGN KEY (CommitteeID) REFERENCES Committee(CommitteeID) ON DELETE CASCADE
);

CREATE TABLE Notifications (
    notificationID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT NOT NULL,
    message TEXT NOT NULL,
    dateCreated DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'Unread',
    FOREIGN KEY (memberID) REFERENCES Committee_members(userID) ON DELETE CASCADE
);

CREATE TABLE Meetings (
    meetingID INT AUTO_INCREMENT PRIMARY KEY,
    CommitteeID INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location TEXT NOT NULL,
    agenda TEXT NOT NULL,
    FOREIGN KEY (CommitteeID) REFERENCES Committee(CommitteeID) ON DELETE CASCADE
);

CREATE TABLE Documents (
    documentID INT AUTO_INCREMENT PRIMARY KEY,
    CommitteeID INT NOT NULL,
    name TEXT NOT NULL,
    filePath TEXT NOT NULL,
    uploadTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CommitteeID) REFERENCES Committee(CommitteeID) ON DELETE CASCADE
);
-- Insert data into Committee table
INSERT INTO Committee (CommitteeName, CommitteeChair) VALUES
('Finance Committee', 'Alice Johnson'),
('HR Committee', 'Bob Smith'),
('Tech Committee', 'Charlie Davis');

-- Insert data into Committee_members table
INSERT INTO Committee_members (memberName, email, username, password, affiliation, role, CommitteeID) VALUES
('John Doe', 'johndoe@example.com', 'johndoe', 'pass123', 'Finance Dept', 'Member', 1),
('Jane Doe', 'janedoe@example.com', 'janedoe', 'pass456', 'HR Dept', 'Chair', 2),
('Sam Wilson', 'samwilson@example.com', 'samwilson', 'pass789', 'Tech Dept', 'Member', 3);

-- Insert data into Notifications table
INSERT INTO Notifications (memberID, message, status) VALUES
(1, 'Meeting scheduled for next week', 'Unread'),
(2, 'New document uploaded', 'Unread'),
(3, 'Reminder: Submit your report', 'Unread');

-- Insert data into Meetings table
INSERT INTO Meetings (CommitteeID, date, time, location, agenda) VALUES
(1, '2025-04-10', '10:00:00', 'Room 101', 'Budget Planning'),
(2, '2025-04-12', '14:00:00', 'Conference Room', 'Employee Welfare Discussion'),
(3, '2025-04-15', '16:00:00', 'Zoom', 'Tech Innovations');

-- Insert data into Documents table
INSERT INTO Documents (CommitteeID, name, filePath) VALUES
(1, 'Budget_Report_2025.pdf', '/files/budget_report_2025.pdf'),
(2, 'HR_Policies.docx', '/files/hr_policies.docx'),
(3, 'Tech_Strategy.pdf', '/files/tech_strategy.pdf');

-- queries
select * from Documents;
select * from Committee_members;
select * from Notifications;
select * from Meetings;
select * from Committee;

use CommitteeManagementSystem;
delete from committee
limit 3;
select * from committee;
alter table committee 
modify CommitteeChair int ;
ALTER TABLE Committee  
ADD CONSTRAINT fk_Committee_Chair  
FOREIGN KEY (CommitteeChair)  
REFERENCES Committee_members(userID);
INSERT INTO Committee (CommitteeID, CommitteeName, CommitteeChair)  
VALUES  
(1, 'Finance Committee'),  -- Alice Johnson (userID = 1) is the Chair  
(2, 'IT Governance Committee'); -- Charlie Brown (userID = 3) is the Chair;  
delete from Committee_members
limit 3;
INSERT INTO Committee (CommitteeID, CommitteeName, CommitteeChair)  
VALUES  
(1, 'Finance Committee', NULL),  
(2, 'HR Committee', NULL),  
(3, 'Tech Committee', NULL);
INSERT INTO Committee_members (memberName, email, username, password, affiliation, role, CommitteeID)  
VALUES  
('John Doe', 'johndoe@example.com', 'johndoe', SHA2('pass123', 256), 'Finance Dept', 'Member', 1),  
('Jane Doe', 'janedoe@example.com', 'janedoe', SHA2('pass456', 256), 'HR Dept', 'Chair', 2),  
('Sam Wilson', 'samwilson@example.com', 'samwilson', SHA2('pass789', 256), 'Tech Dept', 'Member', 3);
UPDATE Committee SET CommitteeChair = 1 WHERE CommitteeID = 1;  -- Alice Johnson (Finance);
UPDATE Committee SET CommitteeChair = 2 WHERE CommitteeID = 2;  -- Bob Smith (HR);
UPDATE Committee SET CommitteeChair = 3 WHERE CommitteeID = 3;  -- Charlie Brown (Tech);
SELECT userID, memberName, CommitteeID FROM Committee_members WHERE role = 'Chair';
UPDATE Committee_members  
SET role = 'Chair' ;
SET SQL_SAFE_UPDATES = 0;
SET SQL_SAFE_UPDATES = 1;
select * from Committee;
UPDATE Committee c  
JOIN Committee_members cm ON c.CommitteeID = cm.CommitteeID  
SET c.CommitteeChair = cm.userID  
WHERE cm.role = 'Chair';
desc  Committee_members;
use  CommitteeManagementSystem;
alter table committee_members 
drop column CommitteeID;
drop table Committee;
drop table committee;
SELECT CONSTRAINT_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'committee_members' 
AND COLUMN_NAME = 'CommitteeID';
ALTER TABLE documents DROP FOREIGN KEY documents_ibfk_1;
Alter table documents drop column CommitteeID;
Alter table meetings drop column CommitteeID;
Alter table Committee_members drop column CommitteeID;
show tables;
desc notifications;




