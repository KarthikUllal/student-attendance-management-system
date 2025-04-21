<?php
session_start();
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['course'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

// Get teacher's assigned course from the session
$teacher_course = $_SESSION['course'];

$flag = 0;
$update = 0;

if (isset($_POST['submit']) && isset($_POST['attendence_status'])) {
    $date = date("Y-m-d");

    // Fetch existing attendance records for today
    $records = mysqli_query($con, "SELECT * FROM attendence_records WHERE date = '$date'");
    $num = mysqli_num_rows($records);

    if ($num > 0) {
        // Update existing attendance records
        foreach ($_POST['attendence_status'] as $id => $attendence_status) {
            $student_name = mysqli_real_escape_string($con, $_POST['student_name'][$id]);
            $roll_number = mysqli_real_escape_string($con, $_POST['roll_number'][$id]);

            $result = mysqli_query($con, "UPDATE attendence_records 
                SET attendence_status = '$attendence_status' 
                WHERE roll_number = '$roll_number' AND date = '$date'");

            if ($result) {
                $update = 1;
            }
        }
    } else {
        // Insert new attendance records
        foreach ($_POST['attendence_status'] as $id => $attendence_status) {
            $student_name = mysqli_real_escape_string($con, $_POST['student_name'][$id]);
            $roll_number = mysqli_real_escape_string($con, $_POST['roll_number'][$id]);
            $class = mysqli_real_escape_string($con, $_POST['class'][$id]);
            $section = mysqli_real_escape_string($con, $_POST['section'][$id]);
            // Force course to be teacher's assigned course
            $course = $teacher_course;

            $check = mysqli_query($con, "SELECT * FROM attendence_records WHERE roll_number = '$roll_number' AND date = '$date'");
            if (mysqli_num_rows($check) == 0) {
                $result = mysqli_query($con, "INSERT INTO attendence_records (student_name, roll_number, class, section, course, attendence_status, date) 
                    VALUES ('$student_name', '$roll_number', '$class', '$section', '$course', '$attendence_status', '$date')");

                if ($result) {
                    $flag = 1;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1100px;
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
        .alert-success {
            text-align: center;
            font-weight: bold;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .radio-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Student Attendance</h2>
            <div>
                <a class="btn btn-success" href="add.php">➕ Add Students</a>
                <a class="btn btn-info" href="view_all.php">📋 View All</a>
            </div>
        </div>

        <?php if ($flag) { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                ✅ <strong>Success!</strong> Attendance Data Inserted Successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <?php if ($update) { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                🔄 <strong>Updated!</strong> Student Attendance Updated Successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <h3>
            <div class="well text-center m-3 p-2 bg-light border">📅 Date: <?php echo date("Y-m-d"); ?></div>
        </h3>

        <div class="card-body">
            <!-- Only show students enrolled in teacher's course -->
            <form action="index.php" method="post">
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Student Name</th>
                            <th>Roll Number</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Course</th>
                            <th>Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM students WHERE course = '$teacher_course'");
                        $serialnumber = 0;
                        $counter = 0;

                        while ($row = mysqli_fetch_array($result)) {
                            $serialnumber++;
                        ?>
                            <tr>
                                <td><?php echo $serialnumber; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['student_name']); ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($row['student_name']); ?>" name="student_name[<?php echo $counter; ?>]">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['roll_number']); ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($row['roll_number']); ?>" name="roll_number[<?php echo $counter; ?>]">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['class']); ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($row['class']); ?>" name="class[<?php echo $counter; ?>]">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['section']); ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($row['section']); ?>" name="section[<?php echo $counter; ?>]">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['course']); ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($row['course']); ?>" name="course[<?php echo $counter; ?>]">
                                </td>
                                <td class="radio-group">
                                    <input type="radio" name="attendence_status[<?php echo $counter; ?>]" value="Present" required> Present
                                    <input type="radio" name="attendence_status[<?php echo $counter; ?>]" value="Absent"> Absent
                                </td>
                            </tr>
                        <?php
                            $counter++;
                        }
                        ?>
                    </tbody>
                </table>
                <input type="submit" name="submit" value="Submit Attendance" class="btn btn-primary w-100">
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
