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
$pdf_name = "schet.pdf";
// LOGOTIP
$select_settings = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_settings = $select_settings->fetch_assoc();
$logo = $fetch_settings['logo'];
$adress = $fetch_settings['contacts'];
$sname = $fetch_settings['sname'];
// END LOGOTIP

$id = $_GET['id'];
$select_contragent = $connect_db->query("SELECT * FROM `scheta` WHERE `id` = '$id' LIMIT 1");
$fetch_contragent = $select_contragent->fetch_assoc();
$select_raboty = $connect_db->query("SELECT * FROM `raboty` WHERE `schet` = '$id'");

// Функция преобразования месяца в строку в родительном падеже
function month2str ($month) {
	if ($month == '01' OR $month == '1') {
		$month = 'января';
	}
	if ($month == '02' OR $month == '2') {
		$month = 'февраля';
	}
	if ($month == '03' OR $month == '3') {
		$month = 'марта';
	}
	if ($month == '04' OR $month == '4') {
		$month = 'апреля';
	}
	if ($month == '05' OR $month == '5') {
		$month = 'мая';
	}
	if ($month == '06' OR $month == '6') {
		$month = 'июня';
	}
	if ($month == '07' OR $month == '7') {
		$month = 'июля';
	}
	if ($month == '08' OR $month == '8') {
		$month = 'августа';
	}
	if ($month == '09' OR $month == '9') {
		$month = 'сентября';
	}
	if ($month == '10') {
		$month = 'октября';
	}
	if ($month == '11') {
		$month = 'ноября';
	}
	if ($month == '12') {
		$month = 'декабря';
	}
	return $month;
}

// Подготавливаем дату
list($predate, $time) = explode(" ", $fetch_contragent['data']);
list($year, $month, $day) = explode("-", $predate);
$date = $day.' '.month2str ($month).' '.$year;

// Шапка счета
$html1 = '<table width="100%">
    <tr>
        <td width="50%" rowspan="2"><img src="'.$logo.'" alt="Логотип" width="100" height="110"></td>
        <td width="50%"><strong>'.$fetch_settings['pname'].'</strong></td>
    </tr>
    <tr>
        <td width="50%" style="vertical-align:top;">'.$adress.'</td>
    </tr>
</table>';
// Конец шапки

// Таблица реквизитов
$html2 = '<table border="1" width="100%">
    <tr>
        <td width="30%">ИНН '.$fetch_settings['inn'].'</td>
        <td width="30%">КПП '.$fetch_settings['kpp'].'</td>
        <td style="text-align: center; vertical-align: bottom;" width="10%" rowspan="2">Сч. №</td>
        <td style="vertical-align: bottom;" width="40%" rowspan="2">'.$fetch_settings['rs'].'</td>
    </tr>
    <tr>
        <td colspan="2">Получатель<br><br><p>'.$fetch_settings['pname'].'</p></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">Банк получателя<br><br><p>'.$fetch_settings['bank'].'</p></td>
        <td style="text-align: center;">Бик</td>
        <td>'.$fetch_settings['bik'].'</td>
    </tr>
    <tr>
        <td style="text-align: center;">Сч. №</td>
        <td>'.$fetch_settings['ks'].'</td>
    </tr>
</table>
<h1 style="width:100%;text-align:center;">Счет на оплату № '.$id.' от '.$date.' г.</h1>
<table width="100%">
	<tr>
		<td width="20%">Плательщик:</td>
		<td width="70%">'.$fetch_contragent['org'].', '.$fetch_contragent['index'].', '.$fetch_contragent['adres'].', ИНН '.$fetch_contragent['inn'].', КПП '.$fetch_contragent['kpp'].', р/с '.$fetch_contragent['rs'].', банк '.$fetch_contragent['bank'].', кор.счет '.$fetch_contragent['ks'].', БИК '.$fetch_contragent['bik'].'</td>
	</tr>
</table>
<table width="100%" border="1">
	<tr>
		<td style="text-align:center;" width="5%">№</td>
		<td style="text-align:center;" width="40%">Наименование товара, работ, услуг</td>
		<td style="text-align:center;" width="10%">Ед. изм.</td>
		<td style="text-align:center;" width="10%">Кол-во</td>
		<td style="text-align:center;" width="17%">Цена без НДС</td>
		<td style="text-align:center;" width="17%">Сумма без НДС</td>
	</tr>';
	$num = 1;
	$kolichestvo = 1;
	$edizm = 'шт';
while($fetch_raboty = $select_raboty->fetch_assoc()) {
	if($fetch_raboty['razdel'] == 'visovi') {
		$bumajka = 'Квитанция №'.$fetch_raboty['subid'].': ';
	}else {
		$bumajka = '';
	}
	$html3 = $html3.'<tr><td style="text-align: center;">'.$num.'</td><td>'.$bumajka.$fetch_raboty['name'].'</td><td style="text-align: center;">'.$edizm.'</td><td style="text-align: right;">'.$fetch_raboty['kol'].'</td><td style="text-align: right;">'.$fetch_raboty['price'].' р.</td><td style="text-align: right;">'.$fetch_raboty['price'] * $fetch_raboty['kol'].',00 р.</td></tr>';
	$num++;
}
$prsumm = num2str((int) $fetch_contragent['summa']);
$html4 = '</table><table width="100%" border="0"><tr><td style="text-align:center;" width="5%"></td><td style="text-align:center;" width="40%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:right;" width="17%">Итого без НДС</td><td style="text-align:right;" width="17%" border="1">'.$fetch_contragent['summa'].' р.</td></tr>
<tr><td style="text-align:center;" width="5%"></td><td style="text-align:center;" width="40%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:right;" width="17%">Итого НДС</td><td style="text-align:right;" width="17%" border="1">---</td></tr>
<tr><td style="text-align:center;" width="5%"></td><td style="text-align:center;" width="40%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:center;" width="10%"></td><td style="text-align:right;" width="17%">Всего к оплате:</td><td style="text-align:right;" width="17%" border="1">'.$fetch_contragent['summa'].' р.</td></tr>
</table>
<p>Всего наименований '.--$num.', на сумму '.$fetch_contragent['summa'].',00 р., без НДС</p>
<p><strong>'.$prsumm.'</strong></p>';
$html = $html1.$html2.$html3.$html4;

$connect_db->query("UPDATE `scheta` SET `status` = '1'");

$pdf_name = 'schet_'.$id.'.pdf';

/* Конвертим в PDF */
include("mpdf/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
$mpdf->charset_in = 'utf8'; /*не забываем про русский*/
$stylesheet = file_get_contents('print.css');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->list_indent_first_level = 0; 
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->AddPage();
$mpdf->WriteHTML("Test string", 2);
$mpdf->Output($pdf_name, 'D');
/*
I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
D: send to the browser and force a file download with the name given by filename.
F: save to a local file with the name given by filename (may include a path).
S: return the document as a string. filename is ignored.
*/
?>