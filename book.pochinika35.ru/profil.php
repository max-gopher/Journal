<?php require("template/default/header.php");
if(!isset($_SESSION['login'])){
	echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="index.php" 
//--> 
</script>';
}
if($_SESSION['partner_id'] != '0') {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
require("config.php");
?>

<?php

if(isset($_GET['stra'])) {
	$stra = $_GET['stra'];
}else{
	$stra = 0;
}
$login = $_SESSION['login'];
$select_login = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$login' LIMIT 1");
$fetch_login = $select_login->fetch_assoc();
if(isset($_GET['uservisov'])) {
	$user_visov = $_GET['uservisov'];
}else{
	$user_visov = $fetch_login['id'];
}

echo '<div class="user_menu">
	<div class="button"><a class="fancybox fancybox.iframe" title="Мои вызовы" href="#">Мои вызовы</a></div>
	<div class="button"><a href="#">Мастерская</a></div>
	<div class="button"><a href="#">Мои продажи</a></div>
	<div class="button"><a href="#">Статистика</a></div>
	<div class="button"><a href="#">Заметки</a></div>
</div>';

echo '<div class="diapason">
	<select name="year">
		<option value="'.date('Y').'">'.date('Y').'</option>
	</select>
	<select name="month">
		<option value="01">Январь</option>
		<option value="02">Февраль</option>
		<option value="03">Март</option>
		<option value="04">Апрель</option>
		<option value="05">Май</option>
		<option value="06">Июнь</option>
		<option value="07">Июль</option>
		<option value="08">Август</option>
		<option value="09">Сентябрь</option>
		<option value="10">Октябрь</option>
		<option value="11">Ноябрь</option>
		<option value="12">Декабрь</option>
	</select>
	<select name="days">
		<option value="01-16">01 - 15</option>
		<option value="16-31">16 - 31</option>
	</select>
</div>';

echo '<table border="1" cellpadding="2">';
echo '<tbody>';
echo '<tr>';
echo '<th>More</th>';
echo '<th>Дата</th>';
echo '<th>Время</th>';
echo '<th>Адрес</th>';
echo '<th>Имя</th>';
echo '<th>Телефон</th>';
echo '<th>Проблема</th>';
echo '<th>Исполнитель</th>';
echo '<th>Статус</th>';
echo '<th>Действия</th>';
echo '</tr>';
select_visovi($stra, $user_visov);
echo '</tbody>';
echo '</table>';

?>

<?php include("template/default/footer.php"); ?>