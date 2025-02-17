<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в БД.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^([A-Z]|[a-z]|[А-Я]|[а-я]|\s){3,150}$/miu', $_POST['fio']) || FALSE) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}

//if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
  //print('Заполните год.<br/>');
  //$errors = TRUE;
//}

if (empty($_POST['phone']) || !preg_match('/^\+?[0-9]{11,14}$/', $_POST['phone']) || FALSE){
  print('Заполните телефоню<br>');
  $errors = TRUE;
}

if (empty($_POST['email']) || !preg_match('/^\w+@\w+.\w{2,}$/', $_POST['email']) || FALSE){
  print('Заполните почту<br>');
  $errors = TRUE;
}

if (empty($_POST['dbirth']) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['dbirth']) || FALSE){
  print('Заполните дату рождения<br>');
  $errors = TRUE;
}

if (empty($_POST['sex']) || !preg_match('/^[0,1]$/', $_POST['sex']) || FALSE){
  print('Заполните пол<br>');
  $errors = TRUE;
}

if (empty($_POST['bio']) || !preg_match('/^(\w,\s){10,1000}$/mui', $_POST['bio']) || FALSE){
  print('Заполните биографию<br>');
  $errors = TRUE;
}

if (empty($_POST['agree']) || !preg_match('/^on$/', $_POST['agree']) || FALSE){
  print('Согласитесь с обработкой персональных данных<br>');
  $errors = TRUE;
}


$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX


if (count($_POST['abilities'])<13) {
  $langs = $db->query('SELECT lang_name FROM languages')->fetchAll();
  //$langs->execute();
  print_r($langs);
  foreach ($_POST['abilities'] as $ability) {
    $is_es = FALSE;
    foreach($langs as $lang){
      if ($lang==$ability){
        $is_es=TRUE;
      }
    }
  }
  if (empty($_POST['abilities']) || $is_es || FALSE){
    print('Заполните языки<br>');
    $errors = TRUE;
  }
}

//if (empty($_POST['bio']) || !preg_match('/^(\w,\s){10,1000}$/mui', $_POST['bio']) || FALSE){
  //print('Заполните биографию<br>');
  //$errors = TRUE;
//}

//if (empty($_POST['agree']) || !preg_match('/^on$/', $_POST['agree']) || FALSE){
//  print('Согласитесь с обработкой персональных данных<br>');
//  $errors = TRUE;
//}


print_r($_POST);

// *************
// Тут необходимо проверить правильность заполнения всех остальных полей.
// *************

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

//$user = 'u68592'; // Заменить на ваш логин uXXXXX
//$pass = '6714103'; // Заменить на пароль
//$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  //[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO application SET name = ?");
  $stmt->execute([$_POST['fio']]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
