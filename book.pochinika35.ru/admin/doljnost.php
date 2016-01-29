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
// Записываем новую должность
if(isset($_POST['newdoljnost'])) {
	$newdoljnost = $_POST['newdoljnost'];
	$stavka = $_POST['stavka'];
	$stmt = $connect_db->prepare("INSERT INTO doljnosti (`doljnost`, `stavka`) VALUE (?,?)");
	$stmt->bind_param("ss", $newdoljnost, $stavka);
	$stmt->execute();
	$stmt->close();
}
// Конец записи

//******************************************* Получаем данные из editanddeldoljnost.php и обновляем запись в базе **********************************************************//
if(isset($_POST['editdoljnost']) && isset($_POST['editstavka'])) {
	$id = $_POST['editdoljnostid'];
	$editdoljnost = $_POST['editdoljnost'];
	$editstavka = $_POST['editstavka'];
	$connect_db->query("UPDATE doljnosti SET `doljnost` = '$editdoljnost', `stavka` = '$editstavka' WHERE id = '$id'");
}
//************************************************************************************************************************************************************************************//
$title = 'Управление должностями'
?>
	<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap>">
		<div id="newdoljnost" class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Добавление новой должности</div>
				<div class="pad0555">
					<form action="doljnost.php" method="post">
						<div class="mtop10">
							<input class="form-control" autofocus required name="newdoljnost" type="text" placeholder="Введите новую должность">
						</div>
						<div class="mtop10">
							<input class="form-control" required name="stavka" type="text" placeholder="Введите ставку">
						</div>
						<div class="mtop10">
							<input class="btn btn-success btn-block" style="margin-top:10px;" name="submit" type="submit" value="Добавить">
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