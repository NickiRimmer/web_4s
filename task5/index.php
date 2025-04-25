<?php

$user = 'u68592'; // Заменить на ваш логин uXXXXX
        $pass = '6714103'; // Заменить на пароль
        $db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $data = $db->query('select * from 4_requests')->fetchAll();
        print_r($data);

function check()
{
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


  $lngs = $db->query('select * from 4_languages')->fetchAll();
  $l = [];
  foreach($lngs as $q2){
    $l[$q2[0]] = $q2[1];
  }
  foreach($l as $lang_id => $land_name){
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
      foreach($l as $lang_id => $lang_name){
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
  if (session_status == PHP_SESSION_NONE){
    
    include(form.php);
  }
  else {
    $data = $db -> query('SELECT * FROM 5_requests WHERE id = ' . $_SESSION['id'] . ' ;') -> fetchAll();
    $values = array();
    $values['fio']=$data['name'];
    $values['phone']=$data['phone'];
    $values['email']=$data['mail'];
    $values['dbirth']=$data['birthday'];
    $values['sex']=$data['sex'];
    $values['bio']=$data['bio'];
    $values['agree']=1;
    $data = $db -> query('SELECT lang_id FROM 5_requests WHERE id = ' . $_SESSION['id'] . ';') -> fetchAll();
    $abilities_values = array();
    foreach($data as $key => $val) {
      $abilities_values[$val] = 1;
    }
    include(form.php);
  }
}

?>
