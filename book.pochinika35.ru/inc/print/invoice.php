<?php 
header ("Content-type: application/pdf", false );
include ("numtostr.php");
?>
<?php
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
// LOGOTIP
$select_set = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_set = $select_set->fetch_assoc();
$logo = $fetch_set['logo'];
$sname = $fetch_set['sname'];

// END LOGOTIP
$date = date('d-m-Y');

// Квитанция о приеме аппарата
if(isset($_GET['getting'])) {
	$id = $_GET['getting'];
	$select_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$id' LIMIT 1");
	$fetch_work = $select_work->fetch_assoc();

	$fio = $fetch_work['fio'];
	$address = $fetch_work['adres'];
	$tel1 = $fetch_work['fone1'];
	$tel2 = $fetch_work['fone2'];
	$dev = $fetch_work['device'].' "'.$fetch_work['brend'].'" '.$fetch_work['model'];
	$sn = $fetch_work['sn'];
	$komplekt = $fetch_work['komplekt'];
	$diagnoz = $fetch_work['brok'];
	$ved_engineer = $fetch_work['engineer'];
	$engineer_nik = $_SESSION['login'];

	// Ищем юзверя по логину и отжимаем его имя, фамилию и отчество
	$select_engineer = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$engineer_nik' LIMIT 1");
	$fetch_engineer = $select_engineer->fetch_assoc();
	$familia = $fetch_engineer['familiya'];
	$name = $fetch_engineer['name'];
	$name = iconv_substr( $name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$otchestvo = $fetch_engineer['otchestvo'];
	$otchestvo = iconv_substr( $otchestvo, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$fio_engineer = $familia.' '.$name.'. '.$otchestvo.'.';
	
	// Подготавливаем Ф.И.О клиента
	list($famk, $namek, $otchek) = explode(" ", $fio);
	if(strlen($namek) > 0) {
		$namek = iconv_substr($namek, 0, 1, 'utf-8');
		$namek = mb_strtoupper($namek, 'utf-8');
		$namek = ' '.$namek.'.';
	}
	if(strlen($otchek) > 0) {
			$otchek = iconv_substr($otchek, 0, 1, 'utf-8');
			$otchek = mb_strtoupper($otchek, 'utf-8');
			$otchek = ' '.$otchek.'.';
	}
	
	// Имя файла вывода
	$pdf_name = 'kvitp'.$id.'.pdf';

	$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<table><tbody><tr><td width=350><img src="'.$logo.'" alt="Логотип" width="100" height="100"></td>
<td>СЦ ПочиникА<br>'.$fetch_set['contacts'].'<br>'.$fetch_set['phone'].'</td></tr></tbody></table>
<table><tbody><tr><td width=150></td><td ><center>Квитанция о приеме оборудования <b>№'.$id.'</b> от <b>'.$date.'г.</b></center></td></tr></tbody></table>

<table><tr><td width="350">ФИО: <b>'.$fio.'</b></td><td>Адрес: <b>'.$address.'</b></td></tr>
<tr><td>Телефон 1: <b>'.$tel1.'</b></td><td>Телефон 2: <b>'.$tel2.'</b></td></tr></table>

<table><tr><td>
Наименование аппарата: <b>'.$dev.'</b><br>
Серийный номер: <b>'.$sn.'</b><br>
Комплектация: <b>'.$komplekt.'</b><br>
Неисправность: <b>'.$diagnoz.'</b><br>
Ведущий инженер: <b>'.$ved_engineer.'</b></td></tr>
</table>

<font size=4>
Условия проведения ремонтных работ: <br>
1. При отказе от ремонта оборудования Заказчик обязуется произвести оплату диагностических работ Исполнителю (от 350 до 500 р.)
<br>2. Оборудование принимается в чистом виде. Очистка от пыли и грязи - платная услуга - 350 руб.
<br>3. Оборудование с согласия Заказчика принято Исполнителем без разборки и проверки неисправностей. Заказчик  согласен, что все неисправности, которые могут быть обнаружены при проведении диагностических работ, возникли до приёма оборудования в ремонт по данной квитанции.
<br>4. Срок ремонта может составлять до 15 дней. В случае отсутствия запасных частей срок ремонта может быть увеличен до 45 дней, при этом Заказчик претензий к Исполнителю иметь не будет.
<br>5. Исполнитель обязуется прилагать все возможные усилия для сохранения необходимой Заказчику информации с их носителей, однако, сохранность информации не гарантируется.
<br>6. Заказчик обязуется забрать отремонтированный аппарат в течении 30 дней после уведомления об окончании ремонта. В противном случае оборудование переходит в собственность Сервисного центра.
</font>
<br>
<br> <table><tr><td width="370">Сдал ________________ ('.$famk.$namek.$otchek.')</td><td> Принял________________('.$fio_engineer.')</td></tr></table><br><br><br>


<table><tbody><tr><td width="350"><img src="'.$logo.'" alt="Логотип" width="100" height="100"></td>
<td>СЦ ПочиникА<br>'.$fetch_set['contacts'].'<br>'.$fetch_set['phone'].'</td></tr></tbody></table>
<table><tbody><tr><td width="150"></td><td ><center>Квитанция о приеме оборудования <b>№'.$id.'</b> от <b>'.$date.'г.</b></center></td></tr></tbody></table>

<table><tr><td width="350">ФИО: <b>'.$fio.'</b></td><td>Адрес: <b>'.$address.'</b></td></tr>
<tr><td>Телефон 1: <b>'.$tel1.'</b></td><td>Телефон 2: <b>'.$tel2.'</b></td></tr></table>

<table><tr><td>
Наименование аппарата: <b>'.$dev.'</b><br>
Серийный номер: <b>'.$sn.'</b><br>
Комплектация: <b>'.$komplekt.'</b><br>
Неисправность: <b>'.$diagnoz.'</b><br>
Ведущий инженер: <b>'.$ved_engineer.'</b></td></tr>
</table>

<font size=4>
Условия проведения ремонтных работ: <br>
1. При отказе от ремонта оборудования Заказчик обязуется произвести оплату диагностических работ Исполнителю (от 150 до 350 р.)
<br>2. Оборудование принимается в чистом виде. Очистка от пыли и грязи - платная услуга - 350 руб.
<br>3. Оборудование с согласия Заказчика принято Исполнителем без разборки и проверки неисправностей. Заказчик  согласен, что все неисправности, которые могут быть обнаружены при проведении диагностических работ, возникли до приёма оборудования в ремонт по данной квитанции.
<br>4. Срок ремонта может составлять до 15 дней. В случае отсутствия запасных частей срок ремонта может быть увеличен до 45 дней, при этом Заказчик претензий к Исполнителю иметь не будет.
<br>5. Исполнитель обязуется прилагать все возможные усилия для сохранения необходимой Заказчику информации с их носителей, однако, сохранность информации не гарантируется.
<br>6. Заказчик обязуется забрать отремонтированный аппарат в течении 30 дней после уведомления об окончании ремонта. В противном случае оборудование переходит в собственность Сервисного центра.
</font>
<br>
<br> <table><tr><td width="370">Сдал ________________ ('.$famk.$namek.$otchek.')</td><td> Принял________________('.$fio_engineer.')</td></tr></table>
</body></html>';
}

// Квитануия о выдаче аппарата
if(isset($_GET['delivery'])) {
	$idwork = $_GET['delivery'];
	//$garan = $_POST['garan'];
	
	$select_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$idwork' LIMIT 1");
	$fetch_work = $select_work->fetch_assoc();
	
	$fio = $fetch_work['fio'];
	$dev = $fetch_work['device'].' "'.$fetch_work['brend'].'" '.$fetch_work['model'];
	$sn = $fetch_work['sn'];
	$komplekt = $fetch_work['komplekt'];
	$garan = $fetch_work['garan'];
	
	list($predate, $pretime) = explode(" ", $fetch_work['date']); // Отделяем дату от времени
	list($year, $month, $day) = explode("-", $predate); // Разчленяем дату
	$old_date = $day.'-'.$month.'-'.$year; // Собираем дату
	
	$delivery_date = date('Y-m-d'); // Дата для базы в поле дата выдачи (delivery)
	
	$raboti = $fetch_work['problemse'];
	$money = $fetch_work['money'];
	$sale = $fetch_work['sale'];
	$sumsale = $fetch_work['sumsale'];
	$idcert = $fetch_work['idcert'];
	
	$engineer_nik = $_SESSION['login'];

	// Ищем юзверя по логину и отжимаем его имя, фамилию и отчество
	$select_engineer = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$engineer_nik' LIMIT 1");
	$fetch_engineer = $select_engineer->fetch_assoc();
	$familia = $fetch_engineer['familiya'];
	$name = $fetch_engineer['name'];
	$name = iconv_substr( $name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$otchestvo = $fetch_engineer['otchestvo'];
	$otchestvo = iconv_substr( $otchestvo, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$fio_engineer = $familia.' '.$name.'. '.$otchestvo.'.';
	
	if(!empty($sale)) {
		$str_sale = 'Скидка на проведенные работы: <b>'.$sale.'%</b><br>';
	}else{
		$str_sale = '';
	}
	
	if(!empty($sumsale)) {
		$str_sumsale = 'Стоимость работ со скидкой: <b>'.$sumsale.' руб.</b><br>';
	}else{
		$str_sumsale = '';
	}
	
	if(!empty($idcert)) {
		$str_cert = 'Использован сертификат <b>id: '.$idcert.'</b><br>';
		$koplate = $fetch_work['koplate'];
	}else{
		$str_cert = '';
		$koplate = $sumsale;
	}
	
	// Временно перегружаем переменную
	//$garan = 0;
	$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<table><tbody><tr><td width="350"><img src="'.$logo.'" alt="Логотип" width="134" height="134"></td>
<td>СЦ ПочиникА<br>'.$fetch_set['contacts'].'<br>'.$fetch_set['phone'].'</td></tr></tbody></table>
<table><tbody><tr><td width="150"></td><td ><center>Квитанция о выдаче оборудования <b>№'.$idwork.'</b> от <b>'.$old_date.' г.</b></center></td></tr></tbody></table>
ФИО: <b>'.$fio.'</b><br>
Наименование аппарата: <b>'.$dev.'</b><br>
Серийный номер: <b>'.$sn.'</b><br>
Отдан в комплектации: <b>'.$komplekt.'</b><br>
Дата выдачи: <b>'.$date.' г.</b><br>
Проведенные работы: <b>'.$raboti.'</b><br>
Стоимость проведенных работ: <b>'.$money.' руб.</b><br>
'.$str_sale.'
'.$str_sumsale.'
'.$str_cert.'
Гарантия на ремонт: <b>'.$garan.' мес.</b><br>
Ведущий инженер: <b>'.$fetch_engineer['familiya'].' '.$fetch_engineer['name'].'</b><br>
Итого к оплате: <b>'.$koplate.' руб.</b><br>
<b>Условия гарантии:</b><br>				
1. Гарантия действительна по предъявлению потребителем данной квитанции.<br>	
2. Настоящая гарантия не дает права на возмещение и покрытие ущерба, проишедшего в результате переделки или регулировки изделия без согласия сервисного центра.<br>	
3. Настоящая гарантия не распространяется на следующее: а) периодическое обслуживание (настройка ПО, чистка и т.д.) б) ремонт или замену частей в связи с их нормальным износом в) ущерб в результате неправильной эксплуатации, ремонта произведенного не представителем сервисного центра г) несчастных случаев, механических повреждений, колебания напряжения электрических цепей, транспортировки.<br>
4. Стоимость проверки работоспособности не включена в цену изделия, поэтому в случае необоснованной претензии она может (по усмотрению продавца) взиматься с покупателя в соответствии с действующими тарифами.<br>	
5. Гарантийный ремонт осуществляется в сервис-центре фирмы, доставка оборудования до сервис-центра осуществляется клиентом.<br>	
6. На новые комплектующие гарантия распространяется при наличии товарного чека.<br><br><br>
Выдал: _______________________ '.$fio_engineer.'
</body></html>';

	$status = 5;
	$connect_db->query("UPDATE `work` SET `delivery` = '$delivery_date', `status` = '$status' WHERE `id` = '$idwork'");
	
	// Имя файла вывода
	$pdf_name = 'kvitv'.$idwork.'.pdf';
	
	// Записываем гарантию в базу
	$add_garant = $connect_db->prepare("INSERT INTO `gperiod` (`receipt`, `month`) VALUE (?,?)");
	$add_garant->bind_param("ii", $idwork, $garan);
	$add_garant->execute();
	$add_garant->close();
}

// Формируем квитанции для выездов
if(isset($_POST['avr'])) {
	$engineer = $_POST['engineer'];
	$number = $_POST['number'];
	$adid = ' ';
	
	// Получаем стартовый номер акта
	$select_number = $connect_db->query("SELECT `number` FROM `akts` WHERE `id` = '1' ORDER BY `id` DESC LIMIT 1");
	$fetch_number = $select_number->fetch_assoc();
	$num = $fetch_number['number'];
	
	// Ищем юзверя по логину и отжимаем его имя, фамилию и отчество
	list($famile, $name) = explode(" ", $engineer);
	$select_engineer = $connect_db->query("SELECT * FROM `users` WHERE `familiya` = '$famile' && `name` = '$name' LIMIT 1");
	$fetch_engineer = $select_engineer->fetch_assoc();
	$familia = $fetch_engineer['familiya'];
	$name = $fetch_engineer['name'];
	$name = iconv_substr( $name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$otchestvo = $fetch_engineer['otchestvo'];
	$otchestvo = iconv_substr( $otchestvo, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$fio_engineer = $familia.' '.$name.'. '.$otchestvo.'.';
	
	$year = date('Y');
	
	// Получаем реквизиты
	$select_rekvez = $connect_db->query("SELECT * FROM `settings` LIMIT 1");
	$fetch_rekvez = $select_rekvez->fetch_assoc();
	$rekvez = $fetch_rekvez['contacts'];
	
	// В цыкле создаем нужное колличество актов
	$i = 1;
	while ($i <= $number){
		$text = '<table border=0  ><tbody><tr>
<td><center><img src="'.$logo.'" alt="Логотип" width="134" height="134"></center></td>
<center><td width="385"><center><b>Акт № '.$num.' от ____.____.'.$year.' г.<br> на выполнение работ-услуг</b><br><font color=green>Номер для скидки: '.$adid.'</font></center></td></center><td><b>'.$rekvez.'<br>'.$fetch_set['phone'].'</b></td></tr><tbody>
</table>
<table width="783">
<tr width="783"><td>
Мы, нижеподписавшиеся, инженер '.$sname.' представитель ИСПОЛНИТЕЛЯ, одной стороны и <br />____________________________________________ представитель ЗАКАЗЧИКА с другой стороны, составили настоящий акт в том, что ИСПОЛНИТЕЛЬ выполнил, а ЗАКАЗЧИК принял следующие работы:</td>
</tr>
</table>
<table width="783">
<tr width="783" height="138"><td>
<center><table border="1" width="541"><tr border="1"><td width="29">№</td><td>Наименование</td><td width="74"></center>Сумма</center></td></tr>
<tr border="1"><td>1</td><td>Выезд к заказчику</td><td></td></tr>
<tr border="1"><td>2</td><td></td><td></td></tr>
<tr border="1"><td>3</td><td></td><td></td></tr>
<tr border="1"><td>4</td><td></td><td></td></tr>
<tr border="1"><td>5</td><td></td><td></td></tr>
<tr border="1"><td></td><td>Итого</td><td></td></tr></table></center>
</tr></td></tbody>
</table>
<table width="783"><tr><td>Итоговая сумма к оплате: _____________________________________</td></tr><tr><td>Работы выполнены в полном объеме, в установленные сроки и с надлежащим качеством. Стороны претензий друг к другу не имеют.</td></tr></table>
<table width="783" height="95"><tr><td width="391">Исполнитель: '.$fio_engineer.'</td><td>Заказчик: </td></tr><tr><td><br /><br />Сдал: ______________</td><td><br /><br />Принял: ______________</td></tr></table>
<br />
<table border="0"><tbody><tr>
<td><center><img src="'.$logo.'" alt="Логотип" width="134" height="134"></center></td>
<center><td width="385"><center><b>Акт № '.$num.' от ____.____.'.$year.' г.<br> на выполнение работ-услуг</b></center></td></center><td><b>'.$rekvez.'<br>'.$fetch_set['phone'].'<b></td></tr><tbody>
</table>
<table width="783">
<tr width="783"><td>
Мы, нижеподписавшиеся, инженер '.$sname.' представитель ИСПОЛНИТЕЛЯ, одной стороны и ____________________________________________ представитель ЗАКАЗЧИКА с другой стороны, составили настоящий акт в том, что ИСПОЛНИТЕЛЬ выполнил, а ЗАКАЗЧИК принял следующие работы:</td>
</tr>
</table>
<table width="783">
<tr width="783" height="138"><td>
<center><table border="1" width="541"><tr border="1"><td width="29">№</td><td>Наименование</td><td width="74"></center>Сумма</center></td></tr>
<tr border="1"><td>1</td><td>Выезд к заказчику</td><td></td></tr>
<tr border="1"><td>2</td><td></td><td></td></tr>
<tr border="1"><td>3</td><td></td><td></td></tr>
<tr border="1"><td>4</td><td></td><td></td></tr>
<tr border="1"><td>5</td><td></td><td></td></tr>
<tr border="1"><td></td><td>Итого</td><td></td></tr></table></center>
</tr></td></tbody>
</table>
<table width="783"><tr><td>Итоговая сумма к оплате: _____________________________________</td></tr><tr><td>Работы выполнены в полном объеме, в установленные сроки и с надлежащим качеством. Стороны претензий друг к другу не имеют.</td></tr></table>
<table width="783" height="95"><tr><td width="391">Исполнитель: '.$fio_engineer.'</td><td>Заказчик: </td></tr><tr><td><br /><br />Сдал: ______________</td><td><br /><br />Принял: ______________</td></tr></tbody></table>';
#<center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"..$ads\"> </center> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
#<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';



		$html=$text.$html;
		$i++;
		$num++;
	};
	
	$connect_db->query("UPDATE `akts` SET `number` = '$num' WHERE `id` = '1'");
	$pdf_name = 'akts'.$num.'.pdf';
}

// Товарный чек и гарантия
// Товарный чек
if(isset($_POST['addchek'])) {
	$user = $_SESSION['login'];
	$num_field = 1;
	
	if(!empty($_POST['akt'])) {
		$akt = $_POST['akt'];
	}else{
		$akt = 0;
	}
	if(!empty($_POST['who'])) {
		$who = $_POST['who'];
	}else{
		$who = 'n/a';
	}
	
	// Реквизиты для товарного чека
	$rekvez = 'Продавец: '.$fetch_set['pname'].'<br>
Адрес: '.$fetc_set['contacts'].' тел. '.$fetch_set['phone'].'<br>
ОГРН: '.$fetch_set['ogrn'].'<br>
ИНН: '.$fetch_set['inn'].'<br>';
	
	$select_id = $connect_db->query("SELECT * FROM `number`");
	$fetch_id = $select_id->fetch_assoc();
	$id = ++$fetch_id['tovarnik'];
	
	$data = date('d.m.Y');
	$itog = 0;
	
	$select_engineer = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$user' LIMIT 1");
	$fetch_engineer = $select_engineer->fetch_assoc();
	$familia = $fetch_engineer['familiya'];
	$name = $fetch_engineer['name'];
	$name = iconv_substr( $name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$otchestvo = $fetch_engineer['otchestvo'];
	$otchestvo = iconv_substr( $otchestvo, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$fio_engineer = $familia.' '.$name.'.'.$otchestvo.'.';
	
	// Тело товарного чека
	$html1 = '<table>
		<tbody>
			<tr>
				<td>
					<img src="'.$logo.'" alt="Логотип" width="134" height="134">
				</td>
				<td>
					<b>'.$rekvez.'</b>
				</td>
			</tr>
		</tbody>
	</table><br><br>
	<table width="710px"><tr><td><center><b>ТОВАРНЫЙ ЧЕК № '.$id.' от '.$data.' г.</center></td></tr></table><br>
	<table width="710px" border="1" bordercolor="black" cellspacing="1" cellpadding="0">
		<tbody>
		<tr><td width="25px"><center><b>№</b></center></td><td><b>Наименование товара, услуги</td><td width="50px"><center><b>Кол-во</b></center></td><td width="100px"><center><b>Цена, руб.</b></center></td><td width="100px"><center><b>Сумма, руб.</b></center></td></tr>';
	
	$select_zakaz = $connect_db->query("SELECT * FROM `tezak` WHERE `user` = '$user'");
	while($fetch_zakaz = $select_zakaz->fetch_assoc()) {
		$summa = $fetch_zakaz['kol'] * $fetch_zakaz['price'];
		$html2 = $html2.'<tr><td><center>'.$num_field.'</center></td><td>'.$fetch_zakaz['name'].'</td><td><center>'.$fetch_zakaz['kol'].'</center></td><td><center>'.$fetch_zakaz['price'].'</center></td><td><center>'.$summa.'</center></td></tr>';
		$itog = $itog + $summa;
		$num_field++;
	}
	
	$html3 = '<tr><td></td><td></td><td></td><td align="right"><b>Итого:</b></td><td><center><b>'.$itog.'</b></center></td></tr></tbody></table><br><br><br>Получено (сумма прописью): '.num2str($itog).'
	<br><br>Продавец_____________________________/'.$fio_engineer.'/';
	
	$html = $html1.$html2.$html3;
	
	// Имя файла
	$pdf_name = 'tovchek_'.$id.'.pdf';
	
	// Обновляем номер товарника 
	$connect_db->query("UPDATE `number` SET `tovarnik` = '$id'");
	
	// Уменьшаем колличество товара на складе и пишим чек в базу
	$select_koltov_from_tezak = $connect_db->query("SELECT * FROM `tezak` WHERE `user` = '$user'");
	while($fetch_koltov_from_tezak = $select_koltov_from_tezak->fetch_assoc()) {
		$idtov_zak = $fetch_koltov_from_tezak['idtov'];
		$koltov_zak = $fetch_koltov_from_tezak['kol'];
		$koltov_garan = $fetch_koltov_from_tezak['garan'];
		$koltov_sn = $fetch_koltov_from_tezak['sn'];
		
		// Записываем чек в базу
		$add_chek = $connect_db->prepare("INSERT INTO `cheki` (`number`, `idtov`, `kol`, `garan`, `sn`, `user`, `akt`, `who`) VALUE (?,?,?,?,?,?,?,?)");
		$add_chek->bind_param("iiiissis", $id, $idtov_zak, $koltov_zak, $koltov_garan, $koltov_sn, $user, $akt, $who);
		$add_chek->execute();
		$add_chek->close();
		
		// Уменьшаем количество на складе
		$select_koltov_from_sklad = $connect_db->query("SELECT * FROM `sklad` WHERE `id` = '$idtov_zak' LIMIT 1");
		$fetch_koltov_from_sklad = $select_koltov_from_sklad->fetch_assoc();
		$koltov_sklad = $fetch_koltov_from_sklad['kol'];
		$koltov = $koltov_sklad - $koltov_zak;
		$connect_db->query("UPDATE `sklad` SET `kol` = '$koltov' WHERE `id` = '$idtov_zak'");
	}
}
// Гарантийный чек
if(isset($_POST['addgaran'])) {
	$user = $_SESSION['login'];
	$num_field = 1;
	$data = date('d.m.Y');
	
	if(!empty($_POST['akt'])) {
		$akt = $_POST['akt'];
	}else{
		$akt = 0;
	}
	if(!empty($_POST['who'])) {
		$who = $_POST['who'];
	}else{
		$who = 'n/a';
	}
	
	$select_engineer = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$user' LIMIT 1");
	$fetch_engineer = $select_engineer->fetch_assoc();
	$familia = $fetch_engineer['familiya'];
	$name = $fetch_engineer['name'];
	$name = iconv_substr( $name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$otchestvo = $fetch_engineer['otchestvo'];
	$otchestvo = iconv_substr( $otchestvo, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
	$fio_engineer = $familia.' '.$name.'.'.$otchestvo.'.';
	
	$select_for_garan = $connect_db->query("SELECT * FROM `tezak` WHERE `user` = '$user'");
	
	$select_id = $connect_db->query("SELECT * FROM `number`");
	$fetch_id = $select_id->fetch_assoc();
	$id = ++$fetch_id['garan'];
	
	$html1 = '<table><tr><td>
<img src="'.$logo.'" alt="Логотип" width="67" height="67"></td><td><b><font size=2>'.$sname.'</font><br></b>
<font size=1>'.$fetch_set['contacts'].', тел. '.$fetch_set['phone'].'</font></td>
</tr></table>
<table width="710px"><tr><td><center><font size=3><b>Гарантийный талон № '.$id.' от '.$data.'г.</b></font><br><font size=2>(недействителен без подписи Покупателя)</font></center></td></tr></table>
<font size=1>Согласно п.1 ст.25 ФЗ от 07.02.1992 г. №2300-1 "О защите прав потребителей" и в соответствии с перечнем непродовольственных товаров надлежащего качества, не подлежащих возврату или обмену на аналогичный товар другого размера, формы, габаритов, фасона, расцветки или комплектации утверждённым Постановлением Правительства РФ от 19.01.1998 г №55 приобретённый товар надлежащего качества обмену или возврату не подлежит.<br>
<table width="710px"><tr><td><center><b>Список оборудования подлежащего гарантийному обслуживанию</b></center></td></tr></table>
<table width="710px" border=1>
<tr><td width="25px"><center><b><font size=1>№</font></b></center></td><td><b><font size=1>Наименование</font></b></td><td width="50px"><center><b><font size=1>Кол-во</font></b></center></td><td width="210px"><center><b><font size=1>S/N</font></b></center></td><td width="60px"><center><b><font size=1>Гарантия</font></b></center></td></tr>';
	while($fetch_for_garan = $select_for_garan->fetch_assoc()) {
		if(!empty($fetch_for_garan['sn'])) {
			$sn = '<td><center>'.$fetch_for_garan['sn'].'</center></td>';
		}else{
			$sn = '<td><center>n/a</center></td>';
		}
		$html2 = $html2.'<tr><td><center>'.$num_field.'</center></td><td>'.$fetch_for_garan['name'].'</td><td><center>'.$fetch_for_garan['kol'].'</center></td>'.$sn.'<td><center>'.$fetch_for_garan['garan'].' мес.</center></td></tr>';
		$num_field++;
	}
	$html3 = '</table>
<span style="font-size: 9px;"><b>Гарантийное обслуживание осуществляется только в сервисном центре по адресу '.$fetch_set['contacts'].', т. '.$fetch_set['phone'].'<br> Режим работы: пн-пт с 10-00 до 20-00, сб с 10-00 до 18-00, вс - выходной.<br> 
В гарантийное обслуживание включается:</b><br>
1. Демонстрация работоспособности оборудования до передачи Покупателю в помещении магазина (по требованию Покупателя).<br>
2. Диагностика неисправностей в помещении сервисного центра (доставку осуществляет Покупатель).<br>
3. Ремонт (или замена) неисправных узлов или оборудования в течении всего срока гарантийного обслуживания в помещении сервисного центра (доставку осуществляет Покупатель).<br>
<b>Гарантийное обслуживание осуществляется в соответствии со следующим порядком:</b><br>
1. Гарантийное обслуживание производится при условии соблюдения Покупателем правил эксплуатации оборудования.<br>
2. В течении трех рабочих дней (с даты доставки неисправного оборудования в помещение сервисного центра) производится диагностика неисправности.<br>
3. Сервисный центр обеспечивает гарантийный ремонт (или замену на аналогичное в случае невозможности ремонта) неисправного оборудования, срок гарантийного ремонта зависит от сложности оборудования и вида неисправности, составляет от 1 до 45 дней после проведения диагностики неисправностей.<br>
4. В случае невозможности ремонта в сервисном центре продавца, срок ремонта устанавливается сервисным центром производителя, с учетом времени , затраченного на транспортировку.<br>
<b>Гарантийное обслуживание не осуществляется в следующих случаях:</b><br>
1. Отсутствия данного гарантийного документа, наличия в нем исправлений и помарок, не заверенных подписью и печатью продавца.<br>
2. Неполностью заполненного данного гарантийного документа (отсутствие на нем подписей продавца и покупателя, печати продавца, даты продажи, серийных номеров оборудования)<br>
3. Повреждения гарантийных наклеек (при их наличии), и не совпадении серийных номеров на изделии и гарантийном документе.<br>
4. Наличие механических повреждений на оборудовании (разломы, сколы, вмятины, царапины, вздутия, следы гари и копоти,разорванные и сгоревшие проводники) , следов ремонта проведенного самостоятельно или попыток вскрытия изделия, нарушения оригинальных пломб производителя<br>
5. Неисправностей, вызванных самостоятельной установкой дополнительного внешнего или внутреннего оборудования.<br>
6. Нарушения комплектности изделия (отсутствие драйверов, документации, соединительных кабелей, крепежа и оригинальной упаковки).<br>
7. Попадания внутрь изделия посторонних предметов, веществ, жидкостей, насекомых.<br>
8. Повреждения	вызванные форс-мажорными обстоятельствами такими как: стихия, пожар, наводнение, бытовыми факторами.<br>
9. Повреждения, вызванные несоответствием Государственным стандартам параметров электропитающих, телекоммукационных, кабельных сетей и других подобных внешних факторов.<br>
10. Повреждения вызванные использованием нестандартных и неоригинальных расходных материалов и запчастей.<br>
11. Нарушения правил транспортировки, установки и эксплуатации.<br>
<b>Гарантия поставщика не распространяется:</b><br>
1. На расходные материалы, носители информации (тонеры, картриджи, фотовалы, термопленки, дискеты, CD-Диски и т.д.), в том числе и поставляемые в комплекте с новой техникой.<br>
2. На все программное обеспечение, а так же на FLASH BIOS устройств входящих в состав новой техники .<br>
3. На соединительные кабели устройств, сетевые шнуры, порты (COM, LPT, PS/2,TV-входы, выходы).<br>
4. На химические источники тока, в т.ч. аккумуляторные батареи.<br>
5. На изделия подверженные механическому износу (вентиляторы и т.п.).<br>
<b>Внимание!</b><br>
Специальные требования и условия эксплуатации оборудования изложены в технических описаниях на приобретенное оборудование, с которыми необходимо ознакомится в первую очередь.</span>
<table cellpadding=50><tr><td><font size=1>
Продавец<br><br>_____________________________<br>'.$fio_engineer.'

</td><td><font size=1>
Товар исправен и проверен в моем присутствии.
Товар получил, с правилами установки и эксплуатации ознакомлен. Претензий по качеству, состоянию комплектации оборудования не имею. С условиями
гарантийного обслуживания ознакомлен и согласен.<br>
Покупатель<br><br>____________________<br>
(фамилия и.о.)<br><br>____________________<br>Подпись</td></tr></table>';

	$html = $html1.$html2.$html3;
	$pdf_name = 'garanchek_'.$id.'.pdf';
	
	// обновляем номер гарантийника
	$connect_db->query("UPDATE `number` SET `garan` = '$id'");
}

/* Конвертим в PDF */
include("mpdf/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
$mpdf->charset_in = 'utf8'; /*не забываем про русский*/
$stylesheet = file_get_contents('print.css');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->list_indent_first_level = 0; 
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->Output($pdf_name, 'D');
/*
I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
D: send to the browser and force a file download with the name given by filename.
F: save to a local file with the name given by filename (may include a path).
S: return the document as a string. filename is ignored.
*/
?>