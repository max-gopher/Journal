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
	$statusid = $_GET['id'];
	$select_as_id = $connect_db->query("SELECT * FROM vstatus WHERE id = '$statusid'");
	$fetch_as_id = $select_as_id->fetch_assoc();
}
?>
	<div style="width: 100%; background: rgba(117, 192, 0, 0.7); color: #fff;"><center><h2 style="padding: 5px;">Редактирование статуса</h2></center></div>
	<div style="display:-moz-box; display:-webkit-box; display:box; -moz-box-orient:horizontal; -webkit-box-orient:horizontal; box-orient:horisontal; width: 100%">
		<div id="newdoljnost" style="display: ;-moz-box-flex:0; -webkit-box-flex:0; box-flex:0; width:40%;">
			<form action="status.php" method="post">
				<div>
					<input style="width:200px; padding:5px;" autofocus required name="editstatus" type="text" value="<?php echo $fetch_as_id['name'] ?>">
				</div>
					<input type="hidden" name="statusid" value="<?php echo $fetch_as_id['id'] ?>">
				<div>
					<input style="margin-top:10px;" name="submit" type="submit" value="Сохранить">
				</div>
			</form>
		</div>
		<div style="-moz-box-flex:0; -webkit-box-flex:0; box-flex:0; width:57%; margin-left:17px">
			<?php select_status(); ?>
		</div>
	</div>
</body>
</html>