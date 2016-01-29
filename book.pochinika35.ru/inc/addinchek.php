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
// к этому скрипту обращается Ajax при выборе товара в чек 
		$idtov = $_POST['addinchek'];
		$start_kol = 1;
		$user = $_SESSION['login'];
		$sn = 0;
		$garan = $_POST['garan'];
	
		$select_dev = $connect_db->query("SELECT * FROM `sklad` WHERE `id` = '$idtov' LIMIT 1");
		$fetch_dev = $select_dev->fetch_assoc();
		$name = $fetch_dev['name'];
		$price = $fetch_dev['priceishod'];
	
		// Проверяем наличие товара в базе и если есть, то инкриментируем колличество а если нет, то добавляем запись
		$select_zak_in_temp = $connect_db->query("SELECT * FROM `tezak` WHERE `idtov` = '$idtov' AND `user` = '$user'");
		$kol = $select_zak_in_temp->num_rows;
		if(!empty($kol)){
			// Инкриментируем колличество и обновляем запись
			$fetch_zak_in_temp = $select_zak_in_temp->fetch_assoc();
			$id = $fetch_zak_in_temp['id'];
			$kolichestvo = $fetch_zak_in_temp['kol'];
			$kolichestvo++;
			$connect_db->query("UPDATE `tezak` SET `kol` = '$kolichestvo' WHERE `id` = '$id'");
		}else{
			// Записываем девайс в базу
			$add_dev = $connect_db->prepare("INSERT INTO `tezak` (`idtov`, `name`, `price`, `kol`, `user`) VALUE (?,?,?,?,?)");
			$add_dev->bind_param("isiis", $idtov, $name, $price, $start_kol, $user);
			$add_dev->execute();
			$add_dev->close;
		}

		// Уменбшаем колличество
		if(isset($_POST['fromcheck'])) {
			$idtov = $_POST['fromcheck'];
			$user = $_SESSION['login'];
			$select_zak_in_temp = $connect_db->query("SELECT * FROM `tezak` WHERE `idtov` = '$idtov' AND `user` = '$user'");
			$fetch_zak_in_temp = $select_zak_in_temp->fetch_assoc();
			$kolichestvo = $fetch_zak_in_temp['kol'] - 1;
			$connect_db->query("UPDATE `tezak` SET `kol` = '$kolichestvo' WHERE `idtov` = '$idtov'");
	
		}

		// Добавляем срок гарантии
		if(isset($_POST['garan'])) {
			$idtov = $_POST['idtov'];
			$garan = $_POST['garan'];
			$user = $_SESSION['login'];
			$connect_db->query("UPDATE `tezak` SET `garan` = '$garan' WHERE `idtov` = '$idtov' AND `user` = '$user'");
		}

		// Добавляем серийник
		if(isset($_POST['sn'])) {
			$idtovsn = $_POST['idtovsn'];
			$sn = $_POST['sn'];
			$user = $_SESSION['login'];
			$connect_db->query("UPDATE `tezak` SET `sn` = '$sn' WHERE `idtov` = '$idtovsn' AND `user` = '$user'");
		}

		// Удаляем запись из базы
		if(isset($_POST['delfromcheck'])) {
			$idtov = $_POST['delfromcheck'];
			$connect_db->query("DELETE FROM `tezak` WHERE `idtov` = '$idtov'");
		}

		echo '<table border="1" cellpadding="2">';
		echo '<tbody>';
		$select_zak = $connect_db->query("SELECT * FROM `tezak` WHERE `user` = '$user'");
		while($fetch_zak = $select_zak->fetch_assoc()) {	
			echo '<tr><td>'.$fetch_zak['name'].'</td><td>'.$fetch_zak['price'].'p.</td><td>'.$fetch_zak['kol'].'</td>';
			if(!empty($fetch_zak['garan'])) {
				echo '<td>'.$fetch_zak['garan'].' мес.</td>';
			}
			if(!empty($fetch_zak['sn'])) {
				echo '<td>'.$fetch_zak['sn'].'</td>';
			}
			echo '<td><a href="#" onclick="addToBask('.$fetch_zak['idtov'].')"><img title="Добавить" alt="Добавить" width="30px" height="30px" src="../img/add.jpg"></a>';
			echo '<a href="#" onclick="fromBask('.$fetch_zak['idtov'].')"><img alt="Уменьшить" title="Уменьшить" width="30px" height="30px" src="../img/reduce.jpg"></a>';
			echo '<a href="#" onclick="open_popup(\'#modal_window'.$fetch_zak['idtov'].'\');"><img alt="Добавить срок гарантии" title="Добавить срок гарантии" width="30px" height="30px" src="../img/garan.jpg"></a>';
			echo '<div class="modal_window" id="modal_window'.$fetch_zak['idtov'].'">'; 
 			echo '<input autofocus id="garan'.$fetch_zak['idtov'].'" type="text" placeholder="Срок гарантии">';
			echo '<input type="submit" value="Сохранить" onclick="addAndCloseGaran('.$fetch_zak['idtov'].');">'; 
			echo '</div>';
			echo '<div id="background"></div>';
			echo '<a href="#" onclick="open_popup(\'#modal_window_sn'.$fetch_zak['idtov'].'\');"><img alt="Добавить серийный номер" title="Добавить серийный номер" width="30px" height="30px" src="../img/sn.jpg"></a>';
			echo '<div class="modal_window" id="modal_window_sn'.$fetch_zak['idtov'].'">'; 
 			echo '<input autofocus id="sn'.$fetch_zak['idtov'].'" type="text" placeholder="Серийный номер">';
			echo '<input type="submit" value="Сохранить" onclick="addAndCloseSn('.$fetch_zak['idtov'].');">'; 
			echo '</div>';
			echo '<a href="#" onclick="delFromBask('.$fetch_zak['idtov'].')"><img alt="Удалить позицию" title="Удалить позицию" width="30px" height="30px" src="../img/del.png"></a></td></tr>';

		}
		echo '</tbody></table>';

?>