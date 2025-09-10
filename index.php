<?php
include 'connect.php';


// Summary counts
$total = $conn->query("SELECT COUNT(*) AS total FROM programs")->fetch_assoc()['total'];
$ug = $conn->query("SELECT COUNT(*) AS ug FROM programs WHERE ugpg='UG'")->fetch_assoc()['ug'];
$pg = $conn->query("SELECT COUNT(*) AS pg FROM programs WHERE ugpg='PG'")->fetch_assoc()['pg'];
$faculties_count = $conn->query("SELECT COUNT(DISTINCT faculty) AS faculty_count FROM programs")->fetch_assoc()['faculty_count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Program Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .highlight-nav {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin: 40px auto;
            max-width: 900px;
        }
        .highlight-nav a {
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            width: 250px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .highlight-nav a:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
        }
        .highlight-nav h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #2980b9;
        }
        .highlight-nav p {
            font-size: 0.95rem;
        }
        .summary-section {
            max-width: 900px;
            margin: 30px auto;
            text-align: center;
        }
        .summary-group-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .summary-cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .summary-cards .card {
            width: 180px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .summary-cards .card h2 {
            color: #27ae60;
            font-size: 1.8rem;
        }
        .summary-cards .card p {
            margin: 0;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="list_programs.php" class="active">List Programs</a>
        <a href="manage_program.php">Manage Programs</a>
    </div>

    <h1 style="text-align: center;margin-top: 30px; margin-bottom: 20px;">Program Management</h1>

    <div class="highlight-nav">
        <a href="list_programs.php">
            <h3> Program List</h3>
            <p>View and filter all academic programs</p>
        </a>
        <a href="manage_program.php">
            <h3> Manage Programs</h3>
            <p>Add, edit, or delete program details</p>
        </a>
    </div>

    <div class="summary-section">
        <div class="summary-group-card">
            <h2 style="margin-top: 20px; margin-bottom: 30px;">Program Summary</h2>
            <div class="summary-cards">
                <div class="card">
                    <h2><?= $total ?></h2>
                    <p>Total Programs</p>
                </div>
                <div class="card">
                    <h2><?= $ug ?></h2>
                    <p>Undergraduate (UG)</p>
                </div>
                <div class="card">
                    <h2><?= $pg ?></h2>
                    <p>Postgraduate (PG)</p>
                </div>
                <div class="card">
                    <h2><?= $faculties_count ?></h2>
                    <p>Faculties Involved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div style="max-width:1000px; margin:40px auto;">
        <h2 style="text-align:center;">Programs by Faculty</h2>
        <canvas id="facultyChart"></canvas>
        <div style="text-align:center; margin-top: 20px;">
            <label for="chartType">View by:</label>
            <select id="chartType" onchange="updateChart()">
                <option value="total" selected>Total Programs</option>
                <option value="ugpg">Undergraduate/Postgraduate</option>
                <option value="mode">Mode of Delivery</option>
                <option value="all">All (Grouped + Stacked)</option>
            </select>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;

        const chartColors = {
            total: ['#9691fdff'],
            ugpg: ['#e4bca0ff', '#ea580c'],
            mode: ['#68b19bff', '#065f46']
        };

        function createChart(labels, data, type) {
            const ctx = document.getElementById('facultyChart').getContext('2d');
            if (chart) chart.destroy();

            let datasets = [];

            if (type === 'total') {
                datasets.push({
                    label: 'Total Programs',
                    data: data.map(r => r.total),
                    backgroundColor: chartColors.total[0],
                    stack: 'total'
                });
            } else if (type === 'ugpg') {
                datasets = [
                    { label: 'Undergraduate', data: data.map(r => r.ug), backgroundColor: chartColors.ugpg[0], stack: 'ugpg' },
                    { label: 'Postgraduate', data: data.map(r => r.pg), backgroundColor: chartColors.ugpg[1], stack: 'ugpg' }
                ];
        
            } else if (type === 'mode') {
                datasets = [
                    { label: 'Conventional', data: data.map(r => r.conventional), backgroundColor: chartColors.mode[0], stack: 'mode' },
                    { label: 'ODL', data: data.map(r => r.odl), backgroundColor: chartColors.mode[1], stack: 'mode' }
                ];
            } else if (type === 'all') {
                datasets = [
                    { label: 'Total Programs', data: data.map(r => r.total), backgroundColor: chartColors.total[0], stack: 'total' },
                    { label: 'Undergraduate', data: data.map(r => r.ug), backgroundColor: chartColors.ugpg[0], stack: 'ugpg' },
                    { label: 'Postgraduate', data: data.map(r => r.pg), backgroundColor: chartColors.ugpg[1], stack: 'ugpg' },
                    { label: 'Conventional', data: data.map(r => r.conventional), backgroundColor: chartColors.mode[0], stack: 'mode' },
                    { label: 'ODL', data: data.map(r => r.odl), backgroundColor: chartColors.mode[1], stack: 'mode' }
                ];
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: { labels: labels, datasets: datasets },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        x: { stacked: type !== 'total' },
                        y: { beginAtZero: true, stacked: type !== 'total' }
                    }
                }
            });
        }

        function updateChart() {
            const type = document.getElementById('chartType').value;
            fetch(`filter_chart_data.php?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    const faculties = data.map(r => r.faculty);
                    createChart(faculties, data, type);
                });
        }

        updateChart();
    </script>
</body>
</html>
