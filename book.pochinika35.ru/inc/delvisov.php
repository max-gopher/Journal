<?php include('head.inc'); ?>
<?php
include("../config.php");
if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="index.php" 
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
if(isset($_GET['id'])) {
	$idv = $_GET['id'];
	$select_raboty = $connect_db->query("SELECT * FROM `raboty` WHERE `subid` = '$idv'");
	while ($fetch_raboty = $select_raboty->fetch_assoc()) {
		if(!empty($fetch_raboty['schet'])) {
			echo 'Для данного вызова сформерован счет №'.$fetch_raboty['schet'].'. Удалите сначала счет.';
			exit;
		}else {
			$del_raboty = $connect_db->query("DELETE FROM `raboty` WHERE `subid` = '$idv'");
			$del_visovid = $connect_db->query("DELETE FROM `visovi` WHERE `id` = '$idv'");
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
	}
}
?>

<?php include('footer.inc'); ?>