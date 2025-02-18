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
                        <option value="Pascal">Pascal</option>
                        <option value="C">C</option>
                        <option value="C++">C++</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="PHP">PHP</option>
                        <option value="Python">Python</option>
                        <option value="Java">Java</option>
                        <option value="Haskel">Haskel</option>
                        <option value="Clojure">Clojure</option>
                        <option value="Prolog">Prolog</option>
                        <option value="Scala">Scala</option>
			<option value="Go">Go</option>
                    </select>
                    </label><br>

                    <label>
                    Биография:<br>
                    <textarea name="bio"></textarea>
                    </label><br>

                    <label><input type="checkbox" checked="checked" name="agree">С контрактом ознакомлен(а)</label><br>

                    <input type="submit" value="OK">
</form>
