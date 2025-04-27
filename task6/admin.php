<?php
$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function get_all()
{
  global $db;
  $data = $db->query('SELECT *, 
    (SELECT group_concat(l.lang_name SEPARATOR \', \')
    FROM 5_req_lang AS rq
    JOIN 5_languages AS l
      ON l.lang_id = rq.lang_id
    WHERE rq.req_id = r.id) AS langs
  FROM 5_requests AS r;')->fetchAll();
  $lang_stats = $db->query('SELECT count(rr.lang_id) AS l_cnt, ll.lang_name AS l_name
  FROM 5_req_lang AS rr
  RIGHT JOIN 5_languages AS ll
  ON rr.lang_id=ll.lang_id 
  GROUP BY ll.lang_id
  ORDER BY count(rr.lang_id) DESC;')->fetchAll();
  //print_r($data);
  //print_r($lang_stats);
  ?>
<html>
  <head>
    <style>
      table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
        font-family: sans-serif; 
      }

      th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
      }

      th {
        background-color: #f2f2f2;
        font-weight: bold;
      }

      tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      tr:hover {
        background-color: #e0e0e0;
      }

      caption {
        caption-side: bottom;
        font-style: italic;
        padding: 8px;
        color: #888;
      }
    </style>
  </head>
  <body>
    <h1>Заявки</h1>
    <table>
      <tr>
        <th>&nbsp;</th>
        <th>ID</th>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Почта</th>
        <th>День рождения</th>
        <th>Пол</th>
        <th>Языки</th>
        <th>Биография</th>
      </tr>
      <?php
      foreach ($data as $req){?>
      <tr>
        <td>
          <form action="" method="POST"><input type="submit" value="&#9998;"><input type="hidden" value="<?= $req['id']?>" name="edit_id"></form>
          <form action="" method="POST"><input type="submit" value="&#10006;"><input type="hidden" value="<?= $req['id']?>" name="del_id"></form>
        </td>
        <td><?= $req['id']?></td>
        <td><?= $req['name']?></td>
        <td><?= $req['phone']?></td>
        <td><?= $req['mail']?></td>
        <td><?= $req['birthday']?></td>
        <td><?= $req['sex']==1 ? 'М' : 'Ж'?></td>
        <td><?= $req['langs']?></td>
        <td><?= $req['bio']?></td>
      </tr>
      <?php } ?>
    </table>
    <br><br>
    <h1>Статистика по языкам</h1>
    <table>
      <tr>
        <th>Язык</th>
        <th>Количество человек</th>
      </tr>
      <?php
      foreach ($lang_stats as $lang){
      ?>
      <tr>
        <td><?= $lang['l_name'] ?></td>
        <td><?= $lang['l_cnt'] ?></td>
      </tr>
      <?php } ?>
    </table>
  </body>
</html>
<?php
}

$sex;

function GET()
{
  $messages = array();
  if (!empty($_COOKIE['save']) && $_COOKIE['save']==1){
    $messages[] = 'Данные успешно сохранены';
    setcookie('save', 0, 100);
  }
  
  global $db;
  $data = $db -> query('SELECT * FROM 5_requests WHERE id = ' . $db->quote($_COOKIE['id']) . ' ;') -> fetchAll();
  //print_r($data);
  $values = array();
  $values['fio']=$data[0]['name'];
  $values['phone']=$data[0]['phone'];
  $values['email']=$data[0]['mail'];
  $values['dbirth']=$data[0]['birthday'];
  $values['sex']=$data[0]['sex'];
  $values['bio']=$data[0]['bio'];
  $values['agree']=1;
  $data = $db -> query('SELECT lang_id FROM 5_req_lang WHERE req_id = ' . $db->quote($_COOKIE['id']) . ';') -> fetchAll();
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

  {
    if ($errors['fio']) {
      setcookie('fio_error', '', 100000);
      setcookie('fio_value', '', 100000);
      $messages[] = '<div class="error">Заполните имя.</div>';
    }
    if ($errors['phone']) {
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

  $data = $db->query('select * from 5_languages')->fetchAll();
  $languages = array();
  foreach($data as $lang){
    $languages[$lang['lang_id']] = $lang['lang_name'];
  }
  include('form.php');
}

function check()
{
  global $db;
  $data = $db->query('select * from 5_languages')->fetchAll();
  $languages = array();
  foreach($data as $lang){
    $languages[$lang['lang_id']] = $lang['lang_name'];
  }
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

function POST()
{
  global $db;
  if (check()) {
    header('Location: admin.php');
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
    global $sex;
    $in_req = $db->prepare("UPDATE 5_requests 
    SET name=:name, phone=:phone, mail=:mail, 
    sex=:sex, bio=:bio, birthday=:bday
    WHERE id = " . $db->quote($_COOKIE['id']));
    //$in_req = $db->prepare("UPDATE 5_requests SET name='changed' WHERE id = " . $db->quote($_SESSION['id']));
    $db->exec("DELETE FROM 5_req_lang WHERE req_id = " . $db->quote((string)$_COOKIE['id']));
    $in_langs = $db->prepare("INSERT INTO 5_req_lang VALUES (:id, :lang_id)");

    print($_POST['dbirth']);
    $in_req->execute(['name'=>$_POST['fio'], 'phone'=>$_POST['phone'],'mail'=>$_POST['email'], 'sex'=>$sex, 
    'bio'=>$_POST['bio'], 'bday'=>$_POST['dbirth']]);
    //$in_req->execute();

    print($_POST['dbirth']);
    print($_POST['abilities']);
    foreach($_POST['abilities'] as $ability){
      $in_langs->execute(['id'=>$_COOKIE['id'], 'lang_id'=>$ability]);
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
  setcookie('editing', 0, 1000);
  header('Location: admin.php');
}

//header('HTTP/1.0 401 Unauthorized');

$data = $db->query('SELECT * FROM 6_admins')->fetchAll();
$admins = array();
  foreach($data as $adm){
  $admins[$adm['admin_login']] = $adm['admin_h_passwd'];
}

//барьер
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    !array_key_exists($_SERVER['PHP_AUTH_USER'], $admins) ||
    !password_verify($_SERVER['PHP_AUTH_PW'], $admins[$_SERVER['PHP_AUTH_USER']])) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="adminum"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

//если всё норм
if ($_SERVER['REQUEST_METHOD']=='GET'){
  if (isset($_COOKIE['editing'])){
    GET();
  }
  else {
    get_all();
  }
}
else {
  //print_r($_POST);
  if (isset($_POST['edit_id'])){
    setcookie('id', $_POST['edit_id'], time() + 60*60);
    setcookie('editing', 1, time() + 60*60);
    header('Location: admin.php');
    exit();
  }
  elseif (isset($_POST['del_id'])){
    try{
      $db->query('DELETE FROM 5_req_lang WHERE req_id = ' . $db->quote($_POST['del_id']));
      $db->query('DELETE FROM 5_requests WHERE id = ' . $db->quote($_POST['del_id']));
      header('Location: admin.php');
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  else {
    POST();
  }
}
?>