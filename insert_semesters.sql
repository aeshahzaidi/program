<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists
$sql = "
CREATE TABLE IF NOT EXISTS semesters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  program_id INT NOT NULL,
  semester_no INT NOT NULL,
  total_credits DECIMAL(5,1) DEFAULT NULL,
  FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'semesters' ready.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Data: [program_id, semester_no, total_credits]
$semesters = [
    [40, 1, 15],
    [40, 2, 15],
    [40, 3, 10],
    [77, 1, 18],
    [77, 2, 21.5]
];

// Prepared statement
$stmt = $conn->prepare("INSERT INTO semesters (program_id, semester_no, total_credits) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $program_id, $semester_no, $total_credits);

foreach ($semesters as $s) {
    $program_id = $s[0];
    $semester_no = $s[1];
    $total_credits = $s[2];
    $stmt->execute();
}

echo "✅ Semesters inserted successfully!";

$stmt->close();
$conn->close();
?>
