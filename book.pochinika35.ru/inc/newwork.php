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
$select_dev = 'Выберите тип аппарата';
$select_brend = 'Выберите производителя';
$select_ing = 'Выберите инженера';
if(isset($_POST['submit'])) {
	$status = 1;
    $partner = $_POST['partner'];
	$selectdevice = $_POST['device'];
	$selectbrend = $_POST['manufacturer'];
	$model = $_POST['model'];
	$sn = $_POST['sn'];
    // Если указан пользователь vk.com
	if(!empty($_POST['vkfio'])) {
		$fio = $_POST['vkfio'];
		$sale = 10;
		$vkgroup = 1;
	}else{
		$fio = clean_input($_POST['fio']);
		$sale = 0;
		$vkgroup = 0;
	}
	$adres = $_POST['adres'];
	$fone1 = $_POST['fone1'];
	$fone2 = $_POST['fone2'];
	$komplekt = $_POST['komplekt'];
	$engineer = $_POST['engineer'];
	$brok = $_POST['brok'];
    // Если не нашлось оборудования в списке
	if(!empty($_POST['newdevice'])) {
		$newdevice = $_POST['newdevice'];
		$add_dev = $connect_db->prepare("INSERT INTO `device` (`Name`) VALUE (?)");
		$add_dev->bind_param("s", $newdevice);
		$add_dev->execute();
		$add_dev->close();
		unset($selectdevice);
		$selectdevice = $newdevice;
	}
    // Если не нашелся нужный бренд в списке
	if(!empty($_POST['newbrend'])) {
		$newbrend = $_POST['newbrend'];
		$add_brend = $connect_db->prepare("INSERT INTO `brends` (`Name`) VALUE (?)");
		$add_brend->bind_param("s", $newbrend);
		$add_brend->execute();
		$add_brend->close();
		unset($selectbrend);
		$selectbrend = $newbrend;
	}
	if($selectbrend == $select_brend) {
		$selectbrend = '';
	}
	if($engineer == $select_ing) {
		$engineer = 'Инженер не определен';
		$status = 1;
	}else {
		$status = 2;
	}
	$add_work = $connect_db->prepare("INSERT INTO `work` (`partner`,`device`, `brend`, `model`, `sn`, `fio`, `adres`, `fone1`, `fone2`, `komplekt`, `engineer`, `brok`, `status`, `vkgroup`, `sale`) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$add_work->bind_param("isssssssssssiii", $partner, $selectdevice, $selectbrend, $model, $sn, $fio, $adres, $fone1, $fone2, $komplekt, $engineer, $brok, $status, $vkgroup, $sale);
	$add_work->execute();
	$add_work->close();
	echo 'Работка подкинута';
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
echo $modal_title_start.'Для любимых инжинеров'.$modal_title_end;
?>
<form action="newwork.php" method="post">
	<div class="row popap">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		    <div class="mtop10">
				<select class="form-control" name="partner">
				    <option value="0">Выберите партнера</option>
				    <?php select_users('no', 'yes'); ?>
				</select>
			</div>
			<div class="mtop10">
				<div>
					Тип аппарата:<img onclick="newEl()" style="margin-left:5px; cursor:pointer;" width="15px" title="Добавить аппарат" alt="Добавить аппарат" src="../img/add.jpg">
				</div>
				<select class="form-control" id="selectdevice" style="display:block;" name="device">
					<option><?php echo $select_dev; ?></option>
					<?php select_dev(); ?>
				</select>
				<input class="form-control" id="newdevice" style="display:none;" type="text" name="newdevice" placeholder="Новый аппарат">
			</div>
			<div class="mtop10">
				<div>
					Производитель:<img onclick="newBrend()" style="margin-left:5px; cursor:pointer;" width="15px" title="Добавить производителя" alt="Добавить производителя" src="../img/add.jpg">
				</div>
				<select class="form-control" id="selectbrend" style="display:block;" name="manufacturer">
					<option><?php echo $select_brend; ?></option>
					<?php select_brend(); ?>
				</select>
				<input class="form-control" id="newbrend" style="display:none;" type="text" name="newbrend" placeholder="Новый производитель">
			</div>
			<div class="mtop10">
				<input class="form-control" name="model" type="text" required placeholder="Модель">
			</div>
			<div class="mtop10">
				<input class="form-control" name="sn" type="text" placeholder="Серийный номер">
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<div class="mtop10">
				<select name="vkgroup" class="form-control" id="selectfio">
					<option value="0">Состоит ли в группе vk.com</option>
					<option value="book">Не состоит</option>
					<option value="vk">Да, состоит</option>
				</select>
			</div>
			<div id="fio" class="mtop10">
				<input class="form-control" name="fio" type="text" required placeholder="Ф.И.О.">
			</div>
			<div id="vkfio" class="mtop10">
				<select data-live-search="true" name="vkfio" style="display:none;" class="form-control selectpicker bs-select-hidden">
					<option value="0">Выберите участника</option>
					<?php select_vkusers(); ?>
				</select>
			</div>
			<div class="mtop10">
				<input class="form-control" name="adres" type="text" placeholder="Адрес">
			</div>
			<div class="mtop10">
				<input class="form-control" name="fone1" type="text" required placeholder="Телефон 1">
			</div>
			<div class="mtop10">
				<input class="form-control" name="fone2" type="text" placeholder="Телефон 2">
			</div>
			<div class="mtop10">
				<input class="form-control" name="komplekt" type="text" required placeholder="Комплектация">
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<div>
				<div class="mtop10">
					Инженер:
				</div>
				<select class="form-control" name="engineer">
					<option><?php echo $select_ing; ?></option>
					<?php select_ing($i = 0); ?>
				</select>
			</div>
			<div class="mtop10">
				<input class="form-control" name="brok" type="text" required placeholder="Неисправность">
			</div>
			<div class="mtop10">
				<input class="btn btn-success" name="submit" type="submit" value="Добавить">
			</div>
		</div>
	</div>
</form>

<?php include('footer.inc'); ?>