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
$pdf_name = "akt_vip_rab.pdf";
// LOGOTIP
$select_set = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_set = $select_set->fetch_assoc();
$logo = $fetch_set['logo'];
$sname = $fetch_set['sname'];
// END LOGOTIP

/*$select_number = $connect_db->query("SELECT * FROM `number` LIMIT 1");
$fetch_number = $select_number->fetch_assoc();
$number = $fetch_number['aktvyprab'];*/



if(isset($_GET['aktNumber'])) {
	$number = $_GET['aktNumber'];
	$select_akt = $connect_db->query("SELECT * FROM `aktswork` WHERE `number` = '$number'");
	if($select_akt->num_rows >=1) {
		$i = 0;
		while($fetch_akt = $select_akt->fetch_assoc()) {
			$user = $fetch_akt['user'];
			$date = date("d-m-Y", strtotime($fetch_akt['date']));
			$who = $fetch_akt['fiok'];
			$work[$i] = $fetch_akt['work'];
			$price[$i] = $fetch_akt['price'];
			$i++;
		}
	}
}else {
	$select_number = $connect_db->query("SELECT * FROM `number` LIMIT 1");
	$fetch_number = $select_number->fetch_assoc();
	$number = $fetch_number['aktvyprab'];

	$user = $_SESSION['login'];
	$date = date('d-m-Y');
	$who = $_POST['who'];
	$work = $_POST['work']; //Массив
	$price = $_POST['price']; //Массив
}

// Сумма для записи в базу
for ($i = 0; $i < count($price); $i++){
	$pre_sum = $pre_sum+$price[$i];
}

$html1 = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<table border=0  ><tbody><tr>
<td><center><img src="'.$logo.'" alt="Логотип" width="134" height="134"></center></td>
<center><td width=500><center><b>Акт № '.$number.' от '.$date.' г.<br> на выполнение работ-услуг</b></center></td></center></tr><tbody>
</table>
<table width=783>
<tr width=783><td>
Исполнитель: '.$sname.'<br>
Заказчик: '.$who.'<br><br>
</td>
</tr>
</table>
<center><table border=1 width=541><tr border=1><td width=29>№</td><td>Наименование</td><td width=74></center>Сумма</center></td></tr>';
for ($i = 0; $i < count($work) && $i < count($price); $i++){
	$v=$i+1;
	$html2 = $html2.'<tr><td>'.$v.'</td><td>'.$work[$i].'</td><td>'.$price[$i].'</td></tr>';
	$summa = $summa+$price[$i];
	if(!isset($_GET['aktNumber'])){
		$add_aktswork = $connect_db->prepare("INSERT INTO `aktswork` (`number`, `user`, `fiok`, `work`, `price`, `summa`) VALUE (?,?,?,?,?,?)");
		$add_aktswork->bind_param("isssii", $number, $user, $who, $work[$i], $price[$i], $pre_sum);
		$add_aktswork->execute();
		$add_aktswork->close();
	}
};
$html3 = '</tbody></table>
<table width=783><tr><td><br>Итоговая сумма к оплате: 
'.$summa.',00 руб.<br>Сумма прописью: <b>'.num2str($summa).'</b>
</td></tr><tr><td>Работы выполнены в полном объеме, в установленные сроки и с надлежащим качеством. Стороны претензий друг к другу не имеют. </td></tr></table>
<table width=783 height=95><tr><td><br /><br />Исполнитель: ______________</td><td><br /><br />Заказчик: ______________</td></tr></table>
<br /><br /><br /><br /><br /><br />';
$html = $html1.$html2.$html3;

$number++;

if(!isset($_GET['aktNumber'])){
	$connect_db->query("UPDATE `number` SET `aktvyprab` = '$number'");
}

//echo $html;
/* Конвертим в PDF */
include("mpdf/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
$mpdf->charset_in = 'utf8'; /*не забываем про русский*/
$stylesheet = file_get_contents('print.css');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->list_indent_first_level = 0;
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
if(!isset($_GET['aktNumber'])) {
	$mpdf->Output($pdf_name, 'F');
}else {
	$mpdf->Output($pdf_name, 'D');
}
/*
I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
D: send to the browser and force a file download with the name given by filename.
F: save to a local file with the name given by filename (may include a path).
S: return the document as a string. filename is ignored.
*/
?>
<?php
if(!isset($_GET['aktNumber'])) {
	echo '<a href="../print/' . $pdf_name . '">Скачать</a>';
}
?>