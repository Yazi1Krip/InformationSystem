<?php
// Начало сессии
session_start();

// Параметры подключения к базе данных
$host = "localhost";
$database = "information-bd";
$username = "root";
$password = "root";

try {
    // Подключение к базе данных с использованием PDO
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    // Установка атрибутов PDO 
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Сообщение отладки (удалите или закомментируйте эту строку)
    // echo "Подключение к базе данных выполнено успешно! <br>";
} catch (PDOException $e) {
    // Сообщение об ошибке
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}

// Создание переменных
$login = $_POST["login"];
$password = $_POST["password"]; // Не хешируем пароль здесь

// Создание запроса
$sql = "SELECT * FROM registration WHERE login = :login";

try {
    // Подготовка запроса
    $stmt = $conn->prepare($sql);
    // Бинд параметров
    $stmt->bindParam(":login", $login);
    // Выполнение запроса
    $stmt->execute();

    // Проверка результата запроса
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id']; // Сохранение ID пользователя в сессии
        header("Location: ../HTML-files/main.html");
        exit();
    } else {
        // Неверные учетные данные
        echo "Неверный логин или пароль!";
    }
} catch (PDOException $e) {
    echo "Ошибка выполнения запроса!";
}

// Закрытие соединения
$conn = null;
?>