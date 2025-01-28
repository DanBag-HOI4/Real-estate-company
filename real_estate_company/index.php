<?php
session_start();
require_once "./vendor/connect.php";
$reviews = $connect->query("SELECT r.*, p.address, p.type 
                          FROM reviews r 
                          JOIN properties p ON r.property_id = p.id 
                          WHERE p.status = 'solved'");
$reviews = mysqli_fetch_all($reviews);

// Добавим обработку фильтров (после session_start(), около строки 3)
$where_conditions = ["status = 'new'"]; // Базовое условие
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
$districts = $connect->query("SELECT DISTINCT district FROM properties WHERE status = 'new' ORDER BY district");

// Подготавливаем и выполняем запрос с фильтрами
$query = "SELECT * FROM properties WHERE $where_clause ORDER BY id DESC";
$stmt = $connect->prepare($query);

if (!empty($params)) {
    $types = str_repeat('s', count($params)); // 's' для строк
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$properties = $stmt->get_result();

// Добавим JavaScript для инициализации полей фильтра
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
</head>

<body class="min-h-screen bg-emerald-200">
    <header class="bg-emerald-900 text-white sticky top-0 z-10 opacity-80">
        <div class="flex justify-between w-3/4 ml-auto mr-auto h-20 items-center">
            <div>
                <a href="./index.php">
                    <p class="text-xl font-bold">Город</p>
                    <p class="text-xs">Агентство недвижимости</p>
                </a>
            </div>
            <div>
                <ul class="flex text-lg gap-10 font-semibold ">
                    <li class="hover:scale-125 transition duration-300"><a href="#offers">Услуги</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="#comments">Отзывы</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="#about_us">О нас</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="#contacts">Контакты</a></li>
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
                    <li class="">
                        <p>+7 (0000) 000-000</p>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main>
    <div class="w-2/3 ml-auto mr-auto mt-24">
            <h2 class="text-3xl font-bold text-center mb-8">Каталог недвижимости</h2>
            
            <!-- Форма фильтров -->
            <form method="GET" class="bg-white p-6 rounded-lg shadow mb-8">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип недвижимости</label>
                        <select name="type" class="w-full rounded-md border-gray-300 shadow-sm" onchange="toggleFilterFields(this.value)">
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
                        <select name="district" class="w-full rounded-md border-gray-300 shadow-sm">
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
                            <input type="number" name="min_price" placeholder="От" 
                                   value="<?= $_GET['min_price'] ?? '' ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                            <input type="number" name="max_price" placeholder="До" 
                                   value="<?= $_GET['max_price'] ?? '' ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Площадь, м²</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_area" placeholder="От" 
                                   value="<?= $_GET['min_area'] ?? '' ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                            <input type="number" name="max_area" placeholder="До" 
                                   value="<?= $_GET['max_area'] ?? '' ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm">
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
                                       class='w-full rounded-md border-gray-300 shadow-sm'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Этажей до</label>
                                <input type='number' name='max_floors' value='" . (isset($_GET['max_floors']) ? $_GET['max_floors'] : '') . "' 
                                       class='w-full rounded-md border-gray-300 shadow-sm'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Материал</label>
                                <select name='material' class='w-full rounded-md border-gray-300 shadow-sm'>
                                    <option value=''>Любой</option>
                                    <option value='brick' " . (isset($_GET['material']) && $_GET['material'] === 'brick' ? 'selected' : '') . ">Кирпич</option>
                                    <option value='stone' " . (isset($_GET['material']) && $_GET['material'] === 'stone' ? 'selected' : '') . ">Камень</option>
                                    <option value='log' " . (isset($_GET['material']) && $_GET['material'] === 'log' ? 'selected' : '') . ">Бревно</option>
                                </select>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Площадь участка от (сот.)</label>
                                <input type='number' name='min_land_area' value='" . (isset($_GET['min_land_area']) ? $_GET['min_land_area'] : '') . "' 
                                       class='w-full rounded-md border-gray-300 shadow-sm'>
                            </div>
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1'>Площадь участка до (сот.)</label>
                                <input type='number' name='max_land_area' value='" . (isset($_GET['max_land_area']) ? $_GET['max_land_area'] : '') . "' 
                                       class='w-full rounded-md border-gray-300 shadow-sm'>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition duration-300">
                            Применить фильтры
                        </button>
                    </div>
                </div>
            </form>

            <!-- Отображение результатов -->
            <div class="grid grid-cols-3 gap-6">
                <?php while($property = mysqli_fetch_assoc($properties)): ?>
                <div class="bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-xl p-6 text-white">
                    <?php if($property['image']): ?>
                        <div class="mb-4 h-48 overflow-hidden rounded-lg">
                            <img src="<?= $property['image'] ?>" 
                                 alt="Фото объекта" 
                                 class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <h3 class="text-xl font-semibold mb-4">
                        <?php
                        switch($property['type']) {
                            case 'house':
                                echo 'Частный дом';
                                break;
                            case 'apartment':
                                echo 'Квартира';
                                break;
                            case 'land':
                                echo 'Земельный участок';
                                break;
                            case 'garage':
                                echo 'Гараж';
                                break;
                            case 'commercial':
                                echo 'Коммерческая недвижимость';
                                break;
                        }
                        ?>
                    </h3>
                    <div class="space-y-2">
                        <!-- Основные характеристики -->
                        <p><span class="font-medium">Адрес:</span> <?= htmlspecialchars($property['address']) ?></p>
                        <p><span class="font-medium">Район:</span> <?= htmlspecialchars($property['district']) ?></p>
                        <p><span class="font-medium">Цена:</span> <?= number_format($property['price'], 0, ',', ' ') ?> ₽</p>
                        <p><span class="font-medium">Площадь:</span> <?= $property['area'] ?> м²</p>
                        
                        <!-- Характеристики для домов и квартир -->
                        <?php if(in_array($property['type'], ['house', 'apartment']) && $property['rooms']): ?>
                            <p><span class="font-medium">Комнат:</span> <?= $property['rooms'] ?></p>
                        <?php endif; ?>
                        
                        <!-- Характеристики для домов -->
                        <?php if($property['type'] == 'house'): ?>
                            <?php if($property['floors']): ?>
                                <p><span class="font-medium">Этажей:</span> <?= $property['floors'] ?></p>
                            <?php endif; ?>
                            <?php if($property['material']): ?>
                                <p><span class="font-medium">Материал:</span> 
                                    <?= $property['material'] == 'brick' ? 'Кирпич' : 
                                       ($property['material'] == 'stone' ? 'Камень' : 'Бревно') ?>
                                </p>
                            <?php endif; ?>
                            <?php if($property['land_area']): ?>
                                <p><span class="font-medium">Площадь участка:</span> <?= $property['land_area'] ?> сот.</p>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Остальные специфические характеристики -->
                        <?php if($property['type'] == 'land' && $property['land_category']): ?>
                            <p><span class="font-medium">Категория земель:</span> 
                                <?= $property['land_category'] == 'settlement' ? 'Поселения' : 
                                   ($property['land_category'] == 'agricultural' ? 'Сельхозназначения' : 'Промназначения') ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if($property['type'] == 'garage' && $property['security']): ?>
                            <p><span class="font-medium">Охрана:</span> 
                                <?= $property['security'] == 'with_security' ? 'С охраной' : 'Без охраны' ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if($property['type'] == 'commercial' && $property['commercial_type']): ?>
                            <p><span class="font-medium">Вид объекта:</span> 
                                <?= $property['commercial_type'] == 'office' ? 'Офис' : 
                                   ($property['commercial_type'] == 'retail' ? 'Торговая площадь' : 'Склад') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <button class="w-full bg-emerald-900 hover:bg-emerald-600 transition duration-300 py-2 px-4 rounded-xl">
                        Подробнее
                    </button>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div
            class="flex justify-between w-2/3 ml-auto mr-auto items-center mt-52 p-7 pl-11 pr-11 rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700">
            <div class="flex flex-col gap-10">
                <h1 class="text-7xl">
                    Быстро найдём <br> покупателя на <br> Вашу квартиру!
                </h1>
                <p class="text-xl">А также проконсультируем <br> по всем интересующим вас вопросам!</p>
                <div class="flex justify-left text-white font-semibold">
                    <form action="./application.php">
                        <input
                            class="bg-emerald-900 hover:scale-125 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl"
                            type="submit" value="ОСТАВИТЬ ЗАЯВКУ" />
                    </form>
                </div>
            </div>
            <img src="img/analog-landscape-city-with-buildings.jpg" class="w-1/2 rounded-xl" alt="Freepik">
        </div>
       

        <div class="w-2/3 ml-auto mr-auto mt-24">
            <h2 class="text-3xl font-bold text-center mb-8" id="comments">Отзывы наших клиентов</h2>
            <div class="grid grid-cols-3 gap-6">
                <?php
                $reviews = $connect->query("SELECT r.*, p.address, p.type, p.price, u.name as client_name 
                                          FROM reviews r 
                                          JOIN properties p ON r.property_id = p.id 
                                          JOIN users u ON r.fio = u.name
                                          WHERE p.status = 'solved'
                                          ORDER BY r.id DESC");
                while($review = mysqli_fetch_assoc($reviews)):
                ?>
                    <div class="bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-xl p-6 text-white">
                        <div class="border-b border-emerald-500 pb-4 mb-4">
                            <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($review['title']) ?></h3>
                            <p class="text-sm opacity-90">Клиент: <?= htmlspecialchars($review['client_name']) ?></p>
                        </div>
                        <div class="mb-4 text-sm">
                            <p class="mb-1">
                                <?= $review['type'] == 'house' ? 'Дом' : 'Квартира' ?> 
                                по адресу: <?= htmlspecialchars($review['address']) ?>
                            </p>
                            <p class="text-emerald-300">
                                Стоимость: <?= number_format($review['price'], 0, ',', ' ') ?> ₽
                            </p>
                        </div>
                        <p class="italic mb-4"><?= htmlspecialchars($review['review']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="flex flex-col gap-5 justify-between ml-auto mr-auto items-center mt-24">
            <h1 id="about_us" class="text-3xl font-bold">О нас</h1>
            <div class="grid grid-cols-2 gap-7 w-2/3 ml-auto mr-auto mt-8 text-xl">
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-7">
                    <p>
                        Наша компания "Город" была основана в 2010 году и за 14 лет работы на рынке недвижимости Москвы
                        зарекомендовала себя как надежный и профессиональный партнер для покупателей, продавцов и
                        арендаторов.
                    </p>
                </div>
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-7">
                    <p>
                        Мы гордимся тем, что наша команда состоит из опытных риэлторов, каждый из которых имеет
                        сертификаты о прохождении специализированных курсов и тренингов. Многие из наших сотрудников
                        учились за границей и обладают уникальными знаниями в сфере недвижимости.
                    </p>
                </div>
            </div>
        </div>

    </main>
    <footer id="contacts" class="sticky top-[100vh] bg-emerald-900 text-white min-h-40 flex items-center mt-32">

        <div class="flex justify-between w-3/4 ml-auto mr-auto items-center">
            <div class="flex flex-col gap-3">
                <h2 class="text-2xl font-semibold">Наши контакты</h2>
                <ul>
                    <li>Адрес: Улица Пушкина дом Колотушкина</li>
                    <li>Телефон: +7 (0000) 000-000</li>
                    <li>Электронная почта: realestatecompany@example.com</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3">
                <h2 class="text-2xl font-semibold">Расписание</h2>
                <ul>
                    <li>Работаем по будням с 10:00 до 19:00</li>
                    <li>В субботу с 11:00 до 15:00</li>
                </ul>
            </div>

        </div>

    </footer>
</body>

</html>