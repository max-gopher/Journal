<?php
// AJAX обработка сертификатов при завершении вызова
if(isset($_GET['ajaxcert'])){
	session_start();

	$host="localhost";
	$user_db="oleg";
	$pass_db="10112012";
	$name_db="newboozilla";
	$connect_db = new mysqli("$host", "$user_db", "$pass_db", "$name_db");
	if ($connect_db->connect_errno){
		echo 'Не удалось подключиться к базе.';
		exit();
	}
	$connect_db->query("SET NAMES UTF8");

	$idcert = $_POST['idcert'];
	$summa = $_POST['summa'];
	$vid = $_POST['vid'];

	// Проверяем состоит ли заказчик в группе vk
	$select_visov = $connect_db->query("SELECT * FROM `visovi` WHERE `id` = '$vid' LIMIT 1");
	$fetch_visov = $select_visov->fetch_assoc();
	if (!empty($fetch_visov['sale'])) {
		$summa = $summa - $summa / 100 * $fetch_visov['sale'];
	}
	// Выбор сертификата
	$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `idcert` = '$idcert' LIMIT 1");
	if($select_cert->num_rows == 0) {
		echo $summa;
	}else {
		$fetch_cert = $select_cert->fetch_assoc();
		if($fetch_cert['ostatok'] >= $summa) {
			$sumsale = 0;
		}
		if($fetch_cert['ostatok'] < $summa) {
			$sumsale = $summa - $fetch_cert['ostatok'];
		}
		echo $sumsale;
	}
	//echo $sumsale;
}

// Обработка сертификатов в мастерской
if(isset($_GET['idwork'])) {
	include('head.inc');
	include('../config.php');
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

	$idwork = $_GET['idwork'];
	$select_cert = "Выберите сертификат";
	$title = 'Использование сертификата';
	echo $modal_title_start.$title.$modal_title_end;

	echo '<form action="usecert.php" method="post">';
	echo '<div class="row popap">';
	echo '<div class="mtop10">';
	echo '<select class="form-control" class="form-control" name="idcert" required>'; 
	echo '<option>'.$select_cert.'</option>';
				select_cert(2);
	echo '</select>';
	echo '</div>';
	echo '<div class="mtop10">';
	echo '<input name="idwork" type="hidden" value="'.$idwork.'">';
	echo '</div>';
	echo '<div class="mtop10">';
	echo '<input class="btn btn-success" name="certwork" type="submit" value="Применить">';
	echo '</div>';
	echo '</div>';
	echo '</form>';
	include('footer.inc');
}
// Обработка данных полученных из формы использования сертификата
if(isset($_POST['certwork'])) {
	include('head.inc');
	include('../config.php');
	if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="../index.php" 
//--> 
</script>	
HTML;
}
	
	$idcert = $_POST['idcert'];
	$idwork = $_POST['idwork'];
	
	$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `idcert` = '$idcert' LIMIT 1");
	$fetch_cert = $select_cert->fetch_assoc();
	$select_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$idwork' LIMIT 1");
	$fetch_work = $select_work->fetch_assoc();
	
	$ostatok = $fetch_cert['ostatok'];
	$money = $fetch_work['money'];
	$sumsale = $fetch_work['sumsale'];
	
	if($ostatok >= $money) {
		$ostatok = $ostatok - $sumsale;
		$connect_db->query("UPDATE `cert` SET `ostatok` = '$ostatok' WHERE `idcert` = '$idcert'");
		$koplate = 0;
		$connect_db->query("UPDATE `work` SET `idcert` = '$idcert', `koplate` = '$koplate' WHERE `id` = '$idwork'");
			echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 1000); 
//--> 
</script>	
HTML;
		exit();
	}
	if($ostatok < $money) {
		$koplate = $sumsale - $ostatok;
		$ostatok = 0;
		$connect_db->query("UPDATE `cert` SET `ostatok` = '$ostatok' WHERE `idcert` = '$idcert'");
		$connect_db->query("UPDATE `work` SET `idcert` = '$idcert', `koplate` = '$koplate' WHERE `id` = '$idwork'");
	}
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