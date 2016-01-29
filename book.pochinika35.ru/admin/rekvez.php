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

$title = 'Добавить реквизиты';

if(isset($_POST['rekvez'])) {
	$pname = str_replace('"', '&quot;', $_POST['pname']);
	$sname = str_replace('"', '&quot;', $_POST['sname']);
	$contacts = str_replace('"', '&quot;', $_POST['contacts']);
	$phone = str_replace('"', '&quot;', $_POST['phone']);
	$ogrn = str_replace('"', '&quot;', $_POST['ogrn']);
	$inn = str_replace('"', '&quot;', $_POST['inn']);
	$kpp = str_replace('"', '&quot;', $_POST['kpp']);
	$bank = str_replace('"', '&quot;', $_POST['bank']);
	$rs = str_replace('"', '&quot;', $_POST['rs']);
	$ks = str_replace('"', '&quot;', $_POST['ks']);
	$bik = str_replace('"', '&quot;', $_POST['bik']);
	$connect_db->query("UPDATE `settings` SET `pname` = '$pname', `sname` = '$sname', `contacts` = '$contacts', `phone` = '$phone', `inn` = '$inn', `ogrn` = '$ogrn', `kpp` = '$kpp', `bank` = '$bank', `rs` = '$rs', `ks` = '$ks', `bik` = '$bik'");
	/*$add_rekvez = $connect_db->prepare("INSERT INTO `settings` (`contacts`) VALUE (?)");
	$add_rekvez->bind_param("s", $contacts);
	$add_rekvez->execute();
	$add_rekvez->close();*/
}

$title1 = 'Загрузить логотип';

if(isset($_POST['uplogo'])) {
	$uploaddir = '/var/www/oleg/data/www/new.boozilla.ru/img/';
	$dir_for_base = '/img/';
	$logo = $_FILES['logo']['name'];
	$file_for_base = $dir_for_base . $logo;
	$uploadfile = $uploaddir . $logo;
	if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile)) {
    	$connect_db->query("UPDATE `settings` SET `logo` = '$file_for_base'");
	} else {
    	$title1 = 'Ошибка загрузки логотипа';
	}
}

$select_settings = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_settings = $select_settings->fetch_assoc();

?>
<div>
	<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="form_rekvez">
		<form action="rekvez.php" method="post">
			<div style="margin-top: 10px;">
				<textarea class="form-control" name="pname" type="text" placeholder="Полное наименование организации"><?php echo !empty($fetch_settings['pname']) ? $fetch_settings['pname'] : ''; ?></textarea>
			</div>
			<div style="margin-top: 10px;">
				<textarea class="form-control" name="sname" type="text" placeholder="Краткое наименование организации"><?php echo !empty($fetch_settings['sname']) ? $fetch_settings['sname'] : ''; ?></textarea>
			</div>
			<div style="margin-top: 10px;">
				<textarea class="form-control" name="contacts" type="text" placeholder="Адрес"><?php echo !empty($fetch_settings['contacts']) ? $fetch_settings['contacts'] : ''; ?></textarea>
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="phone" type="text" placeholder="Телефон" value="<?php echo !empty($fetch_settings['phone']) ? $fetch_settings['phone'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="inn" type="text" placeholder="ИНН" value="<?php echo !empty($fetch_settings['inn']) ? $fetch_settings['inn'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="ogrn" type="text" placeholder="ОГРН" value="<?php echo !empty($fetch_settings['ogrn']) ? $fetch_settings['ogrn'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="kpp" type="text" placeholder="КПП" value="<?php echo !empty($fetch_settings['kpp']) ? $fetch_settings['kpp'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<textarea class="form-control" name="bank" type="text" placeholder="Банк"><?php echo !empty($fetch_settings['bank']) ? $fetch_settings['bank'] : ''; ?></textarea>
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="rs" type="text" placeholder="Расчетный счет" value="<?php echo !empty($fetch_settings['rs']) ? $fetch_settings['rs'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="ks" type="text" placeholder="Корреспондентский счет" value="<?php echo !empty($fetch_settings['ks']) ? $fetch_settings['ks'] : ''; ?>">
			</div>
			<div style="margin-top: 10px;">
				<input class="form-control" name="bik" type="text" placeholder="Бик" value="<?php echo !empty($fetch_settings['bik']) ? $fetch_settings['bik'] : ''; ?>">
			</div>
			<div class="mtop10 mbottom10">
				<input class="btn btn-success" name="rekvez" type="submit" value="Сохранить">
			</div>
		</form>
	</div>
	<div class="result_rekvez">
		
	</div>
</div>
<div>
	<?php echo $modal_title_start.$title1.$modal_title_end; ?>
	<div class="form_logo">
		<form enctype="multipart/form-data" action="rekvez.php" method="POST">
			<div style="margin-top: 10px;">
				<input name="logo" type="file">
			</div>
			<div style="margin-top: 10px;">
				<input name="uplogo" type="submit" value="Загрузить">
			</div>
		</form>
	</div>
	<div class="result_logo">
		<?php 
			if(!empty($fetch_settings['logo'])) {
				$logo = $fetch_settings['logo'];
				echo '<img style="width: 70px" title="logo" alt="logo" src="'.$logo.'">';
			}
		?>
	</div>
</div>
<?php include('../inc/footer.inc'); ?>