<?php
session_start();
require_once "./vendor/connect.php";

// Получаем все отзывы с изображениями объектов
$reviews_query = "
    SELECT r.*, p.address, p.type, p.image 
    FROM reviews r 
    JOIN properties p ON r.property_id = p.id 
    WHERE r.status = 'approved'  
    ORDER BY r.id DESC
";

$reviews = $connect->query($reviews_query);

if (!$reviews) {
    // Выводим ошибку для отладки
    die("Ошибка запроса отзывов: " . $connect->error);
}
?>

<?
// Обновляем базовое условие WHERE
$where_conditions = ["buyer_application_id IS NULL"]; // Показываем только объекты без покупателя
$params = [];

if (isset($_GET['type']) && $_GET['type'] !== '') {
    $where_conditions[] = "type = ?";
    $params[] = $_GET['type'];
}

if (isset($_GET['district']) && $_GET['district'] !== '') {
    $where_conditions[] = "district = ?";
    $params[] = $_GET['district'];
}

if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
    $where_conditions[] = "price >= ?";
    $params[] = (int)$_GET['min_price'];
}

if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
    $where_conditions[] = "price <= ?";
    $params[] = (int)$_GET['max_price'];
}

if (isset($_GET['min_area']) && $_GET['min_area'] !== '') {
    $where_conditions[] = "area >= ?";
    $params[] = (float)$_GET['min_area'];
}

if (isset($_GET['max_area']) && $_GET['max_area'] !== '') {
    $where_conditions[] = "area <= ?";
    $params[] = (float)$_GET['max_area'];
}

if (isset($_GET['rooms']) && $_GET['rooms'] !== '') {
    $where_conditions[] = "rooms = ?";
    $params[] = (int)$_GET['rooms'];
}

// Добавим новые условия фильтрации (после существующих условий, около строки 47)
if (isset($_GET['land_category']) && $_GET['land_category'] !== '') {
    $where_conditions[] = "land_category = ?";
    $params[] = $_GET['land_category'];
}

if (isset($_GET['security']) && $_GET['security'] !== '') {
    $where_conditions[] = "security = ?";
    $params[] = $_GET['security'];
}

if (isset($_GET['commercial_type']) && $_GET['commercial_type'] !== '') {
    $where_conditions[] = "commercial_type = ?";
    $params[] = $_GET['commercial_type'];
}

// Добавим новые условия фильтрации (после существующих условий)
if (isset($_GET['min_floors']) && $_GET['min_floors'] !== '') {
    $where_conditions[] = "floors >= ?";
    $params[] = (int)$_GET['min_floors'];
}

if (isset($_GET['max_floors']) && $_GET['max_floors'] !== '') {
    $where_conditions[] = "floors <= ?";
    $params[] = (int)$_GET['max_floors'];
}

if (isset($_GET['material']) && $_GET['material'] !== '') {
    $where_conditions[] = "material = ?";
    $params[] = $_GET['material'];
}

if (isset($_GET['min_land_area']) && $_GET['min_land_area'] !== '') {
    $where_conditions[] = "land_area >= ?";
    $params[] = (float)$_GET['min_land_area'];
}

if (isset($_GET['max_land_area']) && $_GET['max_land_area'] !== '') {
    $where_conditions[] = "land_area <= ?";
    $params[] = (float)$_GET['max_land_area'];
}

// Формируем WHERE часть запроса
$where_clause = implode(' AND ', $where_conditions);

// Получаем уникальные районы для фильтра
$districts = $connect->query("SELECT DISTINCT district FROM properties ORDER BY district");

// Подготавливаем и выполняем запрос с фильтрами
$query = "SELECT * FROM properties WHERE $where_clause ORDER BY id DESC";
$stmt = $connect->prepare($query);

if (!$stmt) {
    // Обработка ошибки подготовки запроса
    die("Ошибка подготовки запроса: " . $connect->error);
}

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    if (!$stmt->bind_param($types, ...$params)) {
        // Обработка ошибки привязки параметров
        die("Ошибка привязки параметров: " . $stmt->error);
    }
}

if (!$stmt->execute()) {
    // Обработка ошибки выполнения запроса
    die("Ошибка выполнения запроса: " . $stmt->error);
}

$properties = $stmt->get_result();

?>
<script>
    function toggleFilterFields(type) {
        // Скрываем все дополнительные поля фильтров
        document.getElementById('roomsFilter').classList.add('hidden');
        document.getElementById('landFilter').classList.add('hidden');
        document.getElementById('garageFilter').classList.add('hidden');
        document.getElementById('commercialFilter').classList.add('hidden');
        document.getElementById('houseFilter').classList.add('hidden');
        
        // Показываем нужные поля в зависимости от типа
        switch(type) {
            case 'house':
                document.getElementById('roomsFilter').classList.remove('hidden');
                document.getElementById('houseFilter').classList.remove('hidden');
                break;
            case 'apartment':
                document.getElementById('roomsFilter').classList.remove('hidden');
                break;
            case 'land':
                document.getElementById('landFilter').classList.remove('hidden');
                break;
            case 'garage':
                document.getElementById('garageFilter').classList.remove('hidden');
                break;
            case 'commercial':
                document.getElementById('commercialFilter').classList.remove('hidden');
                break;
        }
    }

    // Вызываем функцию при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('select[name="type"]');
        if(typeSelect) {
            toggleFilterFields(typeSelect.value);
        }
    });
</script>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
    <!-- Добавляем стили для анимации -->
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

<body class="min-h-screen bg-emerald-200">
    <header class="bg-emerald-900 text-white sticky top-0 z-10">
        <div class="flex flex-col md:flex-row justify-between w-full md:w-3/4 mx-auto p-4 md:h-20 items-center relative">
            <!-- Логотип -->
            <div class="flex justify-between w-full md:w-auto items-center">
                <a href="./index.php">
                    <p class="text-xl font-bold">Город</p>
                    <p class="text-xs">Агентство недвижимости</p>
                </a>
                <!-- Бургер кнопка -->
                <button id="burger-btn" class="md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Мобильное меню -->
            <nav id="mobile-menu" class="menu-transition menu-hidden md:transform-none w-full md:w-auto bg-emerald-900 md:bg-transparent absolute md:relative top-full md:top-auto left-0 md:left-auto">
                <ul class="flex flex-col md:flex-row text-lg gap-4 md:gap-10 font-semibold items-center p-4 md:p-0">
                    <li class="hover:scale-125 transition duration-300"><a href="#catalog">Каталог</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="#comments">Отзывы</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="#about_us">О нас</a></li>
                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="hover:scale-125 transition duration-300">
                            <a href="<?= $_SESSION['user']['role'] === 'admin' ? './admin_res/admin.php' : './profile.php' ?>">
                                Личный кабинет
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="hover:scale-125 transition duration-300"><a href="./login.php">Войти</a></li>
                        <li class="hover:scale-125 transition duration-300"><a href="./register.php">Регистрация</a></li>
                    <?php endif; ?>
                    <li class="text-center md:text-left">
                        <p>+7 (0000) 000-000</p>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Добавляем JavaScript для управления меню в конец body -->
    <script>
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        let isMenuOpen = false;

        burgerBtn.addEventListener('click', () => {
            isMenuOpen = !isMenuOpen;
            if (isMenuOpen) {
                mobileMenu.classList.remove('menu-hidden');
            } else {
                mobileMenu.classList.add('menu-hidden');
            }
        });

        // Закрываем меню при клике на ссылку
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    mobileMenu.classList.add('menu-hidden');
                    isMenuOpen = false;
                }
            });
        });

        // Обработка изменения размера окна
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.remove('menu-hidden');
            } else if (!isMenuOpen) {
                mobileMenu.classList.add('menu-hidden');
            }
        });
    </script>

    <main>
    <div class="w-full md:w-2/3 mx-auto mt-8 md:mt-24 px-4 md:px-0" id="catalog">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">Каталог недвижимости</h2>
            
            <!-- Форма фильтров -->
            <form method="GET" class="bg-white p-4 md:p-6 rounded-lg shadow mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип недвижимости</label>
                        <select name="type" 
                                class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                onchange="toggleFilterFields(this.value)">
                            <option value="">Все типы</option>
                            <option value="house" <?= isset($_GET['type']) && $_GET['type'] === 'house' ? 'selected' : '' ?>>Частный дом</option>
                            <option value="apartment" <?= isset($_GET['type']) && $_GET['type'] === 'apartment' ? 'selected' : '' ?>>Квартира</option>
                            <option value="land" <?= isset($_GET['type']) && $_GET['type'] === 'land' ? 'selected' : '' ?>>Земельный участок</option>
                            <option value="garage" <?= isset($_GET['type']) && $_GET['type'] === 'garage' ? 'selected' : '' ?>>Гараж</option>
                            <option value="commercial" <?= isset($_GET['type']) && $_GET['type'] === 'commercial' ? 'selected' : '' ?>>Коммерческая недвижимость</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Район</label>
                        <select name="district" 
                                class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <option value="">Все районы</option>
                            <?php while($district = mysqli_fetch_assoc($districts)): ?>
                                <option value="<?= $district['district'] ?>" 
                                    <?= isset($_GET['district']) && $_GET['district'] === $district['district'] ? 'selected' : '' ?>>
                                    <?= $district['district'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <!-- Фильтр по количеству комнат -->
                    <div id='roomsFilter' class='" . 
                        (!isset($_GET['type']) || in_array($_GET['type'], ['house', 'apartment']) ? '' : 'hidden') . "'>
                        <label class='block text-sm font-medium text-gray-700 mb-1'>Количество комнат</label>
                        <select name='rooms' class='w-full rounded-md border-gray-300 shadow-sm'>
                            <option value=''>Любое</option>";
                            for($i = 1; $i <= 5; $i++) {
                                echo "<option value='$i' " . 
                                    (isset($_GET['rooms']) && $_GET['rooms'] == $i ? 'selected' : '') . 
                                    ">$i</option>";
                            }
echo "                      <option value='6' " . (isset($_GET['rooms']) && $_GET['rooms'] == 6 ? 'selected' : '') . ">6+</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Цена, ₽</label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   name="min_price" 
                                   placeholder="От" 
                                   value="<?= $_GET['min_price'] ?? '' ?>"
                                   class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <input type="number" 
                                   name="max_price" 
                                   placeholder="До" 
                                   value="<?= $_GET['max_price'] ?? '' ?>"
                                   class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Площадь, м²</label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   name="min_area" 
                                   placeholder="От" 
                                   value="<?= $_GET['min_area'] ?? '' ?>"
                                   class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <input type="number" 
                                   name="max_area" 
                                   placeholder="До" 
                                   value="<?= $_GET['max_area'] ?? '' ?>"
                                   class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <!-- Фильтр для земельных участков -->
                    <div id='landFilter' class='" . (isset($_GET['type']) && $_GET['type'] === 'land' ? '' : 'hidden') . "'>
                        <label class='block text-sm font-medium text-gray-700 mb-1'>Категория земель</label>
                        <select name='land_category' class='w-full rounded-md border-gray-300 shadow-sm'>
                            <option value=''>Любая</option>
                            <option value='settlement' " . (isset($_GET['land_category']) && $_GET['land_category'] === 'settlement' ? 'selected' : '') . ">Поселения</option>
                            <option value='agricultural' " . (isset($_GET['land_category']) && $_GET['land_category'] === 'agricultural' ? 'selected' : '') . ">Сельхозназначения</option>
                            <option value='industrial' " . (isset($_GET['land_category']) && $_GET['land_category'] === 'industrial' ? 'selected' : '') . ">Промназначения</option>
                        </select>
                    </div>

                    <!-- Фильтр для гаражей -->
                    <div id='garageFilter' class='" . (isset($_GET['type']) && $_GET['type'] === 'garage' ? '' : 'hidden') . "'>
                        <label class='block text-sm font-medium text-gray-700 mb-1'>Охрана</label>
                        <select name='security' class='w-full rounded-md border-gray-300 shadow-sm'>
                            <option value=''>Любая</option>
                            <option value='with_security' " . (isset($_GET['security']) && $_GET['security'] === 'with_security' ? 'selected' : '') . ">С охраной</option>
                            <option value='without_security' " . (isset($_GET['security']) && $_GET['security'] === 'without_security' ? 'selected' : '') . ">Без охраны</option>
                        </select>
                    </div>

                    <!-- Фильтр для коммерческой недвижимости -->
                    <div id='commercialFilter' class='" . (isset($_GET['type']) && $_GET['type'] === 'commercial' ? '' : 'hidden') . "'>
                        <label class='block text-sm font-medium text-gray-700 mb-1'>Вид объекта</label>
                        <select name='commercial_type' class='w-full rounded-md border-gray-300 shadow-sm'>
                            <option value=''>Любой</option>
                            <option value='office' " . (isset($_GET['commercial_type']) && $_GET['commercial_type'] === 'office' ? 'selected' : '') . ">Офис</option>
                            <option value='retail' " . (isset($_GET['commercial_type']) && $_GET['commercial_type'] === 'retail' ? 'selected' : '') . ">Торговая площадь</option>
                            <option value='warehouse' " . (isset($_GET['commercial_type']) && $_GET['commercial_type'] === 'warehouse' ? 'selected' : '') . ">Склад</option>
                        </select>
                    </div>
                    
                    <!-- Фильтры для частного дома -->
                    <div id='houseFilter' class='" . (isset($_GET['type']) && $_GET['type'] === 'house' ? '' : 'hidden') . "'>
                        <div class='grid grid-cols-2 gap-4'>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Этажей от</label>
                                <input type='number' name='min_floors' value='" . (isset($_GET['min_floors']) ? $_GET['min_floors'] : '') . "' 
                                       class='w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Этажей до</label>
                                <input type='number' name='max_floors' value='" . (isset($_GET['max_floors']) ? $_GET['max_floors'] : '') . "' 
                                       class='w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Материал</label>
                                <select name='material' class='w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50'>
                                    <option value=''>Любой</option>
                                    <option value='brick' " . (isset($_GET['material']) && $_GET['material'] === 'brick' ? 'selected' : '') . ">Кирпич</option>
                                    <option value='stone' " . (isset($_GET['material']) && $_GET['material'] === 'stone' ? 'selected' : '') . ">Камень</option>
                                    <option value='log' " . (isset($_GET['material']) && $_GET['material'] === 'log' ? 'selected' : '') . ">Бревно</option>
                                </select>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Площадь участка от (сот.)</label>
                                <input type='number' name='min_land_area' value='" . (isset($_GET['min_land_area']) ? $_GET['min_land_area'] : '') . "' 
                                       class='w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Площадь участка до (сот.)</label>
                                <input type='number' name='max_land_area' value='" . (isset($_GET['max_land_area']) ? $_GET['max_land_area'] : '') . "' 
                                       class='w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50'>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-emerald-600 text-white px-4 py-3 rounded-md hover:bg-emerald-700 transition duration-300 border-2 border-emerald-600 hover:border-emerald-700">
                            Применить фильтры
                        </button>
                    </div>
                </div>
            </form>

            <!-- Отображение результатов -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while($property = mysqli_fetch_assoc($properties)): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <?php if($property['image']): ?>
                        <img src="<?= $property['image'] ?>" alt="Фото объекта" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">
                            <?= $property['type'] == 'house' ? 'Частный дом' : 
                                ($property['type'] == 'apartment' ? 'Квартира' : 
                                ($property['type'] == 'land' ? 'Земельный участок' : 
                                ($property['type'] == 'garage' ? 'Гараж' : 'Коммерческая недвижимость'))) 
                            ?>
                        </h3>
                        <p class="text-gray-600 mb-2">Район: <?= htmlspecialchars($property['district']) ?></p>
                        <p class="text-gray-600 mb-2">Адрес: <?= htmlspecialchars($property['address']) ?></p>
                        <p class="text-emerald-600 font-semibold mb-4">
                            Цена: <?= number_format($property['price'], 0, ',', ' ') ?> ₽
                        </p>

                        <!-- Форма заявки -->
                        <form action="./vendor/add_application.php" method="POST" class="mt-4 space-y-3">
                            <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ваше ФИО</label>
                                <input type="text" 
                                       name="client_name" 
                                       required 
                                       class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Номер телефона</label>
                                <input type="tel" 
                                       name="phone" 
                                       required 
                                       pattern="[0-9+\s\-\(\)]+" 
                                       class="w-full rounded-md border-2 border-emerald-200 shadow-sm p-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition duration-300">
                                Оставить заявку
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div
            class="flex flex-col md:flex-row justify-between w-full md:w-2/3 mx-auto items-center mt-12 md:mt-52 p-4 md:p-7 rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700">
            <div class="flex flex-col gap-6 md:gap-10 text-center md:text-left mb-6 md:mb-0">
                <h1 class="text-4xl md:text-7xl">
                    Быстро найдём <br class="hidden md:block"> покупателя на <br class="hidden md:block"> Вашу квартиру!
                </h1>
                <p class="text-lg md:text-xl">А также проконсультируем <br class="hidden md:block"> по всем интересующим вас вопросам!</p>
                <!-- <div class="flex justify-center md:justify-start">
                    <form action="./application.php">
                        <input class="bg-emerald-900 hover:scale-125 hover:bg-emerald-600 transition duration-300 p-3 md:p-5 rounded-xl" type="submit" value="ОСТАВИТЬ ЗАЯВКУ" />
                    </form>
                </div> -->
            </div>
            <img src="img/analog-landscape-city-with-buildings.jpg" class="w-full md:w-1/2 rounded-xl" alt="Freepik">
        </div>
       

        <div class="w-full md:w-2/3 mx-auto mt-12 md:mt-24 px-4 md:px-0">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8" id="comments">Отзывы наших клиентов</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($review = mysqli_fetch_assoc($reviews)): ?>
                <div class="bg-white rounded-lg shadow p-6 w-[300px]">
                    <?php if($review['image']): ?>
                        <div class="mb-4 h-48 overflow-hidden rounded-lg">
                            <img src="<?= $review['image'] ?>" 
                                 alt="Фото объекта" 
                                 class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($review['title']) ?></h3>
                    <p class="text-gray-600 mb-2">Продавец: <?= htmlspecialchars($review['seller_name']) ?></p>
                    <p class="text-gray-600 mb-4">Покупатель: <?= htmlspecialchars($review['buyer_name']) ?></p>
                    <p class="text-gray-800"><?= htmlspecialchars($review['review']) ?></p>
                </div>
            <?php endwhile; ?>
            </div>
        </div>

        <div class="flex flex-col gap-5 justify-between mx-auto items-center mt-12 md:mt-24 px-4 md:px-0">
            <h1 id="about_us" class="text-2xl md:text-3xl font-bold">О нас</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7 w-full md:w-2/3 mx-auto mt-8 text-lg md:text-xl">
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-4 md:p-7">
                    <p>
                        Наша компания "Город" была основана в 2010 году и за 14 лет работы на рынке недвижимости Москвы
                        зарекомендовала себя как надежный и профессиональный партнер для покупателей, продавцов и
                        арендаторов.
                    </p>
                </div>
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-4 md:p-7">
                    <p>
                        Мы гордимся тем, что наша команда состоит из опытных риэлторов, каждый из которых имеет
                        сертификаты о прохождении специализированных курсов и тренингов. Многие из наших сотрудников
                        учились за границей и обладают уникальными знаниями в сфере недвижимости.
                    </p>
                </div>
            </div>
        </div>

    </main>
    <footer id="contacts" class="sticky top-[100vh] bg-emerald-900 text-white min-h-40 flex items-center mt-12 md:mt-32">
        <div class="flex flex-col md:flex-row justify-between w-full md:w-3/4 mx-auto p-4 md:p-0">
            <div class="flex flex-col gap-3 mb-6 md:mb-0 text-center md:text-left">
                <h2 class="text-xl md:text-2xl font-semibold">Наши контакты</h2>
                <ul>
                    <li>Адрес: Улица Пушкина дом Колотушкина</li>
                    <li>Телефон: +7 (0000) 000-000</li>
                    <li>Электронная почта: realestatecompany@example.com</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3 text-center md:text-left">
                <h2 class="text-xl md:text-2xl font-semibold">Расписание</h2>
                <ul>
                    <li>Работаем по будням с 10:00 до 19:00</li>
                    <li>В субботу с 11:00 до 15:00</li>
                </ul>
            </div>
        </div>
    </footer>
</body>

</html>