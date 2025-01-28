<?php
session_start();
require_once "./connect.php";

$name = $_POST['name'];
$phone = $_POST['phone'];
$login = $_POST['login'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'client'; // По умолчанию все новые пользователи - клиенты

$check_login = $connect->query("SELECT * FROM users WHERE login = '$login'");
if(mysqli_num_rows($check_login) > 0) {
    $_SESSION['error'] = "Такой логин уже существует";
    header("Location: ../register.php");
    exit();
}

$query = "INSERT INTO users (name, phone, login, password, role) VALUES ('$name', '$phone', '$login', '$password', '$role')";
if($connect->query($query)) {
    header("Location: ../login.php");
} else {
    $_SESSION['error'] = "Ошибка при регистрации";
    header("Location: ../register.php");
}
