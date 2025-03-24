<html>
  <head>
    <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
    </style>
  </head>
  <body>

<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
//else {
//  print '<div id="messages">Спасибо, результаты сохранены</div>';
//}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>


    <form action="" method="POST">
      <label>
	ФИО:
	<input type="text" name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" /><br>
      </label>
      <label>
	Телефон:
	<input type="text" name="phone" <?php if ($errors['phone']) {print 'class="error"';} ?> value="<?php print $values['phone']; ?>" /><br>
      </label>
      <label>
	Почта:
	<input type="email" name="email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" /><br>
      </label>
      <label>
        Дата рождения: 
        <input type="date" name="dbirth" <?php if ($errors['dbirth']) {print 'class="error"';} ?> value="<?php print $values['dbirth']; ?>" />
      </label><br>
      
      <label <?php if ($errors['sex']) {print 'class="error"';} ?>>
        Пол:<br>
        <label><input type="radio" <?php if (!$errors['sex'] && $values['sex']!=1) {print 'checked="checked"';}?> name="sex" value="fem">Женский</label>
        <label><input type="radio" <?php if ($values['sex']=='1') {print 'checked="checked"';}?> name="sex" value="male">Мужской</label><br>
      </label>
      
<select name="abilities[]" multiple="multiple">
<?php
$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
$lngs = $db->query('select * from 4_languages')->fetchAll();
$l = [];
foreach($lngs as $q2){
  $l[$q2[0]] = $q2[1];
}
foreach($l as $key => $value){
  echo "<option value=\"$key\"";
  if(!empty($_COOKIE[$key])){
    echo " selected";
  }
  echo ">$value</option>";
}
?>

</select>
      <br>
      <label <?php if ($errors['bio']) {print 'class="error"';} ?>>
        Биография:<br>
        <textarea name="bio"><?php print $values['bio']; ?></textarea>
      </label><br>

      <label>
        Согласен с обработкой пермональных данных
        <input type="checkbox" name="agree" <?php if ($errors['agree']) {print 'class="error"';} ?> value="1" <?php if(!empty($values['agree'])) print('checked'); ?> /><br>
      </label>
      
      <input type="submit" value="ok" />
    </form>
  </body>
</html>
