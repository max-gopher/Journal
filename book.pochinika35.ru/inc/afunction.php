<?php
session_start();
/* Блок для header.php */
/* Для подключения к базе */
$host="localhost";
$user_db="oleg";
$pass_db="10112012";
$name_db="newboozilla";
$login = $_SESSION['login'];
$connect_db = new mysqli("$host", "$user_db", "$pass_db", "$name_db");
if ($connect_db->connect_errno){
	echo 'Не удалось подключиться к базе.';
	exit();
}
$connect_db->query("SET NAMES UTF8");
include('../config.php');
// Промежуточное звено архива. Описание функции смотреть в config.php
if(isset($_GET['arhiv'])) {
	$user = $_POST['user'];
	$vibor = 'yes';
	if($user == 'no'){
		$user = $login;
		$vibor = 'no';
	}
	$doc = $_POST['doc'];
	arhiv($user, $vibor, $doc);
}

// Используется при создании счета
// Генератор таблиц вызовов и работ в мастерской
if (isset($_GET['schet'])) {
	$firm = $_POST['firm'];
	$select_firm = $connect_db->query("SELECT * FROM `firms` WHERE `id` = '$firm' LIMIT 1");
	$fetch_firm = $select_firm->fetch_assoc();
	$firm = $fetch_firm['firm'];
	$select_visov_for_schet = $connect_db->query("SELECT * FROM `visovi` WHERE (`namek` = '$firm' AND `schet` = '') AND `status` = 'Исполнено'");
	//$select_work_for_schet = $connect_db->query("SELECT * FROM `work` WHERE (`fio` = '$firm' AND `schet` = '') AND `status` >= '3'");
	
	if($select_visov_for_schet->num_rows) {
		echo '<div class="mtop10 gborder">
					<div class="subtitle">Вызовы</div>
					<div class="pad0555">';
		echo '<table id="celebs" class="table table-striped mtop10"><thead><tr>';
		echo '<th><input type="checkbox" id="checker" /></th>';
		echo '<th>Номер</th>';
		echo '<th>Дата</th>';
		echo '<th>Проведенные работы</th>';
		echo '<th>Инжинер</th>';
		echo '<th>Кол-во</th>';
		echo '<th>Цена</th>';
		echo '<th>Сумма</th>';
		echo '</tr></thead><tbody>';
		// Фетчим вызовы
			while($fetch_visov_for_schet = $select_visov_for_schet->fetch_assoc()) {
				$subid = $fetch_visov_for_schet['id'];
				$seelct_ravoty_from_visov = $connect_db->query("SELECT * FROM `raboty` WHERE `razdel` = 'visovi' AND `subid` = '$subid'");
				while($fetch_ravoty_from_visov = $seelct_ravoty_from_visov->fetch_assoc()) {
					echo '<tr>';
					echo '<td><input data-rid="'.$fetch_ravoty_from_visov['id'].'" type="checkbox"/></td>';
					echo '<td data-vid="'.$fetch_visov_for_schet['id'].'">'.$fetch_visov_for_schet['id'].'</td>';
					echo '<td>'.$fetch_visov_for_schet['dateforengineer'].'</td>';
					echo '<td>'.$fetch_ravoty_from_visov['name'].'</td>';
					echo '<td>'.$fetch_visov_for_schet['engineer'].'</td>';
					echo '<td>'.$fetch_ravoty_from_visov['kol'].'</td>';
					echo '<td>'.$fetch_ravoty_from_visov['price'].'</td>';
					echo '<td data-money="'.$fetch_ravoty_from_visov['price'] * $fetch_ravoty_from_visov['kol'].'">'.$fetch_ravoty_from_visov['price'] * $fetch_ravoty_from_visov['kol'].'</td>';
					echo '</tr>';
				}
			}
		echo '</tbody></table>';
		echo '</div></div>';
	}
	// Фетчим мастерскую
	/*if($select_work_for_schet->num_rows) {
		echo '<div class="mtop10 gborder">
					<div class="subtitle">Мастерская</div>
					<div class="pad0555">';
		echo '<table id="celebsm" class="table table-striped mtop10"><thead><tr>';
		echo '<th><input type="checkbox" id="checkerm" /></th>';
		echo '<th>Номер</th>';
		echo '<th>Дата</th>';
		echo '<th>Проведенные работы</th>';
		echo '<th>Инжинер</th>';
		echo '<th>Сумма</th>';
		echo '</tr></thead><tbody>';
			while($fetch_visov_for_schet = $select_visov_for_schet->fetch_assoc()) {
				echo '<tr>';
				echo '<td><input data-id="'.$fetch_visov_for_schet['id'].'" type="checkboxm"/></td>';
				echo '<td>'.$fetch_visov_for_schet['id'].'</td>';
				echo '<td>'.$fetch_visov_for_schet['dateforengineer'].'</td>';
				echo '<td>'.$fetch_visov_for_schet['problemse'].'</td>';
				echo '<td>'.$fetch_visov_for_schet['engineer'].'</td>';
				echo '<td>'.$fetch_visov_for_schet['koplate'].'</td>';
				echo '</tr>';
			}
		echo '</tbody></table>';
		echo '</div></div>';
	}*/
	echo '<div><span onclick="genSchet();" class="btn btn-success mtop10">Сформировать счет</span></div>';
}
// Запись счета в базу
if(isset($_GET['schet_write'])) {
	$fetch_firm = selectInfoFirm($_POST['org']);
	$idVisov = $_POST['idVisov'];
	$idRaboty = $_POST['idRaboty'];
	$status = '0';
	foreach($_POST['money'] as $money) {
		$summa = $summa + $money;
	}
	$add_schet = $connect_db->prepare("INSERT INTO `scheta` (`org`, `index`, `adres`, `inn`, `kpp`, `bank`, `rs`, `ks`, `bik`, `summa`, `status`) VALUE (?,?,?,?,?,?,?,?,?,?,?)");
	if(!empty($add_schet->param_count)) {
		$add_schet->bind_param("sssssssssis", $fetch_firm['firm'], $fetch_firm['uindex'], $fetch_firm['fadress'], $fetch_firm['inn'], $fetch_firm['kpp'], $fetch_firm['bank'], $fetch_firm['rs'], $fetch_firm['ks'], $fetch_firm['bik'], $summa, $status);
		$add_schet->execute();
		$add_schet->store_result();
		$last_id = $add_schet->insert_id;
		$add_schet->close();
		foreach($idVisov as $id) {
			$connect_db->query("UPDATE `visovi` SET `schet` = '$last_id' WHERE `id` = '$id'");
			foreach($idRaboty as $idR) {
				$connect_db->query("UPDATE `raboty` SET `schet` = '$last_id' WHERE `id` = '$idR'");
			}
		}
	}else {
		echo 'Что-то пошло не так!';
	}
	//var_dump($idVisov);
}

// Меняем статус счета на оплаченно и выводим обнавленную таблицу счетов
if(isset($_GET['oplacheno'])) {
	$id = $_POST['id'];
	$connect_db->query("UPDATE `scheta` SET `status` = '3'");
	select_schet('1');
}
// Конец создания счета

// Функция для выбора результатов поиска
if(isset($_GET['aselect'])) {
	$fio = $_POST['fio'];
	$select_rec = $connect_db->query("SELECT DISTINCT * FROM `visovi` WHERE `namek` LIKE '%". $fio ."%' GROUP BY `namek`");
	if(!empty($select_rec->num_rows)) {
		while($fetch_rec = $select_rec->fetch_assoc()) {
			echo '<li>'.$fetch_rec['namek'].'</li>';
		}
	}
}

// Выбор мастерской для реселлеров (партнеров)
if(isset($_GET['master'])) {
    $which = $_POST['which'];
    $partner_id = $_POST['partner_id'];
    if($which == 'work') {
        $which = 2;
    }
    if($which == 'ready') {
        $which = 3;
    }
    if($which == 'finish') {
        $which = 5;
    }
    select_work($which, $partner_id);
}

// Проверка сессии
if(isset($_GET['testses'])) {
    if($_POST['sessionLogin'] == $_SESSION['login']){
        echo '1';
    }else{
        echo '0';
    }
}
?>