<?php
session_start();
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['course'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

$teacher_course = $_SESSION['course']; // Teacher's assigned course
$delete_message = "";

// Handle Student Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $delete_query = "DELETE FROM students WHERE id = '$student_id'";
    
    if (mysqli_query($con, $delete_query)) {
        $delete_message = "Student deleted successfully!";
    } else {
        $delete_message = "Error deleting record!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students by Section</title>
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
                <h2 class="mb-0">View Students by Section</h2>
                <a href="add.php" class="btn btn-success">+ Add Student</a>
            </div>

            <div class="card-body">
                <?php if (!empty($delete_message)): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $delete_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="mb-3">
                    <label for="section" class="form-label">Select Section:</label>
                    <select name="section" id="section" class="form-control" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <!-- You can add more sections if needed -->
                    </select>
                    <button type="submit" class="btn btn-primary mt-3 w-100">View Students</button>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['section'])) {
                    $section = mysqli_real_escape_string($con, $_POST['section']);
                    // Only fetch students for the teacher's assigned course and the selected section
                    $query = "SELECT * FROM students WHERE section = '$section' AND course = '$teacher_course'";
                    $result = mysqli_query($con, $query);
                    $serialnumber = 0;

                    if (mysqli_num_rows($result) > 0) {
                        ?>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Serial No</th>
                                    <th>Student Name</th>
                                    <th>Roll Number</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Course</th>
                                    <th>Action</th>
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
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete_student" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo "<div class='alert alert-warning text-center'>⚠️ No students found in Section $section for your course ($teacher_course).</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
