<?php
session_start();
require_once "./connect.php";

$login = $_POST['login'];
$password = $_POST['password'];

$result = $connect->query("SELECT * FROM users WHERE login = '$login'");

if(mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if(password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'login' => $user['login'],
            'role' => $user['role']
        ];
        
        // Перенаправление в зависимости от роли
        if($user['role'] == 'admin') {
            header("Location: ../admin_res/admin.php");
        } else {
            header("Location: ../profile.php");
        }
    } else {
        $_SESSION['error'] = "Неверный пароль";
        header("Location: ../login.php");
    }
} else {
    $_SESSION['error'] = "Пользователь не найден";
    header("Location: ../login.php");
}
