<?php
if (!isset($_SESSION)) { 
    session_start(); 
}
// Проверяем роль пользователя
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once "./vendor/connect.php";

// Получаем все объекты недвижимости с информацией о владельце и покупателе
$properties = $connect->query("
    SELECT p.*, u.name as owner_name, u.phone as owner_phone,
           a.client_name as buyer_name, a.phone as buyer_phone 
    FROM properties p 
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN applications a ON p.buyer_application_id = a.id 
    ORDER BY p.id DESC
");

// Add this after the existing properties query
$reviews = $connect->query("
    SELECT r.*, p.address, p.type 
    FROM reviews r 
    JOIN properties p ON r.property_id = p.id 
    ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Админ панель</title>
    <style>
        .menu-transition {
            transition: transform 0.3s ease-in-out;
        }
        .menu-hidden {
            transform: translateX(-100%);
        }
        @media (min-width: 768px) {
            .menu-hidden {
                transform: none;
            }
        }
    </style>
</head>
<body class="bg-emerald-200 p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Адаптивная шапка -->
        <header class="bg-emerald-900 text-white rounded-lg p-4 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center relative">
                <div class="flex justify-between w-full md:w-auto items-center mb-4 md:mb-0">
                    <h1 class="text-xl md:text-2xl">Админ панель</h1>
                    <button id="burger-btn" class="md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"></path>
                        </svg>
                    </button>
                </div>
                
                <nav id="mobile-menu" class="menu-transition menu-hidden md:transform-none w-full md:w-auto h-0 md:h-auto overflow-hidden">
                    <ul class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
                        <li>Добро пожаловать, <?= $_SESSION['user']['name'] ?></li>
                        <li><a href="../index.php" class="hover:text-emerald-300">На главную</a></li>
                        <li><a href="../profile.php" class="hover:text-emerald-300">Личный кабинет</a></li>
                        <li><a href="../vendor/logout.php" class="text-red-300 hover:text-red-400">Выйти</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Управление объектами недвижимости -->
        <h2 class="text-2xl font-bold mb-6">Управление объектами недвижимости</h2>
        
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
                        <th class="px-6 py-3 text-left">Покупатель</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($property = mysqli_fetch_assoc($properties)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $property['id'] ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($property['owner_name']) ?></td>
                        <td class="px-6 py-4">
                            <?php
                            switch($property['type']) {
                                case 'house': echo 'Частный дом'; break;
                                case 'apartment': echo 'Квартира'; break;
                                case 'land': echo 'Земельный участок'; break;
                                case 'garage': echo 'Гараж'; break;
                                default: echo 'Коммерческая недвижимость';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($property['address']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($property['district']) ?></td>
                        <td class="px-6 py-4"><?= number_format($property['price'], 0, ',', ' ') ?> ₽</td>
                        <td class="px-6 py-4"><?= $property['buyer_name'] ? htmlspecialchars($property['buyer_name']) : 'Нет' ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Reviews Management Section -->
        <h2 class="text-2xl font-bold mb-6 mt-12">Управление отзывами</h2>
        
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-emerald-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Заголовок</th>
                        <th class="px-6 py-3 text-left">Отзыв</th>
                        <th class="px-6 py-3 text-left">Продавец</th>
                        <th class="px-6 py-3 text-left">Покупатель</th>
                        <th class="px-6 py-3 text-left">Статус</th>
                        <th class="px-6 py-3 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($review = mysqli_fetch_assoc($reviews)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $review['id'] ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($review['title']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($review['review']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($review['seller_name']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($review['buyer_name']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-sm <?= 
                                $review['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                ($review['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                            ?>">
                                <?= $review['status'] === 'approved' ? 'Одобрен' : 
                                    ($review['status'] === 'rejected' ? 'Отклонен' : 'Новый') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($review['status'] === 'new'): ?>
                                <form method="POST" action="../vendor/update_review_status.php" class="inline">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <button type="submit" name="status" value="approved" 
                                            class="text-green-600 hover:text-green-900 mr-2">
                                        Одобрить
                                    </button>
                                    <button type="submit" name="status" value="rejected" 
                                            class="text-red-600 hover:text-red-900">
                                        Отклонить
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        let isMenuOpen = false;

        burgerBtn.addEventListener('click', () => {
            isMenuOpen = !isMenuOpen;
            if (isMenuOpen) {
                mobileMenu.classList.remove('menu-hidden');
                mobileMenu.style.height = mobileMenu.scrollHeight + 'px';
            } else {
                mobileMenu.classList.add('menu-hidden');
                mobileMenu.style.height = '0';
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.style.height = 'auto';
                mobileMenu.classList.remove('menu-hidden');
            } else if (!isMenuOpen) {
                mobileMenu.style.height = '0';
                mobileMenu.classList.add('menu-hidden');
            }
        });
    </script>
</body>
</html>