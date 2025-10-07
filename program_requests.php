<?php
session_start();
include 'connect.php';

// Only admin can access
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $action_type = $_POST['action_type'] ?? '';
    $reason = trim($_POST['rejection_reason'] ?? '');

    // Fetch request details first
    $stmtReq = $conn->prepare("SELECT * FROM program_requests WHERE id=? AND status='pending'");
    $stmtReq->bind_param("i", $request_id);
    $stmtReq->execute();
    $resultReq = $stmtReq->get_result();
    $request = $resultReq->fetch_assoc();

    if (!$request) {
        header('Location: program_requests.php?status=error');
        exit;
    }

    if ($action_type === 'approve') {
        if ($request['action'] === 'add') {
            $stmtAdd = $conn->prepare("INSERT INTO programs (faculty, program_name, program_code, ugpg, target, achieve, partial_accreditation, full_accreditation, mod_penyampaian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtAdd->bind_param("ssssiiiss",
                $request['faculty'],
                $request['program_name'],
                $request['program_code'],
                $request['ugpg'],
                $request['target'],
                $request['achieve'],
                $request['partial_accreditation'],
                $request['full_accreditation'],
                $request['mod_penyampaian']
            );
            $stmtAdd->execute();
        } elseif ($request['action'] === 'edit') {
            $stmtEdit = $conn->prepare("UPDATE programs SET program_name=?, program_code=?, ugpg=?, target=?, achieve=?, partial_accreditation=?, full_accreditation=?, mod_penyampaian=? WHERE id=?");
            $stmtEdit->bind_param("ssssiiissi",
                $request['program_name'],
                $request['program_code'],
                $request['ugpg'],
                $request['target'],
                $request['achieve'],
                $request['partial_accreditation'],
                $request['full_accreditation'],
                $request['mod_penyampaian'],
                $request['program_id']
            );
            $stmtEdit->execute();
        } elseif ($request['action'] === 'delete') {
            $stmtDel = $conn->prepare("DELETE FROM programs WHERE id=?");
            $stmtDel->bind_param("i", $request['program_id']);
            $stmtDel->execute();
        }

        // Update request status
        $stmtUpdate = $conn->prepare("UPDATE program_requests SET status='approved', reviewed_by=?, reviewed_at=NOW() WHERE id=?");
        $stmtUpdate->bind_param("ii", $_SESSION['user_id'], $request_id);
        $stmtUpdate->execute();

        header('Location: program_requests.php?status=approved');
        exit;

    } elseif ($action_type === 'reject') {
        $stmt = $conn->prepare(
            "UPDATE program_requests
             SET status='rejected',
                 rejection_reason=?,
                 reviewed_by=?,
                 reviewed_at=NOW()
             WHERE id=?"
        );
        $stmt->bind_param("sii", $reason, $_SESSION['user_id'], $request_id);
        $stmt->execute();

        header('Location: program_requests.php?status=rejected');
        exit;
    }
}

// Filter dropdown
$current_status = $_GET['filter'] ?? 'pending';
$allowed_status = ['pending','approved','rejected','all'];
if (!in_array($current_status,$allowed_status)) $current_status='pending';
$where = $current_status==='all' ? "1=1" : "r.status='".$conn->real_escape_string($current_status)."'";

// Fetch requests
$sql = "SELECT 
            r.id,
            r.program_id,
            r.requested_by,
            r.status,
            r.requested_at,
            r.faculty,
            r.program_name as req_program_name,
            r.program_code,
            r.ugpg,
            r.target,
            r.achieve,
            r.partial_accreditation,
            r.full_accreditation,
            r.mod_penyampaian,
            r.action as user_action,
            r.rejection_reason,
            p.program_name as current_program_name,
            p.program_code as current_program_code,
            p.ugpg as current_ugpg,
            p.target as current_target,
            p.achieve as current_achieve,
            p.partial_accreditation as current_partial,
            p.full_accreditation as current_full,
            p.mod_penyampaian as current_mod,
            u.username
        FROM program_requests r
        LEFT JOIN programs p ON r.program_id = p.id
        LEFT JOIN user u ON r.requested_by = u.id
        WHERE $where
        ORDER BY r.requested_at DESC";

$result = $conn->query($sql);
function h($str){return htmlspecialchars($str ?? '',ENT_QUOTES,'UTF-8');}
if (!$result) die("SQL error: ".$conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Program Requests</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
<style>

body {
  background:#f5f6fa;
  font-family:'Inter',system-ui,sans-serif;
  color:#2c2c2c;
  line-height:1.5;
  margin:0;
}
.container {
  background:#fff;
  margin:30px auto;
  padding:32px;
  max-width:1100px;
  border-radius:12px;
  box-shadow:0 4px 12px rgba(0,0,0,0.06);
}
h2 {font-size:24px;font-weight:600;margin-bottom:24px;}
.filter-select {
  padding:8px 12px;
  font-size:15px;
  border:1px solid #ccc;
  border-radius:6px;
  margin-bottom:20px;
}
.card-request {
  border:1px solid #e5e7eb;
  border-radius:12px;
  padding:20px;
  margin-bottom:20px;
  box-shadow:0 2px 4px rgba(0,0,0,.04);
  transition:box-shadow .2s ease;
}
.card-request:hover {box-shadow:0 4px 8px rgba(0,0,0,.06);}
.card-top {display:flex;justify-content:space-between;align-items:flex-start;}
.program-title {font-size:18px;font-weight:600;margin:0;color:#111827;}
.badge {
  padding:4px 10px;border-radius:6px;font-size:12px;font-weight:500;color:#fff;
}
.status-pending {background:#fbbf24;}
.status-approved {background:#10b981;}
.status-rejected {background:#ef4444;}
.action {font-size:14px;color:#2563eb;margin-top:8px;}
.meta {font-size:13px;color:#6b7280;margin-top:4px;}
.rejection-reason {font-size:13px;color:#ef4444;margin-top:8px;}
.btn {
  display:inline-block;padding:6px 14px;font-size:14px;font-weight:500;
  border:none;border-radius:6px;cursor:pointer;transition:background-color .2s ease;
}
.btn-primary {background:#2563eb;color:#fff;}
.btn-primary:hover {background:#1d4ed8;}
.details-table {
  width:100%;border-collapse:collapse;font-size:14px;margin-top:10px;
}
.details-table td {border:1px solid #e5e7eb;padding:8px 10px;}
.details-table td:first-child {background:#f9fafb;font-weight:500;width:40%;}
.swal2-popup {font-family:'Inter',system-ui,sans-serif !important;}
@media (max-width:600px){
  .container {padding:20px;}
  .program-title {font-size:16px;}
}
</style>
</head>
<body>
<?php include 'navbar_admin.php'; ?>

<div class="container">
  <h2>Program Requests</h2>

  <form method="get">
    <select name="filter" onchange="this.form.submit()">
      <option value="pending" <?=$current_status==='pending'?'selected':''?>>Pending</option>
      <option value="approved" <?=$current_status==='approved'?'selected':''?>>Approved</option>
      <option value="rejected" <?=$current_status==='rejected'?'selected':''?>>Rejected</option>
      <option value="all" <?=$current_status==='all'?'selected':''?>>All</option>
    </select>
  </form>

  <?php if ($result && $result->num_rows>0): ?>
    <?php while ($row = $result->fetch_assoc()):
      $programName = $row['user_action']==='delete' ? $row['req_program_name'] : ($row['req_program_name'] ?: $row['current_program_name']);
    ?>
      <div class="card-request">
        <div class="card-top">
          <h5 class="program-title"><?=h($programName)?></h5>
          <span class="badge status-<?=h($row['status'])?>"><?=ucfirst(h($row['status']))?></span>
        </div>

        <div class="action">
          <?=h($row['username'])?> requested to <?=h($row['user_action'])?> “<?=h($programName)?>”
        </div>
        <div class="meta">
          Faculty: <strong><?=h($row['faculty'])?></strong> on <?=h($row['requested_at'])?>
        </div>
        <?php if($row['status']==='rejected' && $row['rejection_reason']): ?>
          <div class="rejection-reason">Rejection reason: <?=h($row['rejection_reason'])?></div>
        <?php endif; ?>
        <button class="btn btn-primary"
                onclick="openModal(<?= (int)$row['id']?>,'<?=h($row['status'])?>','<?=h($row['username'])?>','<?=h($programName)?>','<?=h($row['user_action'])?>')">View Details</button>
      </div>

      <form id="form<?=$row['id']?>" method="post">
        <input type="hidden" name="request_id" value="<?=$row['id']?>">
        <input type="hidden" name="action_type">
        <textarea name="rejection_reason" style="display:none"></textarea>
      </form>

      <div id="details<?=$row['id']?>" style="display:none">
        <table class="details-table">
          <tr><td>User Action</td><td><?=h($row['user_action'])?></td></tr>
          <tr><td>Requested Program Name</td><td><?=h($row['req_program_name'])?></td></tr>

          <?php if($row['user_action']==='edit' || $row['user_action']==='delete'): ?>
            <tr><td>Current Program Name</td><td><?=h($row['current_program_name'])?></td></tr>
          <?php endif; ?>

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
    <p>No requests.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openModal(id,status,username,programName,userAction){
  let details = document.getElementById('details'+id).innerHTML;
  let showButtons = (status==='pending');

  Swal.fire({
    title:'Request Details',
    html:details,
    width:600,
    showCloseButton:true,
    showDenyButton:showButtons,
    showCancelButton:false,
    confirmButtonText: showButtons?'Approve':'Close',
    denyButtonText: showButtons?'Reject':undefined,
    focusConfirm:false
  }).then(result=>{
    if(showButtons){
      if(result.isConfirmed){
        // Double confirmation for Approve
        Swal.fire({
          title: 'Are you sure?',
          text: 'Approving this request cannot be undone.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, Approve',
          cancelButtonText: 'Cancel'
        }).then(confirmResult=>{
          if(confirmResult.isConfirmed){
            let form=document.getElementById('form'+id);
            form.querySelector('[name=action_type]').value='approve';
            form.submit();
          }
        });

      } else if(result.isDenied){
        // rejection reason
        Swal.fire({
          title:'Reason for rejection',
          input:'textarea',
          inputPlaceholder:'Enter reason...',
          showCancelButton:true,
          confirmButtonText:'Next'
        }).then(r=>{
          if(r.isConfirmed && r.value.trim()!==''){
            let reason = r.value.trim();
            // Double confirmation for Reject
            Swal.fire({
              title: 'Are you sure?',
              text: 'Rejecting this request cannot be undone.',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, Reject',
              cancelButtonText: 'Cancel'
            }).then(confirmReject=>{
              if(confirmReject.isConfirmed){
                let form=document.getElementById('form'+id);
                form.querySelector('[name=action_type]').value='reject';
                form.querySelector('[name=rejection_reason]').value=reason;
                form.submit();
              }
            });
          }
        });
      }
    }
  });
}


</script>
</body>
</html>
