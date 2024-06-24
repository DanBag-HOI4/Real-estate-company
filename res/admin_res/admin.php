<?
if (!isset($_SESSION)) { 
    session_start(); 
}
if(!$_SESSION["admin"]) {
    header("Location: ./index.php");
}
require_once "./vendor/connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="flex justify-center items-center h-screen bg-emerald-200">
    <table class="table-auto border-spacing-4">
        <th>
            <tr>
                <td>ФИО</td>
                <td>Номер телефона</td>
                <td>Дата отправки заявки</td>
            </tr>
            <?
            foreach ($clients as $item) {
                ?>
            <tr>
                <td><?=$item[1]?></td>
                <td><?=$item[2]?></td>
                <td><?=$item[3]?></td>
            </tr>
                <?
            }
            ?>
        </th>
    </table>
</body>
</html>