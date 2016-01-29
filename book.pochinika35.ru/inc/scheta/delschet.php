<?php 
include('../head.inc');
include('../../config.php'); ?>
<?php if(!isset($_SESSION['login'])){
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
if(!isset($_GET['edit'])) {
	$title = 'Удаление счета';
}
echo $modal_title_start.$title.$modal_title_end;
if(isset($_GET['id'])) {
	$sid = $_GET['id'];
	$dschet = '';
	$select_raboty = $connect_db->query("SELECT * FROM `raboty` WHERE `schet` = '$sid'");
	$select_visovi = $connect_db->query("SELECT * FROM `visovi` WHERE `schet` = '$sid'");
	if($select_visovi->num_rows >= 1) {
		while($fetch_visovi = $select_visovi->fetch_assoc()) {
			$vid = $fetch_visovi['id'];
			$connect_db->query("UPDATE `visovi` SET `schet` = '$dschet' WHERE `schet` = '$sid'");
		}
	}
	if ($select_raboty->num_rows >= 1) {
		while($fetch_raboty = $select_raboty->fetch_assoc()) {
			$connect_db->query("UPDATE `raboty` SET `schet` = '$dschet' WHERE `schet` = '$sid'");
		}
		$connect_db->query("DELETE FROM `scheta` WHERE `id` = '$sid'");
	}else {
		$connect_db->query("DELETE FROM `scheta` WHERE `id` = '$sid'");
	}
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
?>

<?php 
// Подключаем footer
include('../footer.inc');
?>