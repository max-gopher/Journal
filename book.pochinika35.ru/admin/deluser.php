<?php include('../inc/head.inc'); ?>
<?php include('../config.php'); ?>
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
?>
<?php
if(isset($_GET['id'])) {
	$id = $_GET['id'];
	$select_userid = $connect_db->query("DELETE FROM users WHERE id = '$id'");
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="user.php" 
//--> 
</script>	
HTML;
}
?>