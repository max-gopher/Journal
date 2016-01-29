<?php include('../inc/head.inc'); ?>
<?php include('../config.php'); ?>
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
?>
<?php
if(isset($_GET['id'])) {
	$userid = $_GET['id'];
	$select_user = $connect_db->query("SELECT * FROM users WHERE `id` = '$userid'");
	$fetch_user = $select_user->fetch_assoc();
}
$title = 'Редактирование пользователя';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Поля редактирования</div>
				<div class="pad0555">
					<form action="user.php" method="post">
						<div class="mtop10">
							<div>
								Логин:
							</div>
							<input class="form-control" required name="editlogin" type="text" value="<?php echo $fetch_user['login'] ?>">
						</div>
						<div class="mtop10">
						<div>
							Пароль:
						</div>
						<input class="form-control" name="editpassword" type="password" value="" placeholder="Введите пароль">
					</div>
						<div class="mtop10">
						<div>
							Имя:
						</div>
						<input class="form-control" required name="editname" type="text" value="<?php echo $fetch_user['name'] ?>">
					</div>
						<div class="mtop10">
						<div>
							Фамилия:
						</div>
						<input class="form-control" required name="editfamiliya" type="text" value="<?php echo $fetch_user['familiya'] ?>">
					</div>
						<div class="mtop10">
						<div>
							Отчество:
						</div>
						<input class="form-control" required name="editotchestvo" type="text" value="<?php echo $fetch_user['otchestvo'] ?>">
					</div>
						<div class="mtop10">
						<div>
							Должность:
						</div>
						<select class="form-control" name="editdoljnost" required>
							<?php select_doljnost_option($userid);  ?>
						</select>
					</div>
						<div class="mtop10">
						<div>
							Телефон:
						</div>
						<input class="form-control" required name="edittelefon" type="tel" value="<?php echo $fetch_user['telefon'] ?>">
					</div>
						<div class="mtop10">
						<div>
							E-mail:
						</div>
						<input class="form-control" required name="editemail" type="email" value="<?php echo $fetch_user['email'] ?>">
					</div>
						<input type="hidden" name="editid" value="<?php echo $fetch_user['id'] ?>"> 
						<div class="mtop10"><input class="btn btn-success btn-block" name="submit" type="submit" value="Сохранить"></div>
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