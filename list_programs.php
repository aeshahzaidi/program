<?php 
include 'connect.php';

$faculty = $_GET['faculty'] ?? '';
$target = $_GET['target'] ?? '';
$ugpg = $_GET['ugpg'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$baseQuery = "FROM programs WHERE 1=1";
if ($faculty) $baseQuery .= " AND faculty = '$faculty'";
if ($target) $baseQuery .= " AND target = '$target'";
if ($ugpg) $baseQuery .= " AND ugpg = '$ugpg'";

if ($search) {
    $safeSearch = $conn->real_escape_string($search);
    $baseQuery .= " AND (program_name LIKE '%$safeSearch%' 
                OR program_code LIKE '%$safeSearch%' 
                OR partial_accreditation LIKE '%$safeSearch%' 
                OR full_accreditation LIKE '%$safeSearch%' 
                OR mod_penyampaian LIKE '%$safeSearch%')";
}


$countResult = $conn->query("SELECT * $baseQuery");
$totalCount = $countResult->num_rows;
$totalPages = ceil($totalCount / $limit);

// Count UG and PG
$countUG = 0;
$countPG = 0;
while ($row = $countResult->fetch_assoc()) {
    if ($row['ugpg'] == 'UG') $countUG++;
    elseif ($row['ugpg'] == 'PG') $countPG++;
}


$programs = $conn->query("SELECT * $baseQuery LIMIT $limit OFFSET $offset");

$faculties = $conn->query("SELECT DISTINCT faculty FROM programs");
$targets = $conn->query("SELECT DISTINCT target FROM programs");
$ugpgs = $conn->query("SELECT DISTINCT ugpg FROM programs");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Program List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 90%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .filter-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        form.filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
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
            height: 38px;
        }

        form.filter-form button {
            padding: 8px 20px;
            background-color: #007acc;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            height: 38px;
            align-self: center;
        }

        form.filter-form button:hover {
            background-color: #005c99;
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

        .pagination {
            margin-top: 40px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 3px 12px;
            background: #65a0c8ff;
            color: white;
            align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a.active {
            background: #005c99;
         
        }
        .pagination a:hover {
            background: #004c80;
        }
        .pagination a.disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
  <?php
session_start(); 

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'navbar_admin.php';
} else {
    include 'navbar_user.php';
}
?>


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

            <label>Target
                <select name="target">
                    <option value="">All</option>
                    <?php while($t = $targets->fetch_assoc()): ?>
                        <option value="<?= $t['target'] ?>" <?= $target == $t['target'] ? 'selected' : '' ?>>
                            <?= $t['target'] ?>
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
            <th>No.</th>
            <th>Faculty</th>
            <th>Program</th>
            <th>Code</th>
            <th>UG/PG</th>
            <th>Target</th>
            <th>Achieve</th>
            <th>Partial Accreditation</th>
            <th>Full Accreditation</th>
            <th>Mode of Delivery</th>
        </tr>
        <?php
         $num = $offset + 1; 
          while($row = $programs->fetch_assoc()): ?>
            <tr>
                 <td><?= $num++ ?></td>
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
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <!-- Previous button -->
        <?php if ($page > 1): ?>
            <a href="?faculty=<?= $faculty ?>&target=<?= $target ?>&ugpg=<?= $ugpg ?>&search=<?= urlencode($search) ?>&page=<?= $page-1 ?>">Previous</a>
        <?php else: ?>
            <a class="disabled">Previous</a>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?faculty=<?= $faculty ?>&target=<?= $target ?>&ugpg=<?= $ugpg ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>" 
               class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Next button -->
        <?php if ($page < $totalPages): ?>
            <a href="?faculty=<?= $faculty ?>&target=<?= $target ?>&ugpg=<?= $ugpg ?>&search=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Next</a>
        <?php else: ?>
            <a class="disabled">Next</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
