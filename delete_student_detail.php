<?php

session_start();
include 'dbconnect.php';

if (empty($_SESSION['email'])) {
    $_SESSION['login_first'] = "Please login first.";
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete_student'])) {
    $delete = (int) $_GET['delete_student'];

    // Look up the photo filename so we can remove the file from disk after the row is deleted.
    $stmt = mysqli_prepare($conn, "SELECT photo FROM student_detail WHERE st_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $delete);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && !empty($row['photo'])) {
        @unlink('st_image/' . $row['photo']);
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM student_detail WHERE st_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $delete);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header('Location: view_student.php?delete_msg=Data deleted successfully');
        exit;
    }
    mysqli_stmt_close($stmt);
}
