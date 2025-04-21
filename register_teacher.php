<?php
include("includes/db.php");

// Array of available courses. Add more courses here as needed.
$courses = ["DBMS", "DSA", "WT", "CFOS", "DMS", "IOT", "DSA LAB", "DBMS LAB", "WT LAB"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $course = $_POST['course']; // Selected course from dropdown

    $query = "INSERT INTO teachers (username, password, course) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $course);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "❌ Registration failed. Try a different username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration | Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            color: white;
        }
        /* Header */
        .header {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
        /* Registration Container */
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            transition: 0.3s;
            color: black;
        }
        .container:hover {
            transform: scale(1.02);
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
            white-space: nowrap;
            text-align: center;
            max-width: 100%;
            overflow: hidden;
        }
        /* Input Fields & Dropdown */
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: 0.3s;
            outline: none;
        }
        input:focus, select:focus {
            border-color: #667eea;
            box-shadow: 0px 0px 5px rgba(102, 126, 234, 0.5);
        }
        /* Button */
        .btn {
            background: #667eea;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #764ba2;
        }
        /* Error Message */
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        /* Login Link */
        .login-link {
            margin-top: 15px;
            display: block;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">📚 Student Attendance Management System</div>
    <div class="container">
        <h2>📝 Register Teacher</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Choose a Username" required>
            <input type="password" name="password" placeholder="Choose a Password" required>
            <!-- Dropdown for course selection -->
            <select name="course" required>
                <option value="">Select Course</option>
                <?php
                foreach($courses as $courseOption) {
                    echo "<option value=\"$courseOption\">$courseOption</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Register</button>
        </form>
        <a href="login.php" class="login-link">Already registered? Login</a>
    </div>
</body>
</html>
