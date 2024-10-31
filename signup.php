<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Personal Finance Tracker - Sign Up</title>

    <!-- External Resources -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="resorce/css/style.css" rel="stylesheet">

    <!-- Inline CSS for background and form styling -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('resorce/images/signupp.png') no-repeat center center fixed; /* Ensure correct image path */
            background-size: cover; /* Background covers full page */
        }

        .login-form-bg {
            background-color: rgba(255, 255, 255, 0.9); /* Transparent background for the form */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Shadow effect */
            max-width: 500px; /* Set a max-width for the form */
            margin: 100px auto; /* Center the form */
        }

        .card {
            border: none;
            background-color: transparent;
        }

        .card-body {
            padding: 30px;
        }

        h4 {
            font-size: 2.2rem; /* Larger font size */
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            border: 1px solid #bdc3c7;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .login-form__footer {
            margin-top: 20px;
            text-align: center;
        }

        .login-form__footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .login-form__footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="h-100">

    <!-- PHP SignUp Script -->
    <?php
    $nameErr = $emailErr = $dobErr = $passErr = $confirm_passErr= "";
    $name = $email = $dob = $pass = $confirm_pass = "";
    $signup_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "include/database-connection.php";

        if (empty($_POST["name"])) {
            $nameErr = "<p style='color:red'>* Name is required</p>";
        } else {
            $name = trim($_POST["name"]);
        }

        if (empty($_POST["email"])) {
            $emailErr = "<p style='color:red'>* Email is required</p>";
        } else {
            $email = trim($_POST["email"]);
        }

        if (empty($_POST["dob"])) {
            $dobErr = "<p style='color:red'>* Date of Birth is required</p>";
        } else {
            $dob = trim($_POST["dob"]);
        }

        if (empty($_POST["password"])) {
            $passErr = "<p style='color:red'>* Password is required</p>";
        } else {
            $pass = trim($_POST["password"]);
        }

        if ($_POST["password"] !== $_POST["confirmPassword"]) {
            $confirm_passErr = "<p style='color:red'>* Passwords do not match</p>";
        } else {
            $confirm_pass = trim($_POST["confirmPassword"]);
        }

        if (!empty($name) && !empty($email) && !empty($dob) && !empty($pass) && $confirm_pass) {
            $check_email_query = "SELECT * FROM users WHERE email = '$email'";
            $existing_user = mysqli_query($conn, $check_email_query);
            $row_count = mysqli_num_rows($existing_user);

            if ($row_count > 0) {
                $signup_err = "<script>alert('Sorry! This email is already registered.')</script>";
            } else {
                $insert_query = "INSERT INTO users (name, dob, email, password) VALUES ('$name', '$dob', '$email', '$pass')";
                $result = mysqli_query($conn, $insert_query);
                header("Location: login.php?registration-successful");
            }
        }
    }
    ?>

    <!-- Signup Form -->
    <div class="login-form-bg">
        <div class="card">
            <div class="card-body shadow">
                <h4 class="text-center">Personal Finance Tracker</h4>
                <?php echo $signup_err; ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" value="<?php echo $name; ?>" name="name" required>
                        <?php echo $nameErr; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" value="<?php echo $email; ?>" name="email" required>
                        <?php echo $emailErr; ?>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" class="form-control" value="<?php echo $dob; ?>" name="dob" required>
                        <?php echo $dobErr; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                        <?php echo $passErr; ?>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirmPassword" required>
                        <?php echo $confirm_passErr; ?>
                    </div>

                    <button type="submit" class="btn">Sign Up</button>
                </form>
                <p class="mt-5 login-form__footer">Have an account? <a href="login.php" class="text-primary">Sign In</a> now</p>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script src="resorce/plugins/common/common.min.js"></script>
    <script src="resorce/js/custom.min.js"></script>
    <script src="resorce/js/settings.js"></script>
    <script src="resorce/js/gleek.js"></script>
    <script src="resorce/js/styleSwitcher.js"></script>
</body>

</html>
