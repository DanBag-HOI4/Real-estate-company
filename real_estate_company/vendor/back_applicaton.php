<?
require_once "./connect.php";
$fio = $_POST["fio"];
$number = $_POST["number"];
$t = time();
$t2 = date("Y-m-d-H:i:s", $t);
$connect->query("INSERT INTO `clients` (`id`, `fio`, `number`, `appdate`) VALUES (NULL, '$fio', '$number', '$t2')");
header("Location: ../index.php");
?>