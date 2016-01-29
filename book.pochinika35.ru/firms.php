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

<div>
	<a class="fancybox fancybox.iframe" title="Добавить контрагента" href="/inc/add.php?firm"><img width="30px" height="30px" title="Добавить контрагента" alt="Добавить контрагента" src="/img/add.jpg"></a>
</div>
<table  class="table table-striped">
	<thead>
		<tr>
			<th>More</th>
			<th>Название</th>
			<th>Телефон</th>
			<th>Email</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<?php select_firms(); ?>
	</tbody>
</table>

<?php include("template/default/footer.php"); ?>