<?
$connect = mysqli_connect("localhost", "root", "", "realestate");

if(!$connect) {
    die("error");
}

$clients = $connect->query("SELECT * FROM `clients`");
$clients = mysqli_fetch_all($clients);
?>