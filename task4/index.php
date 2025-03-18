<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    include('success.php');
    exit();
    //$messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['dbirth'] = !empty($_COOKIE['dbirth_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['agree'] = !empty($_COOKIE['agree_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);

   // Выдаем сообщения об ошибках.
  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
//  $values['phone'] = empty($_COOKIE['phv']) ? '7585' : 1234;
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['dbirth'] = empty($_COOKIE['dbirth_value']) ? '' : $_COOKIE['dbirth_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['agree'] = empty($_COOKIE['agree_value']) ? '' : $_COOKIE['agree_value'];
//  print $values['phone'] ;
//  $values['phone'] = 12345;

if ($errors['fio']) {
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['phone']) {
    print("Telephone error");
    setcookie('phone_error', '', 100000);
    setcookie('phone_value', '', 100000);
    $messages[] = '<div class="error">Заполните телефон.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    setcookie('email_value', '', 100000);
    $messages[] = '<div class="error">Заполните почту.</div>';
  }
  if ($errors['dbirth']) {
    setcookie('dbirth_error', '', 100000);
    setcookie('dbirth_value', '', 100000);
    $messages[] = '<div class="error">Заполните дату рождения.</div>';
  }
  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    setcookie('sex_value', '', 100000);
    $messages[] = '<div class="error">Заполните пол.</div>';
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    setcookie('bio_value', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['agree']) {
    setcookie('agree_error', '', 100000);
    setcookie('agree_value', '', 100000);
    $messages[] = '<div class="error">Согласитесь на обработку ПД.</div>';
  }
  if ($errors['abilities']) {
    setcookie('abilities_error', '', 100000);
    $messages[] = '<div class="error">Заполните языки.</div>';
  }


  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в БД.
else {
//print_r($_POST);

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^([A-Z]|[a-z]|[А-Я]|[а-я]|\s){3,150}$/miu', $_POST['fio']) || FALSE) {
  //print('Заполните имя.<br/>');
  setcookie('fio_error', '1', time() + 60*60*24);
  $errors = TRUE;
}
setcookie('fio_value', $_POST['fio'], time()+60*60*24*30);

if (empty($_POST['phone']) || !preg_match('/^\+?[0-9]{11,14}$/', $_POST['phone']) || FALSE){
//  print('Заполните телефоню<br>');
  setcookie('phone_error', '1');
  $errors = TRUE;
}
setcookie('phone_value', $_POST['phone'], time() + 60*60*24*30);

if (empty($_POST['email']) || !preg_match('/^\w+@\w+.\w{2,}$/', $_POST['email']) || FALSE){
  //print('Заполните почту<br>');
  setcookie('email_error', '1');
  $errors = TRUE;
}
setcookie('email_value', $_POST['email'], time() + 60*60*24*30);

if (empty($_POST['dbirth']) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['dbirth']) || FALSE){
  //print('Заполните дату рождения<br>');
  setcookie('dbirth_error', '1'); 
  $errors = TRUE;
}
setcookie('dbirth_value', $_POST['dbirth'], time() + 60*60*24*30);

//if ((($_POST['sex'])!=1) || (($_POST['sex'])!=2)){c
if (empty($_POST['sex']) || !preg_match('/^(fem|male)$/', $_POST['sex']) ){
  setcookie('sex_error', '1');
  $errors = TRUE;
}
else {
  $sex = preg_match('/^fem$/', $_POST['sex']) ? 0 : 1;
}
setcookie('sex_value', empty($_POST['sex']) ? 'no' : (preg_match('/^fem$/', $_POST['sex']) ? 0 : 1), time() + 60*60*24*30);

if (empty($_POST['bio']) || !preg_match('/^(\w|\s){10,1000}$/mui', $_POST['bio']) || FALSE){
  //print('Заполните биографию<br>');
  setcookie('bio_error', '1');
  $errors = TRUE;
}
setcookie('bio_value', $_POST['bio'], time() + 60*60*24*30);

//print($_POST['agree']);
if (empty($_POST['agree']) || $_POST['agree']!=1 || FALSE){
  //print('Согласитесь с обработкой персональных данных<br>');
  setcookie('agree_error', '1');
  $errors = TRUE;
  //print($_POST['agree']);
}
else {
  setcookie('agree_value', 1, time() + 60*60*24*30);
}
//setcookie('agree_value', empty($_POST['agree']) ? 'no' : $_POST['agree'], time() + 60*60*24*30);


$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
$lngs = $db->query('select * from languages')->fetchAll();
$l = [];
foreach($lngs as $q2){
  $l[$q2[0]] = $q2[1];
}
foreach($l as $lang_id => $land_name){
  setcookie($lang_id, 0, 1000);
}

//print_r($_POST['abilities']);

if (empty($_POST['abilities'])){
  $errors = TRUE;
  //print('Заполните языки');
  setcookie('abilities_error', '1');
}
if (!empty($_POST['abilities']) && count($_POST['abilities'])<13) {
  $is_es = FALSE;
  foreach ($_POST['abilities'] as $k => $ability) {
    $is_es1 = TRUE;
    foreach($l as $lang_id => $lang_name){
//      print "$lang_id $ability <br>";
      if ($lang_id==$ability){
//        print("YEEEEEES <br>");
        $is_es1 = $is_es1 && FALSE;
        setcookie($lang_id, '1', time() + 60*60*24*30);
        //print($lang);
        //print(' '); print($ability); print('<br>');
      }
    }
    $is_es = $is_es || $is_es1;
  }
  //print("<br><br>");
  //print( ($is_es ? '1':'-1'));
  if ($is_es || FALSE){
    //print('Заполните языки<br>');
    setcookie('abilities_error', '1');
    $errors = TRUE;
  }

  //print_r($langs);
}

if ($errors) {
  header('Location: index.php');
  exit();
}
else{
  setcookie('fio_error', 0, 1000);
  setcookie('phone_error', 0, 1000);
  setcookie('email_error', 0, 1000);
  setcookie('dbirth_error', 0, 1000);
  setcookie('sex_error', 0, 1000);
  setcookie('bio_error', 0, 1000);
  setcookie('agree_error', 0, 1000);
  setcookie('abilities_error', 0, 1000);
}
// Сохранение в базу данных.
try{
  
  $in_req = $db->prepare("INSERT INTO requests (name, mail, birthday, sex, bio, phone) VALUES (:name, :mail, :birthday, :sex, :bio, :phone)");
  $in_langs = $db->prepare("INSERT INTO lang_req VALUES (:id, :lang_id)");
  $in_req->execute(['name'=>$_POST['fio'], 'mail'=>$_POST['email'], 'birthday'=>$_POST['dbirth'], 'sex'=>$sex, 'bio'=>$_POST['bio'], 'phone'=>$_POST['phone']]);
  $id = $db->lastInsertId();
  foreach($_POST['abilities'] as $ability){
    $in_langs->execute(['id'=>$id, 'lang_id'=>$ability]);
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
setcookie('save', '1');
header('Location: index.php');
}
?>
