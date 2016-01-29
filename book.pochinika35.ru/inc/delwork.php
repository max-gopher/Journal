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
$title = 'Удаление записи';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
<?php
if(isset($_GET['id'])) {
	$idwork = $_GET['id'];
	$connect_db->query("DELETE FROM `work` WHERE `id` = '$idwork'");
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
<?php include('footer.inc'); ?>