<?php     
$conn = new mysqli("localhost", "root", "", "mydb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $faculty = $_POST['faculty'];
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $ugpg = $_POST['ugpg'];
    $target = $_POST['target'];
    $achieve = $_POST['achieve'];
    $full_accreditation = $_POST['full_accreditation'] ?? '';
    $partial_accreditation = $_POST['partial_accreditation'] ?? '';
    $mod_penyampaian = $_POST['mod_penyampaian'] ?? '';

    if ($id) {
        $stmt = $conn->prepare("UPDATE programs SET faculty=?, program_name=?, program_code=?, ugpg=?, target=?, achieve=?, full_accreditation=?, partial_accreditation=?, mod_penyampaian=? WHERE id=?");
        $stmt->bind_param("sssssssssi", $faculty, $program_name, $program_code, $ugpg, $target,$achieve, $full_accreditation, $partial_accreditation, $mod_penyampaian, $id);
        $stmt->execute();
        header("Location: manage_program.php");
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO programs (faculty, program_name, program_code, ugpg, target,achieve, full_accreditation, partial_accreditation, mod_penyampaian ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $faculty, $program_name, $program_code, $ugpg, $target,$achieve, $full_accreditation, $partial_accreditation, $mod_penyampaian);
        $stmt->execute();
        header("Location: manage_program.php?added=1");
        exit;
    }
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
        .navbar a:hover { background-color: #575757; }
        .container {
            background: #fff;
            margin: 20px auto;
            padding: 100px;
            max-width: 90%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
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
        form button:hover { background: #45a049; }

        /* Fixed table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
            white-space: normal;
        }
        th { background: #eee; }

        /* Column widths */
        th:nth-child(1), td:nth-child(1) { width: 8%; }   /* Faculty */
        th:nth-child(2), td:nth-child(2) { width: 25%; }  /* Name */
        th:nth-child(3), td:nth-child(3) { width: 8%; }   /* Code */
        th:nth-child(4), td:nth-child(4) { width: 6%; }   /* UG/PG */
        th:nth-child(5), td:nth-child(5) { width: 8%; }   /* Target */
        th:nth-child(6), td:nth-child(6) { width: 8%; }   /* Achieve */
        th:nth-child(7), td:nth-child(7) { width: 12%; }  /* Partial Acc. */
        th:nth-child(8), td:nth-child(8) { width: 12%; }  /* Full Acc. */
        th:nth-child(9), td:nth-child(9) { width: 12%; }   /* Mode of Delivery */
        th:nth-child(10), td:nth-child(10) { width: 10%; }/* Actions */

        .actions {
            white-space: nowrap; /* keep Edit/Delete in one line */
        }
        .actions a {
            margin: 0 5px; 
            color: #007BFF; 
            text-decoration: none; 
            display: inline-block;
        }
        .actions a:hover { text-decoration: underline; }

        .popup-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 20px auto;
            max-width: 900px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .popup-message button {
            margin-top: 10px;
            padding: 5px 10px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .modal-buttons button {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .yes-btn { background-color: #28a745; color: white; }
        .yes-btn:hover { background-color: #218838; }
        .no-btn { background-color: #dc3545; color: white; }
        .no-btn:hover { background-color: #c82333; }

        #searchInput {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="list_programs.php" class="active">List Programs</a>
    <a href="manage_program.php">Manage Programs</a>
</div>

<?php if (isset($_GET['added'])): ?>
    <div class="popup-message">
        Program added successfully!
        <button onclick="this.parentElement.style.display='none'">OK</button>
    </div>
<?php endif; ?>

<div class="container">
    <h2><?= $editData ? 'Edit Program' : 'Add Program' ?></h2>
    <form method="POST" onsubmit="return confirmAdd()">
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
        <label>Target:
            <input type="text" name="target" required value="<?= $editData['target'] ?? '' ?>">
        </label>
         <label>Achieve:
            <input type="text" name="achieve" required value="<?= $editData['achieve'] ?? '' ?>">
        </label>
        <label>Full Accreditation:
            <input type="text" name="full_accreditation" value="<?= $editData['full_accreditation'] ?? '' ?>">
        </label>
        <label>Partial Accreditation:
            <input type="text" name="partial_accreditation" value="<?= $editData['partial_accreditation'] ?? '' ?>">
        </label>
        <label>Mode of Delivery:
            <select name="mod_penyampaian" required>
                <option value="">-- Select conventional/odl --</option>
                <option value="conventional" <?= ($editData['mod_penyampaian'] ?? '') == 'conventional' ? 'selected' : '' ?>>conventional</option>
                <option value="odl" <?= ($editData['mod_penyampaian'] ?? '') == 'odl' ? 'selected' : '' ?>>odl</option>
            </select>
        </label>

        <button type="submit"><?= $editData ? 'Update' : 'Add' ?> Program</button>
    </form>
</div>

<div class="container">
    <h2>Existing Programs</h2>
    <input type="text" id="searchInput" placeholder="Search programs...">
    <table>
        <tr>
            <th>Faculty</th>
            <th>Name</th>
            <th>Code</th>
            <th>UG/PG</th>
            <th>Target</th>
             <th>Achieve</th>
            <th>Partial Accreditation</th>
            <th>Full Accreditation</th>
            <th>Mode of Delivery</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $programs->fetch_assoc()): ?>
        <tr>
            <td><?= $row['faculty'] ?></td>
            <td>
                 <?php if (strtolower($row['mod_penyampaian']) === 'odl'): ?>
            <a href="odlprogram_detail.php?id=<?= $row['id'] ?>">
                <?= $row['program_name'] ?>
            </a>
        <?php else: ?>
            <?= $row['program_name'] ?>
        <?php endif; ?>
    </td>
            <td><?= $row['program_code'] ?></td>
            <td><?= $row['ugpg'] ?></td>
            <td><?= $row['target'] ?></td>
              <td><?= $row['achieve'] ?></td>
            <td><?= $row['partial_accreditation'] ?></td>
            <td><?= $row['full_accreditation'] ?></td>
            <td><?= $row['mod_penyampaian'] ?></td>
            <td class="actions">
                <a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="#" class="delete-btn" data-id="<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal for Add Confirmation -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <div id="modalText"></div>
        <div class="modal-buttons">
            <button class="yes-btn" onclick="submitForm()">Yes</button>
            <button class="no-btn" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div><strong>Are you sure you want to delete this program?</strong></div>
        <div class="modal-buttons">
            <button class="yes-btn" id="confirmDeleteBtn">Yes</button>
            <button class="no-btn" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    function confirmAdd() {
        const faculty = document.querySelector('[name="faculty"]').value;
        const programName = document.querySelector('[name="program_name"]').value;
        const programCode = document.querySelector('[name="program_code"]').value;
        const ugpg = document.querySelector('[name="ugpg"]').value;
        const target = document.querySelector('[name="target"]').value;
         const achieve = document.querySelector('[name="achieve"]').value;
        const full = document.querySelector('[name="full_accreditation"]').value;
        const partial = document.querySelector('[name="partial_accreditation"]').value;
        const modPenyampaian = document.querySelector('[name="mod_penyampaian"]').value;

        document.getElementById('modalText').innerHTML = `
            <strong>Are you sure you want to add this program?</strong><br><br>
            <b>Faculty:</b> ${faculty}<br>
            <b>Name:</b> ${programName}<br>
            <b>Code:</b> ${programCode}<br>
            <b>UG/PG:</b> ${ugpg}<br>
            <b>Target:</b> ${target}<br>
             <b>Achieve:</b> ${achieve}<br>
            <b>Full Accreditation:</b> ${full || '-'}<br>
            <b>Partial Accreditation:</b> ${partial || '-'}<br>
            <b>Mode of Delivery:</b> ${modPenyampaian || '-'}<br>
        `;
        document.getElementById('confirmModal').style.display = 'flex';
        return false;
    }

    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    function submitForm() {
        document.querySelector('form').submit();
    }

    let deleteId = null;
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteId = this.getAttribute('data-id');
            document.getElementById('deleteModal').style.display = 'flex';
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteId) {
            window.location.href = '?delete=' + deleteId;
        }
    });

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        deleteId = null;
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tr:not(:first-child)');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
</body>
</html>
