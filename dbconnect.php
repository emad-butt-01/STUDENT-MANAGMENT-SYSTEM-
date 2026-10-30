<?php
// Loads credentials from config.php (kept outside source control) and opens
// a single mysqli connection used by every page that includes this file.

$config = require __DIR__ . '/config.php';

$conn = mysqli_connect(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name']
);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
