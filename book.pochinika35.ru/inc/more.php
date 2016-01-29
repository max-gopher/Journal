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

if(isset($_GET['idcert'])) {
	$title = 'История сертификата №'.$_GET['idcert'];
}
?>
<?php
if(isset($_GET['workid'])) {
	$workid = $_GET['workid'];
	$title = $_GET['title'];
	$select_work_more = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$workid' LIMIT 1");
	$fetch_work_more = $select_work_more->fetch_assoc();
	
	// Приводим дату в человеческий вид
		list($predate, $time) = explode(" ", $fetch_work_more['date']); // Отделяем дату от времени
		list($year, $month, $day) = explode("-", $predate); // Разчленяем дату
		$date = $day.'-'.$month.'-'.$year; // Собираем дату 
	
	// Выбираем статус так как в таблице work он указан числом
	$status = $fetch_work_more['status'];
	$select_status_more = $connect_db->query("SELECT * FROM `vstatus` WHERE `num` = '$status' LIMIT 1");
	$fetch_status_more = $select_status_more->fetch_assoc();
	//
	
	// Получаем заметку к записи
	$select_note_more = $connect_db->query("SELECT * FROM `note` WHERE `idwork` = '$workid' LIMIT 1");
	$fetch_note_more = $select_note_more->fetch_assoc();
	
	echo $modal_title_start.$title.$modal_title_end;
	echo '<div>';
		echo '<div>Номер: '.$fetch_work_more['id'].'</div>';
		echo '<div>Дата: '.$date.'</div>';
		echo '<div>Аппарат: '.$fetch_work_more['device'].'</div>';
		echo '<div>Производитель: '.$fetch_work_more['brend'].'</div>';
		echo '<div>Модель: '.$fetch_work_more['model'].'</div>';
		echo '<div>Серийный номер: '.$fetch_work_more['sn'].'</div>';
		echo '<div>Клиент: '.$fetch_work_more['fio'].'</div>';
		echo '<div>Адрес: '.$fetch_work_more['adres'].'</div>';
		echo '<div>Телефон1: '.$fetch_work_more['fone1'].'</div>';
		echo '<div>Телефон2: '.$fetch_work_more['fone2'].'</div>';
		echo '<div>Комплектация: '.$fetch_work_more['komplekt'].'</div>';
		echo '<div>Инженер: '.$fetch_work_more['engineer'].'</div>';
		echo '<div>Неисправность: '.$fetch_work_more['brok'].'</div>';
		echo '<div>Стоимость ремонта: '.$fetch_work_more['money'].'р.</div>';
		echo '<div>Проведенные работы: '.$fetch_work_more['problemse'].'</div>';
		echo '<div>Статус: '.$fetch_status_more['name'].'</div>';
	if(!empty($fetch_note_more['who'])) {
		echo '======================';
		echo '<div>Заметку добавил: '.$fetch_note_more['who'].'</div>';
		echo '<div>Заметка: '.$fetch_note_more['text'].'</div>';
	}
	echo '</div>';
}

if(isset($_GET['visov'])) {
	$visov = $_GET['visov'];
	$title = $_GET['title'];
	$select_visov_more = $connect_db->query("SELECT * FROM `visovi` WHERE `id` = '$visov' LIMIT 1");
	$fetch_visov_more = $select_visov_more->fetch_assoc();
	
	// Переводим дату вызова в читабельный вид
	list($year, $month, $day) = explode("-", $fetch_visov_more['dateforengineer']); // Разбираем дату по переменным
	$date_for_engineer = $day.'-'.$month.'-'.$year; // Собираем читабельную дату из полученных при разборе переменных
	
	// Создаем переменную с адресом так как в базе это разные поля
	$adres = $fetch_visov_more['street'].' д.'.$fetch_visov_more['home'].' '.$fetch_visov_more['housing'].' кв.'.$fetch_visov_more['apartment'];
	
	// Получаем заметку к записи
	$select_note_more = $connect_db->query("SELECT * FROM `note` WHERE `idwork` = '$visov' LIMIT 1");
	$fetch_note_more = $select_note_more->fetch_assoc();
	
	echo $modal_title_start.$title.$modal_title_end;
	echo '<div>';
		echo '<div>Дата: '.$date_for_engineer.'</div>';
		echo '<div>Время:'.$fetch_visov_more['timeforengineer'].'</div>';
		echo '<div>Адрес: '.$adres.'</div>';
		echo '<div>Имя: '.$fetch_visov_more['namek'].'</div>';
		echo '<div>Телефон: '.$fetch_visov_more['fone'].'</div>';
		echo '<div>Проблема: '.$fetch_visov_more['problemsk'].'</div>';
		echo '<div>Инженер: '.$fetch_visov_more['engineer'].'</div>';
		echo '<div>Проведенные работы: '.$fetch_visov_more['problemse'].'</div>';
		echo '<div>Квитанция: №'.$fetch_visov_more['kvitancia'].'</div>';
		echo '<div>Сумма: '.$fetch_visov_more['money'].'р.</div>';
		echo '<div>Статус: '.$fetch_visov_more['status'].'</div>';
	if(!empty($fetch_note_more['who'])) {
		echo '======================';
		echo '<div>Заметку добавил: '.$fetch_note_more['who'].'</div>';
		echo '<div>Заметка: '.$fetch_note_more['text'].'</div>';
	}
	echo '</div>';
}

if(isset($_GET['number'])) {
	$number = $_GET['number'];
	$title = $_GET['title'];
	$doc = $_GET['doc'];
	$itog = 0;
	echo $modal_title_start.$title.$modal_title_end;
	if($doc == 'cheki') {
		echo '<table class="table table-striped">';
		echo '<thead><tr>';
		echo '<th>Категория</th>';
		echo '<th>Наименование</th>';
		echo '<th>S/N</th>';
		echo '<th>Гарантия</th>';
		echo '<th>Количество</th>';
		echo '<th>Цена</th>';
		echo '<th>Сумма</th>';
		echo '</tr></thead><tbody>';
	
		$select_more_arhiv = $connect_db->query("SELECT * FROM `cheki` WHERE `number` = '$number'");
		while($fetch_more_arhiv = $select_more_arhiv->fetch_assoc()) {
			$idtov = $fetch_more_arhiv['idtov'];
			$select_name_tov = $connect_db->query("SELECT * FROM `sklad` WHERE `id` = '$idtov' LIMIT 1");
			$fetch_name_tov = $select_name_tov->fetch_assoc();
			$catid = $fetch_name_tov['category'];
			$select_cat_tov = $connect_db->query("SELECT * FROM `skladcat` WHERE `id` = '$catid' LIMIT 1");
			$fetch_cat_tov = $select_cat_tov->fetch_assoc();
			$summa = $fetch_more_arhiv['kol'] * $fetch_name_tov['priceishod'];
			echo '<tr><td>'.$fetch_cat_tov['name'].'</td><td>'.$fetch_name_tov['name'].'</td><td>'.$fetch_more_arhiv['sn'].'</td><td>'.$fetch_more_arhiv['garan'].'</td><td>'.$fetch_more_arhiv['kol'].'</td>
			<td>'.$fetch_name_tov['priceishod'].'</td><td>'.$summa.'</td></tr>';
			$itog = $itog + $summa;
		}
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td style="color:red;">Итого:</td><td style="color:red;">'.$itog.'</td></tr></tbody></table>';
	}elseif($doc == 'aktswork') {
		echo '<p class="text-center">Акт №'.$number.' от '.$date.'</p>';
		echo '<p class="text-center">На выполнение работ-услуг</p>';
		echo '<dl class="dl-horizontal">
		<dt>Исполнитель: </dt><dd>ООО "БОВ-Сервис"</dd>
		<dt>Заказчик: </dt><dd>'.$fiok.'</dd>';
		echo '<table class="table table-striped">';
		echo '<thead><tr>';
		echo '<th>№</th>';
		echo '<th>Наименование</th>';
		echo '<th>Цена</th>';
		echo '</tr></thead><tbody>';
		$i = 1;
		$select_more_arhiv = $connect_db->query("SELECT * FROM `aktswork` WHERE `number` = '$number'");
		while($fetch_more_arhiv = $select_more_arhiv->fetch_assoc()) {
			echo '<tr><td>'.$i.'</td><td>'.$fetch_more_arhiv['work'].'</td><td>'.$fetch_more_arhiv['price'].'</td>';
			$itog = $itog + $fetch_more_arhiv['price'];
			$i++;
		}
		echo '<tr><td></td><td style="color:red;">Итого:</td><td style="color:red;">'.$itog.'</td></tr></tbody></table>';
	}
}

// История использования сертификата
if(isset($_GET['idcert'])) {
	$idcert = $_GET['idcert'];
	$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `idcert` = '$idcert' LIMIT 1");
	$fetch_cert = $select_cert->fetch_assoc();
	$sel_visov_with_cert = ("SELECT * FROM `visovi` WHERE `idcert` = '$idcert'");
	$sel_work_with_cert = ("SELECT * FROM `work` WHERE `idcert` = '$idcert'");
	echo '<div class="row popap">';
		echo $modal_title_start.$title.$modal_title_end;
		echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
			echo '<div>Дата продажи: <span class="bold">'.$fetch_cert['date'].'</span></div>';
			echo '<div>Номинал: <span class="bold">'.$fetch_cert['nominal'].'р.</span></div>';
			echo '<div>Остаток: <span class="bold">'.$fetch_cert['ostatok'].'р.</span></div>';
			echo '<div class="table-responsive">';
			echo '<div class="subtitle">Вызовы</div>';
			if($sel_visov_with_cert->num_rows != 0) {
				echo '<table class="table table-striped">';
					echo '<thead><tr><th>Номер</th><th>Дата</th><th>Ф.И.О.</th><th>Адрес</th><th>Инженер</th><th>Проведенные работы</th><th>Сумма</th></tr></thead><tbody>';
					while($fetch_visov_with_cert = $sel_visov_with_cert->fetch_assoc()) {
						echo '<tr><td>'.$fetch_visov_with_cert['id'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['dateforengineer'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['namek'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['street'].' д. '.$fetch_visov_with_cert['home'].' корп. '.$fetch_visov_with_cert['housing'].' кв. '.$fetch_visov_with_cert['apartment'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['engineer'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['problemse'].'</td>';
						echo '<td>'.$fetch_visov_with_cert['sumsale'].'</td></tr>';
					}
				echo '</tbody></table>';
			}else{
				echo '<div class="notfound">На вызовах сертификат не использован</div>';
			}
			echo '<div class="subtitle">Мастерская</div>';
			if($sel_work_with_cert->num_rows != 0) {
				echo '<table class="table table-striped">';
					echo '<thead><tr><th>Номер</th><th>Дата выдачи</th><th>Ф.И.О.</th><th>Инженер</th><th>Проведенные работы</th><th>Сумма</th></tr></thead><tbody>';
					while($fetch_work_with_cert = $sel_work_with_cert->fetch_assoc()) {
						echo '<tr><td>'.$fetch_work_with_cert['id'].'</td>';
						echo '<td>'.$fetch_work_with_cert['delivery'].'</td>';
						echo '<td>'.$fetch_work_with_cert['fio'].'</td>';
						echo '<td>'.$fetch_work_with_cert['engineer'].'</td>';
						echo '<td>'.$fetch_work_with_cert['problemse'].'</td>';
						echo '<td>'.$fetch_work_with_cert['sumsale'].'</td></tr>';
					}
				echo '</tbody></table>';
			}else{
				echo '<div class="notfound">В мастерской сертификат не использован</div>';
			}
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
?>
<?php include('footer.inc'); ?>
