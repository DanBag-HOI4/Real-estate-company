<?php
session_start();
require_once "./connect.php";

$client_name = $_POST['client_name'];
$phone = $_POST['phone'];
$property_id = $_POST['property_id'];

$connect->query("INSERT INTO applications (client_name, phone, property_id) 
                VALUES ('$client_name', '$phone', $property_id)");

$_SESSION['success'] = "Заявка успешно отправлена!";
header("Location: ../index.php");
?> 