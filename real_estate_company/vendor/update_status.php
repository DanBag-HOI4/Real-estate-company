<?php
session_start();
require_once "./connect.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$property_id = $_POST['property_id'];
$status = $_POST['status'];

$query = "UPDATE properties SET status = '$status' WHERE id = $property_id";

if($connect->query($query)) {
    header("Location: ../admin_res/admin.php");
} else {
    $_SESSION['error'] = "Ошибка при обновлении статуса";
    header("Location: ../admin_res/admin.php");
} 