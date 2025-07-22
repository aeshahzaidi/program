<?php
$conn = new mysqli("localhost", "root", "1234", "mydb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total = $conn->query("SELECT COUNT(*) AS total FROM programs")->fetch_assoc()['total'];
$ug = $conn->query("SELECT COUNT(*) AS ug FROM programs WHERE ugpg='UG'")->fetch_assoc()['ug'];
$pg = $conn->query("SELECT COUNT(*) AS pg FROM programs WHERE ugpg='PG'")->fetch_assoc()['pg'];
$faculties = $conn->query("SELECT COUNT(DISTINCT faculty) AS faculty_count FROM programs")->fetch_assoc()['faculty_count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Program Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .highlight-nav {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin: 40px auto;
            max-width: 900px;
        }

        .highlight-nav a {
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            width: 250px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .highlight-nav a:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
        }

        .highlight-nav h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #2980b9;
        }

        .highlight-nav p {
            font-size: 0.95rem;
        }

        .summary-section {
            max-width: 900px;
            margin: 30px auto;
            text-align: center;
        }

        .summary-cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .summary-cards .card {
            width: 200px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .summary-cards .card h2 {
            color: #27ae60;
            font-size: 2rem;
        }

        .summary-cards .card p {
            color: #555;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php"> Home</a>
        <a href="list_programs.php"> List Programs</a>
        <a href="manage_program.php"> Manage Programs</a>
    </div>

    <h1>🎓 Program Dashboard</h1>

    <div class="highlight-nav">
        <a href="list_programs.php">
            <h3> Program List</h3>
            <p>View and filter all academic programs</p>
        </a>
        <a href="manage_program.php">
            <h3> Manage Programs</h3>
            <p>Add, edit, or delete program details</p>
        </a>
    </div>

    <div class="summary-section">
        <h2> Program Summary</h2>
        <div class="summary-cards">
            <div class="card">
                <h2><?= $total ?></h2>
                <p>Total Programs</p>
            </div>
            <div class="card">
                <h2><?= $ug ?></h2>
                <p>Undergraduate (UG)</p>
            </div>
            <div class="card">
                <h2><?= $pg ?></h2>
                <p>Postgraduate (PG)</p>
            </div>
            <div class="card">
                <h2><?= $faculties ?></h2>
                <p>Faculties Involved</p>
            </div>
        </div>
    </div>
</body>
</html>
