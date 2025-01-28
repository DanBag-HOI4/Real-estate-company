<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
require_once "./vendor/connect.php";

$user_id = $_SESSION['user']['id'];
$properties = $connect->query("SELECT * FROM properties WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Профиль</title>
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
    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function toggleAdditionalFields(type) {
            // Скрываем все дополнительные поля
            document.getElementById('roomsField').classList.add('hidden');
            document.getElementById('landField').classList.add('hidden');
            document.getElementById('garageField').classList.add('hidden');
            document.getElementById('commercialField').classList.add('hidden');
            document.getElementById('houseField').classList.add('hidden');
            
            // Показываем нужные поля в зависимости от типа
            switch(type) {
                case 'house':
                    document.getElementById('roomsField').classList.remove('hidden');
                    document.getElementById('houseField').classList.remove('hidden');
                    break;
                case 'apartment':
                    document.getElementById('roomsField').classList.remove('hidden');
                    break;
                case 'land':
                    document.getElementById('landField').classList.remove('hidden');
                    break;
                case 'garage':
                    document.getElementById('garageField').classList.remove('hidden');
                    break;
                case 'commercial':
                    document.getElementById('commercialField').classList.remove('hidden');
                    break;
            }
        }

        // Вызываем функцию при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            if(typeSelect) {
                toggleAdditionalFields(typeSelect.value);
            }
        });
    </script>
</head>
<body class="bg-emerald-200 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Адаптивная шапка -->
        <header class="bg-emerald-900 text-white rounded-lg p-4 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center relative">
                <div class="flex justify-between w-full md:w-auto items-center mb-4 md:mb-0">
                    <h1 class="text-xl md:text-2xl">Личный кабинет</h1>
                    <button id="burger-btn" class="md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"></path>
                        </svg>
                    </button>
                </div>
                
                <nav id="mobile-menu" class="menu-transition menu-hidden md:transform-none w-full md:w-auto h-0 md:h-auto overflow-hidden">
                    <ul class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
                        <li>Добро пожаловать, <?= $_SESSION['user']['name'] ?></li>
                        <li><a href="index.php" class="hover:text-emerald-300">На главную</a></li>
                        <?php if($_SESSION['user']['role'] === 'admin'): ?>
                            <li><a href="./admin_res/admin.php" class="hover:text-emerald-300">Админ-панель</a></li>
                        <?php endif; ?>
                        <li><a href="./vendor/logout.php" class="text-red-300 hover:text-red-400">Выйти</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <h2 class="text-xl mb-4">Мои объекты недвижимости</h2>
        
        <form method="POST" action="./vendor/add_property.php" class="mb-8 bg-emerald-600 p-4 rounded" enctype="multipart/form-data">
            <h3 class="text-white mb-4">Добавить новый объект</h3>
            <div class="grid grid-cols-2 gap-4">
                <select name="type" class="rounded p-2" onchange="toggleAdditionalFields(this.value)">
                    <option value="house">Частный дом</option>
                    <option value="apartment">Квартира</option>
                    <option value="land">Земельный участок</option>
                    <option value="garage">Гараж</option>
                    <option value="commercial">Коммерческая недвижимость</option>
                </select>
                <input type="text" name="address" placeholder="Адрес" class="rounded p-2" required>
                <select name="district" class="rounded p-2" required>
                    <option value="Ленинский">Ленинский</option>
                    <option value="Октябрьский">Октябрьский</option>
                    <option value="Индустриальный">Индустриальный</option>
                    <option value="Устиновский">Устиновский</option>
                    <option value="Первомайский">Первомайский</option>
                </select>
                <input type="number" name="price" placeholder="Цена" class="rounded p-2" required>
                <input type="number" name="area" placeholder="Площадь" class="rounded p-2" required>
                
                <!-- Поля для разных типов недвижимости -->
                <div id="roomsField">
                    <input type="number" name="rooms" placeholder="Количество комнат" class="rounded p-2">
                </div>
                
                <div id="landField" class="hidden">
                    <select name="land_category" class="rounded p-2">
                        <option value="">Выберите категорию земель</option>
                        <option value="settlement">Поселения</option>
                        <option value="agricultural">Сельхозназначения</option>
                        <option value="industrial">Промназначения</option>
                    </select>
                </div>
                
                <div id="garageField" class="hidden">
                    <select name="security" class="rounded p-2">
                        <option value="">Выберите тип охраны</option>
                        <option value="with_security">С охраной</option>
                        <option value="without_security">Без охраны</option>
                    </select>
                </div>
                
                <div id="commercialField" class="hidden">
                    <select name="commercial_type" class="rounded p-2">
                        <option value="">Выберите вид объекта</option>
                        <option value="office">Офис</option>
                        <option value="retail">Торговая площадь</option>
                        <option value="warehouse">Склад</option>
                    </select>
                </div>

                <div id="houseField" class="hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" name="floors" placeholder="Количество этажей" class="rounded p-2">
                        <select name="material" class="rounded p-2">
                            <option value="">Выберите материал</option>
                            <option value="brick">Кирпич</option>
                            <option value="stone">Камень</option>
                            <option value="log">Бревно</option>
                        </select>
                        <input type="number" name="land_area" placeholder="Площадь участка (сот.)" class="rounded p-2">
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-white mb-2">Фотография объекта</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full text-white mb-2" required 
                           onchange="previewImage(event)">
                    <div id="imagePreviewContainer" class="hidden">
                        <img id="imagePreview" class="max-h-48 rounded-lg object-cover mx-auto" alt="Предпросмотр">
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-emerald-900 text-white p-2 rounded hover:bg-emerald-700">Добавить объект</button>
        </form>

        <div class="grid grid-cols-2 gap-4">
            <?php while($property = mysqli_fetch_assoc($properties)): ?>
                <div class="bg-white p-6 rounded-lg shadow">
                    <?php if($property['image']): ?>
                        <div class="mb-4 h-48 overflow-hidden rounded-lg">
                            <img src="<?= $property['image'] ?>" 
                                 alt="Фото объекта" 
                                 class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">
                            <?= $property['type'] == 'house' ? 'Частный дом' : 
                                ($property['type'] == 'apartment' ? 'Квартира' : 
                                ($property['type'] == 'land' ? 'Земельный участок' : 
                                ($property['type'] == 'garage' ? 'Гараж' : 'Коммерческая недвижимость'))) 
                        ?>
                        </h3>
                        <?php if(!$property['buyer_application_id']): ?>
                            <form method="POST" action="./vendor/delete_property.php" 
                                  onsubmit="return confirm('Вы уверены, что хотите удалить объект?')">
                                <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Удалить
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600 mb-2">Район: <?= htmlspecialchars($property['district']) ?></p>
                    <p class="text-gray-600 mb-2">Адрес: <?= htmlspecialchars($property['address']) ?></p>
                    <p class="text-emerald-600 font-semibold">
                        Цена: <?= number_format($property['price'], 0, ',', ' ') ?> ₽
                    </p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Добавляем секцию для объектов с покупателями -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Проданные объекты</h3>
            <?php
            // Получаем объекты текущего пользователя с назначенными покупателями
            $sold_properties = $connect->query("
                SELECT p.*, a.client_name as buyer_name, a.phone as buyer_phone 
                FROM properties p 
                JOIN applications a ON p.buyer_application_id = a.id 
                WHERE p.user_id = {$_SESSION['user']['id']}
            ");
            ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php while($property = mysqli_fetch_assoc($sold_properties)): ?>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-semibold mb-2">
                            <?= $property['type'] == 'house' ? 'Частный дом' : 
                                ($property['type'] == 'apartment' ? 'Квартира' : 
                                ($property['type'] == 'land' ? 'Земельный участок' : 
                                ($property['type'] == 'garage' ? 'Гараж' : 'Коммерческая недвижимость'))) 
                            ?>
                        </h4>
                        <p class="text-gray-600 mb-2">Адрес: <?= htmlspecialchars($property['address']) ?></p>
                        <p class="text-gray-600 mb-4">Покупатель: <?= htmlspecialchars($property['buyer_name']) ?></p>
                        
                        <!-- Форма для отзыва -->
                        <?php
                        // Проверяем, есть ли уже отзыв для этого объекта
                        $existing_review = $connect->query("SELECT * FROM reviews WHERE property_id = {$property['id']}");
                        if($existing_review->num_rows > 0):
                            $review = mysqli_fetch_assoc($existing_review);
                        ?>
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h5 class="font-semibold mb-2">Ваш отзыв</h5>
                                <p class="text-gray-700 mb-2"><?= htmlspecialchars($review['title']) ?></p>
                                <p class="text-gray-600 mb-2"><?= htmlspecialchars($review['review']) ?></p>
                                <p class="mt-2">
                                    Статус: 
                                    <span class="px-2 py-1 rounded-full text-sm <?= 
                                        $review['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                        ($review['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                                    ?>">
                                        <?= $review['status'] === 'approved' ? 'Одобрен' : 
                                            ($review['status'] === 'rejected' ? 'Отклонен' : 'На рассмотрении') ?>
                                    </span>
                                </p>
                            </div>
                        <?php else: ?>
                            <form action="./vendor/back_review.php" method="POST" class="mt-4">
                                <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Заголовок отзыва</label>
                                        <input type="text" name="title" required 
                                               class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Текст отзыва</label>
                                        <textarea name="review" required rows="3" 
                                                  class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2"></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition duration-300">
                                        Оставить отзыв
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
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

        // Обновляем обработчик изменения размера окна
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
