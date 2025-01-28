<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Вход в личный кабинет</title>
</head>
<body class="flex justify-center items-center h-screen bg-emerald-200">
    <div class="w-full max-w-xs">
        <form method="POST" action="./vendor/back_login.php" class="bg-emerald-600 drop-shadow-md rounded-xl px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-white text-lg font-semibold mb-2" for="login">
                    Логин
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       name="login" 
                       type="text" 
                       required>
            </div>
            <div class="mb-6">
                <label class="block text-white text-lg font-semibold mb-2" for="password">
                    Пароль
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                       name="password" 
                       type="password" 
                       required>
                <?php
                    if(isset($_SESSION['error'])) {
                        echo '<p class="text-red-200 text-xs italic">' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']);
                    }
                ?>
            </div>
            <div class="flex flex-col items-center justify-between gap-4">
                <button class="bg-emerald-900 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                        type="submit">
                    Войти
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-white hover:text-emerald-900" href="register.php">
                    Нет аккаунта? Зарегистрируйтесь
                </a>
                <a class="inline-block align-baseline font-bold text-sm text-white hover:text-emerald-900" href="index.php">
                    Вернуться на главную
                </a>
            </div>
        </form>
    </div>
</body>
</html> 