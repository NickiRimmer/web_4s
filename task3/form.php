<form action="" method="POST">

        <input type="text" placeholder="Введите ФИО" name="fio"><br>

	<input type="tel" placeholder="Введите ваш номер телефона" name="phone"><br>

	<input type="email" placeholder="Введите ваш email" name="email"><br>

        <label>
        Дата рождения:<br>
        <input type="date" name="dbirth">
        </label><br>

	<label>
        Пол:<br>
        <label><input type="radio" checked="checked" name="sex" value="fem">Женский</label>
        <label><input type="radio" name="sex" value="male">Мужской</label><br>
	</label>
                    <label>
                    Любимый язык программирования:
                    <br>
                    <select name="abilities[]" multiple="multiple">
<?php
$user = 'u68592'; // Заменить на ваш логин uXXXXX
$pass = '6714103'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68592', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
$lngs = $db->query('select * from languages')->fetchAll();
$l = [];
foreach($lngs as $q2){
  $l[$q2[0]] = $q2[1];
}
foreach($l as $key => $value){
  echo "<option value=\"$key\">$value</option>";
}
?>

                    </select>
                    </label><br>

                    <label>
                    Биография:<br>
                    <textarea name="bio"></textarea>
                    </label><br>

                    <label><input type="checkbox" checked="checked" name="agree">С контрактом ознакомлен(а)</label><br>

                    <input type="submit" value="OK">
</form>
