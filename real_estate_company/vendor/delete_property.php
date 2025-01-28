<?php
session_start();
require_once "./connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$property_id = $_POST['property_id'];
$user_id = $_SESSION['user']['id'];

// Проверяем, принадлежит ли объект текущему пользователю
$check_property = $connect->query("SELECT * FROM properties WHERE id = $property_id AND user_id = $user_id");
if ($check_property->num_rows > 0) {
    $property = mysqli_fetch_assoc($check_property);
    
    // Удаляем изображение, если оно существует
    if ($property['image'] && file_exists('../' . $property['image'])) {
        unlink('../' . $property['image']);
    }
    
    // Удаляем запись из базы данных
    $connect->query("DELETE FROM properties WHERE id = $property_id");
}

header("Location: ../profile.php");
?> 