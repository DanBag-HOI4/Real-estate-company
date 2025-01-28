<?php
session_start();
require_once "./connect.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$review_id = $_POST['review_id'];
$status = $_POST['status'];

$connect->query("UPDATE reviews SET status = '$status' WHERE id = $review_id");

header("Location: ../admin_res/admin.php");
?> 