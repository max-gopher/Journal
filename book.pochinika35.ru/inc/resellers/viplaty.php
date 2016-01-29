<?php
if(isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
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
require_once $_SERVER['DOCUMENT_ROOT'].'/inc/head.inc';
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

if(isset($_POST['add'])) {
	$summa = $_POST['summa'];
	$id = $_POST['id'];
	//var_dump($id);
	$select_partner = select_users('partner', $id);
	$fetch_partner = $select_partner->fetch_assoc();
	$partner_id = $fetch_partner['partner_id'];
	//var_dump($partner_id);
	$stmt = $connect_db->prepare("INSERT INTO `viplaty` (`partner_id`,`summa`) VALUE (?,?)");
	$stmt->bind_param("ss", $partner_id, $summa);
	$stmt->execute();
	$stmt->close();
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
<div class="container-fluid">
	<div class="row pagetitle"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">Оформление выплаты</div></div>
	<div class="row popap">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<form action="./viplaty.php" method="post">
				<input type="text" name="summa" placeholder="Сумма выплаты">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="submit" name="add" value="Отправить">
			</form>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<?php
			$select_partner = select_users('partner', $id);
			$fetch_partner = $select_partner->fetch_assoc();
			$get_viplaty = get_viplaty($fetch_partner['partner_id']);
			if(is_array($get_viplaty)) {
				foreach($get_viplaty as $key => $val) {
					?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Дата</th>
						<th>Сумма</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $key; ?></td>
						<td><?php echo $val; ?> руб.</td>
					</tr>
				</tbody>
			</table>
					<?php
				}
			}else {
				echo $get_viplaty;
			}
			?>
		</div>
	</div>
</div>
