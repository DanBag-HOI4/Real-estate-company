<?php
session_start();
require_once "./connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$review_id = $_POST['review_id'];

// Проверяем, принадлежит ли отзыв текущему пользователю
$check_review = $connect->query("SELECT * FROM reviews WHERE id = $review_id AND fio = '{$_SESSION['user']['name']}'");
if ($check_review->num_rows > 0) {
    $connect->query("DELETE FROM reviews WHERE id = $review_id");
}

header("Location: ../profile.php");
?> 