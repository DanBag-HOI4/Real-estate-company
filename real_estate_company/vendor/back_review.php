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
$fio = $_SESSION['user']['name'];

// Проверяем, существует ли уже отзыв от этого пользователя на этот объект
$check_existing = $connect->query("SELECT * FROM reviews WHERE property_id = $property_id AND fio = '$fio'");
if ($check_existing->num_rows > 0) {
    $_SESSION['error'] = "Вы уже оставили отзыв на этот объект";
    header("Location: ../profile.php");
    exit();
}

// Проверяем, что объект существует и одобрен
$check_property = $connect->query("SELECT * FROM properties WHERE id = $property_id AND status = 'solved'");
if ($check_property->num_rows == 0) {
    $_SESSION['error'] = "Недвижимость не найдена или не одобрена";
    header("Location: ../profile.php");
    exit();
}

$connect->query("INSERT INTO `reviews` (`id`, `fio`, `title`, `review`, `property_id`) 
                VALUES (NULL, '$fio', '$title', '$review', $property_id)");

header("Location: ../profile.php");
?>