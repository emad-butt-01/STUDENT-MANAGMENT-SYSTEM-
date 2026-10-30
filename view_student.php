<?php

session_start();
include 'dbconnect.php';

if (empty($_SESSION['email'])) {
    $_SESSION['login_first'] = "Please login first.";
    header('Location: index.php');
    exit;
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
                <h3 class="text-center text-success"> <?php echo @$_GET['update_success']; echo @$_GET['update_error']; echo @$_GET['delete_msg']; ?> </h3>
                <h2 class="text-center text-danger pt-3 font-weight-bold">Student Management System</h2>
                <div class="container bg-danger" id="formsetting">
                    <h3 class="text-center text-white pb-4 pt-2 font-weight-bold">View Student Detail</h3>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <table class="table table-bordered text-white table-responsive">
                                <thead>
                                    <tr>
                                        <th>SNO.</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>Fathername</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Gender</th>
                                        <th>City</th>
                                        <th>District</th>
                                        <th>State</th>
                                        <th>Nationality</th>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // No user input is interpolated into this query, so a simple
                                // mysqli_query is safe. Prepared statements are reserved for
                                // queries that include user-supplied values.
                                $result = mysqli_query($conn, "SELECT * FROM student_detail");
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td> <?php echo $i++; ?> </td>
                                        <td> <?php echo htmlspecialchars($row['fname']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['lname']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['father_name']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['email']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['mobile']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['gender']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['city']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['district']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['state']); ?> </td>
                                        <td> <?php echo htmlspecialchars($row['nation']); ?> </td>
                                        <td>
                                            <a href="st_image/<?php echo htmlspecialchars($row['photo']); ?>">
                                                <img src="st_image/<?php echo htmlspecialchars($row['photo']); ?>" width="50" height="50">
                                            </a>
                                        </td>
                                        <td>
                                            <a style="color:white; text-decoration:none;" href="edit_student_detail.php?edit_student=<?php echo (int) $row['st_id']; ?>">Edit</a> |
                                            <a style="color:white; text-decoration:none;" href="delete_student_detail.php?delete_student=<?php echo (int) $row['st_id']; ?>">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
