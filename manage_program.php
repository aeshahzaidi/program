<?php   
$conn = new mysqli("localhost", "root", "1234", "mydb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $faculty = $_POST['faculty'];
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $ugpg = $_POST['ugpg'];
    $year = $_POST['year'];
    $full_accreditation = $_POST['full_accreditation'] ?? '';
    $partial_accreditation = $_POST['partial_accreditation'] ?? '';

    if ($id) {
        $stmt = $conn->prepare("UPDATE programs SET faculty=?, program_name=?, program_code=?, ugpg=?, year=?, full_accreditation=?, partial_accreditation=? WHERE id=?");
        $stmt->bind_param("sssssssi", $faculty, $program_name, $program_code, $ugpg, $year, $full_accreditation, $partial_accreditation, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO programs (faculty, program_name, program_code, ugpg, year, full_accreditation, partial_accreditation) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $faculty, $program_name, $program_code, $ugpg, $year, $full_accreditation, $partial_accreditation);
    }

    $stmt->execute();
    header("Location: manage_program.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM programs WHERE id = $id");
    header("Location: manage_program.php");
    exit;
}

$programs = $conn->query("SELECT * FROM programs");

$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM programs WHERE id = $id");
    $editData = $result->fetch_assoc();
}

$facultyOptions = $conn->query("SELECT DISTINCT faculty FROM programs ORDER BY faculty");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Programs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar a:hover {
            background-color: #575757;
        }
        .container {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form label {
            display: block; margin: 10px 0 5px;
        }
        form input, form select {
            width: 100%; padding: 8px; margin-bottom: 10px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        form button {
            background: #4CAF50; color: white; border: none;
            padding: 10px 20px; border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background: #45a049;
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc; padding: 10px; text-align: center;
        }
        th { background: #eee; }
        .actions a {
            margin: 0 5px; color: #007BFF; text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
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
    <h2><?= $editData ? 'Edit Program' : 'Add Program' ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

        <label>Faculty:
            <select name="faculty" required>
                <option value="">-- Select Faculty --</option>
                <?php while($f = $facultyOptions->fetch_assoc()): ?>
                    <option value="<?= $f['faculty'] ?>" <?= ($editData['faculty'] ?? '') == $f['faculty'] ? 'selected' : '' ?>>
                        <?= $f['faculty'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>Program Name:
            <input type="text" name="program_name" required value="<?= $editData['program_name'] ?? '' ?>">
        </label>

        <label>Program Code:
            <input type="text" name="program_code" required value="<?= $editData['program_code'] ?? '' ?>">
        </label>

        <label>UG/PG:
            <select name="ugpg" required>
                <option value="">-- Select UG/PG --</option>
                <option value="UG" <?= ($editData['ugpg'] ?? '') == 'UG' ? 'selected' : '' ?>>UG</option>
                <option value="PG" <?= ($editData['ugpg'] ?? '') == 'PG' ? 'selected' : '' ?>>PG</option>
            </select>
        </label>

        <label>Year:
            <input type="text" name="year" required value="<?= $editData['year'] ?? '' ?>">
        </label>

        <label>Full Accreditation:
            <input type="text" name="full_accreditation" value="<?= $editData['full_accreditation'] ?? '' ?>">
        </label>

        <label>Partial Accreditation:
            <input type="text" name="partial_accreditation" value="<?= $editData['partial_accreditation'] ?? '' ?>">
        </label>

        <button type="submit"><?= $editData ? 'Update' : 'Add' ?> Program</button>
    </form>
</div>

<div class="container">
    <h2>Existing Programs</h2>
    <table>
        <tr>
            <th>Faculty</th>
            <th>Name</th>
            <th>Code</th>
            <th>UG/PG</th>
            <th>Year</th>
            <th>Partial Accreditation</th>
            <th>Full Accreditation</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $programs->fetch_assoc()): ?>
        <tr>
            <td><?= $row['faculty'] ?></td>
            <td><?= $row['program_name'] ?></td>
            <td><?= $row['program_code'] ?></td>
            <td><?= $row['ugpg'] ?></td>
            <td><?= $row['year'] ?></td>
            <td><?= $row['partial_accreditation'] ?></td>
            <td><?= $row['full_accreditation'] ?></td>
            <td class="actions">
                <a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this program?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
