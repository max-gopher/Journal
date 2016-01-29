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
if(isset($_POST['newstatus'])) {
	$newstatus = $_POST['newstatus'];
	$numstatus = $_POST['numstatus'];
	$stmt = $connect_db->prepare("INSERT INTO vstatus (`name`, `num`) VALUE (?,?)");
	$stmt->bind_param("si", $newstatus, $numstatus);
	$stmt->execute();
	$stmt->close();
}

/***************************************************** Получаем данные из editanddelstatus.php и обновляем запись ****************************************/
if(isset($_POST['statusid']))
	$statusid = $_POST['statusid'];
	$editstatus = $_POST['editstatus'];
	$connect_db->query("UPDATE vstatus SET `name` = '$editstatus' WHERE id = '$statusid'");
/******************************************************************************************************************************************************************/
$title = 'Управление статусами'
?>
	<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap">
		<div id="newdoljnost" class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Добавление нового статуса</div>
				<div class="pad0555">
					<form action="status.php" method="post">
						<div class="mtop10">
							<input class="form-control" autofocus required name="newstatus" type="text" placeholder="Введите новый статус">
						</div>
						<div class="mtop10">
							<input class="form-control" autofocus required name="numstatus" type="text" placeholder="Введите номер статуса">
						</div>
						<div class="mtop10">
							<input class="btn btn-success btn-block" name="submit" type="submit" value="Добавить">
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
						<?php select_status(); ?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row popap">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="bs-callout bs-callout-warning">
				<h4>Действующие номера статусов:</h4>
				<table class="table table-striped mperimetr mtop10">
					<thead>
						<tr>
							<th>Код</th>
							<th>Статус</th>
						</tr>
					</thead>
					<tbody>
						<tr><td>1</td><td>Принят</td></tr>
						<tr><td>2</td><td>В работе</td></tr>
						<tr><td>3</td><td>Готов</td></tr>
						<tr><td>4</td><td>Исполнено</td></tr>
						<tr><td>5</td><td>Сдан</td></tr>
					</tbody>
				</table>
  			</div>
		</div>
	</div>
</body>
</html>