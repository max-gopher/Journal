<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Установка системы управления сайтом Cafe CMS</title>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
html{background-color:#ccc}
body{width:800px;height:600px;margin:0px auto 0px auto;padding:30px;background-color:#fff;border:#bbb solid 2px}
div{height:250px;margin:30px 0px 20px 0px}
.title{font-size:36px}
.footer-text{text-align:right}
</style>
</head>
<body>
<p class="title">Установка Cafe CMS </p>
<?
//если передан параметр install, т. е. кнопка "установить" была нажата, начинаем установку
if ($_POST['install']) {
//соединяемся с БД
//сервер БД установлен на этом же сервере, т. е. localhost
//логин и пароль для соединения передаются из форму установки
$connect_db = mysql_connect ('localhost', $_POST['db_login'], $_POST['db_password']);
//дамп таблицы pages, кодировка utf-8; в этой таблице будут храниться статичные страницы
//в таблице будет 5 полей
//id - порядковый номер записи, автоинкремент и примари
//date - дата сохранения записи
//url - для каждой записи можно будет присвоить свой url
//title - заголовок записи
//text - текст записи
$creat_table_pages = "CREATE TABLE `".$_POST['db_name']."`.`".$_POST['db_prefix']."_pages` (
`id` INT(5) AUTO_INCREMENT PRIMARY KEY, 
`date` INT(10), 
`url` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci, 
`title` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci,
`text` VARCHAR(40000) CHARACTER SET utf8 COLLATE utf8_general_ci,
) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
//дамп таблицы users, кодировка utf-8; в этой таблице будут храниться зарегистрированные пользователи
//id - порядковый номер записи
//login - логин пользователя
//password - пароль пользователя
//reg_date - дата регистрации
//status - статус пользователя: 0 - не активен, 1 - админ, 2 - модератор, 3- пользователь
$creat_table_users = "CREATE TABLE `".$_POST['db_name']."`.`".$_POST['db_prefix']."_users` (
`id` INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`login` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`password` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`reg_date` INT(10) NOT NULL, 
`status` INT(1) NOT NULL
) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
//если таблицы pages и users созданы, добавляем первого пользователя, т. е. регистрируем админа
if(mysql_query($creat_table_pages) && mysql_query($creat_table_users)){
//определяем переменные переданные из формы
$now_date = mktime (); //текущая дата в таймстампе
$adm_login = $_POST['adm_login']; //логин админа
$adm_password = $_POST['adm_password']; //пароль админа
$ip = $_SERVER['REMOTE_ADDR'];
//добавляем запись в таблицу users
$add_admin = "INSERT ".$_POST['db_name'].".".$_POST['db_prefix']."_users (
login, 
password, 
reg_date, 
status
) 
VALUES (
'$adm_login', 
'$adm_password', 
'$now_date', 
'1')";
//если админ добавлен
if (mysql_query($add_admin)){
//записываем настройки в файл config.php
$w_string = "<?
\$db_name = \"".$_POST['db_name']."\";        //База данных
\$db_prefix = \"".$_POST['db_prefix']."\";    //Префикс для таблиц
\$db_login = \"".$_POST['db_login']."\";    //Логин для доступа к БД
\$db_password = \"".$_POST['db_password']."\";    //Пароль для доступа к БД
\$site_name = \"".$_POST['site_name']."\";    //Название сайта
?>";
$fp = fopen($_SERVER["DOCUMENT_ROOT"]."/config.php", "w");
fwrite($fp, $w_string);
fclose($fp);
//выводим сообщение об успешной установке
echo "<div>
<p>Установка завершена!</p>

<p>Запонмните ваш логин ".$_POST['adm_login']." и пароль ".$_POST['adm_password'].".</p>

<p>Теперь ваш сайт доступен по адресу <a href=\"http://".$_SERVER['SERVER_NAME']."\">".$_SERVER['SERVER_NAME']."</a></p>
<p><a href=\"/admin/\">Перейти в панель управления сайтом</a>.</p>
<p>Не забудьте удалить файл install.php.</p>
</div>";
}
//если сохранить настройки не получилось, выводим сообщение об ошибке
else{
echo "<div>
<p>Ошибка при сохранении настроек.</p>
</div>";
}
}
//если таблицы pages и users не получилось создать, выводим сообщение об ошибке
else {
echo "<div>
<p>Во время установки возникла ошибка: не удалось создать таблицы.</p>
</div>";
}
}
//если параметр install не передан, т. е. кнопка "установить" не была нажата, выводим форму установки
else {
?>
<p>Заполните все поля и нажмите кнокпу &laquo;установить&raquo;.</p>
<form action="/install.php" name="install_form" method="post">
<div style="width:50%;float:left">
Название сайта:<br>
<input type="text" name="site_name" size="40"><br><br>
Логин администратора сайта:<br>
<input type="text" name="adm_login" size="40"><br>
Пароль администратора сайта:<br>
<input type="text" name="adm_password" size="40"><br><br>
</div>
<div style="width:50%;float:left">База данных:<br>
<input type="text" name="db_name" size="40"><br>
Префикс для таблиц:<br>
<input type="text" name="db_prefix" size="40"><br><br>
Логин для доступа к БД:<br>
<input type="text" name="db_login" size="40"><br>
Пароль для доступа к БД:<br>
<input type="text" name="db_password" size="40"><br><br>
<input type="submit" name="install" value="установить">
</div>
</form>
<hr>
<p class="footer-text">Система управления сайтом Cafe CMS. Разработчик <a href="http://rad-li.ru">rad-li</a>.</p>
<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"></a></p>
<?
}
?>
</body>
</html>