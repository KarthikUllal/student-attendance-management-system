<?php
session_start();
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['course'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

$teacher_course = $_SESSION['course'];
$delete_message = "";

// Handle student deletion if request is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
    $delete_query = "DELETE FROM students WHERE id = '$student_id' AND course = '$teacher_course'";
    
    if (mysqli_query($con, $delete_query)) {
        $delete_message = "Student deleted successfully";
    } else {
        $delete_message = "Error deleting record";
    }
}

// Determine section filter
$selected_section = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter_section'])) {
    $selected_section = mysqli_real_escape_string($con, $_POST['filter_section']);
}

// Build query: if a specific section is selected, add condition; otherwise, show all
if (!empty($selected_section) && $selected_section !== 'all') {
    $query = "SELECT * FROM students WHERE course = '$teacher_course' AND section = '$selected_section'";
} else {
    $query = "SELECT * FROM students WHERE course = '$teacher_course'";
}
$result = mysqli_query($con, $query);

// Get distinct sections for this course (for dropdown)
$sections_query = "SELECT DISTINCT section FROM students WHERE course = '$teacher_course'";
$sections_result = mysqli_query($con, $sections_query);
$sections = [];
while ($row = mysqli_fetch_assoc($sections_result)) {
    $sections[] = $row['section'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 22px;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>My Students - <?php echo htmlspecialchars($teacher_course); ?></span>
            <a href="add.php" class="btn btn-success">+ Add Student</a>
        </div>
        <div class="card-body">
            <?php if (!empty($delete_message)): ?>
                <div class="alert alert-success text-center">
                    <?php echo $delete_message; ?>
                </div>
            <?php endif; ?>

            <!-- Section Filter Form -->
            <form method="POST" class="mb-3">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="filter_section" class="form-label">Select Section:</label>
                        <select name="filter_section" id="filter_section" class="form-control" required>
                            <option value="all" <?php if($selected_section == "all" || $selected_section=="") echo "selected"; ?>>All Sections</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section); ?>" <?php if($selected_section == $section) echo "selected"; ?>>
                                    <?php echo htmlspecialchars($section); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Filter Students</button>
                    </div>
                </div>
            </form>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-striped">
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
                        $serial = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $serial++;
                        ?>
                            <tr>
                                <td><?php echo $serial; ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['class']); ?></td>
                                <td><?php echo htmlspecialchars($row['section']); ?></td>
                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                        <!-- Keep current filter value when deleting -->
                                        <input type="hidden" name="filter_section" value="<?php echo htmlspecialchars($selected_section); ?>">
                                        <button type="submit" name="delete_student" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">No students found for your course <?php echo htmlspecialchars($teacher_course); ?><?php if($selected_section && $selected_section != 'all') echo " in Section " . htmlspecialchars($selected_section); ?>.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
