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
$user = $_SESSION['login'];
$who = 'arhiv';

// Получаем должность пользователя
$select_user = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$user' LIMIT 1");
$fetch_user = $select_user->fetch_assoc();
$doljnost = $fetch_user['doljnost'];
// Получаем id должности для отображения документов пользователя, либо документов всех пользователей
$select_doljnost = $connect_db->query("SELECT * FROM `doljnosti` WHERE `doljnost` = '$doljnost' LIMIT 1");
$fetch_doljnost = $select_doljnost->fetch_assoc();
if($fetch_doljnost['id'] == '6' || $fetch_doljnost['id'] == '13'){
	echo '<form class="form-inline"><div class="form-group"><select id="arhiv_select_users" class="form-control mbottom10 max-width300" name="user">
			<option value="no">Выберите пользователя</option>';
			select_users($who);
	echo '</select></div>';
}
?>
	<div class="form-group">
		<select id="arhiv_select_document" class="form-control mbottom10 max-width300" name="doc">
			<option value="no">Выберите документ</option>
			<option value="cheki">Чеки, гарантия</option>
			<option value="aktswork">Акты выполненных работ</option>
		</select>
	</div>
</form>
<div>
	<table id="arhive_table" class="table table-striped">
	<!-- Тут выводится таблица -->
	</table>
</div>
<?php include("template/default/footer.php"); ?>