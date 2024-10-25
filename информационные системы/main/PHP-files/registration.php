<?php
// Начало сессии
session_start();
// Удачи Мамику попытаться защитить эту работу, не зная ни PHP, ни как работает PDO; 
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
    // Сообщение отладки
  
} catch (PDOException $e) {
    // Сообщение об ошибке
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}

// Создание переменных
$login = $_POST["login"];
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);
$email = $_POST["email"];
$name = $_POST["name"];
$surname = $_POST["surname"];
$middlename = $_POST["middlename"];

// Проверка на существующего пользователя по логину
$checkLoginSql = "SELECT * FROM registration WHERE login = :login";
$checkLoginStmt = $conn->prepare($checkLoginSql);
$checkLoginStmt->bindParam(':login', $login);
$checkLoginStmt->execute();

// Проверка на существующего пользователя по email
$checkEmailSql = "SELECT * FROM registration WHERE email = :email";
$checkEmailStmt = $conn->prepare($checkEmailSql);
$checkEmailStmt->bindParam(':email', $email);
$checkEmailStmt->execute();

if ($checkLoginStmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Пользователь с таким логином уже существует!";
} elseif ($checkEmailStmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Пользователь с таким email уже существует!";
} else {
    // Запрос на событие
    $sql = "INSERT INTO registration (login, password, email, name, surname, middlename) 
            VALUES (:login, :password, :email, :name, :surname, :middlename)";

    try {
        // Подготовка запроса
        $stmt = $conn->prepare($sql);
        // Привязка параметров
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':middlename', $middlename);
        // Выполнение запроса
        $stmt->execute();   
        // Сообщение об успешной вставке
        header("Location: ../../main/HTML-files/authorization.html");
        exit(); // Важно завершить выполнение скрипта после переадресации
    } catch (PDOException $e) {
        // Сообщение об ошибке
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
}

// Закрытие соединения
$conn = null;
?>