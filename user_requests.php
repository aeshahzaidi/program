<?php
include 'connect.php';
session_start(); 

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// --- ADD / UPDATE ---
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
    $requested_by = $_SESSION['user_id'] ?? null; 

    if ($id) {
        // Request to update
        $stmt = $conn->prepare("INSERT INTO program_requests 
            (program_id, action, faculty, program_name, program_code, ugpg, target, achieve,
             full_accreditation, partial_accreditation, mod_penyampaian, status, requested_by) 
            VALUES (?, 'update', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->bind_param("issssiiissi", $id, $faculty, $program_name, $program_code, $ugpg, $target, $achieve,
                          $full_accreditation, $partial_accreditation, $mod_penyampaian, $requested_by);
        $stmt->execute();
        header("Location: user_requests.php?request_submitted=1&page=1");
        exit;
    } else {
        // Request to add
        $stmt = $conn->prepare("INSERT INTO program_requests 
            (action, faculty, program_name, program_code, ugpg, target, achieve,
             full_accreditation, partial_accreditation, mod_penyampaian, status, requested_by) 
            VALUES ('add', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->bind_param("ssssiiissi", $faculty, $program_name, $program_code, $ugpg, $target, $achieve,
                          $full_accreditation, $partial_accreditation, $mod_penyampaian, $requested_by);
        $stmt->execute();
        header("Location: user_requests.php?request_submitted=1&page=1");
        exit;
    }
}

// --- DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $requested_by = $_SESSION['user_id'] ?? null;

    // Request to delete 
    $stmt = $conn->prepare("INSERT INTO program_requests 
        (program_id, action, status, requested_by) 
        VALUES (?, 'delete', 'pending', ?)");
    $stmt->bind_param("ii", $id, $requested_by);
    $stmt->execute();

    header("Location: user_requests.php?request_submitted=1&page=1");
    exit;
}

// --- PAGINATION + SEARCH ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20; 
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$where = "";

if (!empty($search)) {
    $searchEsc = $conn->real_escape_string($search);
    $where = "WHERE faculty LIKE '%$searchEsc%' 
              OR program_name LIKE '%$searchEsc%' 
              OR program_code LIKE '%$searchEsc%' 
              OR ugpg LIKE '%$searchEsc%'
              OR target LIKE '%$searchEsc%'
              OR achieve LIKE '%$searchEsc%'
              OR partial_accreditation LIKE '%$searchEsc%'
              OR full_accreditation LIKE '%$searchEsc%'
              OR mod_penyampaian LIKE '%$searchEsc%'";
}

// Count total
$countResult = $conn->query("SELECT COUNT(*) as total FROM programs $where");
$totalCount = (int)$countResult->fetch_assoc()['total'];
$totalPages = max(1, (int)ceil($totalCount / $limit));

// Fetch programs (ordered by faculty + program_name)
$programs = $conn->query("SELECT * FROM programs $where 
                          ORDER BY faculty, program_name 
                          LIMIT $limit OFFSET $offset");


$editData = null;
if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM programs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}


$facultyOptions = $conn->query("SELECT DISTINCT faculty FROM programs ORDER BY faculty");


function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Programs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="manageprogram.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
<?php
include 'navbar_user.php'
?>

<script>

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2200,
    timerProgressBar: true
});

// Success messages
<?php if (isset($_GET['request_submitted'])): ?>
Toast.fire({ icon: 'info', title: 'Your request has been submitted for admin approval' });
<?php endif; ?>
</script>

<div class="container">
    <h2><?= !empty($editData) ? 'Edit Program' : 'Add Program' ?></h2>
    <form method="POST" onsubmit="return confirmSave(event, this)">
        <input type="hidden" name="id" value="<?= h($editData['id'] ?? '') ?>">

        <label>Faculty:
            <select name="faculty" required>
                <option value="">-- Select Faculty --</option>
                <?php while($f = $facultyOptions->fetch_assoc()): ?>
                    <option value="<?= h($f['faculty']) ?>" <?= (($editData['faculty'] ?? '') === $f['faculty']) ? 'selected' : '' ?>>
                        <?= h($f['faculty']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>Program Name:
            <input type="text" name="program_name" required value="<?= h($editData['program_name'] ?? '') ?>">
        </label>

        <label>Program Code:
            <input type="text" name="program_code" required value="<?= h($editData['program_code'] ?? '') ?>">
        </label>

        <label>UG/PG:
            <select name="ugpg" required>
                <option value="">-- Select UG/PG --</option>
                <option value="UG" <?= (($editData['ugpg'] ?? '') === 'UG') ? 'selected' : '' ?>>UG</option>
                <option value="PG" <?= (($editData['ugpg'] ?? '') === 'PG') ? 'selected' : '' ?>>PG</option>
            </select>
        </label>

        <label>Target:
            <input type="text" name="target" required value="<?= h($editData['target'] ?? '') ?>">
        </label>

        <label>Achieve:
            <input type="text" name="achieve" required value="<?= h($editData['achieve'] ?? '') ?>">
        </label>

        <label>Full Accreditation:
            <input type="text" name="full_accreditation" value="<?= h($editData['full_accreditation'] ?? '') ?>">
        </label>

        <label>Partial Accreditation:
            <input type="text" name="partial_accreditation" value="<?= h($editData['partial_accreditation'] ?? '') ?>">
        </label>

        <label>Mode of Delivery:
            <select name="mod_penyampaian" required>
                <option value="">-- Select conventional/odl --</option>
                <option value="conventional" <?= (($editData['mod_penyampaian'] ?? '') === 'conventional') ? 'selected' : '' ?>>conventional</option>
                <option value="odl" <?= (($editData['mod_penyampaian'] ?? '') === 'odl') ? 'selected' : '' ?>>odl</option>
            </select>
        </label>

        <button class="btn" type="submit"><?= !empty($editData) ? 'Update' : 'Add' ?> Program</button>
    </form>
</div>

<div class="container">
    <h2>Existing Programs</h2>

    <form id="searchBox" method="get">
        <input type="text" name="search" placeholder="Search programs..." value="<?= h($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>No.</th>
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
        <?php
        $num = $offset + 1; 
        while($row = $programs->fetch_assoc()): ?>
        <tr>
            <td><?= $num++ ?></td>
            <td><?= h($row['faculty']) ?></td>
            <td>
                <?php if (strtolower($row['mod_penyampaian']) === 'odl'): ?>
                    <a href="odlprogram_detail.php?id=<?= (int)$row['id'] ?>">
                        <?= h($row['program_name']) ?>
                    </a>
                <?php else: ?>
                    <?= h($row['program_name']) ?>
                <?php endif; ?>
            </td>
            <td><?= h($row['program_code']) ?></td>
            <td><?= h($row['ugpg']) ?></td>
            <td><?= h($row['target']) ?></td>
            <td><?= h($row['achieve']) ?></td>
            <td><?= h($row['partial_accreditation']) ?></td>
            <td><?= h($row['full_accreditation']) ?></td>
            <td><?= h($row['mod_penyampaian']) ?></td>
            <td class="actions">
                <a href="?edit=<?= (int)$row['id'] ?>">Edit</a>
                <a href="#" onclick="return confirmDelete(<?= (int)$row['id'] ?>)">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Previous</a>
        <?php else: ?>
            <a class="disabled">Previous</a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
        <?php else: ?>
            <a class="disabled">Next</a>
        <?php endif; ?>
    </div>
</div>

<script>

function esc(s){ return (s||'').replace(/[&<>"']/g, m=>({ "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[m])); }

// Confirm Save (Add/Update)
function confirmSave(e, form) {
    if (form.dataset.confirmed === '1') return true; 
    e.preventDefault();

    const detailsHtml = `
        <table class="details-table">
            <tr><td>Faculty</td><td>${esc(form.faculty.value)}</td></tr>
            <tr><td>Program Name</td><td>${esc(form.program_name.value)}</td></tr>
            <tr><td>Program Code</td><td>${esc(form.program_code.value)}</td></tr>
            <tr><td>UG/PG</td><td>${esc(form.ugpg.value)}</td></tr>
            <tr><td>Target</td><td>${esc(form.target.value)}</td></tr>
            <tr><td>Achieve</td><td>${esc(form.achieve.value)}</td></tr>
            <tr><td>Full Accreditation</td><td>${esc(form.full_accreditation.value)}</td></tr>
            <tr><td>Partial Accreditation</td><td>${esc(form.partial_accreditation.value)}</td></tr>
            <tr><td>Mode of Delivery</td><td>${esc(form.mod_penyampaian.value)}</td></tr>
        </table>
    `;

    Swal.fire({
        title: 'Submit request for admin approval?',
        html: detailsHtml,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit request',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.dataset.confirmed = '1';
            form.submit();
        }
    });
    return false;
}

// Confirm Delete
function confirmDelete(id) {
    Swal.fire({
        title: 'Submit delete request?',
        text: 'This will be sent to admin for approval.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, request delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?delete=' + id;
        }
    });
    return false;
}
</script>
<script>
// Save scroll position before leaving
window.addEventListener("beforeunload", function () {
    localStorage.setItem("scrollY", window.scrollY);
});

// Restore scroll position after reload
window.addEventListener("load", function () {
    if (localStorage.getItem("scrollY")) {
        window.scrollTo(0, localStorage.getItem("scrollY"));
        localStorage.removeItem("scrollY"); 
    }
});
</script>

</body>
</html>
