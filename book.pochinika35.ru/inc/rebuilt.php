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
if(isset($_GET['id']) && isset($_POST['problemse']) && isset($_POST['money'])) {
	$idwork = $_GET['id'];
	$problemse = $_POST['problemse'];
	$money = $_POST['money'];
	$status = 3;
	
	$select_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$idwork' LIMIT 1");
	$fetch_work = $select_work->fetch_assoc();
	if(!empty($fetch_work['sale'])) {
		$sumsale = $money-($money/100*$fetch_work['sale']);
	}else{
		$sumsale = $money;
	}
	if(!empty($_POST['garan'])) {
		$garan = $_POST['garan'];
	}else {
		$garan = '0';
	}
	$koplate = $sumsale;
	// Обновляем запись
	$connect_db->query("UPDATE `work` SET `money` = '$money', `problemse` = '$problemse', `garan` = '$garan', `status` = '$status', `sumsale` = '$sumsale', `koplate` = '$koplate' WHERE `id` = '$idwork'");
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

<?php 
$title = 'Отметить отремонтированным';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
<?php
if(isset($_GET['id']) && !isset($_POST['submit'])){
	$idwork = $_GET['id'];
	$select_work = $connect_db->query("SELECT * FROM `work` WHERE `id` = '$idwork' LIMIT 1");
	$fetch_work = $select_work->fetch_assoc();
	if(!empty($fetch_work['money'])) {
		$oldmoney = $fetch_work['money'];
	}else{
		$oldmoney = "";
	}
	if(!empty($fetch_work['garan'])) {
		$oldgaran = $fetch_work['garan'];
	}else {
		$oldgaran = "";
	}
	if(!empty($fetch_work['problemse'])) {
		$oldproblemse = $fetch_work['problemse'];
	}else {
		$oldproblemse = "";
	}
}
?>

<div class="row popap">
	<form action="rebuilt.php" method="post">
		<div>
			<textarea name="problemse" class="form-control" type="text" placeholder="Опешите проведенные работы" autofocus required rows="5" cols="50"><?php echo $oldproblemse; ?></textarea>
		</div>
		<div>
			<input name="garan" class="form-control" value="<?php echo $oldgaran; ?>" type="text" required placeholder="Срок гарантии">
		</div>
		<div>
			<input name="money" class="form-control" value="<?php echo $oldmoney; ?>" type="text" required placeholder="Сумма без скидки">
		</div>
		<div>
			<button name="submit" class="btn btn-success" formaction="rebuilt.php?id=<?php echo $_GET['id']; ?>">Сохранить</button>
		</div>
	</form>
</div>

<?php include('footer.inc'); ?>