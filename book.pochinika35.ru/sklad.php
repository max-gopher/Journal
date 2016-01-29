<?php require("template/default/header.php");
if(!isset($_SESSION['login'])){
	echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="index.php" 
//--> 
</script>';
}
if($_SESSION['partner_id'] != '0') {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
require("config.php");
?>

<?php 
$user = $_SESSION['login'];
$connect_db->query("DELETE FROM `tezak` WHERE `user` = '$user'");
?>

<?php
// Просмотр склада
if(isset($_GET['list'])) {
	// Меню склада
	echo '<div style="display: inline-block">';
	echo '<div style="display: inline-block"><a class="fancybox fancybox.iframe" alt="Добавить категорию" title="Добавить категорию" href="inc/add.php?skladcat"><img width="30px" height="30px" src="img/add.jpg"></a></div>';
	echo '<div style="display: inline-block"><a class="fancybox fancybox.iframe" alt="Добавить оборудование" title="Добавить оборудование" href="inc/add.php?devsklad"><img width="30px" height="30px" src="img/devadd.jpg"></a></div>';
	echo '<div style="display: inline-block"><a class="fancybox fancybox.iframe" alt="Создать чек" title="Создать чек" href="inc/addchek.php?devsklad"><img width="30px" height="30px" src="img/add_chek.png"></a></div>';
	echo '</div>';
	
	echo '<div id="bask"></div>';
	
	echo '<table style="margin-top:10px; width:70%" border="1" cellpadding="2">';
	echo '<tbody>';
	// В цыкле формируем списки категорий и девайсов.
	$select_sklad_cat = $connect_db->query("SELECT * FROM `skladcat` ORDER BY `name` ASC");
	while($fetch_sklad_cat = $select_sklad_cat->fetch_assoc()) {
		$cat = 'category'.$fetch_sklad_cat['id'];
		$device = 'device'.$fetch_sklad_cat['id'];
		// Выводим список категорий а дальше JQuery по нажатию на категорию отобразит под ней список девайсов.
		echo '<tr><th style="cursor: pointer;" onclick="devList('.$device.')">'.$fetch_sklad_cat['name'].'</th></tr>';
		// Получаем id категории для поиска девайсов принадлежащих этой категории.
		$category = $fetch_sklad_cat['id'];
		// Печатаем начало таблицы и скрываем ее.
		echo '<tr id="'.$device.'" style="display: none; width:100%;"><td style=" width: 100%;">';
		echo '<div><div style="display: inline-block; width: 20%;">Категория</div><div style="display: inline-block; width: 40%;">Название</div><div style="display: inline-block; width: 10%;">Кол</div><div style="display: inline-block; width: 10%;">Входящая</div><div style="display: inline-block; width: 10%;">Исходящая</div><div style="display: inline-block; width: 10%;">Действия</div></div>';
		// В цыкле формируем список девайсов
		$select_sklad = $connect_db->query("SELECT * FROM `sklad` WHERE `category` = '$category' AND `kol` > '0'");
		while($fetch_sklad = $select_sklad->fetch_assoc()) {
			// Находим категорию по id и печатаем ее название
			$idcat = $fetch_sklad['category'];
			$select_cat_for_dev = $connect_db->query("SELECT `name` FROM `skladcat` WHERE `id` = '$idcat' LIMIT 1");
			$fetch_cat_for_dev = $select_cat_for_dev->fetch_assoc();
			echo '<div>';
			echo '<div style="display: inline-block; width: 20%;">'.$fetch_cat_for_dev['name'].'</div>';
			echo '<div style="display: inline-block; width: 40%;">'.$fetch_sklad['name'].'</div>';
			echo '<div style="display: inline-block; width: 10%;">'.$fetch_sklad['kol'].'</div>';
			echo '<div style="display: inline-block; width: 10%;">'.$fetch_sklad['pricevhod'].'</div>';
			echo '<div style="display: inline-block; width: 10%;">'.$fetch_sklad['priceishod'].'</div>';
			echo '<div style="display: inline-block; width: 10%;">';
			echo '<a class="fancybox fancybox.iframe" alt="Редактировать запись" title="Редактировать запись" href="inc/edit.php?devsklad='.$fetch_sklad['id'].'"><img width="24px" height="24px" src="/img/edit.jpg"></a>';
			echo '<a class="fancybox fancybox.iframe" style="margin-left:5px;" alt="Удалить запись" title="Удалить запись" href="inc/del.php?devsklad='.$fetch_sklad['id'].'"><img width="24px" height="24px" src="/img/del.png"></a>';
			echo '<a style="margin-left:5px;" alt="Добавить в товарный чек" title="Добавить в товарный чек" href="#" onclick="addToBask('.$fetch_sklad['id'].')"><img width="24px" height="24px" src="/img/add_bask.png"></a>';
			echo '</div>';
			echo '</div>';
		}
		
		echo '</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

// Категории склада
if(isset($_GET['category'])) {
	echo '<div><a class="fancybox fancybox.iframe" alt="Добавить категорию" title="Добавить категорию" href="inc/add.php?skladcat"><img width="24px" height="24px" src="img/add.jpg"></a></div>';
	echo '<table style="margin-top:10px;" border="1" cellpadding="2">';
	echo '<tbody>';
	$select_sklad_cat = $connect_db->query("SELECT * FROM `skladcat` ORDER BY `name` ASC");
	while($fetch_sklad_cat = $select_sklad_cat->fetch_assoc()) {
		echo '<tr><td>'.$fetch_sklad_cat['name'].'</td>';
		echo '<td>';
		echo '<a class="fancybox fancybox.iframe" href="inc/edit.php?skladcat='.$fetch_sklad_cat['id'].'"><img width="24px" height="24px" alt="Редактировать категорию" title="Редактировать категорию" src="img/edit.jpg"></a>';
		echo '<a class="fancybox fancybox.iframe" href="inc/del.php?skladcat='.$fetch_sklad_cat['id'].'"><img width="24px" height="24px" alt="Удалить категорию" title="Удалить категорию" src="img/del.png"></a>';
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}
// Конец категориям
?>
<?php include("template/default/footer.php"); ?>