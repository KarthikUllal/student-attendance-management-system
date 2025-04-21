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

        .table th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-center w-100">Attendance Records</h2>
            </div>

            <div class="card-body">
                <?php
                if (isset($_POST['date']) && !empty($_POST['date'])) {
                    $date = mysqli_real_escape_string($con, $_POST['date']);
                    $query = "SELECT * FROM attendence_records WHERE date = '$date' AND course = '$teacher_course'";
                    $result = mysqli_query($con, $query);
                    $serialnumber = 0;

                    if (mysqli_num_rows($result) > 0) {
                        ?>
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
                                while ($row = mysqli_fetch_array($result)) {
                                    $serialnumber++;
                                    ?>
                                    <tr>
                                        <td><?php echo $serialnumber; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['class']); ?></td>
                                        <td><?php echo htmlspecialchars($row['section']); ?></td>
                                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                                        <td>
                                            <strong
                                                class="<?php echo ($row['attendence_status'] == 'Present') ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo htmlspecialchars($row['attendence_status']); ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo "<div class='alert alert-warning text-center'>⚠️ No records found for this date and course.</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning text-center'>⚠️ No date selected. Please go back and choose a date.</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
