<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create semester_course table if not exists
$sql = "
CREATE TABLE IF NOT EXISTS semester_course (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  program_id INT NOT NULL,
  sem_no INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES courses(id),
  FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";
if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'semester_course' ready.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Fetch course IDs
$courses = [];
$result = $conn->query("SELECT id, course_code, program_id FROM courses");
while ($row = $result->fetch_assoc()) {
    $courses[$row['program_id']][$row['course_code']] = $row['id'];
}

// Mapping: program_id, semr_no, [course_codes]
$mappings = [
    // Program 40 (MBA)
    [40, 1, ["MTKL5013","MTKL5023","MTKL5033","MTKL5043","MTKL5053"]],
    [40, 2, ["MTKL5063","MTKL5073","MTKL5083","MTKL5093","MPSW5013"]],
    [40, 3, ["MTKL5103","MTKL5117"]],
    
    // Program 77 (MTDSA)
    [77, 1, ["MPSW5013","MPSW5063","MAXL5113","MTDL5123","MAXL5133","MAXL5143"]],
    [77, 2, ["MAXL5153","MTDL5163","MTDL5233","MTDL5253","MTDL5223","MTDL5273","MTPL5314","MTPL5326"]],
];

// Insert mappings
$stmt = $conn->prepare("INSERT INTO semester_course (course_id, program_id, sem_no) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $course_id, $program_id, $sem_no);

foreach ($mappings as $m) {
    $program_id = $m[0];
    $sem_no = $m[1];
    foreach ($m[2] as $code) {
        if (isset($courses[$program_id][$code])) {
            $course_id = $courses[$program_id][$code];
            $stmt->execute();
        } else {
            echo "⚠️ Skipped: Course $code not found for Program $program_id<br>";
        }
    }
}

echo "✅ Semester-course mappings inserted successfully!";

$stmt->close();
$conn->close();
?>
