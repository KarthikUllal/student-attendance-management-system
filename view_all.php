<?php
session_start();
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['course'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

$teacher_course = $_SESSION['course']; // Get the teacher's assigned course
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 20px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 15px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Student Attendance Records</h2>
            <div>
                <a class="btn btn-success" href="add.php">➕ Add Students</a>
                <a class="btn btn-info" href="index.php">🔙 Back</a>
            </div>
        </div>

        <div class="card-body">
            <?php
            // Only show attendance records for the teacher's assigned course
            $result = mysqli_query($con, "SELECT DISTINCT date FROM attendence_records WHERE course = '$teacher_course'");
            if (mysqli_num_rows($result) > 0) {
            ?>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serialnumber = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $serialnumber++;
                        ?>
                        <tr>
                            <td><?php echo $serialnumber; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td>
                                <form action="show_attendence.php" method="POST">
                                    <input type="hidden" name="date" value="<?php echo $row['date'] ?>">
                                    <input type="submit" class="btn btn-primary" value="📅 Show Attendance">
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php 
            } else {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        ⚠️ No attendance records found.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
