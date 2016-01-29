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
	<a class="fancybox fancybox.iframe btn btn-primary" title="Добавить сертификат" href="/inc/new.php?cert">Добавить</a>
	<a class="btn btn-primary" title="Просмотреть принятые" href="/cert.php?acting">Действующие</a>
	<a class="btn btn-primary" title="Просмотреть что в работе" href="/cert.php?finish">Использованные</a>
</div>
		<?php 
		if(isset($_GET['acting'])) {
			$which = 1;
		}
		if(isset($_GET['finish'])) {
			$which = 0;
		}
		select_cert($status = $which);
		?>

<?php include("template/default/footer.php"); ?>