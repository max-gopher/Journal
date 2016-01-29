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
<div><h1>Страница не найдена</h1></div>
<?php include("template/default/footer.php"); ?>