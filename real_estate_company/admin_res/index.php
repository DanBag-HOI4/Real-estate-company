<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="flex justify-center items-center h-screen bg-emerald-200">
<div class="w-full max-w-xs ">
  <form method="POST" action="./vendor/back_login.php" class="bg-emerald-600 drop-shadow-md rounded-xl px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
      <label class="block text-white text-lg font-semibold mb-2" for="login">
        Введите свой логин
      </label>
      <input class="drop-shadow-md shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="login" id="login" type="text" placeholder="Логин">
    </div>
    <div class="mb-6">
      <label class="block text-white text-lg font-semibold mb-2" for="password">
        Введите пароль
      </label>
      <input class="drop-shadow-md shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="password" id="password" type="password" placeholder="Пароль">
      <!-- <p class="text-red-500 text-xs italic">Пожалуйста, введите номер телефона.</p> -->
    </div>
    <div class="flex items-center justify-center">
        <input type="submit" class="bg-emerald-900 hover:scale-125 hover:bg-emerald-600 transition duration-300 p-3 rounded-xl text-white" type="submit" value="Отправить данные" />
    </div>
  </form>
  <p class="text-center text-gray-500 text-xs">
    &copy;2020 Acme Corp. All rights reserved.
  </p>
</div>
</body>
</html>