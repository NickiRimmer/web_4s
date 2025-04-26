<?php

$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$data = $db->query('select * from 5_languages')->fetchAll();
$languages = array();
foreach($data as $lang){
  $languages[$lang['lang_id']] = $lang['lang_name'];
}
//$data = $db->query('SELECT h_password, id FROM 5_users WHERE login = ' . $db->quote('2edad74d'))->fetchAll();
//print_r($data);
$sex;


function check()
{
  global $languages;
  // Проверяем ошибки.
  $errors = FALSE;

  if (empty($_POST['fio']) || !preg_match('/^([A-Z]|[a-z]|[А-Я]|[а-я]|\s){3,150}$/miu', $_POST['fio']) || FALSE) {
    setcookie('fio_error', '1', time() + 60*60*24);
    $errors = TRUE;
  }
  setcookie('fio_value', $_POST['fio'], time()+60*60*24*30);

  if (empty($_POST['phone']) || !preg_match('/^\+?[0-9]{11,14}$/', $_POST['phone']) || FALSE){
    setcookie('phone_error', '1');
    $errors = TRUE;
  }
  setcookie('phone_value', $_POST['phone'], time() + 60*60*24*30);

  if (empty($_POST['email']) || !preg_match('/^\w+@\w+.\w{2,}$/', $_POST['email']) || FALSE){
    setcookie('email_error', '1');
    $errors = TRUE;
  }
  setcookie('email_value', $_POST['email'], time() + 60*60*24*30);

  if (empty($_POST['dbirth']) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['dbirth']) || FALSE){
    setcookie('dbirth_error', '1'); 
    $errors = TRUE;
  }
  setcookie('dbirth_value', $_POST['dbirth'], time() + 60*60*24*30);

  if (empty($_POST['sex']) || !preg_match('/^(fem|male)$/', $_POST['sex']) ){
    setcookie('sex_error', '1');
    $errors = TRUE;
  }
  else {
    global $sex;
    $sex = preg_match('/^fem$/', $_POST['sex']) ? 0 : 1;
  }
  setcookie('sex_value', empty($_POST['sex']) ? 'no' : (preg_match('/^fem$/', $_POST['sex']) ? 0 : 1), time() + 60*60*24*30);

  if (empty($_POST['bio']) || !preg_match('/^(\w|\s){10,1000}$/mui', $_POST['bio']) || FALSE){
    setcookie('bio_error', '1');
    $errors = TRUE;
  }
  setcookie('bio_value', $_POST['bio'], time() + 60*60*24*30);

  if (empty($_POST['agree']) || $_POST['agree']!=1 || FALSE){
    setcookie('agree_error', '1');
    $errors = TRUE;
  }
  else {
    setcookie('agree_value', 1, time() + 60*60*24*30);
  }
  //setcookie('agree_value', empty($_POST['agree']) ? 'no' : $_POST['agree'], time() + 60*60*24);

  
  foreach($languages as $lang_id => $land_name){
    setcookie($lang_id, 0, 1000);
  }

  if (empty($_POST['abilities'])){
    $errors = TRUE;
    setcookie('abilities_error', '1');
  }
  if (!empty($_POST['abilities']) && count($_POST['abilities'])<13) {
    $is_es = FALSE;
    foreach ($_POST['abilities'] as $k => $ability) {
      $is_es1 = TRUE;
      foreach($languages as $lang_id => $lang_name){
        if ($lang_id==$ability){
          $is_es1 = $is_es1 && FALSE;
          setcookie($lang_id, '1', time() + 60*60*24*30);
        }
      }
      $is_es = $is_es || $is_es1;
    }
    if ($is_es || FALSE){
      setcookie('abilities_error', '1');
      $errors = TRUE;
    }
  }

  return $errors;
}

function GET()
{
  $messages = array();
  if (!empty($_COOKIE['save']) && $_COOKIE['save']==1){
    $messages[] = 'Данные успешно сохранены';
    if (empty($_COOKIE[session_name()]) && !empty($_COOKIE['passwd']) && !empty($_COOKIE['login'])){
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.',
       $_COOKIE['login'], 
       $_COOKIE['passwd']);
    }
  }

  global $languages;
  if (empty($_COOKIE[session_name()])){
    // Складываем предыдущие значения полей в массив, если есть.
    print("way1<br>");
    $values = array();
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
    $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['dbirth'] = empty($_COOKIE['dbirth_value']) ? '' : $_COOKIE['dbirth_value'];
    $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['agree'] = empty($_COOKIE['agree_value']) ? '' : $_COOKIE['agree_value'];

    $abilities_values = array();
    foreach($languages as $key => $name){
      if (!empty($_COOKIE[$key])){
        $abilities_values[$key] = 1;
      }
    }
    
    {
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
    }
    include('form.php');
  }
  else {
    session_start();
    //print("way2<br>");
    global $db;
    $data = $db -> query('SELECT * FROM 5_requests WHERE id = ' . $db->quote($_SESSION['id']) . ' ;') -> fetchAll();
    //print_r($data);
    $values = array();
    $values['fio']=$data[0]['name'];
    $values['phone']=$data[0]['phone'];
    $values['email']=$data[0]['mail'];
    $values['dbirth']=$data[0]['birthday'];
    $values['sex']=$data[0]['sex'];
    $values['bio']=$data[0]['bio'];
    $values['agree']=1;
    $data = $db -> query('SELECT lang_id FROM 5_req_lang WHERE req_id = ' . $db->quote($_SESSION['id']) . ';') -> fetchAll();
    //print_r($data);
    $abilities_values = array();
    foreach($data as $key => $val) {
      $abilities_values[$val['lang_id']] = 1;
    }
    $errors = array();
    $errors['fio'] = !empty($_COOKIE['fio_error']);
    $errors['phone'] = !empty($_COOKIE['phone_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['dbirth'] = !empty($_COOKIE['dbirth_error']);
    $errors['sex'] = !empty($_COOKIE['sex_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['agree'] = !empty($_COOKIE['agree_error']);
    $errors['abilities'] = !empty($_COOKIE['abilities_error']);
    include('form.php');
  }
}

function POST()
{
  global $db;
  if (check()) {
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
    if (empty($_COOKIE[session_name()])){
      print("way1<br>");
      global $sex;
      $in_req = $db->prepare("INSERT INTO 5_requests (name, mail, birthday, sex, bio, phone) 
      VALUES (:name, :mail, :birthday, :sex, :bio, :phone)");
      $in_langs = $db->prepare("INSERT INTO 5_req_lang VALUES (:id, :lang_id)");
      
      $in_req->execute(['name'=>$_POST['fio'], 'mail'=>$_POST['email'], 'birthday'=>$_POST['dbirth'], 'sex'=>$sex, 'bio'=>$_POST['bio'], 'phone'=>$_POST['phone']]);

      $id = $db->lastInsertId();
      foreach($_POST['abilities'] as $ability){
        $in_langs->execute(['id'=>$id, 'lang_id'=>$ability]);
      }

      $in_users = $db -> prepare("INSERT INTO 5_users VALUES (:login, :h_pass, :id)");
      $login = substr(md5(rand()), rand(0, 24), 8);
      while (count($db->query("SELECT * from 5_users WHERE login = " . $db->quote($login))->fetchAll()) != 0){
        global $login;
        $login = substr(md5(rand()), rand(0, 24), 8);
      }

      $passwd = substr(md5(rand()), rand(0, 24), 8);

      setcookie('login', $login, time() + 60);
      setcookie('passwd', $passwd, time() + 60);

      $in_users ->execute(['login'=>$login, 'h_pass'=> password_hash($passwd, PASSWORD_DEFAULT), 'id'=>$id]);
    }
    else {
      print("way2<br>");
      session_start();
      global $sex;
      $in_req = $db->prepare("UPDATE 5_requests 
      SET name=:name, phone=:phone, mail=:mail, 
      sex=:sex, bio=:bio, birthday=:bday
      WHERE id = " . $db->quote($_SESSION['id']));
      //$in_req = $db->prepare("UPDATE 5_requests SET name='changed' WHERE id = " . $db->quote($_SESSION['id']));
      $db->exec("DELETE FROM 5_req_lang WHERE req_id = " . $db->quote((string)$_SESSION['id']));
      $in_langs = $db->prepare("INSERT INTO 5_req_lang VALUES (:id, :lang_id)");

      print($_POST['dbirth']);
      $in_req->execute(['name'=>$_POST['fio'], 'phone'=>$_POST['phone'],'mail'=>$_POST['email'], 'sex'=>$sex, 
      'bio'=>$_POST['bio'], 'bday'=>$_POST['dbirth']]);
      //$in_req->execute();

      print($_POST['dbirth']);
      print($_POST['abilities']);
      foreach($_POST['abilities'] as $ability){
        $in_langs->execute(['id'=>$_SESSION['id'], 'lang_id'=>$ability]);
      }
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


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  GET();
}
else {
  POST();
}

?>
