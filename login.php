<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM teachers WHERE username='$username'";
    $result = mysqli_query($con, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['course'] = $row['course']; // Store teacher's assigned course
            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            $error = "❌ Invalid credentials!";
        }
    } else {
        $error = "⚠️ User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login | Student Attendance Management System</title>
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

        /* Login Container */
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
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
        }

        /* Input Fields */
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: 0.3s;
            outline: none;
        }

        input:focus {
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

        /* Register Link */
        .register-link {
            margin-top: 15px;
            display: block;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="header">📚 Student Attendance Management System</div>

    <div class="container">
        <h2>🔑 Teacher Login</h2>
        <?php if (isset($error))
            echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <a href="register_teacher.php" class="register-link">Not registered? Sign up</a>
    </div>

</body>

</html>
