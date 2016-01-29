<?php
include('head.inc');
include("../config.php");
if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="index.php" 
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

include('head.inc');
?>

<?php
$title = 'Добавление в черный список';
echo $modal_title_start.$title.$modal_title_end;

?>

<?php
if(isset($_POST['idblack'])) {
	$cause = $_POST['cause'];
	$idzapisi = $_POST['id'];
	$select_zapis = $connect_db->query("SELECT * FROM `visovi` WHERE `id` = '$idzapisi' LIMIT 1");
	$fetch_zapis = $select_zapis->fetch_assoc();
	
	if(!empty($fetch_zapis['housing'])){
		$adres = $fetch_zapis['street'].' '.$fetch_zapis['home'].'-'.$fetch_zapis['housing'].'-'.$fetch_zapis['apartment'];
	}else {
		$adres = $fetch_zapis['street'].' '.$fetch_zapis['home'].'-'.$fetch_zapis['apartment'];
	}
	$tel = $fetch_zapis['fone'];
	$problemse = $fetch_zapis['problemse'];
	$debet = $fetch_zapis['money'];
	
	$add_black = $connect_db->prepare("INSERT INTO `black_list` (`adres`, `tel`, `id`, `cause`, `work`, `debt`) VALUE (?,?,?,?,?,?)");
	$add_black->bind_param("ssissi", $adres, $tel, $idzapisi, $cause, $problemse, $debet);
	$add_black->execute();
	$add_black->close();
} 

if(isset($_GET['black_visov'])) {
	echo '<div style="display:inline-block; width:49%; float:left;">';
	echo '<form action="black_list.php" method="post">';
	echo '<div><input style="width: 200px;" name="cause" required autofocus placeholder="Причина"></div>';
	echo '<div><input type="hidden" name="id" value="'.$_GET['black_visov'].'"></div>';
	echo '<div><input name="idblack" type="submit" value="Добавить"></div>';
	echo '</form>';
	echo '</div>';
	echo '<div style="display:inline-block; width:49%; float:right;">';
	echo '<table border="1">';
	echo '<tr><th colspan="2">Черный список</th></tr>';
		select_black_list();
	echo '</table>';
	echo '</div>';
}

if(isset($_GET['black'])) {
	echo '<div>';
	echo '<form action="black_list.php" method="post">';
	echo '<div><input style="width: 200px;" name="who" required placeholder="Кого?"></div>';
	echo '<div><input style="width: 200px;" name="fone" placeholder="Телефон"></div>';
	echo '<div><input style="width: 200px;" name="work" placeholder="Работы"></div>';
	echo '<div><input style="width: 200px;" name="summa" placeholder="Сумма"></div>';
	echo '<div><input style="width: 200px;" name="cause" required placeholder="За что?"></div>';
	echo '<div><input name="addblack" type="submit" value="Добавить"></div>';
	echo '</form>';
	echo '</div>';
}
if(isset($_POST['addblack'])) {
	$adres = $_POST['who'];
	$tel = $_POST['fone'];
	$work = $_POST['work'];
	$debt = $_POST['summa'];
	$cause = $_POST['cause'];
	$id = 0;
	$add_black = $connect_db->prepare("INSERT INTO `black_list` (`adres`, `tel`, `id`, `cause`, `work`, `debt`) VALUE (?,?,?,?,?,?)");
	$add_black->bind_param("ssissi", $adres, $tel, $id, $cause, $work, $debt);
	$add_black->execute();
	$add_black->close();
}
include('footer.inc'); ?>