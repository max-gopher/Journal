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
?>

<?php 

// Определяем тайтл для страницы
$title = 'Редактировать запись';
if(isset($_GET['skladcat'])) {
	$title = 'Редактирование котегории склада';
}
if(isset($_GET['editaktviprab'])) {
	$title = 'Редактировать форму акта выполненных работ';
}
if(isset($_GET['firm']) || $_POST['idfirm']) {
	$title = 'Редактирование контрагента';
}
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>

<?php
// Формируем тело страницы

// Начало склада
// Форма Редактирование категорий склада
if(isset($_GET['skladcat'])) {
	$idcat = $_GET['skladcat'];
	$select_cat = $connect_db->query("SELECT * FROM `skladcat` WHERE `id` = '$idcat' LIMIT 1");
	$fetch_cat = $select_cat->fetch_assoc();
	
	echo '<div>';
		echo '<form action="edit.php" method="post">';
			echo '<div>';
				echo '<input name="cat" required type="text" value="'.$fetch_cat['name'].'">';
			echo '</div>';
			echo '<input name="idcat" type="hidden" value="'.$fetch_cat['id'].'">';
			echo '<div>';
				echo '<input name="editcat" type="submit" value="Добавить">';
			echo '</div>';
		echo '</form>';
	echo '</div>';
}
// Обработка данных полученных из формы редактирования категорий
if(isset($_POST['editcat'])) {
	$cat = $_POST['cat'];
	$idcat = $_POST['idcat'];
	$connect_db->query("UPDATE `skladcat` SET `name` = '$cat' WHERE `id` = '$idcat'");
}
// Форма Редактирования Девайсов на складе
if(isset($_GET['devsklad'])) {
	$iddev = $_GET['devsklad'];
	$select_dev = $connect_db->query("SELECT * FROM `sklad` WHERE `id` = '$iddev' LIMIT 1");
	$fetch_dev = $select_dev->fetch_assoc();
	
	// Получаем название категории по ее id
	$categoryid = $fetch_dev['category'];
	$select_cat = $connect_db->query("SELECT `name` FROM `skladcat` WHERE `id` = '$categoryid' LIMIT 1");
	$fetch_cat = $select_cat->fetch_assoc();
	
	echo '<div>';
		echo '<form action="edit.php" method="post">';
			echo '<div style="margin-top: 10px;">';
				echo '<select name="category" required>';
					echo '<option>'.$fetch_cat['name'].'</option>';
					select_cat();
				echo '</select>';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="name" required type="text" value="'.$fetch_dev['name'].'">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="kol" required type="text" value="'.$fetch_dev['kol'].'">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="pricevhod" required type="text" value="'.$fetch_dev['pricevhod'].'">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="priceishod" required type="text" value="'.$fetch_dev['priceishod'].'">';
			echo '</div>';
			echo '<input name="iddev" type="hidden" value="'.$fetch_dev['id'].'">';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="adddev" type="submit" value="Сохранить">';
			echo '</div>';
		echo '</form>';
	echo '</div>';
}
// Обработка данных полученных из формы редактирования девайсов на складе
if(isset($_POST['adddev'])) {
	$iddev = $_POST['iddev'];
	$category = $_POST['category'];
	$name = $_POST['name'];
	$kol = $_POST['kol'];
	$pricevhod = $_POST['pricevhod'];
	$priceishod = $_POST['priceishod'];
	
	// Получаем id каткгории по её названию
	$select_cat = $connect_db->query("SELECT `id` FROM `skladcat` WHERE `name` = '$category' LIMIT 1");
	$fetch_cat = $select_cat->fetch_assoc();
	$category = $fetch_cat['id'];
	
	$connect_db->query("UPDATE `sklad` SET `name` = '$name', `category` = '$category', `kol` = '$kol', `pricevhod` = '$pricevhod', `priceishod` = '$priceishod' WHERE `id` = '$iddev'");
}

// Форма редактирования записи черного списка
if(isset($_GET['black'])) {
	$idblack = $_GET['black'];
	
	$select_black = $connect_db->query("SELECT * FROM `black_list` WHERE `id` = '$idblack' LIMIT 1");
	$fetch_black = $select_black->fetch_assoc();
	
	echo '<div>';
	echo '<form action="edit.php" method="post">';
	echo '<div><input style="width: 200px;" name="who" required placeholder="Кого?" value="'.$fetch_black['adres'].'"></div>';
	echo '<div><input style="width: 200px;" name="fone" placeholder="Телефон" value="'.$fetch_black['fone'].'"></div>';
	echo '<div><input style="width: 200px;" name="work" placeholder="Работы" value="'.$fetch_black['work'].'"></div>';
	echo '<div><input style="width: 200px;" name="summa" placeholder="Сумма" value="'.$fetch_black['debt'].'"></div>';
	echo '<div><input style="width: 200px;" name="cause" required placeholder="За что?" value="'.$fetch_black['cause'].'"></div>';
	echo '<input name="idblack" type="hidden" value="'.$idblack.'">';
	echo '<div><input name="editblack" type="submit" value="Добавить"></div>';
	echo '</form>';
	echo '</div>';
}
//
if(isset($_POST['editblack'])) {
	$idblack = $_POST['idblack'];
	$adres = $fetch_black['adres'];
	$fone = $fetch_black['fone'];
	$work = $fetch_black['work'];
	$debt = $fetch_black['debt'];
	$cause = $fetch_black['cause'];
	
	$connect_db->query("UPDATE `black_list` SET `adres` = '$adres', `fone` = '$fone', `cause` = '$cause', `work` = 'work', `debt` = '$debt' WHERE `id` = '$idblack'");
}

// Начало формы акта выполненных работ
if(isset($_GET['editaktviprab'])) {
	
}
// Конец формы акта выполненных работ

// Начало контрагентов
// Начало формы редактирования контрагента
if(isset($_GET['firm']) || isset($_POST['idfirm'])) {	
	// Оюработка данных из формы редактирования контрагентов
	if(isset($_POST['idfirm'])) {
		$idfirm = $_POST['idfirm'];
		$firm = str_replace('"', '&quot;', $_POST['firm']);
		$fone = $_POST['fone'];
		$email = $_POST['email'];
		$uindex = $_POST['uindex'];
		$uadress = $_POST['uadress'];
		$fadress = $_POST['fadress'];
		$inn = $_POST['inn'];
		$kpp = $_POST['kpp'];
		$oktmo = $_POST['oktmo'];
		$bank = str_replace('"', '&quot;', $_POST['bank']);
		$rs = $_POST['rs'];
		$ks = $_POST['ks'];
		$bik = $_POST['bik'];
		$fdir = $_POST['fdir'];
		$idir = $_POST['idir'];
		$odir = $_POST['odir'];
		$dogovor = $_POST['dogovor'];
		$osnov = $_POST['osnov'];
		$status = $connect_db->query("UPDATE `firms` SET `firm` = '$firm', `fone` = '$fone', `email` = '$email', `uindex` = '$uindex', `uadress` = '$uadress', `fadress` = '$fadress', `inn` = '$inn', `kpp` = '$kpp', `oktmo` = '$oktmo', `bank` = '$bank', `rs` = '$rs', `ks` = '$ks', `bik` = '$bik', `fdir` = '$fdir', `idir` = '$idir', `odir` = '$odir', `osnov` = '$osnov', `dogovor` = '$dogovor' WHERE `id` = '$idfirm'");
		if(empty($status)) {
			$error_mesg = '<p class="bg-danger">Произошла ошибка. Попробуйте повторить попытку. Если ошибка будет сохраняться, сообщите системному администратору.</p>';
		}else{
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
	}
// Конец обработке данных из формы редактирования контрагентов
// Начало формы редактирования контрагента
	if(isset($_GET['firm'])) {
		$idfirm = $_GET['firm'];
		$select_firm = $connect_db->query("SELECT * FROM `firms` WHERE `id` = '$idfirm' LIMIT 1");
		$fetch_firm = $select_firm->fetch_assoc();
	}
	
	echo '<div class="row popap">';
	echo $error_mesg;
	echo '<form action="edit.php?firm='.$idfirm.'" method="post">';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Организация</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Наименование:</label>
			<input class="form-control" name="firm" type="text" value="'.str_replace('"', '&quot;', $fetch_firm['firm']).'">
		</div>';
	echo '<div class="mtop10 form-group">
			<label>ИНН:</label>
			<input class="form-control" name="inn" type="text" value="'.$fetch_firm['inn'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>КПП:</label>
			<input class="form-control" name="kpp" type="text" value="'.$fetch_firm['kpp'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>ОКТМО:</label>
			<input class="form-control" name="oktmo" type="text" value="'.$fetch_firm['oktmo'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Банк:</label>
			<input class="form-control" name="bank" type="text" value="'.str_replace('"', '&quot;', $fetch_firm['bank']).'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Расчетный счет:</label>
			<input class="form-control" name="rs" type="text" value="'.$fetch_firm['rs'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Корреспондентский счет:</label>
			<input class="form-control" name="ks" type="text" value="'.$fetch_firm['ks'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>БИК:</label>
			<input class="form-control" name="bik" type="text" value="'.$fetch_firm['bik'].'"></div>';
	echo '</div></div></div>';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Контакты</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Телефон:</label>
			<input class="form-control" name="fone" type="text" value="'.$fetch_firm['fone'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>E-mail:</label>
			<input class="form-control" name="email" type="text" value="'.$fetch_firm['email'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Юр. Индекс:</label>
			<input class="form-control" name="uindex" type="text" value="'.$fetch_firm['uindex'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Юр. Адрес:</label>
			<input class="form-control" name="uadress" type="text" value="'.$fetch_firm['uadress'].'"></div>';
	echo '<div class="mtop10 form-group">
			<label>Фактический адрес:</label>
			<input class="form-control" name="fadress" type="text" value="'.$fetch_firm['fadress'].'"></div>';
	echo '</div></div></div>';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Директор</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Фамилия:</label>
			<input class="form-control" name="fdir" type="text" value="'.$fetch_firm['fdir'].'">';
	echo '<div class="mtop10 form-group">
			<label>Имя:</label>
			<input class="form-control" name="idir" type="text" value="'.$fetch_firm['idir'].'">';
	echo '<div class="mtop10 form-group">
			<label>Отчество:</label>
			<input class="form-control" name="odir" type="text" value="'.$fetch_firm['odir'].'">';
	echo '<div class="mtop10 form-group">
			<label>На основании:</label>
			<input class="form-control" name="osnov" type="text" value="'.$fetch_firm['osnov'].'">';
	echo '<div class="mtop10 form-group">
			<label>Договор:</label>
			<input class="form-control" name="dogovor" type="text" value="'.$fetch_firm['dogovor'].'">';
	echo '<div class="mtop10"><input class="form-control" name="idfirm" type="hidden" value="'.$idfirm.'">';
	echo '<div class="mtop10"><input class="btn btn-success btn-block" name="firmadd" type="submit" value="Сохранить"></div>';
	echo '</div></div></div>';
	echo '</form>';
	echo '</div>';
}
// Конец формы редактирования контрагента
// Конец контрагенты
?>
	
<?php include('footer.inc'); ?>