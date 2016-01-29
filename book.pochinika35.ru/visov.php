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
$user_visov = 0;
?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>More</th>
			<th>Дата</th>
			<th>Время</th>
			<th>Адрес</th>
			<th>Имя</th>
			<th>Телефон</th>
			<th>Проблема</th>
			<th>Исполнитель</th>
			<th>Статус</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<?php select_visovi($stra, $user_visov); ?>
	</tbody>
</table>

<?php require("template/default/footer.php"); ?>
