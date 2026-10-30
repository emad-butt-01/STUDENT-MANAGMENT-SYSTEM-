<?php

session_start();
include 'dbconnect.php';

if (empty($_SESSION['email'])) {
    $_SESSION['login_first'] = "Please login first.";
    header('Location: index.php');
    exit;
}

$success = $error = $match_err = $size_error = '';

if (isset($_POST['add'])) {
    $fname       = $_POST['fname'];
    $lname       = $_POST['lname'];
    $email       = $_POST['email'];
    $fathername  = $_POST['fathername'];
    $gender      = $_POST['gender'];
    $city        = $_POST['city'];
    $district    = $_POST['district'];
    $nationality = $_POST['nationality'];
    $state       = $_POST['state'];
    $mobile      = $_POST['mobile'];

    $image      = $_FILES['image']['name'];
    $image_type = $_FILES['image']['type'];
    $image_size = $_FILES['image']['size'];
    $image_tmp  = $_FILES['image']['tmp_name'];

    $allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
    $max_size      = 2 * 1024 * 1024; // 2 MB in bytes

    if (empty($image)) {
        $match_err = "Please choose an image to upload.";
    } elseif (!in_array($image_type, $allowed_types, true)) {
        $match_err = "Invalid image format. Only JPG and PNG files are allowed.";
    } elseif ($image_size > $max_size) {
        $size_error = "Image is too large. Maximum size is 2 MB.";
    } else {
        move_uploaded_file($image_tmp, "st_image/$image");

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO student_detail
                (fname, lname, father_name, email, mobile, gender, district, city, state, nation, photo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'sssssssssss',
            $fname, $lname, $fathername, $email, $mobile,
            $gender, $district, $city, $state, $nationality, $image
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "Student data submitted successfully";
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
    <title>DBMS Project</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        jQuery(document).ready(function ($) {
            $('#toggler').click(function (event) {
                event.preventDefault();
                $('#wrap').toggleClass('toggled');
            });
        });
    </script>
</head>
<body>
    <div class="d-flex" id="wrap">
        <div class="sidebar bg-light border-light">
            <div class="sidebar-heading">
                <p class="text-center">Manage Students</p>
            </div>
            <ul class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-vcard-o"></i>Dashboard</a>
                <a href="add_student.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-user"></i>Add Student Detail</a>
                <a href="view_student.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-eye"></i>View Student Detail</a>
                <a href="edit_student_detail.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-pencil"></i>Edit Student Detail</a>
                <a href="add_teacher.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-user"></i>Add Teacher Detail</a>
                <a href="view_teacher.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-eye"></i>View Teacher Detail</a>
                <a href="edit_teacher.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-pencil"></i>Edit Teacher Detail</a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-sign-out"></i>Logout</a>
            </ul>
        </div>
        <div class="main-body">
            <button class="btn btn-outline-dark mt-3" id="toggler">
                <i class="fa fa-bars"></i>
            </button>
            <section id="main-form">
                <span class="text-center text-success font-weight-bold"> <?php echo $size_error; echo $match_err; ?> </span>
                <h2 class="text-center text-danger pt-3 font-weight-bold">Student Management System</h2>
                <div class="container bg-danger" id="formsetting">
                    <h3 class="text-center text-white pb-4 pt-2 font-weight-bold">Add Student Detail</h3>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-12 m-auto">
                                <div class="form-group">
                                    <label class="text-white">First Name</label>
                                    <input type="text" name="fname" placeholder="Enter your first name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Father name</label>
                                    <input type="text" name="fathername" placeholder="Enter your father name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Email</label>
                                    <input type="email" name="email" placeholder="Enter your email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">City</label>
                                    <input type="text" name="city" placeholder="Enter your city" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Nationality</label>
                                    <input type="text" name="nationality" placeholder="Enter your nationality" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Gender</label>
                                    <input type="radio" name="gender" value="male" class="form-check-input ml-2">
                                    <label class="form-check-label text-white pl-4">Male</label>
                                    <input type="radio" name="gender" value="female" class="form-check-input ml-2">
                                    <label class="form-check-label text-white pl-4">Female</label>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-12 m-auto">
                                <div class="form-group">
                                    <label class="text-white">Last Name</label>
                                    <input type="text" name="lname" placeholder="Enter your last name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Mobile</label>
                                    <input type="text" name="mobile" placeholder="Enter your number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">District</label>
                                    <input type="text" name="district" placeholder="Enter your district" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-white">State</label>
                                    <input type="text" name="state" placeholder="Enter your state" class="form-control">
                                </div>
                                <label class="text-white">Upload image</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupAddon01" name="image">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose File/Student image</label>
                                    </div>
                                </div>
                                <input type="submit" name="add" value="Add detail" class="btn btn-success px-5 mt-4 mb-1">
                                <span class="text-center text-success font-weight-bold"> <?php echo $success; echo $error; ?> </span>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
