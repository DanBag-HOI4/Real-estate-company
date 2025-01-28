<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Регистрация</title>
</head>
<body class="flex justify-center items-center h-screen bg-emerald-200">
<div class="w-full max-w-xs">
  <form method="POST" action="./vendor/back_register.php" class="bg-emerald-600 drop-shadow-md rounded-xl px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
      <label class="block text-white text-lg font-semibold mb-2" for="name">
        ФИО
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="name" type="text" required>
    </div>
    <div class="mb-4">
      <label class="block text-white text-lg font-semibold mb-2" for="phone">
        Телефон
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="phone" type="text" required>
    </div>
    <div class="mb-4">
      <label class="block text-white text-lg font-semibold mb-2" for="login">
        Логин
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="login" type="text" required>
    </div>
    <div class="mb-4">
      <label class="block text-white text-lg font-semibold mb-2" for="password">
        Пароль
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="password" type="password" required>
    </div>
    <div class="flex items-center justify-center">
      <button class="bg-emerald-900 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
        Зарегистрироваться
      </button>
    </div>
  </form>
</div>
</body>
</html>
