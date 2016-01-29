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
	//echo 'Не удалось подключиться к базе.';
}
$connect_db->query("SET NAMES UTF8");
$pdf_name = "prihodnik.pdf";
// LOGOTIP
$select_set = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_set = $select_set->fetch_assoc();
$logo = $fetch_set['logo'];
$sname = $fetch_set['sname'];
// END LOGOTIP

$select_number = $connect_db->query("SELECT * FROM `number` LIMIT 1");
$fetch_number = $select_number->fetch_assoc();

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
$predate = date("Y-m-d");
list($year, $month, $day) = explode("-", $predate);

$html = '<div style=" width:300px; border:1px solid black;"><table width="290px" cellspacing="0" border="0">
<tr><td colspan="7" style="border-bottom:1px solid black; text-align:center;">'.$sname.'<td></tr>
<tr><td colspan="7" style="font-size:10px;text-align:center;">(организация)</td></tr>
<tr><td colspan="7" style="width:100%"></td></tr>
<tr><td colspan="7" style="font-weight:bold; text-transform:uppercase; text-align:center;">Квитанция</td></tr>
<tr><td colspan="7" style="width:100%"></td></tr>
<tr><td colspan="7" style="width:100%"></td></tr>
<tr>
	<td colspan="5">к приходному кассовому ордеру</td>
	<td colspan="2" style="border-bottom:1px solid black; text-align:center;">'.$fetch_number['prihodnik'].'</td>
</tr>
<tr>
	<td style="width:10%;">от</td>
	<td style="border-bottom:1px solid black; text-align:center; width:15%;">"'.$day.'"</td>
	<td style="width:10%;"></td>
	<td style="border-bottom:1px solid black; text-align:center; width:30%;">'.month2str ($month).'</td>
	<td style="width:10%;"></td>
	<td style="border-bottom:1px solid black; text-align:center; width:15%;">'.$year.'</td>
	<td style="width:10%;">г.</td>
</tr>
<tr><td colspan="7" style="width:100%"></td></tr>
<tr>
	<td colspan="2">Принято от</td>
	<td colspan="5" style="border-bottom:1px solid black; text-align:center;"></td>
</tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr>
	<td colspan="2">Основание:</td>
	<td colspan="5" style="border-bottom:1px solid black; text-align:center; height:22px;"></td>
</tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr>
	<td colspan="1" style="height:22px; font-size:12px;">Сумма</td>
	<td colspan="3" style="height:22px; border-bottom:1px solid black;"></td>
	<td style="height:22px; font-size:12px;">руб.</td>
	<td style="height:22px; border-bottom:1px solid black; text-align:center;">0</td>
	<td style="height:22px; font-size:12px;">коп.</td>
</tr>
<tr>
	<td colspan="1"></td>
	<td colspan="3" style="font-size:10px;text-align:center;">(цифрами)</td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr><td colspan="7" style="font-size:10px;text-align:center;">(прописью)</td></tr>
<tr><td colspan="7" style="width:100%; height:22px; border-bottom:1px solid black"></td></tr>
<tr>
	<td colspan="4" style="border-bottom:1px solid black;"></td>
	<td style="height:22px; font-size:12px;">руб.</td>
	<td style="border-bottom:1px solid black; text-align:center">0</td>
	<td style="height:22px; font-size:12px;">коп.</td>
</tr>
<tr>
	<td colspan="2" style="height:22px;">В том числе</td>
	<td colspan="5" style="border-bottom:1px solid black; height:22px;"></td>
</tr>
<tr>
	<td style="border-bottom:1px solid black; text-align:center;">"'.$day.'"</td>
	<td></td>
	<td colspan="2" style="border-bottom:1px solid black; text-align:center">'.month2str($month).'</td>
	<td></td>
	<td style="border-bottom:1px solid black; text-align:center">'.$year.'</td>
	<td>г.</td>
</tr>
<tr>
	<td colspan="3" style="height:22px; text-align:center;"></td>
	<td colspan="4"></td>
</tr>
<tr><td colspan="7" style="height:22px; vertical-align:top; padding-left:5%; font-size:12px;">М.П. (штампа)</td></tr>
<tr>
	<td colspan="2" style="font-size:11px;">Главный бухгалтер</td>
	<td colspan="2" style="border-bottom:1px solid black;"></td>
	<td colspan="3" style="border-bottom:1px solid black; font-size:12px; text-align:center;">/Бабешко Е. Е./</td>
</tr>
<tr>
	<td colspan="2" style="font-size:10px;"></td>
	<td colspan="2" style="font-size:10px; text-align:center;">(подпись)</td>
	<td colspan="3" style="font-size:10px; text-align:center;">(расшифровка)</td>
</tr>
<tr>
	<td colspan="2" style="font-size:11px;">Кассир</td>
	<td colspan="2" style="border-bottom:1px solid black;"></td>
	<td colspan="3" style="border-bottom:1px solid black; font-size:12px; text-align:center;">/Бабешко Е. Е./</td>
</tr>
<tr>
	<td colspan="2" style="font-size:10px;"></td>
	<td colspan="2" style="font-size:10px; text-align:center;">(подпись)</td>
	<td colspan="3" style="font-size:10px; text-align:center;">(расшифровка)</td>
</tr>
</table></div>';
$number = $fetch_number['prihodnik'];
$number++;
$connect_db->query("UPDATE `number` SET `prihodnik` = '$number' WHERE `id` = '1'");
//echo $html;

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