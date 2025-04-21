<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar Styling */
        nav {
            background: #333;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Logo */
        .logo {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            color: #ffcc00;
            text-decoration: none;
        }

        /* Navigation Links */
        .nav-links {
            list-style: none;
            display: flex;
            align-items: center;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background-color: #ffcc00;
            color: black;
        }

        /* Responsive Navbar */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle div {
            width: 30px;
            height: 4px;
            background: white;
            margin: 5px 0;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 60px;
                left: 0;
                background: #333;
                text-align: center;
                padding: 15px 0;
            }

            .nav-links li {
                margin: 10px 0;
            }

            .menu-toggle {
                display: flex;
            }

            .nav-links.show {
                display: flex;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo">Teacher Portal</a>
        <div class="menu-toggle" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="add.php">Add Record</a></li>
            <!-- <li><a href="show_attendence.php">Show Attendance</a></li> -->
            <li><a href="view_all.php">View All</a></li>
            <?php if (isset($_SESSION['teacher_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="view_my_students.php">My Students</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="view_students.php">View Students</a>
            </li>

        </ul>
    </nav>

    <script>
        function toggleMenu() {
            document.querySelector('.nav-links').classList.toggle('show');
        }
    </script>

</body>

</html>