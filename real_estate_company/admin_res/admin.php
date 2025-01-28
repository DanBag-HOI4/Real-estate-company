<?
if (!isset($_SESSION)) { 
    session_start(); 
}
// Проверяем роль пользователя
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once "./vendor/connect.php";

// Получаем все объекты недвижимости
$properties = $connect->query("SELECT p.*, u.name as owner_name FROM properties p 
                             JOIN users u ON p.user_id = u.id 
                             ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Админ панель</title>
</head>
<body class="bg-emerald-200 p-8">
    <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl">Добро пожаловать, <?= $_SESSION['user']['name'] ?></h1>
            <div class="flex gap-4">
                <a href="../index.php" class="text-emerald-900 hover:text-emerald-700">На главную</a>
                <a href="../profile.php" class="text-emerald-900 hover:text-emerald-700">Личный кабинет</a>
                <a href="../vendor/logout.php" class="text-red-600 hover:text-red-800">Выйти</a>
            </div>
        </div>
        
        <h1 class="text-2xl font-bold mb-6">Управление объектами недвижимости</h1>
        
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-emerald-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Владелец</th>
                        <th class="px-6 py-3 text-left">Тип</th>
                        <th class="px-6 py-3 text-left">Адрес</th>
                        <th class="px-6 py-3 text-left">Район</th>
                        <th class="px-6 py-3 text-left">Цена</th>
                        <th class="px-6 py-3 text-left">Статус</th>
                        <th class="px-6 py-3 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($property = mysqli_fetch_assoc($properties)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $property['id'] ?></td>
                        <td class="px-6 py-4"><?= $property['owner_name'] ?></td>
                        <td class="px-6 py-4"><?= $property['type'] == 'house' ? 'Дом' : 'Квартира' ?></td>
                        <td class="px-6 py-4"><?= $property['address'] ?></td>
                        <td class="px-6 py-4"><?= $property['district'] ?></td>
                        <td class="px-6 py-4"><?= number_format($property['price'], 0, ',', ' ') ?> ₽</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                <?= $property['status'] == 'new' ? 'bg-blue-100 text-blue-800' : 
                                    ($property['status'] == 'solved' ? 'bg-green-100 text-green-800' : 
                                    'bg-red-100 text-red-800') ?>">
                                <?= $property['status'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="../vendor/update_status.php" class="flex gap-2">
                                <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                                <select name="status" class="rounded border-gray-300 text-sm">
                                    <option value="new" <?= $property['status'] == 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="solved" <?= $property['status'] == 'solved' ? 'selected' : '' ?>>Solved</option>
                                    <option value="rejected" <?= $property['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                <button type="submit" class="bg-emerald-600 text-white px-3 py-1 rounded text-sm hover:bg-emerald-700">
                                    Обновить
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Существующая таблица клиентов -->
        <h2 class="text-2xl font-bold my-6">Заявки клиентов</h2>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-emerald-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">ФИО</th>
                        <th class="px-6 py-3 text-left">Номер телефона</th>
                        <th class="px-6 py-3 text-left">Дата отправки заявки</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($clients as $item): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $item[1] ?></td>
                        <td class="px-6 py-4"><?= $item[2] ?></td>
                        <td class="px-6 py-4"><?= $item[3] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>