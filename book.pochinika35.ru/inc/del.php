<?php
include('head.inc');
include("../config.php");
if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="../index.php" 
//--> 
</script>	
HTML;
}
if($_SESSION['partner_id'] != '0') {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
?>

<?php 
if(isset($_GET['idcert'])) {
	$title = 'Удаление сертификата';
}
if(isset($_GET['firm'])) {
	$title = 'Удаление контрагента';
}
echo $modal_title_start.$title.$modal_title_end; ?>

<?php 

// Начало склада
// Удаляем категорию склада
if(isset($_GET['skladcat'])) {
	$idcat = $_GET['skladcat'];
	$connect_db->query("DELETE FROM `skladcat` WHERE `id` = '$idcat'");
}
// Удаляем девайс со склада
if(isset($_GET['devsklad'])) {
	$iddev = $_GET['devsklad'];
	$connect_db->query("DELETE FROM `sklad` WHERE `id` = '$iddev'");
}


// Начало черного списка. Удаление из черного списка
if(isset($_GET['black'])) {
	$idblack = $_GET['black'];
	
	$select_black = $connect_db->query("SELECT * FROM `black_list` WHERE `id` = '$idblack' LIMIT 1");
	$fetch_black = $select_black->fetch_assoc();
	
	$connect_db->query("DELETE FROM `black_list` WHERE `id` = '$idblack'");
	echo 'Запись "'.$fetch_black['adres'].'" успешно удалена.';
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 1000); 
//--> 
</script>	
HTML;
}

// Удаление сертификата
if(isset($_GET['visov'])) {
	echo '<div class="row popap">';
	echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$idcert = $_GET['idcert'];
	$connect_db->query("DELETE FROM `cert` WHERE `id` = '$idcert'");
	echo 'Сертификат № '.$idcert.' был удален.';
	echo '</div>';
	echo '</div>';
		echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 1000); 
//--> 
</script>	
HTML;
}

if(isset($_GET['firm'])) {
	echo '<div class="row popap">';
	echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$idfirm = $_GET['firm'];
	$connect_db->query("DELETE FROM `firms` WHERE `id` = '$idfirm'");
	echo 'Контрагент был удален.';
	echo '</div>';
	echo '</div>';
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 1000); 
//--> 
</script>	
HTML;
}

include('footer.inc'); ?>