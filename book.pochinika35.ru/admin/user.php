<?php include('../inc/head.inc'); ?>
<?php include('../config.php'); ?>
<?php if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="/index.php" 
//--> 
</script>	
HTML;
}
?>
<?php
// Запись нового пользователя
if(isset($_POST['addlogin'])) {
	$addlogin = $_POST['addlogin'];
	$addpass = sha1($_POST['addpassword']);
	$addname = $_POST['addname'];
	$addfamiliya = $_POST['addfamiliya'];
	$addotchestvo = $_POST['addotchestvo'];
	$adddoljnost = $_POST['adddoljnost'];
	$addtelefon = $_POST['addtelefon'];
	$addemail = $_POST['addemail'];
    if($adddoljnost == 'Реселлер') {
        $addPartnerId  = newPartnerId();
    }else{
        $addPartnerId  = '0';
    }
	$stmt = $connect_db->prepare("INSERT INTO users (`login`, `pass`, `name`, `familiya`, `otchestvo`, `partner_id`, `doljnost`, `telefon`, `email`) VALUE (?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssisss", $addlogin, $addpass, $addname, $addfamiliya, $addotchestvo, $addPartnerId, $adddoljnost, $addtelefon, $addemail);
	$stmt->execute();
	$stmt->close();
}
// Конец записи

// Обновление пользователя
if(isset($_POST['editid'])) {
	$editid = $_POST['editid'];
	$select_userid = $connect_db->query("SELECT * FROM users WHERE `id` = '$editid'");
	$fetch_userid = $select_userid->fetch_assoc();
	$editlogin = $_POST['editlogin'];
	$editpassword = $_POST['editpassword'];
	$editname = $_POST['editname'];
	$editfamiliya = $_POST['editfamiliya'];
	$editotchestvo = $_POST['editotchestvo'];
    $editdoljnost = $_POST['editdoljnost'];
    if($editdoljnost == 'Реселлер') {
        $addPartnerId  = newPartnerId();
    }else{
        $addPartnerId  = '0';
    }
	$edittelefon = $_POST['edittelefon'];
	$editemail = $_POST['editemail'];
	
	if(strlen($_POST['editpassword']) < 8) {
		$editpasswordfetch = $fetch_userid['pass'];
		$connect_db->query("UPDATE users SET `login` = '$editlogin', `pass` = '$editpasswordfetch', `name` =  '$editname', `familiya` = '$editfamiliya', `otchestvo` = '$editotchestvo', `doljnost` = '$editdoljnost', `telefon` = '$edittelefon', `email` = '$editemail' WHERE `id` = '$editid'");
	}else {
		$editpassword = sha1($editpassword);
		$connect_db->query("UPDATE users SET `login` = '$editlogin', `pass` = '$editpassword', `name` =  '$editname', `familiya` = '$editfamiliya', `otchestvo` = '$editotchestvo', `doljnost` = '$editdoljnost', `telefon` = '$edittelefon', `email` = '$editemail' WHERE `id` = '$editid'");
	}
    if($_SESSION['partner_id'] != '0') {
        echo '<div style="text-align:center;" class="h1">Изменения сохранены</div>';
        echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
setTimeout(function () {
parent.jQuery.fancybox.close();
}, 1000); 
//--> 
</script>	
HTML;
        exit;
    }
}
if($_SESSION['partner_id'] != '0') {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
// Конец редактирования
$title = 'Управление пользователями';
?>
	<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Создание нового пользователя</div>
				<div class="pad0555">
					<form action="user.php" method="post">
						<div class="mtop10"><input class="form-control" required name="addlogin" type="text" placeholder="Введите логин"></div>
						<div class="mtop10"><input class="form-control" required name="addpassword" type="password" placeholder="Введите пароль"></div>
						<div class="mtop10"><input class="form-control" required name="addname" type="text" placeholder="Введите имя"></div>
						<div class="mtop10"><input class="form-control" required name="addfamiliya" type="text" placeholder="Введите фамилию"></div>
						<div class="mtop10"><input class="form-control" required name="addotchestvo" type="text" placeholder="Введите отчество"></div>
						<div class="mtop10">
							<select class="form-control" name="adddoljnost" required>
								<?php select_doljnost_option($userid = 0);  ?>
							</select>
						</div>
						<div class="mtop10"><input class="form-control" required name="addtelefon" type="tel" placeholder="Введите телефон"></div>
						<div class="mtop10"><input class="form-control" required name="addemail" type="email" placeholder="Введите email"></div>
						<div class="mtop10"><input class="btn btn-success btn-block" name="submit" type="submit" value="Добавить"></div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Существующие пользователи</div>
				<div class="pad0555">
					<table class="table table-striped mperimetr mtop10">
						<?php select_users(); ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>