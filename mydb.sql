-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2025 at 08:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `credit_hours` decimal(4,1) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sem_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_code`, `credit_hours`, `program_id`, `sem_no`) VALUES
(1, 'TALENT MANAGEMENT', 'MTKL5013', 3.0, 40, 1),
(2, 'MANAGING INNOVATION AND TECHNOLOGICAL CHANGE', 'MTKL5023', 3.0, 40, 1),
(3, 'TECHNOLOGY AND OPERATIONS MANAGEMENT', 'MTKL5033', 3.0, 40, 1),
(4, 'TECHNOPRENEURIAL LEADERSHIP', 'MTKL5043', 3.0, 40, 1),
(5, 'ACCOUNTING AND FINANCE FOR MANAGERS', 'MTKL5053', 3.0, 40, 2),
(6, 'ECONOMICS FOR MANAGERS', 'MTKL5063', 3.0, 40, 2),
(7, 'PROCUREMENT AND LOGISTICS MANAGEMENT', 'MTKL5073', 3.0, 40, 2),
(8, 'DIGITAL MARKETING AND TECHNOLOGY', 'MTKL5083', 3.0, 40, 2),
(9, 'STRATEGIC TECHNOLOGY MANAGEMENT', 'MTKL5093', 3.0, 40, 2),
(10, 'RESEARCH METHODOLOGY', 'MPSW5013', 3.0, 40, 3),
(11, 'GLOBAL MANAGEMENT AND ORGANIZATIONAL BEHAVIOUR', 'MTKL5103', 3.0, 40, 3),
(12, 'RESEARCH PROJECT', 'MTKL5117', 7.0, 40, 1),
(13, 'RESEARCH METHODOLOGY', 'MPSW5013', 3.0, 77, 1),
(14, 'ENTREPRENUERSHIP', 'MPSW5063', 3.0, 77, 1),
(15, 'FUNDAMENTAL OF DATA SCIENCE', 'MAXL5113', 3.0, 77, 1),
(16, 'BIG DATA MANAGEMENT', 'MTDL5123', 3.0, 77, 1),
(17, 'APPLIED STATISCAL METHODS', 'MAXL5133', 3.0, 77, 1),
(18, 'APPLIED MACHINE LEARNING', 'MAXL5143', 3.0, 77, 2),
(19, 'BIG DATA ANALYTICS AND VISUALIZATION', 'MAXL5153', 3.0, 77, 2),
(20, 'MODELLING AND DECISION MAKING', 'MTDL5163', 3.0, 77, 2),
(21, 'SOCIAL MEDIA ANALYTICS (ELECTIVE)', 'MTDL5233', 3.0, 77, 2),
(22, 'HEALTHCARE ANALYTICS (ELECTIVE)', 'MTDL5253', 3.0, 77, 2),
(23, 'MANUFACTURING ANALYTICS (ELECTIVE)', 'MTDL5223', 3.0, 77, 2),
(24, 'CUSTOMER AND FINANCIAL ANALYTICS (ELECTIVE)', 'MTDL5273', 3.0, 77, 2),
(25, 'PROJECT 1', 'MTPL5314', 0.0, 77, 2),
(26, 'PROJECT 2', 'MTPL5326', 3.5, 77, 2);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `designation` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `email`, `phone`, `designation`, `program_id`) VALUES
(1, 'NORHIDAYAH BINTI MOHAMAD', 'norhidayah@utem.edu.my', NULL, 'instructor', 40),
(2, 'MURZIDAH BINTI AHMAD MURAD', 'murzidah@utem.edu.my', NULL, 'instructor', 40),
(3, 'NURULIZWA BINTI ABDUL RASHID', 'nurulizwa@utem.edu.my', NULL, 'instructor', 40),
(4, 'MOHD AMIN BIN MOHAMAD', NULL, NULL, 'instructor', 40),
(5, 'NUSAIBAH BINTI MANSOR', NULL, NULL, 'instructor', 40),
(6, 'HAZMILAH BINTI HASAN', NULL, NULL, 'instructor', 40),
(7, 'AMIR BIN ARIS', NULL, NULL, 'instructor', 40),
(8, 'NOR AZAH BINTI ABDUL AZIZ', 'azahaziz@utem.edu.my', NULL, 'instructor', 40),
(9, 'GANAGAMBEGAI A/P LAXAMANAN', NULL, NULL, 'instructor', 40),
(10, 'JOHANNA BINTI ABDULLAH JAAFAR', 'johanna@utem.edu.my', NULL, 'instructor', 40),
(11, 'MISLINA BINTI ATAN@MOHD SALLEH', NULL, NULL, 'instructor', 40),
(12, 'MOHAMED A. S. DOHEIR', NULL, NULL, 'instructor', 40),
(13, 'MEHRAN DOULATABADI', NULL, NULL, 'instructor', 40),
(14, 'SITI NUR AISYAH BINTI ALIAS', 'sitinuraisyah@utem.edu.my', NULL, 'instructor', 40),
(15, 'NURUL FARHAINI BINTI RAZALI', NULL, NULL, 'instructor', 40),
(16, 'NOOR FAZILLA BINTI ABD YUSOF', 'elle@utem.edu.my', NULL, 'instructor', 77),
(17, 'NUR ZAREEN BINTI ZULKARNAIN', 'zareen@utem.edu.my', NULL, 'instructor', 77),
(18, 'SITI AZIRAH BINTI ASMAI', NULL, NULL, 'instructor', 77),
(19, 'FAUZIAH BINTI KASMIN', 'fauziah@utem.edu.my', NULL, 'instructor', 77),
(20, 'HALIZAH BINTI BASIRON', NULL, NULL, 'coordinator', 77),
(21, 'YOGAN A/L JAYA KUMAR', 'yogan@utem.edu.my', NULL, 'instructor', 77),
(22, 'SEK YONG WEE', NULL, NULL, 'instructor', 77),
(23, 'ZURAINI BINTI OTHMAN', 'zuraini@utem.edu.my', NULL, 'instructor', 77);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `faculty` varchar(100) DEFAULT NULL,
  `program_name` varchar(255) DEFAULT NULL,
  `program_code` varchar(20) DEFAULT NULL,
  `ugpg` varchar(10) DEFAULT NULL,
  `target` int(11) DEFAULT NULL,
  `achieve` int(11) DEFAULT NULL,
  `partial_accreditation` varchar(100) DEFAULT NULL,
  `full_accreditation` varchar(100) DEFAULT NULL,
  `mod_penyampaian` enum('conventional','odl') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `faculty`, `program_name`, `program_code`, `ugpg`, `target`, `achieve`, `partial_accreditation`, `full_accreditation`, `mod_penyampaian`) VALUES
(1, 'FTMK', 'Diploma in Computer Science', 'DCS', 'UG', NULL, NULL, '', '', 'conventional'),
(2, 'FTMK', 'Bachelor of Computer Science (Computer Networking) with Honours', 'BITC', 'UG', NULL, NULL, '', '', 'conventional'),
(3, 'FTMK', 'Bachelor of Computer Science (Database Management) with Honours', 'BITD', 'UG', NULL, NULL, '', '', 'conventional'),
(4, 'FTMK', 'Bachelor of Computer Science (Artificial Intelligence) with Honours', 'BITI', 'UG', NULL, NULL, '', '', 'conventional'),
(5, 'FTMK', 'Bachelor of Computer Science (Interactive Media) with Honours', 'BITM', 'UG', NULL, NULL, '', '', 'conventional'),
(6, 'FTMK', 'Bachelor of Computer Science (Software Development) with Honours', 'BITS', 'UG', NULL, NULL, '', '', 'conventional'),
(7, 'FTMK', 'Bachelor of Computer Science (Computer Security) with Honours', 'BITZ', 'UG', NULL, NULL, '', '', 'conventional'),
(8, 'FTMK', 'Bachelor of Information Technology (Game Technology) with Honours', 'BITE', 'UG', NULL, NULL, '', '', 'conventional'),
(9, 'FTMK', 'Master of Computer Science (Multimedia Computing)', 'MCSM', 'PG', NULL, NULL, '', '', 'conventional'),
(10, 'FTMK', 'Master of Computer Science (Database Technology)', 'MITD', 'PG', NULL, NULL, '', '', 'conventional'),
(11, 'FTMK', 'Master of Computer Science (Internetworking Technology)', 'MITI', 'PG', NULL, NULL, '', '', 'conventional'),
(12, 'FTMK', 'Master in Computer Science (Software Engineering)', 'MITS', 'PG', NULL, NULL, '', '', 'conventional'),
(13, 'FTMK', 'Master of Computer Science (Security Science)', 'MITZ', 'PG', NULL, NULL, '', '', 'conventional'),
(14, 'FTMK', 'Master in Mobile Software Development', 'MMSD', 'PG', NULL, NULL, '', '', 'conventional'),
(15, 'FTMK', 'Master of Technology (Data Science & Analytics)', 'MTDS', 'PG', NULL, NULL, '', '', 'conventional'),
(16, 'FTMK', 'Master of Technology in Data Science & Analytics', 'MTDL', 'PG', NULL, NULL, '', '', 'conventional'),
(17, 'FTMK', 'Master in Information and Communication Technology', 'MITA', 'PG', NULL, NULL, '', '', 'conventional'),
(18, 'FTMK', 'Doctor of Philosophy in Information and Communication Technology', 'PITA', 'PG', NULL, NULL, '', '', 'conventional'),
(19, 'FTMK', 'Doctor of Information Technology', 'PDIT', 'PG', NULL, NULL, '', '', 'conventional'),
(20, 'FTKE', 'Diploma in Electrical Engineering', 'DEL', 'UG', NULL, NULL, '', '', 'conventional'),
(21, 'FTKE', 'Bachelor of Electrical Engineering with Honours', 'BELG', 'UG', NULL, NULL, '', '', 'conventional'),
(22, 'FTKE', 'Bachelor of Mechatronics Engineering with Honours', 'BELM', 'UG', NULL, NULL, '', '', 'conventional'),
(23, 'FTKE', 'Bachelor of Electrical Engineering Technology (Industrial Power) with Honours', 'BELK', 'UG', NULL, NULL, '', '', 'conventional'),
(24, 'FTKE', 'Bachelor of Electrical Engineering Technology (Industrial Automation & Robotic) with Honours', 'BELR', 'UG', NULL, NULL, '', '', 'conventional'),
(25, 'FTKE', 'Bachelor of Electrical Engineering Technology with Honours', 'BELT', 'UG', NULL, NULL, '', '', 'conventional'),
(26, 'FTKE', 'Bachelor of Technology in Electrical Maintenance System with Honours', 'BELS', 'UG', NULL, NULL, '', '', 'conventional'),
(27, 'FTKE', 'Master of Science (M.Sc.) in Electrical Engineering', 'MEKA', 'PG', NULL, NULL, '', '', 'conventional'),
(28, 'FTKE', 'Master of Science (M.Sc.) in Mechatronics Engineering', 'MEKM', 'PG', NULL, NULL, '', '', 'conventional'),
(29, 'FTKE', 'Master of Electrical Engineering (Industrial Power)', 'MEKP', 'PG', NULL, NULL, '', '', 'conventional'),
(30, 'FTKE', 'Master of Electrical Engineering', 'MEKG', 'PG', NULL, NULL, '', '', 'conventional'),
(31, 'FTKE', 'Master of Mechatronics Engineering', 'MEKH', 'PG', NULL, NULL, '', '', 'conventional'),
(32, 'FTKE', 'Doctor of Philosophy (Ph. D)', 'PEKA', 'PG', NULL, NULL, '', '', 'conventional'),
(33, 'FTKE', 'Doctor of Engineering (D. Eng)', 'EEKA', 'PG', NULL, NULL, '', '', 'conventional'),
(34, 'FPTT', 'Bachelor of Technopreneurship', 'BTEC', 'UG', NULL, NULL, '', '', 'conventional'),
(35, 'FPTT', 'Bachelor of Technology Management (Technology Innovation)', 'BTMI', 'UG', NULL, NULL, '', '', 'conventional'),
(36, 'FPTT', 'Bachelor of Technology Management (High Technology Marketing)', 'BTMM', 'UG', NULL, NULL, '', '', 'conventional'),
(37, 'FPTT', 'Bachelor of Technology Management (Supply Chain Management & Logistics)', 'BTMS', 'UG', NULL, NULL, '', '', 'conventional'),
(38, 'FPTT', 'Master of Technovation', 'MTV', 'PG', NULL, NULL, '', '', 'conventional'),
(40, 'FPTT', 'Master of Business Administration (Technology and Innovation Management)', 'MBA', 'PG', NULL, NULL, '', '', 'odl'),
(41, 'FPTT', 'PhD in Entrepreneurship', 'PIPE', 'PG', NULL, NULL, '', '', 'conventional'),
(42, 'FPTT', 'PhD in Technology Management', 'PIPM', 'PG', NULL, NULL, '', '', 'conventional'),
(43, 'FTKEK', 'Diploma in Electronic Engineering', 'DER', 'UG', NULL, NULL, '', '', 'conventional'),
(44, 'FTKEK', 'Bachelor Of Electronic Engineering With Honours', 'BERG', 'UG', NULL, NULL, '', '', 'conventional'),
(45, 'FTKEK', 'Bachelor Of Computer Engineering Technology (Computer Systems) With Honours', 'BERC', 'UG', NULL, NULL, '', '', 'conventional'),
(46, 'FTKEK', 'Bachelor Of Electronics Engineering Technology (Industrial Electronics) With Honours', 'BERE', 'UG', NULL, NULL, '', '', 'conventional'),
(47, 'FTKEK', 'Bachelor Of Electronics Engineering Technology (Telecommunications) With Honours', 'BERT', 'UG', NULL, NULL, '', '', 'conventional'),
(48, 'FTKEK', 'Bachelor Of Electronic Engineering Technology With Honours', 'BERZ', 'UG', NULL, NULL, '', '', 'conventional'),
(49, 'FTKEK', 'Bachelor Of Technology In Industrial Electronic Automation With Honours', 'BERL', 'UG', NULL, NULL, '', '', 'conventional'),
(50, 'FTKEK', 'Bachelor Of Computer Engineering with Honours', 'BERR', 'UG', NULL, NULL, '', '', 'conventional'),
(51, 'FTKEK', 'Bachelor Of Technology In Telecommunications', 'BERW', 'UG', NULL, NULL, '', '', 'conventional'),
(52, 'FTKEK', 'Master of Science in Electronic Engineering', 'MENA', 'PG', NULL, NULL, '', '', 'conventional'),
(53, 'FTKEK', 'Master of Electronic Engineering (Electronic System)', 'MENE', 'PG', NULL, NULL, '', '', 'conventional'),
(54, 'FTKEK', 'Master of Electronic Engineering (Telecommunication System)', 'MENT', 'PG', NULL, NULL, '', '', 'conventional'),
(55, 'FTKEK', 'Master of Electronic Engineering (Computer Engineering)', 'MENC', 'PG', NULL, NULL, '', '', 'conventional'),
(56, 'FTKEK', 'Doctor of Philosophy (Electronic Engineering)', 'PEKE', 'PG', NULL, NULL, '', '', 'conventional'),
(57, 'FTKEK', 'Doctor of Philosophy (Computer Engineering)', 'PEKC', 'PG', NULL, NULL, '', '', 'conventional'),
(58, 'FTKEK', 'Doctor of Philosophy (Telecommunication Systems)', 'PENT', 'PG', NULL, NULL, '', '', 'conventional'),
(59, 'FTKIP', 'Diploma of Manufacturing Engineering', 'DMI', 'UG', NULL, NULL, '', '', 'conventional'),
(60, 'FTKIP', 'Bachelor of Manufacturing Engineering', 'BMIG', 'UG', NULL, NULL, '', '', 'conventional'),
(61, 'FTKIP', 'Bachelor of Industrial Engineering', 'BMIF', 'UG', NULL, NULL, '', '', 'conventional'),
(62, 'FTKIP', 'Bachelor of Manufacturing Engineering Technology (Product Design)', 'BMID', 'UG', NULL, NULL, '', '', 'conventional'),
(63, 'FTKIP', 'Bachelor of Manufacturing Engineering Technology (Process and Technology)', 'BMIP', 'UG', NULL, NULL, '', '', 'conventional'),
(64, 'FTKIP', 'Bachelor of Manufacturing Engineering Technology', 'BMIW', 'UG', NULL, NULL, '', '', 'conventional'),
(65, 'FTKIP', 'Bachelor of Technology in Welding', 'BMIK', 'UG', NULL, NULL, '', '', 'conventional'),
(66, 'FTKIP', 'Bachelor of Technology in Industrial Machining', 'BMIM', 'UG', NULL, NULL, '', '', 'conventional'),
(67, 'FTKIP', 'Master of Manufacturing Engineering (Advanced Materials & Processing)', 'MMFB', 'PG', NULL, NULL, '', '', 'conventional'),
(68, 'FTKIP', 'Master of Manufacturing Engineering (Manufacturing System Engineering)', 'MMFS', 'PG', NULL, NULL, '', '', 'conventional'),
(69, 'FTKIP', 'Master of Manufacturing Engineering (Industrial Engineering)', 'MMFD', 'PG', NULL, NULL, '', '', 'conventional'),
(70, 'FTKIP', 'Master of Manufacturing Engineering (Quality System Engineering)', 'MMFQ', 'PG', NULL, NULL, '', '', 'conventional'),
(71, 'FTKIP', 'Master of Science in Manufacturing Engineering', 'MMFA', 'PG', NULL, NULL, '', '', 'conventional'),
(72, 'FTKIP', 'Doctor of Philosophy (Manufacturing Engineering)', 'PMFA', 'PG', NULL, NULL, '', '', 'conventional'),
(73, 'FTKIP', 'Doctor of Engineering (Manufacturing Engineering)', 'EMFA', 'PG', NULL, NULL, '', '', 'conventional'),
(74, 'FAIX', 'Bachelor of Computer Science (Computer Security) with Honours', 'BAXZ', 'UG', NULL, NULL, '', '', 'conventional'),
(75, 'FAIX', 'Bachelor of Computer Science (Artificial Intelligence) with Honours', 'BAXI', 'UG', NULL, NULL, '', '', 'conventional'),
(76, 'FAIX', 'Master of Computer Science (Security Science)', NULL, 'PG', NULL, NULL, '', '', 'conventional'),
(77, 'FAIX', 'Master of Technology (Data Science and Analytics)', 'MAXL', 'PG', NULL, NULL, '', '', 'odl'),
(78, 'FTKM', 'Diploma in Mechanical Engineering', 'DMC', 'UG', NULL, NULL, '', '', 'conventional'),
(79, 'FTKM', 'Bachelor of Automotive Engineering with Honours', 'BMCK', 'UG', NULL, NULL, '', '', 'conventional'),
(80, 'FTKM', 'Bachelor of Mechanical Engineering with Honours', 'BMCG', 'UG', NULL, NULL, '', '', 'conventional'),
(81, 'FTKM', 'Bachelor of Mechanical Engineering Technology (Automotive Technology) with Honours', 'BMMA', 'UG', NULL, NULL, '', '', 'conventional'),
(82, 'FTKM', 'Bachelor of Mechanical Engineering Technology (Refrigeration and AirConditioning Systems) with Honours', 'BMMH', 'UG', NULL, NULL, '', '', 'conventional'),
(83, 'FTKM', 'Bachelor of Mechanical Engineering Technology (Maintenance Technology) with Honours', 'BMMM', 'UG', NULL, NULL, '', '', 'conventional'),
(84, 'FTKM', 'Bachelor of Mechanical Engineering Technology with Honours', 'BMMV', 'UG', NULL, NULL, '', '', 'conventional'),
(85, 'FTKM', 'Bachelor of Manufacturing Engineering Technology (Process and Technology) with Honours', 'BMMP', 'UG', NULL, NULL, '', '', 'conventional'),
(86, 'FTKM', 'Bachelor of Manufacturing Engineering Technology (Product Design) with Honours', 'BMMD', 'UG', NULL, NULL, '', '', 'conventional'),
(87, 'FTKM', 'Bachelor of Manufacturing Engineering Technology with Honours', 'BMMW', 'UG', NULL, NULL, '', '', 'conventional'),
(88, 'FTKM', 'Master of Mechanical Engineering (Energy Engineering)', 'MMKE', 'PG', NULL, NULL, '', '', 'conventional'),
(89, 'FTKM', 'Master of Mechanical Engineering (Automotive)', 'MMKA', 'PG', NULL, NULL, '', '', 'conventional'),
(90, 'FTKM', 'Master of Mechanical Engineering (Product Design)', 'MMKD', 'PG', NULL, NULL, '', '', 'conventional'),
(91, 'FTKM', 'Master of Mechanical Engineering', 'MMKM', 'PG', NULL, NULL, '', '', 'conventional'),
(92, 'IPTK', 'Master of Engineering Business Management', 'MIEM', 'PG', NULL, NULL, '', '', 'conventional'),
(93, 'IPTK', 'Master of Business Information Management', 'MIIM', 'PG', NULL, NULL, '', '', 'conventional');

-- --------------------------------------------------------

--
-- Table structure for table `program_details`
--

CREATE TABLE `program_details` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `date_published` date DEFAULT NULL,
  `recognition` text DEFAULT NULL,
  `prerequisite` text DEFAULT NULL,
  `PLO` text DEFAULT NULL,
  `PEO` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `feesft_local` decimal(10,2) DEFAULT NULL,
  `feesft_international` decimal(10,2) DEFAULT NULL,
  `feespt_local` decimal(10,2) DEFAULT NULL,
  `feespt_international` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_details`
--

INSERT INTO `program_details` (`id`, `program_id`, `duration`, `date_published`, `recognition`, `prerequisite`, `PLO`, `PEO`, `description`, `feesft_local`, `feesft_international`, `feespt_local`, `feespt_international`) VALUES
(1, 40, '1600 Hours 00 Minutes', NULL, 'Master of Business Administration', 'This MBA program also accepts entry through the Accreditation of Prior Experiential Learning (APEL A) pathway, providing an opportunity for individuals without formal academic qualifications to pursue postgraduate studies based on their work experience and prior learning. To be eligible through APEL A, applicants must be Malaysian citizens aged 30 years and above, possess at least STPM, diploma, or equivalent qualifications, and demonstrate significant working experience relevant to the field of study. Candidates must undergo a comprehensive APEL A assessment, including a portfolio submission and an aptitude test conducted by the Malaysian Qualifications Agency (MQA). This pathway reflects our commitment to recognising professional expertise and lifelong learning, enabling more learners to access quality education and advance in their careers.', 'PLO1: Critically evaluate and apply the principles of business administration in diverse contexts.\r\n\r\nPLO2: Design and implement innovative solutions for complex business problems using advanced methodologies\r\n\r\nPLO3: Exemplify practical leadership and management skills within the local and global business industry.\r\n\r\nPLO4: Engage in advanced social discourse in diverse and complex business environments.\r\n\r\nPLO5: Collaborate effectively and strategically with diverse teams in professional and cross-cultural settings. \r\n\r\nPLO6: Leverage digital skills responsibly and effectively as part of continuous professional development in business administration.\r\n\r\nPLO7: Analyze and interpret data using advanced numeracy skills for strategic decision-making and complex problem-solving.\r\n\r\nPLO8: Demonstrate advanced leadership capabilities and take full responsibility for managing diverse teams in challenging business environments.\r\n\r\nPLO9: Foster and maintain personal development strategies for continuous learning and adaptability in the rapidly evolving business landscape.\r\n\r\nPLO10: Develop and apply entrepreneurial skills to create and implement innovative solutions to strategic business challenges.\r\n\r\nPLO11: Uphold and advocate for professional ethics, values, and attitudes in all aspects of business administration', 'PEO1: Demonstrate mastery of theoretical and practical knowledge in business. \r\n\r\nPEO2: Demonstrate comprehensive managerial and entrepreneurial knowledge and skills to lead effectively and responsibly in different organisations \r\n\r\nPEO3: Adopt and apply a broad range of digital applications and analytical techniques competently to support business functions \r\n\r\nPEO4: Demonstrate teamwork, interpersonal communication, creativity and innovation skills\r\n\r\nPEO5: Commit and seek learning for continuous development', 'Our MBA program is now available in Open and Distance Learning (ODL) mode, specially designed for ambitious professionals who seek to elevate their careers without putting them on hold. With the flexibility to study anytime, anywhere this mode empowers working adults to gain a prestigious business education that fits seamlessly into their busy schedules. The ODL platform combines the best of academic excellence with practical relevance, offering recorded lectures, interactive modules, and live virtual sessions that bring real-world business challenges to life. Learners engage in case studies, group projects, role-playing exercises, and industry-led discussions, all from the comfort of their own space. Whether climbing the corporate ladder or shifting into a leadership role, our ODL MBA gives you the competitive edge—on your terms, at your pace, and with the full support of experienced faculty and industry experts.', 18210.00, 23960.00, NULL, NULL),
(2, 77, '1600 Hours 00 Minutes', NULL, 'Master of Technology in Data Science and Analytics', NULL, 'PLO1: utilize data science and analytics knowledge for effective and excellent practice as a data scientist and data analyst.\r\nPLO2: conduct systematic investigations and apply critical and creative thinking to generate innovative solutions in data science and analytics.\r\nPLO3: apply knowledge, technology and skills of data science and analytics to discover potential yet hidden information, knowledge and insights for data-driven and well-informed decision making.\r\nPLO4: effectively interact with diverse peers, superiors, clients and experts.\r\nPLO5: display effective communication, both orally and in writing, for data science and analytics solutions, to a diverse audience, including peers, superiors, clients, and experts.\r\nPLO6: practise digital skills to acquire, interpret and extend knowledge in data science and analytics.\r\nPLO7: determine suitable numerical tools to manage and resolve data science and analytics problems.\r\nPLO8: operate effectively in community and multidisciplinary teams either as a leader or a group member, demonstrate respect for cultural diversity and contribute to their organization and society.\r\nPLO9: apply independent and lifelong learning skills to keep up with latest relevant knowledge and cutting edge technologies in Data Science and Analytics.\r\nPLO10: demonstrate entrepreneurial and managerial skills.\r\nPLO11: manage research and services by applying ethics, values, attitude, professionalism and sustainable practices in data science and analytics.', 'PEO1: have in-depth knowledge and apply enhanced technical, digital, and numeracy skills (of data science and analytics and related disciplines) to provide innovative solutions in computing.\r\nPEO2: demonstrate effective leadership, interpersonal, and communication skills to interact effectively with a wide variety of audiences or multi-disciplinary teams, tolerate and value different global perspectives and cultures.\r\nPEO3: engage and advocate lifelong learning activities and have an entrepreneurial mindset.\r\nPEO4: practise professional, ethical and societal responsibilities with integrity, and show adaptability in different roles and surroundings in contributing to the community.', 'The Master of Technology in Data Science and Analytics with ODL mode is aimed at recent graduates and industry practitioners from various academic disciplines with strong analytic and computing skills or experience. The programme is designed to equip the students with fundamental and applied knowledge, technical skills, and current technologies in the data science and analytics area. These include the fundamental principles of data science, the capability to analyse a diversity of big data, the skills of using data science tools and applying the data analytics techniques to various domains, as well as the capability to present the analytics results to the intended audience. The materials emphasise state-of-the-practice techniques, tools, and technology, and recognised methodology through university-industry collaborations. Graduates from this programme will have career opportunities as data scientists, data analysts and many more.', 25110.00, 43410.00, 25730.00, 44030.00);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sem_no` int(11) DEFAULT NULL,
  `total_credits` decimal(5,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `program_id`, `sem_no`, `total_credits`) VALUES
(1, 40, 1, 15.0),
(2, 40, 2, 15.0),
(3, 40, 3, 10.0),
(4, 77, 1, 18.0),
(5, 77, 2, 21.5);

-- --------------------------------------------------------

--
-- Table structure for table `semester_course`
--

CREATE TABLE `semester_course` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sem_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester_course`
--

INSERT INTO `semester_course` (`id`, `course_id`, `program_id`, `sem_no`) VALUES
(1, 1, 40, 1),
(2, 2, 40, 1),
(3, 3, 40, 1),
(4, 4, 40, 1),
(5, 5, 40, 1),
(6, 6, 40, 2),
(7, 7, 40, 2),
(8, 8, 40, 2),
(9, 9, 40, 2),
(10, 10, 40, 2),
(11, 11, 40, 3),
(12, 12, 40, 3),
(13, 13, 77, 1),
(14, 14, 77, 1),
(15, 15, 77, 1),
(16, 16, 77, 1),
(17, 17, 77, 1),
(18, 18, 77, 1),
(19, 19, 77, 2),
(20, 20, 77, 2),
(21, 21, 77, 2),
(22, 22, 77, 2),
(23, 23, 77, 2),
(24, 24, 77, 2),
(25, 25, 77, 2),
(26, 26, 77, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_details`
--
ALTER TABLE `program_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `semester_course`
--
ALTER TABLE `semester_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `program_details`
--
ALTER TABLE `program_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `semester_course`
--
ALTER TABLE `semester_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `program_details`
--
ALTER TABLE `program_details`
  ADD CONSTRAINT `program_details_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `semester_course`
--
ALTER TABLE `semester_course`
  ADD CONSTRAINT `semester_course_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `semester_course_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
