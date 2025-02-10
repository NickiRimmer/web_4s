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
        <label><input type="radio" checked="checked" name="sex" value="Значение1">Женский</label>
        <label><input type="radio" name="sex" value="Значение2">Мужской</label><br>
	</label>
                    <label>
                    Любимый язык программирования:
                    <br>
                    <select name="abilities[]" multiple="multiple">
                        <option value="1">Pascal</option>
                        <option value="2">C</option>
                        <option value="3">C++</option>
                        <option value="4">JavaScript</option>
                        <option value="5">PHP</option>
                        <option value="6">Python</option>
                        <option value="7">Java</option>
                        <option value="8">Haskel</option>
                        <option value="9">Clojure</option>
                        <option value="10">Prolog</option>
                        <option value="11">Scala</option>
			<option value="12">Go</option>
                    </select>
                    </label><br>

                    <label>
                    Биография:<br>
                    <textarea name="bio"></textarea>
                    </label><br>

                    <label><input type="checkbox" checked="checked" name="agree">С контрактом ознакомлен(а)</label><br>

                    <input type="submit" value="OK">
</form>
