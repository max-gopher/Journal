<?php 
include('../head.inc');
include('../../config.php'); ?>
<?php if(!isset($_SESSION['login'])){
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
if(!isset($_GET['edit'])) {
	$title = 'Создание счета';
}
echo $modal_title_start.$title.$modal_title_end;
?>
<div class="row popap">
	<form action="adaptation" method="post">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="mtop10">
				<select data-live-search="true" name="firm" class="form-control selectpicker bs-select-hidden">
					<option value="0">Выберите организацию</option>
					<?php select_urik(); ?>
				</select>
			</div>
			<div id="r"></div>
		</div>
	</form>
</div>
<?php 
// Подключаем footer
include('../footer.inc');
?>