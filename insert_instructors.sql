<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists
$sql = "
CREATE TABLE IF NOT EXISTS instructors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  designation VARCHAR(100) NOT NULL,
  program_id INT NOT NULL,
  FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'instructors' ready.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Data: [name, email, phone, designation, program_id]
$instructors = [
    ["NORHIDAYAH BINTI MOHAMAD", "norhidayah@utem.edu.my", NULL, "instructor", 40],
    ["MURZIDAH BINTI AHMAD MURAD", "murzidah@utem.edu.my", NULL, "instructor", 40],
    ["NURULIZWA BINTI ABDUL RASHID", "nurulizwa@utem.edu.my", NULL, "instructor", 40],
    ["MOHD AMIN BIN MOHAMAD", NULL, NULL, "instructor", 40],
    ["NUSAIBAH BINTI MANSOR", NULL, NULL, "instructor", 40],
    ["HAZMILAH BINTI HASAN", NULL, NULL, "instructor", 40],
    ["AMIR BIN ARIS", NULL, NULL, "instructor", 40],
    ["NOR AZAH BINTI ABDUL AZIZ", "azahaziz@utem.edu.my", NULL, "instructor", 40],
    ["GANAGAMBEGAI A/P LAXAMANAN", NULL, NULL, "instructor", 40],
    ["JOHANNA BINTI ABDULLAH JAAFAR", "johanna@utem.edu.my", NULL, "instructor", 40],
    ["MISLINA BINTI ATAN@MOHD SALLEH", NULL, NULL, "instructor", 40],
    ["MOHAMED A. S. DOHEIR", NULL, NULL, "instructor", 40],
    ["MEHRAN DOULATABADI", NULL, NULL, "instructor", 40],
    ["SITI NUR AISYAH BINTI ALIAS", "sitinuraisyah@utem.edu.my", NULL, "instructor", 40],
    ["NURUL FARHAINI BINTI RAZALI", NULL, NULL, "instructor", 40],

    ["NOOR FAZILLA BINTI ABD YUSOF", "elle@utem.edu.my", NULL, "instructor", 77],
    ["NUR ZAREEN BINTI ZULKARNAIN", "zareen@utem.edu.my", NULL, "instructor", 77],
    ["SITI AZIRAH BINTI ASMAI", NULL, NULL, "instructor", 77],
    ["FAUZIAH BINTI KASMIN", "fauziah@utem.edu.my", NULL, "instructor", 77],
    ["HALIZAH BINTI BASIRON", NULL, NULL, "coordinator", 77],
    ["YOGAN A/L JAYA KUMAR", "yogan@utem.edu.my", NULL, "instructor", 77],
    ["SEK YONG WEE", NULL, NULL, "instructor", 77],
    ["ZURAINI BINTI OTHMAN", "zuraini@utem.edu.my", NULL, "instructor", 77]
];

// Prepared statement
$stmt = $conn->prepare("INSERT INTO instructors (name, email, phone, designation, program_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $email, $phone, $designation, $program_id);

foreach ($instructors as $i) {
    $name = $i[0];
    $email = $i[1];
    $phone = $i[2];
    $designation = $i[3];
    $program_id = $i[4];
    $stmt->execute();
}

echo "✅ Instructors inserted successfully!";

$stmt->close();
$conn->close();
?>
