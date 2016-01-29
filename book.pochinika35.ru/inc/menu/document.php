<?php
include('../head.inc');
include("../../config.php");
if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="../../index.php" 
//--> 
</script>	
HTML;
}
?>
<?php 
// Определяем пользователя
$user = $_SESSION['login'];

// Определяем тайтл для страницы
$title = 'Создание документа';

?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
<div class="row popap">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="max-width450 mtop10">
			<select class="form-control" id="namedoc">
				<option value="0">Выберите документ</option>
				<option value="aktrab">Акт выполненных работ</option>
				<option value="prihodnik">Приходный ордер</option>
			</select>
		</div>
		<div id="aktrab" data="1" class="aktrab max-width450">
			<form method="post">
				<div class="btn btn-primary mtop10" onclick="addFields()">Добавить</div>
				<div><input class="form-control" type="hidden" name="user" value="<?php echo $user; ?>"></div>
				<div class="mtop10"><input class="form-control" type="text" name="komu" placeholder="Кому"></div>
				<div id="filds">
					<div class="mtop10">
						<input class="form-control" type="text" name="raboty_0" placeholder="Выполненные работы">
						<input class="form-control" type="text" name="kol_0" placeholder="Количество">
						<input class="form-control" type="text" name="cena_0" placeholder="Цена">
					</div>
				</div>
				<span class="mtop10 btn btn-success" onclick="submitAktrab()">Оформить</span>
			</form>
		</div>
		<div id="prihodnik" data="1" style="display:none;">
			Test
		</div>
		<div class="mtop10" style="display:none;" id="r"><span style="color:red;font-size:20px;">Идет формирование документа. Подождите.</span></div>
	</div>
</div>

<?php include('../footer.inc'); ?>