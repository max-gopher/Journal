<?php require("template/default/header.php");
if(!isset($_SESSION['login'])){
	echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="index.php" 
//--> 
</script>';
}
require("config.php");
?>

<div class="master_menu">
	<a class="fancybox fancybox.iframe btn btn-primary" title="Добавить услугу" href="/inc/price.php">Добавить</a>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Номер</th>
			<th>Услуга</th>
			<th>Стоимость</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<?php selectPrice(); ?>
	</tbody>
</table>

<?php require("template/default/footer.php"); ?>