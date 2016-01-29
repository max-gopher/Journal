<?php include($_SERVER['DOCUMENT_ROOT'].'/inc/head.inc'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/config.php'); ?>
<?php if(!isset($_SESSION['login'])){
	echo <<<HTML
		<script language="JavaScript" type="text/javascript">
<!-- 
location="/index.php" 
//--> 
</script>	
HTML;
}
if(empty($_SERVER['HTTP_REFERER'])) {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
?>
<?php
if(isset($_SESSION['partner_id'])) {
	$partner_id = $_SESSION['partner_id'];
	$select_user = $connect_db->query("SELECT * FROM `users` WHERE `partner_id` = '$partner_id'");
	$fetch_user = $select_user->fetch_assoc();
}
$title = 'Редактирование личных данных';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>
	<div class="row popap">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="mtop10 gborder">
				<div class="subtitle">Поля редактирования</div>
				<div class="pad0555">
					<form action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/admin/user.php" method="post">
						<div class="mtop10">
							<div>
								Логин:
							</div>
							<input class="form-control" required name="editlogin" type="text" value="<?php echo $fetch_user['login'] ?>">
						</div>
						<div class="mtop10">
						    <div>
							    Пароль (минимум 8 символов):
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
				<div class="subtitle">Информация</div>
				<div class="pad0555">
					<div class="mperimetr mtop10">
						<ul><li>Если вы не хотите менять пароль, то просто оставьте это поле пустым</li></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>