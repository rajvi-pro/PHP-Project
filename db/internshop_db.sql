-- Admin table
CREATE TABLE `admin` (
    `adminId` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL COMMENT 'hashed password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`adminId`, `username`, `email`, `password`) VALUES
(5, 'admin', 'admin@gmail.com', '$2y$10$S9SY/a32gq.ZyDIMg2Enn.gvXQvdmXllxgde..dkGYYKFHFmxHgHq');


-- Categories table
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL,
    `created_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status` ENUM('active','inactive') DEFAULT 'active',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `category_name`, `created_on`, `status`, `is_deleted`) VALUES
(1, 'Information Technology', '2025-10-11 06:14:20', 'active', ''),
(2, 'Business Management', '2025-11-09 10:12:03', 'active', ''),
(3, 'Humanities', '2025-10-10 08:38:06', 'active', ''),
(4, 'Pharmacy', '2025-10-10 08:39:10', 'active', ''),
(5, 'Doctor', '2025-11-10 14:08:44', 'active', ''),
(6, 'Engineering', '2025-11-10 13:55:20', 'active', ''),
(7, 'Engineering', '2025-11-11 04:37:18', 'inactive', '');


-- Employer table
DROP TABLE IF EXISTS `employer_info`;
CREATE TABLE `employer_info` (
    `eid` INT NOT NULL AUTO_INCREMENT,
    `emp_fname` VARCHAR(100) NOT NULL,
    `emp_lname` VARCHAR(100) NOT NULL,
    `emp_phone` VARCHAR(15) NOT NULL,
    `emp_email` VARCHAR(50) NOT NULL UNIQUE,
    `emp_pass` VARCHAR(255) NOT NULL COMMENT 'hashed password',
    `com_name` VARCHAR(50) NOT NULL,
    `com_link` VARCHAR(50),
    'com_address' VARCHAR(50),
    `com_about` VARCHAR(500),
    `com_dp_path` VARCHAR(100),
    `status` VARCHAR(15) NOT NULL DEFAULT 'unverified',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `employer_info` (`eid`, `emp_fname`, `emp_lname`, `emp_phone`, `emp_email`, `emp_pass`, `com_name`, `com_link`, `com_about`, `com_address`, `com_dp_path`, `status`, `is_deleted`) VALUES
(2, 'Raajvi', 'virwani', '9512114772', 'rajvivirwani@gmail.com', '123456', 'MegMa', 'www.megma.com', 'we preffere accureacy', '6th floor , Rajhans complex, oppo bank', 'uploads/com/2_raajvi_virwani_1762754579.jpg', 'active', 0),
(6, 'Vency', 'Chawda', '9874563210', 'vencyc06@gmail.com', '098765', 'PrimeCare', 'www.primecare.com', 'nice', 'SG highway, Ahmedabad', '', 'active', 0),
(8, 'aayusha', 'virwani', '9998366002', 'aayusha@gmail.com', '$2y$10$teCOZ5zT1S60X.vvODWDmOQJ4UIEIwkp6OMDY/Juo867e3NpdemkO', '', '', '', '', '', 'pending', 1),
(9, 'aayusha', 'virwani', '9998366002', 'aa0@gmail.com', '$2y$10$rYAwv2W09ZFOdMTZ.h6NUuOJYEuS4Ms0LnqusYm8MuycuptrDe/..', '', '', '', '', '', 'active', 1),
(10, 'Parul', 'Patel', '8523697410', 'parul@gmail.com', '$2y$10$HQqGNBR30OLU.1JRt9Tvqe2GhDNqu9wcnXPf1YqXUNA9seUPXxkoq', 'TTEC', 'www.ttec.com', 'This is perfection', '23 , global complex, oppo SBI bank.', 'uploads/com/10_parul_patel_1762752853.png', 'active', 0),
(11, 'Rahul', 'Roy', '9632587410', 'rahul@gmail.com', '$2y$10$MleoEgVD9gaGiVvWLC4MBur842h/nPn.bhLnwUnGfTTWVbiskPJta', 'BTTEC', 'www.bttec.com', 'we are very passionate in our work', '5th floor, Rajhans Complex , oppo Trilok appartment', 'uploads/com/11_rahul_roy_1762753454.png', 'active', 1);


-- Internship details table
DROP TABLE IF EXISTS `internship_details`;
CREATE TABLE `internship_details` (
    `ip_id` INT NOT NULL AUTO_INCREMENT,
    `eid` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `created_on` DATE NOT NULL,
    `action` VARCHAR(10),
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `category` VARCHAR(50),
    `profile` VARCHAR(50),
    `skills` VARCHAR(100),
    `location` VARCHAR(50),
    `openings` INT,
    `start_date` DATE,
    `apply_by` DATE,
    `duration` INT,
    `role1` VARCHAR(200),
    `role2` VARCHAR(200),
    `role3` VARCHAR(200),
    `role4` VARCHAR(200),
    `role5` VARCHAR(200),
    `stipend` INT,
    `perk1` VARCHAR(30),
    `perk2` VARCHAR(30),
    `perk3` VARCHAR(30),
    `perk4` VARCHAR(30),
    `q1` VARCHAR(250),
    `q2` VARCHAR(250),
    `q3` VARCHAR(250),
    `q4` VARCHAR(250),
    `status` VARCHAR(15) NOT NULL DEFAULT 'unverified',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`ip_id`),
    CONSTRAINT `fk_ID_eid` FOREIGN KEY (`eid`) REFERENCES `employer_info` (`eid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `internship_details` (`ip_id`, `eid`, `title`, `created_on`, `action`, `category`, `profile`, `skills`, `location`, `openings`, `start_date`, `apply_by`, `duration`, `role1`, `role2`, `role3`, `role4`, `role5`, `stipend`, `perk1`, `perk2`, `perk3`, `perk4`, `q1`, `q2`, `q3`, `q4`, `is_deleted`, `status`) VALUES
(36, 8, 'Research Intern internship at Delhi', '2025-11-09', 'Posted', 'Information Technology', 'Research Intern', 'Time management, Problem-solving', 'Delhi', 2, '2025-12-02', '2025-11-09', 1, 'Writing and Testing Code', '2.Collaboration', '3.Participating in Meetings', '', '', 5000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', '', '', '', 1, 'inactive'),
(37, 8, 'Research Intern internship at Banglore', '2025-11-09', 'Posted', 'Information Technology', 'Research Intern', 'Time management, Problem-solving', 'Banglore', 2, '2025-12-02', '2025-11-09', 1, 'Writing and Testing Code', '2.Collaboration', '3.Participating in Meetings', '', '', 5000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', '', '', '', 0, 'active'),
(38, 9, 'Research Intern internship at Delhi', '2025-11-09', 'Posted', 'Business Management', 'Research Intern', 'Java , HTML , PHP', 'Delhi', 50, '2025-11-12', '2025-11-01', 1, '1.Assisting with Business Operations', '2.Learning Organizational Processes', '3.Report Writing', '', '', 10000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', '', '', '', 0, 'active'),
(39, 10, 'Public Policy Intern internship at Kolkata', '2025-11-10', 'Posted', 'Engineering', 'Public Policy Intern', 'Research Skills, Critical Thinking, Analytical Skills', 'Kolkata', 10, '2026-01-01', '2025-12-20', 6, '1.Assisting with Business Operations', '2.Learning Organizational Processes', '3.Report Writing', '', '', 8000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', 'What are your strength?', '', '', 0, 'active'),
(40, 10, 'Public Policy Intern internship at Kolkata', '2025-11-10', 'Posted', 'Pharmacy', 'Public Policy Intern', 'Research Skills , Knowledge of all Medicines', 'Kolkata', 5, '2026-02-01', '2025-12-01', 10, '1.Assisting with Business Operations', '2.Learning Organizational Processes', '3.Report Writing', '', '', 10000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', 'What are your strength?', '', '', 0, 'active'),
(41, 10, 'Management Trainee internship at Mumbai', '2025-11-10', 'Posted', 'Business Management', 'Management Trainee', 'Communication, Problem Solving, Leadership,', 'Mumbai', 20, '2026-02-02', '2025-12-30', 3, '1.Assisting with Business Operations', '2.Learning Organizational Processes', '3.Report Writing', '', '', 3000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', 'What are your strength?', '', '', 0, 'active'),
(42, 2, 'MegMa Care internship at Mumbai', '2025-11-10', 'Posted', 'Humanities', 'MegMa Care', 'Communication,perfection', 'Mumbai', 10, '2025-12-01', '2025-11-20', 2, '1.Assisting with Business Operations', '2.Learning Organizational Processes', '3.Report Writing', '', '', 5000, 'Certificate of completion.', 'Letter of Recommendation.', 'Flexible work hours.', '5 days a week.', 'Why should be you hired for this role?', 'What are your strength?', '', '', 0, 'active');


-- Student table
DROP TABLE IF EXISTS `student_info`;
CREATE TABLE `student_info` (
  `sid` int(11) NOT NULL,
  `stu_fname` varchar(30) NOT NULL,
  `stu_lname` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `city` varchar(30) NOT NULL,
  `stu_email` varchar(100) NOT NULL,
  `stu_phone` varchar(15) NOT NULL,
  `stu_pass` varchar(100) NOT NULL,
  `resume_path` varchar(100) NOT NULL,
  `dp_path` varchar(100) NOT NULL,
  `profile_completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `reset_token` varchar(100) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `student_info` (`sid`, `stu_fname`, `stu_lname`, `gender`, `city`, `stu_email`, `stu_phone`, `stu_pass`, `resume_path`, `dp_path`, `profile_completed`, `is_deleted`, `status`, `reset_token`, `token_expiry`) VALUES
(1, 'Raajvi', 'virwani', 'Female', 'Ahmedabad', 'rajvivirwani19@gmail.com', '9512114772', '$2y$10$7m4rL4jbT4ce.z31n5iS4.Sx58MMG2n4vadJHeRGDtMWLzsFOohKW', 'uploads/resume/1raajvivirwani1756055214.pdf', 'uploads/dp/1raajvivirwani1756055178.png', 0, 0, 'active', '0267a8ac4a6787a93550016f0adf1627', '2025-11-08 21:32:41'),
(2, 'Raajvi', 'virwani', '', '', 'kvirwani@gmail.com', '9998366002', '123456', '', '', 0, 1, 'active', NULL, NULL),
(6, 'jyoti', 'virwani', '', '', 'jyoti14@gmail.com', '9327338419', '$2y$10$iC33dEcNVJXRvqsfX87Pqecg3S1t2cCPf8vbXpYLiDyZOhPzTHBY.', '', '', 0, 0, 'active', NULL, NULL),
(7, 'jyoti', 'virwani', '', '', 'jyoti@gmail.com', '9327338419', '123456', '', '', 0, 0, 'active', NULL, NULL),
(8, 'Vency', 'Chawda', '', '', 'vencyc0@gmail.com', '9512114772', '$2y$10$BeG4pYHuE.pXeP3Rrnd9Qe0NlLXFb6jAeAjYsKMXQ9StjUeQBrGq6', '', 'uploads/dp/8vencychawda1762626894.jpg', 0, 1, '', NULL, NULL),
(9, 'Rajvi', 'virwani', 'Female', 'maniinagar', 'rajvivi@gmail.com', '9512114772', '$2y$10$J7E10Akg8FvUCyeciodeQOtY8hTEA4KW1RM//OQ1XnUEOcKNG0uiS', '', 'uploads/dp/dp_9_1762712627.png', 1, 0, 'active', NULL, NULL),
(10, 'Kajal', 'virwani', 'Female', 'Mumbai', 'kv@gmail.com', '9512114772', '$2y$10$G081BOROod.1lRC.fKR4ROQlMMXGbrL/uCpHYlpHDdDcRVcVk0rta', 'uploads/resume/10kajalvirwani1762752579.pdf', 'uploads/dp/dp_10_1762752512.jpg', 0, 0, 'active', NULL, NULL),
(11, 'Payal', 'virwani', 'Female', 'Chennai', 'payal@gmail.com', '9512114700', '$2y$10$epyg5u8mlfAgCa19/TRGKujARYobTGJtDlkhMDJxwtuHWUaRlR1SS', 'uploads/resume/11payalvirwani1762754906.pdf', 'uploads/dp/dp_11_1762754869.jpg', 0, 0, 'active', NULL, NULL);

-- Applications table
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
    `appid` INT NOT NULL AUTO_INCREMENT,
    `ip_id` INT NOT NULL,
    `eid` INT NOT NULL,
    `sid` INT NOT NULL,
    `applied_on` DATE NOT NULL,
    `q1` VARCHAR(250),
    `q2` VARCHAR(250),
    `q3` VARCHAR(250),
    `q4` VARCHAR(250),
    `status` VARCHAR(15) NOT NULL DEFAULT 'New',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`appid`),
    CONSTRAINT `fk_APP_ip_id` FOREIGN KEY (`ip_id`) REFERENCES `internship_details` (`ip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_APP_eid` FOREIGN KEY (`eid`) REFERENCES `employer_info` (`eid`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_APP_sid` FOREIGN KEY (`sid`) REFERENCES `student_info` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `applications` (`appid`, `ip_id`, `eid`, `sid`, `applied_on`, `q1`, `q2`, `q3`, `q4`, `status`, `is_deleted`) VALUES
(6, 38, 9, 1, '2025-11-10', 'nice', '', '', '', 'New', 0),
(7, 42, 2, 1, '2025-11-10', 'fghuh', 'sdhujj', '', '', 'Hired', 0);


-- Bookmarks table
DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
    `bid` INT NOT NULL AUTO_INCREMENT,
    `sid` INT NOT NULL,
    `ip_id` INT NOT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`bid`),
    CONSTRAINT `fk_BK_sid` FOREIGN KEY (`sid`) REFERENCES `student_info` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_BK_ip_id` FOREIGN KEY (`ip_id`) REFERENCES `internship_details` (`ip_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Feedback table
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
    `fid` INT NOT NULL AUTO_INCREMENT,
    `sid` INT NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `feedback` VARCHAR(500),
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`fid`),
    CONSTRAINT `fk_FB_sid` FOREIGN KEY (`sid`) REFERENCES `student_info` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
