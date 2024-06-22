<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="min-h-screen bg-emerald-100">
    <header class="bg-emerald-900 text-white">
        <div class="flex justify-between w-3/4 ml-auto mr-auto h-20 items-center">
            <div>
                <p class="text-xl font-bold">Город</p>
                <p class="text-xs">Агентство недвижимости</p>
            </div>
            <div class="">
                <ul class="flex text-lg gap-10 font-semibold ">
                    <li class="hover:scale-125 transition duration-300"><a href="">Услуги</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="">Отзывы</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="">О нас</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="">Контакты</a></li>
                    <li class="hover:scale-125 transition duration-300"><a href="">+7 (0000) 000-000</a></li>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <div class="flex justify-between w-2/3 ml-auto mr-auto items-center mt-32">
            <div class="flex flex-col gap-10"> 
                <h1 class="text-7xl">
                    Быстро найдём <br> покупателя на <br> Вашу квартиру!
                </h1>
                <p class="text-xl">А также проконсультируем <br> по всем интересующим вас вопросам!</p>
                <div class="flex justify-left text-white font-semibold">
                    <button class="bg-emerald-900 hover:scale-125 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl">ОСТАВИТЬ ЗАЯВКУ</button>
                </div>
            </div>
            <img src="img/analog-landscape-city-with-buildings.jpg" class="w-1/2 rounded-xl" alt="Freepik">
        </div>
            
        <div class="grid grid-cols-3 gap-24 w-2/3 ml-auto mr-auto mt-32">
            <div class="flex flex-col gap-5 bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-5 text-white hover:scale-125 transition duration-300">
                <h2 class="text-2xl font-semibold">
                    ПОМОЩЬ В ПРОДАЖЕ НЕДВИЖИМОСТИ
                </h2>
                <p>Обеспечим продажу вашей недвижимости к запланированному сроку с максимальной выгодой.</p>
                <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl text-2xl font-semibold">Подробнее</button>
            </div>

            <div class="flex flex-col gap-5 bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-5 text-white hover:scale-125 transition duration-300">
                <h2 class="text-2xl font-semibold">
                    ПОМОЩЬ В ПОКУПКЕ
                    НЕДВИЖИМОСТИ
                </h2>
                <p>Подберём лучший объект под ваши параметры на вторичном рынке и в новостройке</p>
                <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl text-2xl font-semibold">Подробнее</button>
            </div>

            <div class="flex flex-col gap-5 bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-5 text-white hover:scale-125 transition duration-300">
                <h2 class="text-2xl font-semibold">
                    КОМПЛЕКСНОЕ СОПРОВОЖДЕНИЕ
                    СДЕЛОК
                </h2>
                <p>Возьмем на себя работу с документами в вашей сделке</p>
                <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl text-2xl font-semibold">Подробнее</button>
            </div>
        </div>
            
    </main>
    <footer class="sticky top-[100vh] bg-emerald-900 text-white min-h-40 flex items-center mt-32">

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