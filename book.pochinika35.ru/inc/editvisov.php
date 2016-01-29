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
if($_GET['id']) {
	$id_visova = $_GET['id'];
	$select_visov = $connect_db->query("SELECT * FROM visovi WHERE `id` = '$id_visova'");
	$fetch_visov = $select_visov->fetch_assoc();
}
?>

<?php
if(isset($_POST['idvisova'])) {
	$partner = $_POST['partner'];
    $idv = $_POST['idvisova'];
	$datav = $_POST['data'];
    $partner = $_POST['partner'];
	$timev = $_POST['time'];
	$streetv = $_POST['street'];
	$homev = $_POST['home'];
	$housingv = $_POST['housing'];
	$apartmentv = $_POST['apartment'];
	$fonev = $_POST['fone'];
	if(!empty($_POST['vknamek'])) {
		$namekv = $_POST['vknamek'];
		$sale = 10;
		$vkgroup = 1;
		if($money != 0) {
			$sumsale = $money-($money/100*$sale);
		}
	}else{
		$namekv = str_replace('"', '&quot;', $_POST['namek']);
		$sale = 0;
		$vkgroup = 0;
		$sumsale = $money;
	}
	//$namekv = $_POST['namek'];
	$problemskv = $_POST['problemsk'];
	$engineerv = $_POST['engineer'];
	$statusv = $_POST['status'];
	$money = $_POST['money'];
	$problemse = $_POST['problemse'];
	
	$connect_db->query("UPDATE visovi SET `partner` = '$partner', `dateforengineer` = '$datav', `timeforengineer` = '$timev', `street` = '$streetv', `home` = '$homev', `housing` = '$housingv', `apartment` = '$apartmentv', `fone` = '$fonev', `namek` = '$namekv', `problemsk` = '$problemskv', `engineer` = '$engineerv', `status` = '$statusv', `problemse` = '$problemse', `vkgroup` = '$vkgroup', `money` = '$money', `sumsale` = '$sumsale', `sale` = '$sale' WHERE `id` = '$idv'");
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
<?php 
$title = 'Редактирование вызова';
if(!empty($fetch_visov['schet'])) {
	$disabled = 'disabled';
	$title = '<span style="color:red;">Сформерован счет №'.$fetch_visov['schet'].'</span>';
}
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
		<div class="row popap">
			<form id="myForm" method="post" action="editvisov.php">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div id="sandbox-container" class="mtop10">
						<div>
							Дата вызова
						</div>
						<div class="input-group date">
							<input type="text" name="data" class="form-control" required placeholder="Дата вызова" value="<?php echo $fetch_visov['dateforengineer']; ?>" ><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
						</div>
					</div>
					<div class="mtop10">
						<div>
							Время вызова
						</div>
						<div class="input-group clockpicker">
							<input type="text" name="time" required class="form-control" value="<?php echo $fetch_visov['timeforengineer']; ?>">
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-time"></span>
							</span>
						</div>
					</div>
					<div class="mtop10">
						<div>
							Улица
						</div>
						<input class="form-control" name="street" type="text" placeholder="Улица" value="<?php echo $fetch_visov['street']; ?>">
					</div>
					<div class="mtop10">
						<div>
							Дом
						</div>
						<input class="form-control" name="home" type="text" placeholder="Дом" value="<?php echo $fetch_visov['home']; ?>">
					</div>
					<div class="mtop10">
						<div>
							Корпус
						</div>
						<input class="form-control" name="housing" type="text" placeholder="Корпус" value="<?php echo $fetch_visov['housing']; ?>">
					</div>
					<div class="mtop10">
						<div>
							Квартира
						</div>
						<input class="form-control" name="apartment" type="text" placeholder="Квартира" value="<?php echo $fetch_visov['apartment']; ?>">
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div class="mtop10">
						<div>
							Телефон
						</div>
						<input class="form-control" name="fone" type="text" placeholder="Телефон" value="<?php echo $fetch_visov['fone']; ?>">
					</div>
					<div class="mtop10">
						<?php if($fetch_visov['vkgroup'] == 1) {
							$vkselected = 'selected';
						}else{
							$bookselected = 'selected';
						} ?>
						<div>
							Сосоит в группе vk?
						</div>
						<select name="vkgroup" class="form-control" id="selectfio">
							<option <?php echo $bookselected; ?> value="book">Не состоит</option>
							<option <?php echo $vkselected; ?> value="vk">Да, состоит</option>
						</select>
					</div>
					<div class="mtop10">
						<div>
							Ф.И.О.:
						</div>
						<div id="fio">
							<input class="form-control" name="namek" type="text" required placeholder="Ф.И.О." value="<?php echo $fetch_visov['namek']; ?>">
						</div>
						<div id="vkfio">
							<select  data-live-search="true" name="vknamek" style="display:none;" class="form-control selectpicker bs-select-hidden">
								<option value="0">Выберите участника</option>
								<?php select_vkusers($fetch_visov['namek']); ?>
							</select>
						</div>
					</div>
					<div class="mtop10">
						<div>
							Заявленная проблема
						</div>
						<input class="form-control" name="problemsk" type="text" placeholder="Заявленная проблема" value="<?php echo $fetch_visov['problemsk']; ?>">
					</div>
					<div class="mtop10">
				        <select class="form-control" name="partner">
				            <option value="0">Выберите партнера</option>
				            <?php select_users('no', $fetch_visov['partner']); ?>
				        </select>
			        </div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div class="mtop10">
						<div>
							Ведущий инженер
						</div>
						<select  class="form-control" id="username" name="engineer" required> 
							<option><?php echo $fetch_visov['engineer']; ?></option>
							<?php select_ing($fetch_visov['engineer']); ?>
						</select>
					</div>
					<div class="mtop10">
						<div>
							Статус
						</div>
						<select class="form-control" name="status" required>
							<option><?php echo $fetch_visov['status'] ?></option>
							<?php select_status_visov(); ?>
						</select>
					</div>
					<div class="mtop10">
						<div>
							Выполненные работы
						</div>
						<input  class="form-control" name="problemse" placeholder="Выполненные работы" type="text" value="<?php echo $fetch_visov['problemse']; ?>">
					</div>
						<?php if(!empty($fetch_visov['kvitancia'])) {
							echo '<div class="mtop10"><div>Номер квитанции</div><input class="form-control" name="kvitancia" type="text" value='.$fetch_visov['kvitancia'].'></div>';
						}  ?>
						<?php if(!empty($fetch_visov['money'])) {
							echo '<div class="mtop10"><div>Сумма ремонта</div><input class="form-control" name="money" type="text" value='.$fetch_visov['money'].'></div>';
						}  ?>
					<div style="display: none;">
						<input class="form-control" name="idvisova" type="text" value="<?php echo $id_visova ; ?>">
					</div>
					<div class="mtop10">
						<input <?php echo $disabled; ?> class="btn btn-success" id="edit" name="submit" type="submit" value="Сохранить изменения">
					</div>
				</div>
			</form>
		</div>
<?php include('footer.inc'); ?>