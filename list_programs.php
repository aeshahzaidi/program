<?php 
$conn = new mysqli("localhost", "root", "1234", "mydb");

$faculty = $_GET['faculty'] ?? '';
$year = $_GET['year'] ?? '';
$ugpg = $_GET['ugpg'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM programs WHERE 1=1";
if ($faculty) $query .= " AND faculty = '$faculty'";
if ($year) $query .= " AND year = '$year'";
if ($ugpg) $query .= " AND ugpg = '$ugpg'";
if ($search) {
    $safeSearch = $conn->real_escape_string($search);
    $query .= " AND (program_name LIKE '%$safeSearch%' OR program_code LIKE '%$safeSearch%')";
}

$faculties = $conn->query("SELECT DISTINCT faculty FROM programs");
$years = $conn->query("SELECT DISTINCT year FROM programs");
$ugpgs = $conn->query("SELECT DISTINCT ugpg FROM programs");

$programs = $conn->query($query);

// Count UG, PG, and total
$countUG = 0;
$countPG = 0;
$totalCount = 0;

$programData = [];
while ($row = $programs->fetch_assoc()) {
    $programData[] = $row;
    if ($row['ugpg'] == 'UG') $countUG++;
    elseif ($row['ugpg'] == 'PG') $countPG++;
    $totalCount++;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Program List</title>
    <link rel="stylesheet" href="style.css">
    <style>
     
        

        
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h2 {
            margin-top: 0;
        }

        /* 🔹 Filter layout fix */
        .filter-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        form.filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
            justify-content: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            width: 100%;
        }

        form.filter-form label {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            min-width: 150px;
            flex: 1;
        }

        form.filter-form select,
        form.filter-form input[type="text"] {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
        }

        form.filter-form button {
            padding: 10px 16px;
            background-color: #007acc;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            height: 38px;
        }

        form.filter-form button:hover {
            background-color: #005c99;
        }

        @media (max-width: 768px) {
            form.filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            form.filter-form label,
            form.filter-form button {
                width: 100%;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .summary-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 30px;
        }

        .summary-box ul {
            list-style: none;
            padding-left: 0;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="list_programs.php" class="active">List Programs</a>
    <a href="manage_program.php">Manage Programs</a>
</div>

<div class="container">
    <h2>Program List</h2>

    <div class="filter-wrapper">
        <form method="GET" class="filter-form">
            <label>Faculty
                <select name="faculty">
                    <option value="">All</option>
                    <?php while($f = $faculties->fetch_assoc()): ?>
                        <option value="<?= $f['faculty'] ?>" <?= $faculty == $f['faculty'] ? 'selected' : '' ?>>
                            <?= $f['faculty'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <label>Year
                <select name="year">
                    <option value="">All</option>
                    <?php while($y = $years->fetch_assoc()): ?>
                        <option value="<?= $y['year'] ?>" <?= $year == $y['year'] ? 'selected' : '' ?>>
                            <?= $y['year'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <label>UG/PG
                <select name="ugpg">
                    <option value="">All</option>
                    <?php while($p = $ugpgs->fetch_assoc()): ?>
                        <option value="<?= $p['ugpg'] ?>" <?= $ugpg == $p['ugpg'] ? 'selected' : '' ?>>
                            <?= $p['ugpg'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <label>Search
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Name or Code">
            </label>

            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="summary-box">
        <h3>Summary</h3>
        <ul>
            <li><strong>Total Programs:</strong> <?= $totalCount ?></li>
            <li><strong>Undergraduate (UG):</strong> <?= $countUG ?></li>
            <li><strong>Postgraduate (PG):</strong> <?= $countPG ?></li>
        </ul>
    </div>

    <table>
        <tr>
            <th>Faculty</th>
            <th>Program</th>
            <th>Code</th>
            <th>UG/PG</th>
            <th>Year</th>
        </tr>
        <?php foreach($programData as $row): ?>
            <tr>
                <td><?= $row['faculty'] ?></td>
                <td><?= $row['program_name'] ?></td>
                <td><?= $row['program_code'] ?></td>
                <td><?= $row['ugpg'] ?></td>
                <td><?= $row['year'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
