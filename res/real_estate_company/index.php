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
                    <li class=""><p>+7 (0000) 000-000</p></li>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <div class="flex justify-between w-2/3 ml-auto mr-auto items-center mt-52 p-7 pl-11 pr-11 rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700">
            <div class="flex flex-col gap-10"> 
                <h1 class="text-7xl">
                    Быстро найдём <br> покупателя на <br> Вашу квартиру!
                </h1>
                <p class="text-xl">А также проконсультируем <br> по всем интересующим вас вопросам!</p>
                <div class="flex justify-left text-white font-semibold">
                <form action="./application.php">
                    <input class="bg-emerald-900 hover:scale-125 hover:bg-emerald-600 transition duration-300 p-5 rounded-xl" type="submit" value="ОСТАВИТЬ ЗАЯВКУ" />
                </form>
                </div>
            </div>
            <img src="img/analog-landscape-city-with-buildings.jpg" class="w-1/2 rounded-xl" alt="Freepik">
        </div>
        <div class="flex flex-col w-2/3 ml-auto mr-auto mt-24 gap-7 items-center">
            <h1 id="offers" class="text-3xl font-bold">Наши услуги</h1>
            <div class="grid grid-cols-3 gap-24 mt-8">
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-125 transition duration-300">
                    <h2 class="text-2xl font-semibold">
                        ПОМОЩЬ В ПРОДАЖЕ НЕДВИЖИМОСТИ
                    </h2>
                    <p>Обеспечим продажу вашей недвижимости к запланированному сроку с максимальной выгодой.</p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
    
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-125 transition duration-300">
                    <h2 class="text-2xl font-semibold">
                        ПОМОЩЬ В ПОКУПКЕ
                        НЕДВИЖИМОСТИ
                    </h2>
                    <p>Подберём лучший объект под ваши параметры на вторичном рынке и в новостройке</p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
    
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-125 transition duration-300">
                    <h2 class="text-2xl font-semibold">
                        КОМПЛЕКСНОЕ СОПРОВОЖДЕНИЕ
                        СДЕЛОК
                    </h2>
                    <p>Возьмем на себя работу с документами в вашей сделке</p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
            </div>
        </div>

        <div class="modalBackground">
            <div class="modalActive">
                <div class="modalClose">
                    <p>X</p>
                </div>
                <div class="modalWindow"></div>
            </div>
        </div>

        <div class="flex flex-col w-2/3 ml-auto mr-auto mt-24 gap-7 items-center">
            <h1 id="comments" class="text-3xl font-bold">Отзывы</h1>
            <div class="grid grid-cols-3 gap-24 mt-8">
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-110 transition duration-300">
                    <img src="" alt="">
                    <h2 class="text-2xl font-semibold">
                        Продала квартиру за 2 дня!
                    </h2>
                    <p class="font-thin">Алла Тарабрина</p>
                    <p>Хочу оставить отзыв - благодарность Роману Гизатдинову, за проделанную работу по продаже и покупке квартиры.
                        Продать 2-х комн. малогабаритную квартиру на 1-м этаже в панельной пятиэтажке, да за хорошие деньги - дело непростое. Но Роман нашел покупателей за два дня!
                        Мой выбор квартиры прошел также быстро. Роман предоставил за один день шесть достойных вариантов. И мой выбор был сделан!
                        Большое человеческое спасибо за оформление всех сопутствующих документов и грамотное проведение сделок.
                        Какие бы подводные камни не встречались, Роман всегда решит все вопросы.
                        Спасибо за терпение, честность, отзывчивость, умение понять и выслушать клиента( в любое время).
                        С 2013 года обращаюсь только к Роману и советую этого Профессионала всем!
                    </p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
    
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-110 transition duration-300">
                    <img src="" alt="">
                    <h2 class="text-2xl font-semibold">
                        Продала квартиру за 2 дня!
                    </h2>
                    <p class="font-thin">Алла Тарабрина</p>
                    <p>Хочу оставить отзыв - благодарность Роману Гизатдинову, за проделанную работу по продаже и покупке квартиры.
                        Продать 2-х комн. малогабаритную квартиру на 1-м этаже в панельной пятиэтажке, да за хорошие деньги - дело непростое. Но Роман нашел покупателей за два дня!
                        Мой выбор квартиры прошел также быстро. Роман предоставил за один день шесть достойных вариантов. И мой выбор был сделан!
                        Большое человеческое спасибо за оформление всех сопутствующих документов и грамотное проведение сделок.
                        Какие бы подводные камни не встречались, Роман всегда решит все вопросы.
                        Спасибо за терпение, честность, отзывчивость, умение понять и выслушать клиента( в любое время).
                        С 2013 года обращаюсь только к Роману и советую этого Профессионала всем!
                    </p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
    
                <div class="flex flex-col gap-5 justify-between bg-gradient-to-b from-emerald-900 to-emerald-700 rounded-2xl p-7 text-white hover:scale-110 transition duration-300">
                    <img src="" alt="">
                    <h2 class="text-2xl font-semibold">
                        Продала квартиру за 2 дня!
                    </h2>
                    <p class="font-thin">Алла Тарабрина</p>
                    <p>Хочу оставить отзыв - благодарность Роману Гизатдинову, за проделанную работу по продаже и покупке квартиры.
                        Продать 2-х комн. малогабаритную квартиру на 1-м этаже в панельной пятиэтажке, да за хорошие деньги - дело непростое. Но Роман нашел покупателей за два дня!
                        Мой выбор квартиры прошел также быстро. Роман предоставил за один день шесть достойных вариантов. И мой выбор был сделан!
                        Большое человеческое спасибо за оформление всех сопутствующих документов и грамотное проведение сделок.
                        Какие бы подводные камни не встречались, Роман всегда решит все вопросы.
                        Спасибо за терпение, честность, отзывчивость, умение понять и выслушать клиента( в любое время).
                        С 2013 года обращаюсь только к Роману и советую этого Профессионала всем!
                    </p>
                    <button class="bg-emerald-900 hover:bg-emerald-600 transition duration-300 p-7 rounded-xl text-2xl font-semibold">Подробнее</button>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-5 justify-between ml-auto mr-auto items-center mt-24">
            <h1 id="about_us" class="text-3xl font-bold">О нас</h1>
            <div class="grid grid-cols-2 gap-7 w-2/3 ml-auto mr-auto mt-8 text-xl">
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-7">                
                    <p>
                        Наша компания "Город" была основана в 2010 году и за 14 лет работы на рынке недвижимости Москвы зарекомендовала себя как надежный и профессиональный партнер для покупателей, продавцов и арендаторов.
                    </p>
                </div>
                <div class="rounded-xl text-white bg-gradient-to-l from-emerald-900 to-emerald-700 p-7">  
                    <p>
                    Мы гордимся тем, что наша команда состоит из опытных риэлторов, каждый из которых имеет сертификаты о прохождении специализированных курсов и тренингов. Многие из наших сотрудников учились за границей и обладают уникальными знаниями в сфере недвижимости.
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