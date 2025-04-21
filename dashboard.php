<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

// Fetch Total Students
$total_students_query = mysqli_query($con, "SELECT COUNT(*) as total FROM students");
$total_students = mysqli_fetch_assoc($total_students_query)['total'];

// Fetch Total Attendance Records
$total_attendance_query = mysqli_query($con, "SELECT COUNT(*) as total FROM attendence_records");
$total_attendance = mysqli_fetch_assoc($total_attendance_query)['total'];

// Fetch Attendance Summary
$present_query = mysqli_query($con, "SELECT COUNT(*) as total FROM attendence_records WHERE attendence_status = 'Present'");
$present_count = mysqli_fetch_assoc($present_query)['total'];

$absent_query = mysqli_query($con, "SELECT COUNT(*) as total FROM attendence_records WHERE attendence_status = 'Absent'");
$absent_count = mysqli_fetch_assoc($absent_query)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            font-size: 18px;
            font-weight: bold;
        }
        .present {
            color: green;
            font-weight: bold;
        }
        .absent {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container dashboard-container">
    <h2 class="text-center mb-4">📊 Teacher Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-primary text-white">Total Students</div>
                <div class="card-body">
                    <h3><?php echo $total_students; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-success text-white">Total Attendance Records</div>
                <div class="card-body">
                    <h3><?php echo $total_attendance; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-warning text-white">Attendance Summary</div>
                <div class="card-body">
                    <p class="present">Present: <?php echo $present_count; ?></p>
                    <p class="absent">Absent: <?php echo $absent_count; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 text-center">
        <a href="add.php" class="btn btn-primary">➕ Add Student</a>
        <a href="show_attendence.php" class="btn btn-info">📅 View Attendance</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
