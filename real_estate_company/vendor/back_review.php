<?php
session_start();
require_once "./connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$property_id = $_POST["property_id"];
$title = $_POST["title"];
$review = $_POST["review"];
$seller_id = $_SESSION['user']['id'];

// Получаем информацию о покупателе
$property_info = $connect->query("
    SELECT p.user_id as seller_id, a.client_name as buyer_name, u.name as seller_name
    FROM properties p 
    JOIN applications a ON p.buyer_application_id = a.id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = $property_id
");

if ($property_info->num_rows == 0) {
    $_SESSION['error'] = "Объект не найден или не продан";
    header("Location: ../profile.php");
    exit();
}

$info = mysqli_fetch_assoc($property_info);

// Проверяем, что текущий пользователь является продавцом
if ($info['seller_id'] != $seller_id) {
    $_SESSION['error'] = "У вас нет прав для добавления отзыва";
    header("Location: ../profile.php");
    exit();
}

$connect->query("INSERT INTO `reviews` (`title`, `review`, `property_id`, `seller_name`, `buyer_name`, `status`) 
                VALUES ('$title', '$review', $property_id, '{$info['seller_name']}', '{$info['buyer_name']}', 'new')");

header("Location: ../profile.php");
?>