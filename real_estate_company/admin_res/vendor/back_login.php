<?php
if (!isset($_SESSION)) { 
    session_start(); 
}

require_once "./connect.php";
$login = $_POST["login"];
$password = $_POST["password"];

$result = $connect->query("SELECT * FROM `admins` WHERE `login` = '$login' AND `password` = '$password'");

if(mysqli_num_rows($result)>0) {
    $row = mysqli_fetch_array($result);
    $admin = array(
        "dbid" => $row["id"],
        "dblogin" => $row["login"]
    );
    $_SESSION["admin"] = $admin;
    header("Location: ../admin.php");
} else {
    header("Location: ../index.php");
}

?>