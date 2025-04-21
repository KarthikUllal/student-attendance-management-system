<?php
session_start();
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['course'])) {
    header("Location: login.php");
    exit();
}

include("includes/navigation.php");
include("includes/header.php");
include("includes/db.php");

$flag = 0;
if (isset($_POST['submit'])) {
    // Teacher's assigned course is stored in the session
    $course = $_SESSION['course'];
    
    // Sanitize form inputs
    $student_name = mysqli_real_escape_string($con, $_POST['name']);
    $roll_number  = mysqli_real_escape_string($con, $_POST['roll']);
    $class        = mysqli_real_escape_string($con, $_POST['class']);
    $section      = mysqli_real_escape_string($con, $_POST['section']);

    // Insert new student with teacher's course
    $query = "INSERT INTO students (student_name, roll_number, class, section, course)
              VALUES ('$student_name', '$roll_number', '$class', '$section', '$course')";
    if (mysqli_query($con, $query)) {
        $flag = 1;
    } else {
        die("Error: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 600px;
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
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <?php if ($flag) { ?>
                <div class="alert alert-success text-center">
                    ✅ Student Added Successfully!
                </div>
            <?php } ?>
            <div class="card-header">Add Student</div>
            <div class="card-body">
                <form action="add.php" method="post">
                    <div class="form-group mb-3">
                        <label for="name">Student Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter student name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="roll">Roll Number</label>
                        <input type="text" name="roll" id="roll" class="form-control" placeholder="Enter roll number" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="class">Class</label>
                        <input type="text" name="class" id="class" class="form-control" placeholder="Enter class" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="section">Section</label>
                        <input type="text" name="section" id="section" class="form-control" placeholder="Enter section" required>
                    </div>
                    
                    <!-- The course is automatically assigned from the teacher's session -->
                    <input type="hidden" name="course" value="<?php echo $_SESSION['course']; ?>">
                    
                    <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="index.php" class="btn btn-info">Take Attendence</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
