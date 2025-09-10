<?php
header("Content-Type: application/json");

include 'connect.php';


$type = $_GET['type'] ?? 'total';
$data = [];

if ($type === 'total') {
    $sql = "SELECT faculty,
                   COUNT(*) AS total
            FROM programs
            GROUP BY faculty";

} elseif ($type === 'ugpg') {
    $sql = "SELECT faculty,
                   SUM(CASE WHEN ugpg='UG' THEN 1 ELSE 0 END) AS ug,
                   SUM(CASE WHEN ugpg='PG' THEN 1 ELSE 0 END) AS pg
            FROM programs
            GROUP BY faculty";

} elseif ($type === 'mode') {
    $sql = "SELECT faculty,
                   SUM(CASE WHEN mod_penyampaian='CONVENTIONAL' THEN 1 ELSE 0 END) AS conventional,
                   SUM(CASE WHEN mod_penyampaian='ODL' THEN 1 ELSE 0 END) AS odl
            FROM programs
            GROUP BY faculty";

} elseif ($type === 'all') {
    $sql = "SELECT faculty,
                   COUNT(*) AS total,
                   SUM(CASE WHEN ugpg='UG' THEN 1 ELSE 0 END) AS ug,
                   SUM(CASE WHEN ugpg='PG' THEN 1 ELSE 0 END) AS pg,
                   SUM(CASE WHEN mod_penyampaian='CONVENTIONAL' THEN 1 ELSE 0 END) AS conventional,
                   SUM(CASE WHEN mod_penyampaian='ODL' THEN 1 ELSE 0 END) AS odl
            FROM programs
            GROUP BY faculty";
} else {
    echo json_encode([]);
    exit;
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
