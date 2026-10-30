<?php

session_start();
include 'dbconnect.php';

$email_err = $pws_err = $success = $error = '';

if (isset($_POST['submit'])) {
    $fname     = $_POST['fname'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $pass  = password_hash($password, PASSWORD_BCRYPT);
    $cpass = password_hash($cpassword, PASSWORD_BCRYPT);

    // Check whether the email is already registered.
    $stmt = mysqli_prepare($conn, "SELECT st_id FROM register WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($rows > 0) {
        $email_err = "Email already exists. Please try another email.";
    } else if ($password !== $cpassword) {
        $pws_err = "Your password does not match";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO register (fname, email, password, cpassword) VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, 'ssss', $fname, $email, $pass, $cpass);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Registered Successfully.";
        } else {
            $error = "Unable to submit data. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function content1() {
            document.getElementById("div1").style.display = "block";
            document.getElementById("div2").style.display = "none";
        }
        function content2() {
            document.getElementById("div1").style.display = "none";
            document.getElementById("div2").style.display = "block";
        }
    </script>
</head>
<body>
    <section class="section">
        <h4 class="text-center text-warning font-weight-bold pt-3"> <?php echo @$_SESSION['login_first']; ?> </h4>
        <h2 class="text-center text-danger pt-4 pb-2 font-weight-bold">Student Management System</h2>
        <p class="text-center font-weight-bold text-danger"><?php echo @$_GET['error']; ?></p>
        <div class="container bg-danger" id="formsetting">
            <h3 class="text-white text-center py-3">Register Panel | Login</h3>
            <div class="row">
                <div class="col-ms-7 col-sm-7 col 12">
                    <img src="images/work-net.jpg" class="img-fluid">
                </div>
                <div class="col-ms-5 col-sm-5 col 12">
                    <button class="btn btn-info px-5" onclick="content1()">Register</button>
                    <button class="btn btn-info px-5" onclick="content2()">Login</button>

                    <!-- Register form -->
                    <div id="div1" style="display:block" class="mt-4">
                        <form method="post" action="">
                            <div class="form-group">
                                <label class="text-white">Full name</label>
                                <input type="text" name="fname" placeholder="Enter your name" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label class="text-white">Email</label>
                                <span class="float-right text-white font-weight-bold"><?php echo $email_err; ?></span>
                                <input type="email" name="email" placeholder="Enter your email" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label class="text-white">Password</label>
                                <input type="password" name="password" placeholder="Enter your password" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label class="text-white">Confirm Password</label>
                                <span class="float-right text-white font-weight-bold"><?php echo $pws_err; ?></span>
                                <input type="password" name="cpassword" placeholder="Re-enter your password" class="form-control" required="required">
                            </div>
                            <input type="submit" name="submit" value="Register" class="btn btn-success px-5">
                            <span class="float-right text-white font-weight-bold"><?php echo $success; echo $error; ?></span>
                        </form>
                    </div>

                    <!-- Login form -->
                    <div id="div2" style="display:none" class="mt-4">
                        <form method="post" action="">
                            <div class="form-group">
                                <label class="text-white">Email</label>
                                <input type="email" name="email" placeholder="Enter your email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-white">Password</label>
                                <input type="password" name="password" placeholder="Enter your password" class="form-control">
                            </div>
                            <input type="submit" name="submit1" value="Login" class="btn btn-success px-5">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

<?php
if (isset($_POST['submit1'])) {
    $email = $_SESSION['email'] = $_POST['email'];
    $pwd   = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT password FROM register WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && password_verify($pwd, $row['password'])) {
        echo "<script>window.open('main.php?success=Loggedin successfully', '_self')</script>";
    } else {
        echo "<script>window.open('index.php?error=Username or Password is incorrect', '_self')</script>";
    }
}
?>
