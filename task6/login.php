<?php

header('Content-Type: text/html; charset=UTF-8');


function GET()
{
  ?>
<html>

<head>
  <style>
  </style>
</head>
<body>
  <form action="" method="POST">
    <label>Логин: <input name="login" /></label>
    <br>
    <label>Пароль: <input name="pass" /></label>
    <br>
    <input type="submit" value="Войти" />
  </form>
</body>
</html>
  <?php
  exit();
}

function POST(){
  $user = 'u68592'; // Заменить на ваш логин uXXXXX
  $pass = '6714103'; // Заменить на пароль
  $db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  
  if (empty($_POST['login']) || !preg_match('/^([A-Z]|[a-z]|[0-9]){8}$/miu', $_POST['login']) || 
  empty($_POST['pass']) || !preg_match('/^([A-Z]|[a-z]|[0-9]){8}$/miu', $_POST['pass'])) {
    print("Неверный логин или пароль<br>");
    print('<a href="login.php">Вернуться</a>');
    exit();
  }

  $data = $db->query('SELECT h_password, id FROM 5_users WHERE login = ' . $db->quote($_POST['login']))->fetchAll();
  
  if (empty($data)){
    password_verify($_POST['pass'], '$2y$10$TEA5k7D0TWy/lvwCueuAWeaAu.I7A6SRL22PZkDAmuX5D8IClpxHi');
    print("Неверный логин или пароль<br>");
    print('<a href="login.php">Вернуться</a>');
  }
  else if (password_verify($_POST['pass'], $data[0]['h_password'])){
    session_start();
    $_SESSION['id'] = $data[0]['id'];
    header('Location: ./');
  }
  else {
    print("Неверный логин или пароль<br>");
    print('<a href="login.php">Вернуться</a>');
  }
}

if (empty($_COOKIE[session_name()])){
  if ($_SERVER['REQUEST_METHOD']=='GET'){
    GET();
  }
  else {
    POST();
  }
}
else {
  session_start();
  setcookie(session_name(), '', 1000, '/');
  session_destroy();
  header('Location: ./');
  exit();
}
?>
