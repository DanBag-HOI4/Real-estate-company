<?php
session_start();
require_once "./connect.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$property_id = $_POST['property_id'];
$application_id = $_POST['application_id'];

if($application_id) {
    $connect->query("UPDATE properties SET buyer_application_id = $application_id WHERE id = $property_id");
}

header("Location: ../admin_res/admin.php");
?> 