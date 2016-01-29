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
$title = 'Вернуть аппарат на доработку';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
<?php
if(isset($_GET['id'])) {
	$idwork = $_GET['id'];
	$status = 2;
	
	$connect_db->query("DELETE `gperiod` WHERE `receipt` = '$idwork'");
	$connect_db->query("UPDATE `work` SET `status` = '$status' WHERE `id` = '$idwork'");
	
	echo '<div style="width:100%; text-align:center;">Аппарат был возвращен на доработку</div>';
}
?>
<?php include('footer.inc'); ?>