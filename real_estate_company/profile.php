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
<body class="bg-emerald-200 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl">Добро пожаловать, <?= $_SESSION['user']['name'] ?></h1>
            <div class="flex gap-4">
                <a href="index.php" class="text-emerald-900 hover:text-emerald-700">На главную</a>
                <?php if($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="./admin_res/admin.php" class="text-emerald-900 hover:text-emerald-700">Админ-панель</a>
                <?php endif; ?>
                <a href="./vendor/logout.php" class="text-red-600 hover:text-red-800">Выйти</a>
            </div>
        </div>
        
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
                        <div class="flex items-start gap-2">
                            <span class="px-2 py-1 rounded text-sm <?= 
                                $property['status'] == 'new' ? 'bg-blue-100 text-blue-800' : 
                                ($property['status'] == 'solved' ? 'bg-green-100 text-green-800' : 
                                'bg-red-100 text-red-800') 
                            ?>">
                                <?= $property['status'] == 'new' ? 'На рассмотрении' : 
                                    ($property['status'] == 'solved' ? 'Одобрено' : 'Отклонено') 
                                ?>
                            </span>
                            <?php if($property['status'] == 'new'): ?>
                                <form method="POST" action="./vendor/delete_property.php" 
                                      onsubmit="return confirm('Вы уверены, что хотите удалить этот объект?')">
                                    <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 text-sm px-2 py-1">
                                        ✕
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-medium">Адрес:</span> <?= htmlspecialchars($property['address']) ?></p>
                        <p><span class="font-medium">Район:</span> <?= htmlspecialchars($property['district']) ?></p>
                        <p><span class="font-medium">Цена:</span> <?= number_format($property['price'], 0, ',', ' ') ?> ₽</p>
                        <p><span class="font-medium">Площадь:</span> <?= $property['area'] ?> м²</p>
                        
                        <?php if(in_array($property['type'], ['house', 'apartment']) && $property['rooms']): ?>
                            <p><span class="font-medium">Комнат:</span> <?= $property['rooms'] ?></p>
                        <?php endif; ?>
                        
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
                    
                    <?php if($property['status'] == 'solved'): ?>
                        <div class="mt-4 pt-4 border-t">
                            <?php
                            // Проверяем, есть ли уже отзыв для этого объекта
                            $existing_review = $connect->query("SELECT * FROM reviews WHERE property_id = {$property['id']} AND fio = '{$_SESSION['user']['name']}'");
                            if($review = mysqli_fetch_assoc($existing_review)):
                            ?>
                                <div class="bg-gray-50 p-4 rounded">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-medium">Ваш отзыв</h4>
                                        <form method="POST" action="./vendor/delete_review.php" 
                                              onsubmit="return confirm('Вы уверены, что хотите удалить отзыв?')">
                                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Удалить отзыв
                                            </button>
                                        </form>
                                    </div>
                                    <h5 class="font-medium mb-2"><?= htmlspecialchars($review['title']) ?></h5>
                                    <p class="text-gray-600"><?= htmlspecialchars($review['review']) ?></p>
                                </div>
                            <?php else: ?>
                                <h4 class="font-medium mb-2">Оставить отзыв</h4>
                                <form method="POST" action="./vendor/back_review.php" class="space-y-3">
                                    <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                                    <input type="text" name="title" placeholder="Заголовок отзыва" 
                                           class="w-full rounded p-2 border" required>
                                    <textarea name="review" placeholder="Текст отзыва" 
                                            class="w-full rounded p-2 border" required></textarea>
                                    <button type="submit" 
                                            class="bg-emerald-900 text-white px-4 py-2 rounded hover:bg-emerald-700">
                                        Отправить отзыв
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
