<?php 
include('head.inc');
include('../config.php'); ?>
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

// Подключаем head
?>

<?php
if(isset($_GET['visov'])){
	$title = 'Оформление нового вызова';
}
if(isset($_GET['firm'])) {
	$title = 'Добавление контрагента';
}
if (isset($_GET['cert']) || isset($_POST['certadd'])) {
	$title = 'Довавление сертификата';
}
echo $modal_title_start.$title.$modal_title_end;
?>

<?php
// Наченается тело файла. Этот файл занимается обработкой новых вызовов, новой работой (принятие аппарата на ремонт) и так далее.

$select_ing = 'Выберите инженера';
$select_st = 'Выберите статус';

// Контрагенты. Начало.
// Форма для добавления контрагента
if(isset($_GET['firm'])) {
	echo '<div style="display:inline-block; width:49%;">';
	echo '<form action="new.php" method="post">';
	echo '<div><input name="firm" required type="text" placeholder="Наименование организации"></div>';
	echo '<div style="margin-top: 10px;"><input name="fone" required type="text" placeholder="Телефон"></div>';
	echo '<div style="margin-top: 10px;"><input name="email" required type="text" placeholder="Email"></div>';
	echo '<div style="margin-top: 10px;"><input name="uadress" required type="text" placeholder="Юредический адрес"></div>';
	echo '<div style="margin-top: 10px;"><input name="fadress" required type="text" placeholder="Фактический адрес"></div>';
	echo '<div style="margin-top: 10px;"><input name="inn" required type="text" placeholder="ИНН"></div>';
	echo '<div style="margin-top: 10px;"><input name="kpp" required type="text" placeholder="КПП"></div>';
	echo '<div style="margin-top: 10px;"><input name="ogrn" required type="text" placeholder="ОГРН"></div>';
	echo '<div style="margin-top: 10px;"><input name="bank" required type="text" placeholder="Наименование банка"></div>';
	echo '<div style="margin-top: 10px;"><input name="rs" required type="text" placeholder="Расчетный счет"></div>';
	echo '<div style="margin-top: 10px;"><input name="bik" required type="text" placeholder="БИК"></div>';
	echo '<div style="margin-top: 10px;"><input name="firmadd" type="submit" value="Добавить"></div>';
	echo '</form>';
	echo '</div>';
}
// Обработка данных полученных из формы добавления контрагента
if(isset($_POST['firmadd'])) {
	
	$firm = $_POST['firm'];
	$fone = $_POST['fone'];
	$email = $_POST['email'];
	$uadress = $_POST['uadress'];
	$fadress = $_POST['fadress'];
	$inn = $_POST['inn'];
	$kpp = $_POST['kpp'];
	$ogrn = $_POST['ogrn'];
	$bank = $_POST['bank'];
	$rs = $_POST['rs'];
	$bik = $_POST['bik'];
	
	$stmt = $connect_db->prepare("INSERT INTO firms (`firm`, `fone`, `email`, `uadress`, `fadress`, `inn`, `kpp`, `ogrn`, `bank`, `rs`, `bik`) VALUE (?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssiiisii", $firm, $fone, $email, $uadress, $fadress, $inn, $kpp, $ogrn, $bank, $rs, $bik);
	$stmt->execute();
	$stmt->close();
}
// Конец обработки данных полученных из формы добавления контрагента
// Контрагенты. Конец.

// Ночало выдачи сертификатов
if(isset($_GET['cert'])) {
	echo '<div class="row popap">';
	echo '<form action="new.php" method="post">';
	echo '<div><input class="form-control" name="idcert" required type="text" maxlength="4" placeholder="id сертификата"></div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="nominal" required type="text" placeholder="Номинал"></div>';
	echo '<div style="margin-top: 10px;"><input class="btn btn-success" name="certadd" type="submit" value="Добавить"></div>';
	echo '</form>';
	echo '</div>';
}
if(isset($_POST['certadd'])) {
	$idcert = $_POST['idcert'];
	$nominal = $_POST['nominal'];
	
	$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `idcert` = '$idcert'");
	if($select_cert->num_rows != 0) {
		echo '<div class="error">Такой сертификат уже добавлен</div>';
		echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
location="new.php?cert";
}, 2000); 
//--> 
</script>	
HTML;
		exit();
	}
	
	$stmt = $connect_db->prepare("INSERT INTO cert (`idcert`, `nominal`, `ostatok`) VALUE (?,?,?)");
	$stmt->bind_param("sii", $idcert, $nominal, $nominal);
	$stmt->execute();
	$stmt->close();
	echo 'Сертификат добавлен';
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 2000); 
//--> 
</script>	
HTML;
}
//Конец выдачи сертификатов
?>

<?php 
// Подключаем footer
include('footer.inc');
?>