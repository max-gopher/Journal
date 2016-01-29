<?php include('head.inc'); ?>
<?php
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
if(isset($_POST['idvisova'])) {
	$idv = $_POST['idvisova'];
	$datav = $_POST['data'];
	$timev = $_POST['time'];
	$streetv = $_POST['street'];
	$homev = $_POST['home'];
	$housingv = $_POST['housing'];
	$apartmentv = $_POST['apartment'];
	$fonev = $_POST['fone'];
	$namekv = $_POST['namek'];
	$problemskv = $_POST['problemsk'];
	$engineerv = $_POST['engineer'];
	$statusv = $_POST['status'];
	$connect_db->query("UPDATE visovi SET `dateforengineer` = '$datav', `timeforengineer` = '$timev', `street` = '$streetv', `home` = '$homev', `housing` = '$housingv', `apartment` = '$apartmentv', `fone` = '$fonev', `namek` = '$namekv', `problemsk` = '$problemskv', `engineer` = '$engineerv', `status` = '$statusv' WHERE `id` = '$idv'");
}
?>