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
$title = 'Добавление записи';
if(isset($_GET['skladcat'])) {
	$title = 'Добавление котегории склада';
}
if(isset($_GET['devsklad'])) {
	$title = 'Добавление оборудования на склада';
}
if(isset($_GET['visov'])){
	$title = 'Оформление нового вызова';
}
if(isset($_GET['firm'])) {
	$title = 'Добавление контрагента';
}
if(isset($_GET['addprintform'])) {
	$title = 'Добавление новой печатной формы';	
}
$base_time = date('H:i');
$base_date = date('Y-m-d');
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>

<?php
// Наченается тело файла. Этот файл занимается обработкой новых вызовов, новой работой (принятие аппарата на ремонт) и так далее.

$select_ing = 'Выберите инженера';
$select_st = 'Выберите статус';
$select_urik = 'Выберите организацию';

// Формируем тело страницы. Данная страница будет отображать и обрабатывать все, что мы будем добавлять в журнал.

// Склад
// Форма Довавление категори склада
if(isset($_GET['skladcat'])) {
	echo '<div>';
		echo '<form action="add.php" method="post">';
			echo '<div>';
				echo '<input name="newcat" required type="text" placeholder="Новая категория">';
			echo '</div>';
			echo '<div>';
				echo '<input name="addcat" type="submit" value="Добавить">';
			echo '</div>';
		echo '</form>';
	echo '</div>';
}
// Обработка данных полученных из формы добавления категории
if(isset($_POST['addcat'])) {
	$newcat = $_POST['newcat'];
	$add_cat = $connect_db->prepare("INSERT INTO `skladcat` (`name`) VALUE (?)");
	$add_cat->bind_param("s", $newcat);
	$add_cat->execute();
	if(!$add_cat) {
		echo 'Ошибочка вышла: запесь не сохранена в базу.';
	}else {
		echo 'Категоия "'.$newcat.'" добавленна в базу.';
	}
	$add_cat->close();
}

// Форма Добавление девайсов на склад
if(isset($_GET['devsklad'])) {
	$defcat = 'Выберите категорию';
	echo '<div>';
		echo '<form action="add.php" method="post">';
			echo '<div style="margin-top: 10px;">';
				echo '<select name="category" required>';
					echo '<option>'.$defcat.'</option>';
					select_cat();
				echo '</select>';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="name" required type="text" placeholder="Новое оборудование">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="kol" required type="text" placeholder="Количество">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="pricevhod" required type="text" placeholder="Входящая цена">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="priceishod" required type="text" placeholder="Исходящая цена">';
			echo '</div>';
			echo '<div style="margin-top: 10px;">';
				echo '<input name="adddev" type="submit" value="Добавить">';
			echo '</div>';
		echo '</form>';
	echo '</div>';
}
// Обработка данных полученных из формы добавления девайсов на склад
if(isset($_POST['adddev'])) {
	$name = $_POST['name'];
	$category = $_POST['category'];
	$kol = $_POST['kol'];
	$pricevhod = $_POST['pricevhod'];
	$priceishod = $_POST['priceishod'];
	
	// Подготавливаем категорию. Из формы получаем строку и по ней находим id категории
	$select_cat = $connect_db->query("SELECT `id` FROM `skladcat` WHERE `name` = '$category' LIMIT 1");
	$fetch_cat = $select_cat->fetch_assoc();
	$category = $fetch_cat['id'];
	
	// Записываем девайс в базу
	$add_dev = $connect_db->prepare("INSERT INTO `sklad` (`name`, `category`, `kol`, `pricevhod`, `priceishod`) VALUE (?,?,?,?,?)");
	$add_dev->bind_param("siiii", $name, $category, $kol, $pricevhod, $priceishod);
	$add_dev->execute();
	if(!$add_dev) {
		echo 'Ошибочка вышла: запесь не сохранена в базу.';
	}else {
		echo 'Запись "'.$name.'" добавленна в базу.';
	}
	$add_dev->close;
}
// Конец Склада

// Вызовы. Ночало.
// Форма для оформления нового вызова
if(isset($_GET['visov'])) {
	echo '<div class="row popap">';
	echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
	echo '<form action="add.php" method="post">';
    
    echo '<div style="margin-top: 10px;">';
	echo '<select class="form-control" name="partner">'; 
	echo '<option value="0">Выберите партнера</option>';
				select_users('no', 'yes');
	echo '</select>';
	echo '</div>';
    
	echo '<div class="mtop10" id="sandbox-container"><div class="input-group date">
		<input type="text" name="data" class="form-control" required placeholder="Дата вызова" value="'.$base_date.'" ><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
	</div></div>';
	echo '<div class="input-group clockpicker mtop10">
		<input type="text" name="time" required class="form-control" value="'.$base_time.'">
		<span class="input-group-addon">
			<span class="glyphicon glyphicon-time"></span>
		</span>
	</div>';
	echo '<div id="select" style="display:inline-block; margin-top: 10px;"><span style="cursor:pointer;" onclick="fisik()">Физ.лицо</span><span style="margin-left:10px; cursor:pointer;" onclick="yurik()">Организация</span></div>';
	echo '<div id="fisik" style="display:none;">';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="street" type="text" placeholder="Введите улицу"></div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="home" type="text" placeholder="№ дома"></div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="housing" type="text" placeholder="Корпус"></div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="apartament" type="text" placeholder="Квартира"></div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="fone" type="text" placeholder="Телефон"></div>';
	echo '<div style="margin-top: 10px;">
				<select name="vkgroup" class="form-control" id="selectfio">
					<option>Состоит ли в группе vk.com</option>
					<option value="book">Не состоит</option>
					<option value="vk">Да, состоит</option>
				</select>
			</div>';
	echo '<div id="fio" style="margin-top: 10px;">
				<input AUTOCOMPLETE="off" class="form-control" name="namek" type="text" placeholder="Ф.И.О.">
				<div id="presult" style="display: none;" class="btn-group bootstrap-select open">
					<div style="max-height: 210px; overflow: hidden; min-height: 134px; top: -2px;" class="dropdown-menu open">
						<ul id="result" style="max-height: 156px; overflow-y: auto; min-height: 80px; padding: 5px;" class="dropdown-menu inner" role="menu">
						</ul>
					</div>
				</div>
			</div>
			<div id="vkfio" style="margin-top: 10px;">
				<select data-live-search="true" name="vknamek" style="display:none;" class="form-control selectpicker bs-select-hidden">
					<option value="0">Выберите участника</option>';
					select_vkusers();
	echo'</select></div>';
	echo '</div>';
	echo '<div id="urik" style="display:none; margin-top: 10px;">';
	echo '<select class="form-control" name="urik">';
	echo '<option value="0">'.$select_urik.'</option>';
				select_urik();
	echo '</select>';
	echo '</div>';
	echo '<div style="margin-top: 10px;"><input class="form-control" name="problemsk" type="text" required placeholder="Проблема заявленная клиетом"></div>';
	echo '<div style="margin-top: 10px;">';
	echo '<select class="form-control" name="engineer" required>'; 
	echo '<option>'.$select_ing.'</option>';
				select_ing();
	echo '</select>';
	echo '</div>';
	echo '<div style="margin-top: 10px;">';
	echo '<select class="form-control" name="status" required>';
	echo '<option>'.$select_st.'</option>';
				select_status_visov();
	echo '</select>';
	echo '</div>';
	echo '<div style="margin-top: 10px;"><input class="btn btn-success" name="visov" type="submit" value="Оформить"></div>';
	echo '</form>';
	echo '</div>';
	echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">';
	echo '<table class="table table-striped">';
	echo '<thead class="thead-center"><tr><th colspan="2">Черный список</th></tr></thead><tbody>';
		select_black_list('visov');
	echo '</tbody></table>';
	echo '</div>';
	echo '</div>';
}
// Обработка данных полученных из формы нового вызова
if(isset($_POST['visov'])) {
	$partner = $_POST['partner'];
    $data = $_POST['data'];
	$time = $_POST['time'];
	// Если выбран юрик
	if(!empty($_POST['urik'])) {
		if($_POST['urik'] != "0") {
			$id_firm = $_POST['urik'];
			$select_firm = $connect_db->query("SELECT * FROM `firms` WHERE `id` = '$id_firm' LIMIT 1");
			$fetch_firm = $select_firm->fetch_assoc();
			// Разбиваем строку по запятой и сохраняем в массив
			$adress = $fetch_firm['fadress'];
			//var_dump($adress);
			$fone = $fetch_firm['fone'];
			$namek = $fetch_firm['firm'];
			$sale = 0;
			$vkgroup = 0;
		}else{
			echo 'Не выбранна организация';
		}	
	// Если выбран физик
	}else{
		$street = $_POST['street'];
		$home = $_POST['home'];
		$housing = $_POST['housing'];
		$apartament = $_POST['apartament'];
		$fone = $_POST['fone'];
		if(!empty($_POST['vknamek'])) {
			$namek = $_POST['vknamek'];
			$sale = 10;
			$vkgroup = 1;
		}else{
			$namek = $_POST['namek'];
			$sale = 0;
			$vkgroup = 0;
		}
	}
	$problemsk = $_POST['problemsk'];
	$engineer = $_POST['engineer'];
	$status = $_POST['status'];
	// Если не выбран сотрудник
	if($engineer == $select_ing) {
		$userror = '<div style="width:100%; color:red;"><center><h4>Вы не выбрали инженера</h4></center></div>';
	}
	// Если не выбран статус
	elseif($engineer == $select_st) {
		$userror = '<div style="width:100%; color:red;"><center><h4>Вы не выбрали статус</h4></center></div>';
	}
	// Если все заполненно корректно
	else {
		//echo 'Data: '.$data.'<br>Time: '.$time.'<br>Street: '.$street.'<br>Home: '.$home.'<br>Housing: '.$housing.'<br>Apartament: '.$apartament.'<br>Sdress: '.$adress.'<br>Phone: '.$fone.'<br>Namek: '.$namek.'<br>Problemsk: '.$problemsk.'<br>Engineer: '.$engineer.'<br>Status: '.$status.'<br>Vkgroup: '.$vkgroup.'<br>Sale: '.$sale.'<br>';
		// Если пустая переменная $adress, тогда записываем физика
		if(empty($adress)) {
			$stmt = $connect_db->prepare("INSERT INTO visovi (`partner`,`dateforengineer`, `timeforengineer`, `street`, `home`, `housing`, `apartment`, `fone`, `namek`, `problemsk`, `engineer`, `status`, `vkgroup`, `sale`) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param("isssssssssssii", $partner, $data, $time, $street, $home, $housing, $apartament, $fone, $namek, $problemsk, $engineer, $status, $vkgroup, $sale);
			$stmt->execute();
			$stmt->close();
		}else { // Иначе записываем юрика
			$stmt = $connect_db->prepare("INSERT INTO visovi (`partner`, `dateforengineer`, `timeforengineer`, `adress`, `fone`, `namek`, `problemsk`, `engineer`, `status`, `vkgroup`, `sale`) VALUE (?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param("issssssssii", $partner, $data, $time, $adress, $fone, $namek, $problemsk, $engineer, $status, $vkgroup, $sale);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	echo 'Вызов добавлен';
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
// Вызовы. Конец.

// Контрагенты. Начало.
// Форма для добавления контрагента
if(isset($_GET['firm'])) {
	
	// Обработка данных полученных из формы добавления контрагента
	if(isset($_POST['firmadd'])) {
		$firm = str_replace('"', '&quot;', $_POST['firm']);
		$fone = $_POST['fone'];
		$email = $_POST['email'];
		$uindex = $_POST['uindex'];
		$uadress = str_replace('"', '&quot;', $_POST['uadress']);
		$fadress = str_replace('"', '&quot;', $_POST['fadress']);
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
		$osnov = $_POST['osnov'];
		$dogovor = $_POST['dogovor'];
	
		$add_firm = $connect_db->prepare("INSERT INTO firms (`firm`, `fone`, `email`, `uindex`, `uadress`, `fadress`, `inn`, `kpp`, `oktmo`, `bank`, `rs`, `ks`, `bik`, `fdir`, `idir`, `odir`, `osnov`, `dogovor`) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		if(!empty($add_firm->param_count)) {
			$add_firm->bind_param("ssssssssssssssssss", $firm, $fone, $email, $uindex, $uadress, $fadress, $inn, $kpp, $oktmo, $bank, $rs, $ks, $bik, $fdir, $idir, $odir, $osnov, $dogovor);
			$add_firm->execute();
			$add_firm->close();
			echo <<<HTML
			<script language="JavaScript" type="text/javascript">
				<!-- 
					setTimeout(function () {
						parent.jQuery.fancybox.close();
					}, 1000); 
				//--> 
			</script>	
HTML;
		}else {
			$error_mesg = '<p class="bg-danger">Произошла ошибка. Попробуйте повторить попытку. Если ошибка будет сохраняться, сообщите системному администратору.</p>';
			$add_firm->close();
		}
	}
	// Конец обработки данных полученных из формы добавления контрагента
	
	$select_pravform = 'Выберите правовую форму';
	echo '<div class="row popap">';
	echo $error_mesg;
	echo '<form action="add.php?firm" method="post">';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Организация</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Наименование:</label>
			<input class="form-control" name="firm" type="text">
		</div>';
	echo '<div class="mtop10 form-group">
			<label>ИНН:</label>
			<input class="form-control" name="inn" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>КПП:</label>
			<input class="form-control" name="kpp" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>ОКТМО:</label>
			<input class="form-control" name="oktmo" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Банк:</label>
			<input class="form-control" name="bank" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Расчетный счет:</label>
			<input class="form-control" name="rs" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Корреспонденский счет:</label>
			<input class="form-control" name="ks" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>БИК:</label>
			<input class="form-control" name="bik" type="text"></div>';
	echo '</div></div></div>';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Контакты</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Телефон:</label>
			<input class="form-control" name="fone" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>E-mail:</label>
			<input class="form-control" name="email" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Юр. Индекс:</label>
			<input class="form-control" name="uindex" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Юр. Адрес:</label>
			<input class="form-control" name="uadress" type="text"></div>';
	echo '<div class="mtop10 form-group">
			<label>Фактический адрес:</label>
			<input class="form-control" name="fadress" type="text"></div>';
	echo '</div></div></div>';
	echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">';
	echo '<div class="mtop10 gborder">
				<div class="subtitle">Директор</div>
				<div class="pad0555">';
	echo '<div class="mtop10 form-group">
			<label>Фамилия:</label>
			<input class="form-control" name="fdir" type="text">';
	echo '<div class="mtop10 form-group">
			<label>Имя:</label>
			<input class="form-control" name="idir" type="text">';
	echo '<div class="mtop10 form-group">
			<label>Отчество:</label>
			<input class="form-control" name="odir" type="text">';
	echo '<div class="mtop10 form-group">
			<label>На основании:</label>
			<input class="form-control" name="osnov" type="text">';
	echo '<div class="mtop10 form-group">
			<label>Договор:</label>
			<input class="form-control" name="dogovor" type="text">';
	echo '<div class="mtop10"><input class="btn btn-success btn-block" name="firmadd" type="submit" value="Добавить"></div>';
	echo '</div></div></div>';
	echo '</form>';
	echo '</div>';
}
// Контрагенты. Конец.
// =================================================================================================================
// Начало добавления новой печатрой формы
// Форма добавления новой печатной формы
if(isset($_GET['addprintform'])) {
	echo '<div>
		<form action="add.php" method="post">
			<div><input type="text" name="id_s" placeholder="Идентификатор формы"></div>
			<div><input type="text" name="name" placeholder="Название формы"></div>
			<div><textarea rows="15" cols="100" name="head" placeholder="Начало формы"></textarea></div>
			<div><textarea rows="15" cols="100" name="footer" placeholder="Конец формы"></textarea></div>
			<div><input type="submit" name="saveaktviprab" value="Добавить"></div>
		</form>
	</div>';
}
// Конец формы добавления новой печатной формы
// Обработка данных полученных от формы добавления новой печатной формы
if(isset($_POST['saveaktviprab'])) {
	$id_s = $_POST['id_s'];
	$name = $_POST['name'];
	$head = $_POST['head'];
	$footer = $_POST['footer'];
	
	$add_form = $connect_db->prepare("INSERT INTO form (`id_s`, `name`, `head`, `footer`) VALUE (?,?,?,?)");
	$add_form->bind_param("ssss", $id_s, $name, $head, $footer);
	$add_form->execute();
	$add_form->close();
	
	echo 'Форма добавлена';
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
// Конец добавления новой печатной формы
?>

<?php
// Подключаем footer
include('footer.inc');
?>