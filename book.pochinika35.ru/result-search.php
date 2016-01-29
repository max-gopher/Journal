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

<?php
if(isset($_POST['multisearch'])) {
	$multisearch = $_POST['multisearch'];
	$pattern = '/(\d{2})-(\d{2})-(\d{4})/';
	if(preg_match($pattern, $multisearch)) {
		list($day, $month, $year) = explode("-", $multisearch);
		$multisearch = $year.'-'.$month.'-'.$day;
	}
	// Смотрим мастерскую
	// Ищем по id
	$select_work_id = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$multisearch'");
	// Ищем по дате приема
	$select_work_date = $connect_db->query("SELECT * FROM `work` WHERE `date` LIKE '$multisearch%'");
	// Ищем по дате выдачи
	$select_work_delivery = $connect_db->query("SELECT * FROM `work` WHERE `delivery` = '$multisearch'");
	// Ищем по аппарату
	$select_work_device = $connect_db->query("SELECT * FROM `work` WHERE `device` = '$multisearch'");
	// Ищем по бренду
	$select_work_brend = $connect_db->query("SELECT * FROM `work` WHERE `brend` = '$multisearch'");
	// Ищем по модели
	$select_work_model = $connect_db->query("SELECT * FROM `work` WHERE `model` = '$multisearch%'");
	// Ищем по серийнику
	$select_work_sn = $connect_db->query("SELECT * FROM `work` WHERE `sn` = '$multisearch'");
	// Ищем по фио
	$select_work_fio = $connect_db->query("SELECT * FROM `work` WHERE `fio` LIKE '%$multisearch%'"); 
	// Ищем по адресу
	$select_work_adres = $connect_db->query("SELECT * FROM `work` WHERE `adres` LIKE '$multisearch%'");
	// Ищем по fone1
	$select_work_fone1 = $connect_db->query("SELECT * FROM `work` WHERE `fone1` LIKE '$multisearch'");
	// Ищем по fone2
	$select_work_fone2 = $connect_db->query("SELECT * FROM `work` WHERE `fone2` LIKE '$multisearch'");
}
?>
<div>
	<center><h1>
		Результаты поиска
		</h1></center>
<?php 
if($select_work_id->num_rows > 0) {
	$result = $select_work_id;
}
if($select_work_date->num_rows > 0) {
	$result = $select_work_date;
}
if($select_work_delivery->num_rows > 0) {
	$result = $select_work_delivery;
}
if($select_work_device->num_rows > 0) {
	$result = $select_work_device;
}
if($select_work_brend->num_rows > 0) {
	$result = $select_work_brend;
}
if($select_work_model->num_rows > 0) {
	$result = $select_work_model;
}
if($select_work_sn->num_rows > 0) {
	$result = $select_work_sn;
}
if($select_work_fio->num_rows > 0) {
	$result = $select_work_fio;
}
if($select_work_adres->num_rows > 0) {
	$result = $select_work_adres;
}
if($select_work_fone1->num_rows > 0) {
	$result = $select_work_fone1;
}
if($select_work_fone2->num_rows > 0) {
	$result = $select_work_fone2;
}

if($result->num_rows > 0) {
	echo '<table border="1" cellpadding="2">';
	echo '<tbody>';
	echo '<tr><td style="text-align: center;">Дата</td><td style="text-align: center;">ФИО</td><td style="text-align: center;">Телефон</td><td style="text-align: center;">Квитанция</td></tr>';
	while($fetch_result = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>'.$fetch_result['date'].'</td><td>'.$fetch_result['fio'].'</td><td>'.$fetch_result['fone1'].'</td><td>'.$fetch_result['id'].'</td>';
		echo '</tr>';
	}
}else{
	echo 'Ничего нету!)';
}
	echo '</tbody>';
	echo '</table>';
?>
</div>
<?php include("template/default/footer.php"); ?>