<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Create courses table
$sql = "
CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_name VARCHAR(255) NOT NULL,
  course_code VARCHAR(50) NOT NULL,
  credit_hours DECIMAL(4,1) NOT NULL,
  program_id INT NOT NULL,
  FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
if (!$conn->query($sql)) die("Error creating courses: " . $conn->error);

// 2. Create semester_course table
$sql = "
CREATE TABLE IF NOT EXISTS semester_course (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  program_id INT NOT NULL,
  sem_no INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
if (!$conn->query($sql)) die("Error creating semester_course: " . $conn->error);

// 3. Data: [course_name, course_code, credit_hours, program_id, sem_no]
$courses = [
    // Program 40 - MBA
    ["TALENT MANAGEMENT", "MTKL5013", 3, 40, 1],
    ["MANAGING INNOVATION AND TECHNOLOGICAL CHANGE", "MTKL5023", 3, 40, 1],
    ["TECHNOLOGY AND OPERATIONS MANAGEMENT", "MTKL5033", 3, 40, 1],
    ["TECHNOPRENEURIAL LEADERSHIP", "MTKL5043", 3, 40, 1],
    ["ACCOUNTING AND FINANCE FOR MANAGERS", "MTKL5053", 3, 40, 1],
    ["ECONOMICS FOR MANAGERS", "MTKL5063", 3, 40, 2],
    ["PROCUREMENT AND LOGISTICS MANAGEMENT", "MTKL5073", 3, 40, 2],
    ["DIGITAL MARKETING AND TECHNOLOGY", "MTKL5083", 3, 40, 2],
    ["STRATEGIC TECHNOLOGY MANAGEMENT", "MTKL5093", 3, 40, 2],
    ["RESEARCH METHODOLOGY", "MPSW5013", 3, 40, 2],
    ["GLOBAL MANAGEMENT AND ORGANIZATIONAL BEHAVIOUR", "MTKL5103", 3, 40, 3],
    ["RESEARCH PROJECT", "MTKL5117", 7, 40, 3],

    // Program 77 - MTDSA
    ["RESEARCH METHODOLOGY", "MPSW5013", 3, 77, 1],
    ["ENTREPRENUERSHIP", "MPSW5063", 3, 77, 1],
    ["FUNDAMENTAL OF DATA SCIENCE", "MAXL5113", 3, 77, 1],
    ["BIG DATA MANAGEMENT", "MTDL5123", 3, 77, 1],
    ["APPLIED STATISCAL METHODS", "MAXL5133", 3, 77, 1],
    ["APPLIED MACHINE LEARNING", "MAXL5143", 3, 77, 1],
    ["BIG DATA ANALYTICS AND VISUALIZATION", "MAXL5153", 3, 77, 2],
    ["MODELLING AND DECISION MAKING", "MTDL5163", 3, 77, 2],
    ["SOCIAL MEDIA ANALYTICS (ELECTIVE)", "MTDL5233", 3, 77, 2],
    ["HEALTHCARE ANALYTICS (ELECTIVE)", "MTDL5253", 3, 77, 2],
    ["MANUFACTURING ANALYTICS (ELECTIVE)", "MTDL5223", 3, 77, 2],
    ["CUSTOMER AND FINANCIAL ANALYTICS (ELECTIVE)", "MTDL5273", 3, 77, 2],
    ["PROJECT 1", "MTPL5314", 0, 77, 2],
    ["PROJECT 2", "MTPL5326", 3.5, 77, 2]
];

// 4. Insert into courses + semester_course
$insertCourse = $conn->prepare("INSERT INTO courses (course_name, course_code, credit_hours, program_id) VALUES (?, ?, ?, ?)");
$insertCourse->bind_param("ssdi", $course_name, $course_code, $credit_hours, $program_id);

$insertMap = $conn->prepare("INSERT INTO semester_course (course_id, program_id, sem_no) VALUES (?, ?, ?)");
$insertMap->bind_param("iii", $course_id, $program_id, $sem_no);

foreach ($courses as $c) {
    [$course_name, $course_code, $credit_hours, $program_id, $sem_no] = $c;

    // Insert course
    $insertCourse->execute();
    $course_id = $conn->insert_id;

    // Insert mapping
    $insertMap->execute();
}

echo "✅ Courses and semester mappings inserted successfully!";

$insertCourse->close();
$insertMap->close();
$conn->close();
?>
