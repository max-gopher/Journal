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
if(isset($_GET['id'])) {
	// Получаем запись по id для дальнейшего редактирования
	$id = $_GET['id'];
	$select_edit_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$id' LIMIT 1");
	$fetch_edit_work = $select_edit_work->fetch_assoc();
}
if(isset($_POST['submit'])) {
    $partner = $_POST['$partner'];
	$select_brend = 'Выберите производителя';
	$select_ing = 'Инженер не определен';
	$idwork = $_POST['idwork'];
	$selectdevice = $_POST['device'];
	$selectbrend = $_POST['manufacturer'];
	$model = $_POST['model'];
	$sn = $_POST['sn'];
	$money = $_POST['money'];
	if(!empty($_POST['vkfio'])) {
		$fio = $_POST['vkfio'];
		$sale = 10;
		$vkgroup = 1;
		if($money != 0) {
			$sumsale = $money-($money/100*10);
		}
	}else{
		$fio = $_POST['fio'];
		$sale = 0;
		$vkgroup = 0;
		$sumsale = $money;
	}
	$adres = $_POST['adres'];
	$fone1 = $_POST['fone1'];
	$fone2 = $_POST['fone2'];
	$komplekt = $_POST['komplekt'];
	$engineer = $_POST['engineer'];
	$brok = $_POST['brok'];
	$problemse = $_POST['problemse'];
	if(!empty($_POST['newdevice'])) {
		$newdevice = $_POST['newdevice'];
		$add_dev = $connect_db->prepare("INSERT INTO `device` (`Name`) VALUE (?)");
		$add_dev->bind_param("s", $newdevice);
		$add_dev->execute();
		$add_dev->close();
		unset($selectdevice);
		$selectdevice = $newdevice;
	}
	if(!empty($_POST['newbrend'])) {
		$newbrend = $_POST['newbrend'];
		$add_brend = $connect_db->prepare("INSERT INTO `brends` (`Name`) VALUE (?)");
		$add_brend->bind_param("s", $newbrend);
		$add_brend->execute();
		$add_brend->close();
		unset($selectbrend);
		$selectbrend = $newbrend;
	}
	if($engineer == $select_ing) {
		$engineer = $select_ing;
		$status = 1;
	}else {
		$status = 2;
	}
	if($selectbrend == $select_brend) {
		$selectbrend = '';
	}
	$connect_db->query("UPDATE `work` SET `partner` = '$partner', `device` = '$selectdevice', `brend` = '$selectbrend', `model` = '$model', `sn` = '$sn', `fio` = '$fio', `adres` = '$adres', `fone1` = '$fone1', `fone2` = '$fone2', `komplekt` = '$komplekt', `engineer` = '$engineer', `brok` = '$brok', `money` = '$money', `problemse` = '$problemse', `status` = '$status', `vkgroup` = '$vkgroup', `sumsale` = '$sumsale', `sale` = '$sale' WHERE `id` = '$idwork'");
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

$title = 'Редактировать запись';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
	
<form action="editwork.php" method="post">
	<div class="row popap">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		    <div class="mtop10">
				<select class="form-control" name="partner">
				    <option value="0">Выберите партнера</option>
				    <?php select_users('no', $fetch_edit_work['partner']); ?>
				</select>
			</div>
			<div style="margin-top: 10px;">
				<div>
					Тип аппарата:<img onclick="newEl()" style="margin-left:5px; cursor:pointer;" width="15px" title="Добавить аппарат" alt="Добавить аппарат" src="../img/add.jpg">
				</div>
				<select class="form-control" id="selectdevice" style="display:block;" name="device">
					<option><?php echo $fetch_edit_work['device']; ?></option>
					<?php select_dev(); ?>
				</select>
				<input class="form-control" id="newdevice" style="display:none;" type="text" name="newdevice" placeholder="Новый аппарат">
			</div>
			<div style="margin-top: 10px;">
				<div>
					Производитель:<img onclick="newBrend()" style="margin-left:5px; cursor:pointer;" width="15px" title="Добавить производителя" alt="Добавить производителя" src="../img/add.jpg">
				</div>
				<select class="form-control" id="selectbrend" style="display:block;" name="manufacturer">
					<option><?php echo $fetch_edit_work['brend']; ?></option>
					<?php select_brend(); ?>
				</select>
				<input class="form-control" id="newbrend" style="display:none;" type="text" name="newbrend" placeholder="Новый производитель">
			</div>
			<div style="margin-top: 10px;">
				<div>
					Модель:
				</div>
				<input class="form-control" name="model" type="text" required placeholder="Модель" value="<?php echo $fetch_edit_work['model']; ?>">
			</div>
			<div style="margin-top: 10px;">
				<div>
					Серийный номер:
				</div>
				<input class="form-control" name="sn" type="text" value="<?php echo $fetch_edit_work['sn']; ?>" placeholder="Серийный номер">
			</div>
			<div style="margin-top: 10px;">
				<?php if($fetch_edit_work['vkgroup'] == 1) {
					$vkselected = 'selected';
				}else{
					$bookselected = 'selected';
				}
				?>
				<div>
					Сосоит в группе vk?
				</div>
				<select name="vkgroup" class="form-control" id="selectfio">
					<option <?php echo $bookselected; ?> value="book">Не состоит</option>
					<option <?php echo $vkselected; ?> value="vk">Да, состоит</option>
				</select>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<div style="margin-top: 10px;">
				<div>
					Ф.И.О.:
				</div>
				<div id="fio">
					<input class="form-control" name="fio" type="text" required placeholder="Ф.И.О." value="<?php echo $fetch_edit_work['fio']; ?>">
				</div>
				<div id="vkfio">
					<select  data-live-search="true" name="vkfio" style="display:none;" class="form-control selectpicker bs-select-hidden">
						<option value="0">Выберите участника</option>
						<?php select_vkusers($fetch_edit_work['fio']); ?>
					</select>
				</div>
			</div>
			<div style="margin-top: 10px;">
				<div>
					Адрес:
				</div>
				<input class="form-control" name="adres" type="text" value="<?php echo $fetch_edit_work['adres'];?>" placeholder="Адрес">
			</div>
			<div style="margin-top: 10px;">
				<div>
					Телефон 1:
				</div>
				<input class="form-control" name="fone1" type="text" required placeholder="Телефон 1" value="<?php echo $fetch_edit_work['fone1']; ?>">
			</div>
			<div style="margin-top: 10px;">
				<div>
					Телефон 2:
				</div>
				<input class="form-control" name="fone2" type="text" value="<?php echo $fetch_edit_work['fone2']; ?>" placeholder="Телефон 2">
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<div style="margin-top: 10px;">
				<div>
					Комплектация:
				</div>
				<input class="form-control" name="komplekt" type="text" required placeholder="Комплектация" value="<?php echo $fetch_edit_work['komplekt']; ?>">
			</div>
			<div>
				<div style="margin-top: 10px;">
					Инженер:
				</div>
				<select class="form-control" name="engineer">
					<option><?php echo $fetch_edit_work['engineer']; ?></option>
					<?php select_ing(); ?>
				</select>
			</div>
			<div style="margin-top: 10px;">
				<div>
					Неисправность:
				</div>
				<input class="form-control" name="brok" type="text" required placeholder="Неисправность" value="<?php echo $fetch_edit_work['brok']; ?>">
			</div>
			<?php if(!empty($fetch_edit_work['money'])) {
				echo '<div style="margin-top:10px;"><div>Стоимость ремонта без скидки</div>';
					echo '<input class="form-control" name="money" type="text" required value="'.$fetch_edit_work['money'].'">';
				echo '</div>';
			} ?>
			<?php if(!empty($fetch_edit_work['sumsale'])) {
				echo '<div style="margin-top:10px;"><div>Стоимость ремонта со скидкой</div>';
					echo '<input class="form-control" name="sumsale" disabled type="text" required value="'.$fetch_edit_work['sumsale'].'">';
				echo '</div>';
			} ?>
			<?php if(!empty($fetch_edit_work['problemse'])) {
				echo '<div style="margin-top:10px;"><div>Неисправность</div>';
					echo '<input class="form-control" name="problemese" type="text" required value="'.$fetch_edit_work['problemse'].'">';
				echo '</div>';
			} ?>
			<input name="idwork" type="hidden" value="<?php echo $_GET['id']; ?>">
			<div style="margin-top: 10px;">
				<input class="btn btn-success" name="submit" type="submit" value="Сохранить">
			</div>
		</div>
	</div>
</form>

<?php include('footer.inc'); ?>