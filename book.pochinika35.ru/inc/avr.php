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
$title = 'Распечатать квитанции';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>

<?php $select_ing = 'Выберите инженера'; ?>

<form action="/inc/print/invoice.php" method="post">
	<div style="margin-top: 10px;">
		<select name="engineer">
			<option><?php echo $select_ing; ?></option>
			<?php select_ing(); ?>
		</select>
	</div>
	<div style="margin-top: 10px;">
		<input name="number" type="text" placeholder="Количество квитанций">
	</div>
	<div style="margin-top: 10px;">
		<input name="avr" type="submit" value="Сгенерировать">
	</div>
</form>

<?php include('footer.inc'); ?>