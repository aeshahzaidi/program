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

    if ($id) {
        $stmt = $conn->prepare("UPDATE programs SET faculty=?, program_name=?, program_code=?, ugpg=?, year=? WHERE id=?");
        $stmt->bind_param("sssssi", $faculty, $program_name, $program_code, $ugpg, $year, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO programs (faculty, program_name, program_code, ugpg, year) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $faculty, $program_name, $program_code, $ugpg, $year);
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
       
    
        .container { background: #f9f9f9; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        form label { display: block; margin: 10px 0 5px; }
        form input, form select {
            width: 100%; padding: 8px; margin-bottom: 10px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        form button {
            background: #4CAF50; color: white; border: none;
            padding: 10px 20px; border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%; border-collapse: collapse;
            margin-top: 10px;
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
    <a href="index.php"> Home</a>
    <a href="list_programs.php"> List Programs</a>
    <a href="manage_program.php"> Manage Programs</a>
</div>

<div class="container">
    <h2><?= $editData ? 'Edit Program' : 'Add Program' ?></h2>
    <form method="POST" id="addProgramForm" onsubmit="return confirmAddProgram()">
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
            <th>Actions</th>
        </tr>
        <?php while($row = $programs->fetch_assoc()): ?>
        <tr>
            <td><?= $row['faculty'] ?></td>
            <td><?= $row['program_name'] ?></td>
            <td><?= $row['program_code'] ?></td>
            <td><?= $row['ugpg'] ?></td>
            <td><?= $row['year'] ?></td>
            <td class="actions">
                <a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
function confirmAddProgram() {
    const form = document.getElementById('addProgramForm');

    const faculty = form.faculty.value;
    const name = form.program_name.value;
    const code = form.program_code.value;
    const ugpg = form.ugpg.value;
    const year = form.year.value;

    if (!form.id.value) {
        const message = `Are you sure you want to add this program?\n\n` +
                        `Faculty: ${faculty}\n` +
                        `Program Name: ${name}\n` +
                        `Program Code: ${code}\n` +
                        `UG/PG: ${ugpg}\n` +
                        `Year: ${year}`;
        return confirm(message);
    }

    return true;
}
</script>

</body>
</html>
