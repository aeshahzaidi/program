<?php
// request_status.php
session_start();
include 'connect.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Filter dropdown
$current_status = $_GET['filter'] ?? 'all';
$allowed_status = ['pending','approved','rejected','all'];
if (!in_array($current_status,$allowed_status)) $current_status='all';

$whereStatus = $current_status==='all'
    ? '1=1'
    : "r.status='".$conn->real_escape_string($current_status)."'";


$user_id = (int)$_SESSION['user_id'];
$sql = "
SELECT 
    r.id,
    r.program_id,
    r.status,
    r.requested_at,
    COALESCE(r.faculty, p.faculty) AS faculty,
    COALESCE(r.program_name, p.program_name) AS req_program_name,
    COALESCE(r.program_code, p.program_code) AS program_code,
    COALESCE(r.ugpg, p.ugpg) AS ugpg,
    COALESCE(r.target, p.target) AS target,
    COALESCE(r.achieve, p.achieve) AS achieve,
    COALESCE(r.partial_accreditation, p.partial_accreditation) AS partial_accreditation,
    COALESCE(r.full_accreditation, p.full_accreditation) AS full_accreditation,
    COALESCE(r.mod_penyampaian, p.mod_penyampaian) AS mod_penyampaian,
    r.action AS user_action,
    r.rejection_reason
FROM program_requests r
LEFT JOIN programs p ON r.program_id = p.id
WHERE r.requested_by=$user_id AND $whereStatus
ORDER BY r.requested_at DESC";

$result = $conn->query($sql) or die("SQL error: ".$conn->error);

function h($str){ return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Program Requests</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>

  .details-table { 
    width:100%; border-collapse:collapse; font-size:14px; margin-top:10px; display:none; 
    transition: max-height 0.3s ease; 
}
.card-request.active .details-table { display:table; }
.card-request { cursor:pointer; } 

#request-page { font-family:'Inter',system-ui,sans-serif; color:#2c2c2c; line-height:1.5; background:#f5f6fa; padding:30px 0; }
#request-page .container { background:#fff; margin:0 auto; padding:32px; max-width:1100px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); }
#request-page h2 { font-size:24px; font-weight:600; margin-bottom:24px; }
#request-page .filter-select { padding:8px 12px; font-size:15px; border:1px solid #ccc; border-radius:6px; margin-bottom:20px; }
#request-page .card-request { border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin-bottom:20px; box-shadow:0 2px 4px rgba(0,0,0,.04); transition:box-shadow .2s ease; }
#request-page .card-request:hover { box-shadow:0 4px 8px rgba(0,0,0,.06); }
#request-page .card-top { display:flex; justify-content:space-between; align-items:flex-start; }
#request-page .program-title { font-size:18px; font-weight:600; margin:0; color:#111827; }
#request-page .badge { padding:4px 10px; border-radius:6px; font-size:12px; font-weight:500; color:#fff; }
#request-page .status-pending { background:#fbbf24; }
#request-page .status-approved { background:#10b981; }
#request-page .status-rejected { background:#ef4444; }
#request-page .meta { font-size:13px; color:#6b7280; margin-top:4px; }
#request-page .rejection-reason { font-size:13px; color:#ef4444; margin-top:8px; }
#request-page .details-table { width:100%; border-collapse:collapse; font-size:14px; margin-top:10px; }
#request-page .details-table td { border:1px solid #e5e7eb; padding:8px 10px; }
#request-page .details-table td:first-child { background:#f9fafb; font-weight:500; width:40%; }
</style>
</head>
<body>
<?php

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'navbar_admin.php';
} else {
    include 'navbar_user.php';
}
?>

<div id="request-page">
  <div class="container">
    <h2>My Program Requests</h2>
    <form method="get">
      <select name="filter" class="filter-select" onchange="this.form.submit()">
        <option value="all" <?=$current_status==='all'?'selected':''?>>All</option>
        <option value="pending" <?=$current_status==='pending'?'selected':''?>>Pending</option>
        <option value="approved" <?=$current_status==='approved'?'selected':''?>>Approved</option>
        <option value="rejected" <?=$current_status==='rejected'?'selected':''?>>Rejected</option>
      </select>
    </form>
<?php if ($result->num_rows>0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="card-request">
      <div class="card-top">
        <h5 class="program-title"><?=h($row['req_program_name'])?></h5>
        <span class="badge status-<?=h($row['status'])?>"><?=ucfirst(h($row['status']))?></span>
      </div>
      <div class="meta">
        You requested to <strong><?=h($row['user_action'])?></strong> this program
        on <?=h($row['requested_at'])?>
      </div>
      <?php if($row['status']==='rejected' && $row['rejection_reason']): ?>
        <div class="rejection-reason">Rejection reason: <?=h($row['rejection_reason'])?></div>
      <?php endif; ?>
      <table class="details-table">
         <tr><td>Faculty</td><td><?=h($row['faculty'])?></td></tr>
         <tr><td>Program Name</td><td><?=h($row['req_program_name'])?></td></tr>
         <tr><td>Program Code</td><td><?=h($row['program_code'])?></td></tr>
         <tr><td>UG/PG</td><td><?=h($row['ugpg'])?></td></tr>
         <tr><td>Target</td><td><?=h($row['target'])?></td></tr>
         <tr><td>Achieve</td><td><?=h($row['achieve'])?></td></tr>
         <tr><td>Partial Accreditation</td><td><?=h($row['partial_accreditation'])?></td></tr>
         <tr><td>Full Accreditation</td><td><?=h($row['full_accreditation'])?></td></tr>
         <tr><td>Mode of Delivery</td><td><?=h($row['mod_penyampaian'])?></td></tr>
      </table>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>No requests found.</p>
<?php endif; ?>

<script>

document.querySelectorAll('.card-request').forEach(card => {
    card.addEventListener('click', () => {
        card.classList.toggle('active');
    });
});
</script> 
    <?php if ($result->num_rows>0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card-request">
          <div class="card-top">
            <h5 class="program-title"><?=h($row['req_program_name'])?></h5>
            <span class="badge status-<?=h($row['status'])?>"><?=ucfirst(h($row['status']))?></span>
          </div>
          <div class="meta">
            You requested to <strong><?=h($row['user_action'])?></strong> this program
            on <?=h($row['requested_at'])?>
          </div>
          <?php if($row['status']==='rejected' && $row['rejection_reason']): ?>
            <div class="rejection-reason">Rejection reason: <?=h($row['rejection_reason'])?></div>
          <?php endif; ?>
          <table class="details-table">
             <tr><td>Faculty</td><td><?=h($row['faculty'])?></td></tr>
             <tr><td>Program Name</td><td><?=h($row['req_program_name'])?></td></tr>
            <tr><td>Program Code</td><td><?=h($row['program_code'])?></td></tr>
            <tr><td>UG/PG</td><td><?=h($row['ugpg'])?></td></tr>
            <tr><td>Target</td><td><?=h($row['target'])?></td></tr>
            <tr><td>Achieve</td><td><?=h($row['achieve'])?></td></tr>
            <tr><td>Partial Accreditation</td><td><?=h($row['partial_accreditation'])?></td></tr>
            <tr><td>Full Accreditation</td><td><?=h($row['full_accreditation'])?></td></tr>
            <tr><td>Mode of Delivery</td><td><?=h($row['mod_penyampaian'])?></td></tr>
          </table>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No requests found.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
