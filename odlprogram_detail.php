<?php 
$conn = new mysqli("localhost", "root", "", "mydb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$program_id = $_GET['id'] ?? 0;

// Fetch program + details + coordinator
$sql = "
    SELECT 
        p.id, 
        p.faculty, 
        p.program_name, 
        p.program_code, 
        p.ugpg, 
        p.target,
        p.partial_accreditation, 
        p.full_accreditation, 
        p.mod_penyampaian,
        pd.duration, 
        pd.recognition, 
        pd.prerequisite, 
        pd.plo, 
        pd.peo, 
        pd.description, 
        pd.feesft_local, 
        pd.feesft_international, 
        pd.feespt_local, 
        pd.feespt_international,
        i.name AS coordinator_name,
        i.email AS coordinator_email
    FROM programs p
    LEFT JOIN program_details pd ON p.id = pd.program_id
    LEFT JOIN instructors i ON i.program_id = p.id AND i.designation = 'coordinator'
    WHERE p.id = $program_id
";
$program = $conn->query($sql)->fetch_assoc();

// Fetch instructors (not coordinators)
$instructors_sql = "
    SELECT name, email, designation
    FROM instructors
    WHERE program_id = $program_id AND designation <> 'coordinator'
";
$instructors = $conn->query($instructors_sql);

// Fetch courses grouped by semester
$courses_sql = "
    SELECT c.course_code, c.course_name, c.credit_hours, sc.sem_no
    FROM courses c
    JOIN semester_course sc ON c.id = sc.course_id
    WHERE sc.program_id = $program_id
    ORDER BY sc.sem_no ASC, c.course_code ASC
";
$courses = $conn->query($courses_sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Program Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f9fafb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .program-header {
      background: linear-gradient(90deg, #2a8af190, #2c3e50);
      color: white;
      border-radius: 12px;
      padding: 20px 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .coordinator-card {
      border: 2px solid #6610f2;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(102,16,242,0.15);
      background: #fff;
    }
    .coordinator-card h6 {
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    .semester-card {
      border-left: 5px solid #6610f2;
      border-radius: 10px;
      margin-bottom: 25px;
      background: #fff;
      padding: 1rem 1.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .semester-card h6 {
      background: #99caff90;
      padding: 10px 15px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1.1rem;
      color: #6610f2;
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
    }
    .badge-credit {
      color: #000 !important;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
      min-width: 28px;
      text-align: center;
    }

    .badge-total {
  background: #eef2ff;
  color: #4338ca;
  font-weight: 600;
  border-radius: 6px;
  padding: 4px 10px;
  font-size: 0.85rem;
}

    .instructor-card {
      border-radius: 12px;
      overflow: hidden;
      text-align: center;
      transition: 0.3s;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      background: #fff;
      min-width: 200px;
      max-width: 200px;
      flex: 0 0 auto;
    }
    .instructor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

<div class="container py-4">

  <!-- Header Row -->
  <div class="row mb-4">
    <!-- Program Title + Info -->
    <div class="col-lg-8">
      <div class="program-header h-100">
        <h2><?= htmlspecialchars($program['program_name']) ?> (<?= htmlspecialchars($program['program_code']) ?>)</h2>
        <p><strong>Faculty:</strong> <?= htmlspecialchars($program['faculty']) ?> | 
           <strong>Level:</strong> <?= htmlspecialchars($program['ugpg']) ?> | 
           <strong>Duration:</strong> <?= htmlspecialchars($program['duration']) ?></p>
      </div>
    </div>

    <!-- Coordinator + Recognition Cards -->
    <div class="col-lg-4 d-flex flex-column gap-3 mt-3 mt-lg-0">
      <?php if (!empty($program['coordinator_name'])): ?>
      <div class="coordinator-card p-3">
        <h6 class="text-primary">Coordinator</h6>
        <p class="mb-1 fw-semibold"><?= htmlspecialchars($program['coordinator_name']) ?></p>
        <a href="mailto:<?= htmlspecialchars($program['coordinator_email']) ?>" class="text-decoration-none">
          <?= htmlspecialchars($program['coordinator_email']) ?>
        </a>
      </div>
      <?php endif; ?>

      <?php if (!empty($program['recognition'])): ?>
      <div class="coordinator-card p-3">
        <h6 class="text-success">Recognition</h6>
        <p class="mb-0"><?= htmlspecialchars($program['recognition']) ?></p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Description -->
  <?php if (!empty($program['description'])): ?>
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="fw-bold mb-3">Programme Description</h5>
      <p><?= nl2br(htmlspecialchars($program['description'])) ?></p>
    </div>
  </div>
  <?php endif; ?>

  <!-- Extra Info Accordion -->
  <div class="accordion mb-5" id="programExtras">

    <?php if (!empty($program['peo'])): ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingPEO">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePEO">
          🎯 Programme Educational Objectives (PEO)
        </button>
      </h2>
      <div id="collapsePEO" class="accordion-collapse collapse show">
        <div class="accordion-body"><?= nl2br(htmlspecialchars($program['peo'])) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($program['plo'])): ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingPLO">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePLO">
          📌 Programme Learning Outcomes (PLO)
        </button>
      </h2>
      <div id="collapsePLO" class="accordion-collapse collapse">
        <div class="accordion-body"><?= nl2br(htmlspecialchars($program['plo'])) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($program['prerequisite'])): ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingPrereq">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrereq">
          📖 Entry Requirements / Prerequisites
        </button>
      </h2>
      <div id="collapsePrereq" class="accordion-collapse collapse">
        <div class="accordion-body"><?= nl2br(htmlspecialchars($program['prerequisite'])) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <div class="accordion-item">
      <h2 class="accordion-header" id="headingFees">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFees">
          💰 Programme Fees
        </button>
      </h2>
      <div id="collapseFees" class="accordion-collapse collapse">
        <div class="accordion-body">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Category</th>
                <th>Local (RM)</th>
                <th>International (RM)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Full-time</td>
                <td><?= htmlspecialchars($program['feesft_local']) ?></td>
                <td><?= htmlspecialchars($program['feesft_international']) ?></td>
              </tr>
              <tr>
                <td>Part-time</td>
                <td><?= htmlspecialchars($program['feespt_local']) ?></td>
                <td><?= htmlspecialchars($program['feespt_international']) ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Instructors (moved below accordion) -->
  <?php if ($instructors->num_rows > 0): ?>
  <h4 class="mb-3">Instructors</h4>
  <div class="position-relative mb-5">
    <!-- Left arrow -->
    <button class="btn btn-outline-primary position-absolute top-50 start-0 translate-middle-y" 
            id="instructorPrev" style="z-index: 2;">
      ‹
    </button>

    <!-- Scrollable row -->
    <div class="d-flex overflow-auto" id="instructorCarousel" style="scroll-behavior: smooth; gap: 1rem; padding: 0 3rem;">
      <?php while ($ins = $instructors->fetch_assoc()): ?>
        <div class="instructor-card p-3">
          <h6 class="fw-bold"><?= htmlspecialchars($ins['name']) ?></h6>
          <p class="text-muted small mb-2"><?= htmlspecialchars($ins['designation']) ?></p>
          <a href="mailto:<?= htmlspecialchars($ins['email']) ?>" class="btn btn-sm btn-primary">Contact</a>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Right arrow -->
    <button class="btn btn-outline-primary position-absolute top-50 end-0 translate-middle-y" 
            id="instructorNext" style="z-index: 2;">
      ›
    </button>
  </div>
  <?php endif; ?>

  <!-- Courses by Semester -->
  <h4 class="mb-3">Courses by Semester</h4>
  <?php
  $semester_courses = [];
  if ($courses->num_rows > 0) {
      while ($row = $courses->fetch_assoc()) {
          $sem = $row['sem_no'];
          $semester_courses[$sem][] = $row;
      }

      foreach ($semester_courses as $sem => $courses_arr) {
          $total_credits = array_sum(array_column($courses_arr, 'credit_hours'));
          echo "<div class='card semester-card'>";
          echo "<h6>Semester " . htmlspecialchars($sem) . " <span class='badge-total'>Total Credits: " . htmlspecialchars($total_credits) . "</span></h6>";
          echo "<table class='table table-hover mb-0'>";
          echo "<thead><tr><th>Course Code</th><th>Course Name</th><th>Credits</th></tr></thead><tbody>";
          foreach ($courses_arr as $course) {
              echo "<tr>
                      <td>" . htmlspecialchars($course['course_code']) . "</td>
                      <td>" . htmlspecialchars($course['course_name']) . "</td>
                      <td><span class='badge-credit'>" . htmlspecialchars($course['credit_hours']) . "</span></td>
                    </tr>";
          }
          echo "</tbody></table></div>";
      }
  } else {
      echo "<p>No courses found for this program.</p>";
  }
  ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const carousel = document.getElementById("instructorCarousel");
  document.getElementById("instructorPrev").addEventListener("click", () => {
    carousel.scrollBy({ left: -260, behavior: 'smooth' });
  });
  document.getElementById("instructorNext").addEventListener("click", () => {
    carousel.scrollBy({ left: 260, behavior: 'smooth' });
  });
</script>
</body>
</html>
