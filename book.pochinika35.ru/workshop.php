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

<div class="master_menu">
	<a class="fancybox fancybox.iframe btn btn-primary" title="Подкинуть работки" href="/inc/newwork.php">Добавить</a>
	<a class="btn btn-primary" title="Просмотреть принятые" href="/workshop.php?adopted">Принятые</a>
	<a class="btn btn-primary" title="Просмотреть что в работе" href="/workshop.php?work">Вработе</a>
	<a class="btn btn-primary" title="Просмотреть готовые" href="/workshop.php?ready">Готовые</a>
	<a class="btn btn-primary" title="Просмотреть выданные" href="/workshop.php?finish">Выданные</a>
</div>
<table class="table table-striped mtop10">
	<tbody>
		
		<?php 
		if(isset($_GET['adopted'])) {
			$which = 1;
		}
		if(isset($_GET['work'])) {
			$which = 2;
		}
		if(isset($_GET['ready'])) {
			$which = 3;
		}
		if(isset($_GET['finish'])) {
			$which = 5;
		}
		select_work($status = $which);
		?>
	</tbody>
</table>

<?php include("template/default/footer.php"); ?>