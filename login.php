<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Personal Finance Tracker - Log in form</title>
    
    <!-- Inline CSS for background image and form styling -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('resorce/images/bgg.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form-bg {
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency for the form */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Stronger shadow for more contrast */
            width: 400px;
        }

        .card {
            border: none;
            background-color: transparent;
        }

        .card-body {
            padding: 30px;
        }

        h4 {
            font-size: 2.2rem; /* Larger font to fit in one line */
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            display: block;
            text-align: left; /* Align label text to the left */
            margin-bottom: 5px;
        }

        .form-control {
            border: 1px solid #bdc3c7;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left; /* Align input text to the left */
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
            text-align: center; /* Center align footer text */
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

<body>

    <!-- PHP Login Script -->
    <?php 
    $emailErr = $passErr = $loginErr =  "";
    $email = $pass = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "include/database-connection.php";
        
        if (empty($_POST["email"])) {
            $emailErr = "<p style='color:red'>* Email is required</p>";
        } else {
            $email = trim($_POST["email"]);
        }

        if (empty($_POST["password"])) {
            $passErr = "<p style='color:red'>* Password is required</p>";
        } else {
            $pass = trim($_POST["password"]);
        }

        if (!empty($email) && !empty($pass)) {
            $sql_command = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
            $result = mysqli_query($conn, $sql_command);
            $row = mysqli_num_rows($result);

            if ($row > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    session_start();
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["name"] = $row["name"];
                    $_SESSION["dob"] = $row["dob"];
                    header("Location: index.php?login-success");
                }
            } else {
                $loginErr = "<script>alert('Sorry! Invalid Email/Password!!');</script>";
            }
        }
    }
    ?>

    <!-- Login Form -->
    <div class="login-form-bg">
        <div class="card">
            <div class="card-body">
                <h4>Personal Finance Tracker</h4>
                <?php echo $loginErr; ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                        <?php echo $emailErr; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                        <?php echo $passErr; ?>
                    </div>
                    <button type="submit" class="btn">Sign In</button>
                </form>
                <p class="login-form__footer">Don't have an account? <a href="signup.php">Sign Up</a> now</p>
            </div>
        </div>
    </div>

</body>

</html>
