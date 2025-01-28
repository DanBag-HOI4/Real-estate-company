<?php
session_start();
require_once "./connect.php";

if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$type = $_POST['type'];
$address = $_POST['address'];
$district = $_POST['district'];
$price = $_POST['price'];
$area = $_POST['area'];
$rooms = $_POST['rooms'];

// Добавляем обработку новых полей (после получения основных полей)
$land_category = isset($_POST['land_category']) ? $_POST['land_category'] : null;
$security = isset($_POST['security']) ? $_POST['security'] : null;
$commercial_type = isset($_POST['commercial_type']) ? $_POST['commercial_type'] : null;

// Добавим новые поля для дома (после получения основных полей)
$floors = isset($_POST['floors']) ? $_POST['floors'] : null;
$material = isset($_POST['material']) ? $_POST['material'] : null;
$land_area = isset($_POST['land_area']) ? $_POST['land_area'] : null;

// Обработка загруженного файла
$image_path = null;
if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/';
    
    // Создаем директорию, если её нет
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Генерируем уникальное имя файла
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid() . '.' . $file_extension;
    $target_file = $upload_dir . $file_name;
    
    // Проверяем тип файла
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        $_SESSION['error'] = "Разрешены только изображения форматов JPG, JPEG и PNG";
        header("Location: ../profile.php");
        exit();
    }
    
    // Перемещаем файл
    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_path = 'uploads/' . $file_name;
    } else {
        $_SESSION['error'] = "Ошибка при загрузке изображения";
        header("Location: ../profile.php");
        exit();
    }
}

// Обновляем SQL запрос
$query = "INSERT INTO properties (user_id, type, address, district, price, area, rooms, 
                                land_category, security, commercial_type, image,
                                floors, material, land_area) 
          VALUES ($user_id, '$type', '$address', '$district', $price, $area, " . 
          ($rooms ? $rooms : "NULL") . ", " .
          ($land_category ? "'$land_category'" : "NULL") . ", " .
          ($security ? "'$security'" : "NULL") . ", " .
          ($commercial_type ? "'$commercial_type'" : "NULL") . ", " .
          ($image_path ? "'$image_path'" : "NULL") . ", " .
          ($floors ? $floors : "NULL") . ", " .
          ($material ? "'$material'" : "NULL") . ", " .
          ($land_area ? $land_area : "NULL") . ")";

if($connect->query($query)) {
    header("Location: ../profile.php");
} else {
    $_SESSION['error'] = "Ошибка при добавлении объекта";
    header("Location: ../profile.php");
}
