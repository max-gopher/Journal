<?php
include('head.inc');
include("../config.php");
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
?>

<?php 

// Определяем тайтл для страницы
$title = 'Формирование товарного чека и гарантии';

?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
<?php
$user = $_SESSION['login'];
$select_zakaz = $connect_db->query("SELECT * FROM `tezak` WHERE `user` = '$user'");
$kol = $select_zakaz->num_rows;
$kol_with_garan = 0;
while($fetch_zakaz = $select_zakaz->fetch_assoc()) {
	if(!empty($fetch_zakaz['garan'])) {
		$kol_with_garan++;
	}
}
if(!empty($kol)) {
		echo '<div>';
		echo '<form action="print/invoice.php" method="post">';
		echo '<div><input type="text" name="who" placeholder="Кому" ></div>';
		echo '<div><input type="text" name="akt" placeholder="Акт выполненных работ"></div>';
		echo '<div><input name="addchek" type="submit" value="Сформировать чек"></div>';
		echo '</form>';
		echo '</div>';
		if($kol_with_garan > 0) {
			echo '<div>';
			echo '<form action="print/invoice.php" method="post">';
			echo '<div><input type="text" name="who" placeholder="Кому" ></div>';
			echo '<div><input type="text" name="akt" placeholder="Акт выполненных работ"></div>';
			echo '<div><input name="addgaran" type="submit" value="Сформировть гарантийный чек"></div>';
			echo '</form>';
			echo '</div>';
		}
}else{
	echo 'Вы ничего не добавили в чек. Закройте это окно и добавьте оборудование в чек.';
}
?>
<?php include('footer.inc'); ?>