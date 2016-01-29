<?php require($_SERVER['DOCUMENT_ROOT']."/template/default/header.php");
include ("numtostr.php");
if(!isset($_SESSION['login'])){
	echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="'.$_SERVER['DOCUMENT_ROOT'].'index.php" 
//--> 
</script>';
}
require($_SERVER['DOCUMENT_ROOT']."/config.php");
$modal_title_start = '<div style="width: 100%; background: rgba(117, 192, 0, 0.7); color: #fff;"><center><h2 style="padding: 5px; margin: 5px 0 0 0;">';
$modal_title_end = '</h2></center></div>';
?>
<?php
echo '<div><a class="fancybox fancybox.iframe" href="http://'.$_SERVER['HTTP_HOST'].'/inc/add.php?addprintform"><img width="30px" height="30px" src="http://'.$_SERVER['HTTP_HOST'].'/img/add.jpg" title="Добавить печатную форму" alt="Добавить печатную форму"></a></div>';

$nomer = 1;
$today = (date("d.m.Y"));
$who = 'Мне';
$sum = 1000;
$summa = $sum.',00 руб.<br><b>'.num2str($sum).'</b>';
$select_form = $connect_db->query("SELECT * FROM `form` WHERE `id_s` = 'akt_vip_rab'");
while ($fetch_form = $select_form->fetch_assoc()) {
	
	// Рисуем форму
	echo $modal_title_start.$fetch_form['name'].$modal_title_end;
	echo '<div class="edit-form">';
	echo '<div class="buttom-form">
	<a class="fancybox fancybox.iframe" href="http://'.$_SERVER['HTTP_HOST'].'/inc/edit.php?editaktviprab?id_s='.$fetch_form['id_s'].'"><img width="30px" height="30px" src="http://'.$_SERVER['HTTP_HOST'].'/img/edit.jpg" title="Редактировать форму" alt="Редактировать форму"></a>';
	echo '</div>';
	echo '<div class="form">';
	
	$head = stripslashes($fetch_form['head']); //Удаляем экранирующие слеши
	// Замена шорткодов
	$search = array('#logo#', '#nomer#', '#today#', '#who#', '#summa#'); // Кого меняем
	$replace = array($logo, $nomer, $today, $who, $summa); // На что меняем
	$head = str_replace($search, $replace, $head); // Выполняем замену
	echo $head;
	
	$footer = stripslashes($fetch_form['footer']); //Удаляем экранирующие слеши
	// Замена шорткодов
	$footer = str_replace($search, $replace, $footer); // Выполняем замену
	echo $footer;
	
	echo '</div>';
	echo '</div>';
}

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/template/default/footer.php"); ?>