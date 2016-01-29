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
	$select_as_id = $connect_db->query("SELECT * FROM doljnosti WHERE id = '$id'");
	$select_as_id1 = $select_as_id->fetch_assoc();
}
$title = 'Редактирование должности'
?>
	<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap">
		<div id="editdoljnost" class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Поля редактирования должности</div>
				<div class="pad0555">
					<form name="formeditstavka" action="doljnost.php" method="post">
						<div class="mtop10">
							<div>Должность:</div>
							<input class="form-control" name="editdoljnost" required type="text" value="<?php echo $select_as_id1['doljnost']; ?>">
						</div>
						<div class="mtop10">
							<div>Ставка:</div>
							<input class="form-control" required name="editstavka" type="text" value="<?php echo $select_as_id1['stavka']; ?>">
						</div>
						<input name="editdoljnostid" type="hidden" value="<?php echo $select_as_id1['id'] ?>">
						<div class="mtop10">
							<input class="btn btn-success btn-block" name="submit" type="submit" value="Сохранить">
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Существующие должности</div>
				<div class="pad0555">
					<table class="table table-striped mperimetr mtop10">
						<?php select_doljnost(); ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>