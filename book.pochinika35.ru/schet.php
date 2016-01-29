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

if(!isset($_GET['which'])) {
	$which = '0';
}else {
	$which = $_GET['which'];
}
?>

<div class="master_menu">
	<a class="fancybox fancybox.iframe btn btn-primary" title="Создать новый счет" href="/inc/scheta/adaptation.php">Создать</a>
	<a class="btn btn-primary" title="Просмотреть выставленные счета" href="/schet.php?which=1">Выставленные</a>
	<a class="btn btn-primary" title="Просмотреть оплаченные счета" href="/schet.php?which=3">Оплаченные</a>
	<a class="btn btn-primary" title="Просмотреть частично оплаченные счета" href="/schet.php?which=2">Оплаченные частично</a>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>More</th>
			<th>Дата</th>
			<th>Организация</th>
			<th>Адрес</th>
			<th>Сумма</th>
			<th>Статус</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody id="scheta">
		<?php select_schet($which); ?>
	</tbody>
</table>

<?php require("template/default/footer.php"); ?>