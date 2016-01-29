<?php 
session_start();
$host="localhost";
$user_db="oleg";
$pass_db="10112012";
$name_db="newboozilla";
$login = $_SESSION['login'];
$connect_db = new mysqli("$host", "$user_db", "$pass_db", "$name_db");
if ($connect_db->connect_errno){
	echo 'Не удалось подключиться к базе.';
	exit();
}
$connect_db->query("SET NAMES UTF8");
include ('config.php');

// Реализация выхода
if(isset($_GET['exit'])) {
	session_unset();
    session_destroy();
}
// LOGOTIP
$select_logo = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_logo = $select_logo->fetch_assoc();
$logo = $fetch_logo['logo'];
// END LOGOTIP
?>
<?php
if(isset($_POST['login']) && isset($_POST['pass'])){
    $user = $_POST['login'];
    $pass = sha1($_POST['pass']);
    $get_error = $connect_db->query("SELECT * FROM `users` WHERE login = '$user'");
    $fetch_error = $get_error->fetch_assoc();
    if($fetch_error['error'] >= '3') {
        echo $fetch_error['error'];
        $login_error = '<div class="index_error"><img alt="Наша служба безопасности связалась с компетентными службами" title="Наша служба безопасности связалась с компетентными службами" src="img/fsb.jpg"><h1>Ваш аккаунт заблокирован. <a title="Написать письмо" href="mailto:info@pochinika35.ru">Свяжитесь с администратором</a></h1></div>';	
    }else{
	$get_users = $connect_db->query("SELECT * FROM `users` WHERE login='$user' AND pass='$pass'");
	if ($get_users->num_rows == 1){
            $res = $get_users->fetch_array(MYSQLI_ASSOC);	
            $_SESSION['login'] = $user;
            $_SESSION['partner_id'] = $res['partner_id'];
            if(isset($_SESSION['login']) && $_SESSION['partner_id'] == '0'){
                //echo ' Сессия '.$_SESSION['partner_id'];
                echo <<<HTML
			<script language="JavaScript" type="text/javascript">
<!-- 
location="visov.php" 
//--> 
</script>
HTML;
            }else{
                echo <<<HTML
			<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>
HTML;
            }
        }else{
            $login = $fetch_error['login'];
            $error = $fetch_error['error'];
            $error++;
            $connect_db->query("UPDATE `users` SET `error` = '$error' WHERE `login` = '$login'");
            $login_error = '<div class="index_error"><img alt="Наша служба безопасности связалась с компетентными службами" title="Наша служба безопасности связалась с компетентными службами" src="img/fsb.jpg"><h1>За Вами уже выехали!</h1></div>';	
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Журнал внутреннего учета</title>
		<meta http-equiv="Content-Language" content="ru">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link media="screen" type="text/css" href="/template/default/ball/css/bootstrap.css" rel="stylesheet">
		<link media="screen" type="text/css" href="/template/default/css/style.css" rel="stylesheet">
		<script src="/template/default/ball/js/jquery-1.11.3.js"></script>
		<script src="/template/default/ball/js/bootstrap.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="header_index">
				<?php
				if (!isset($login_error)) {
					echo '<img title="ПочиникА" alt="ПочиникА" src="'.$logo.'">';
				}else {
					echo $login_error;
				}
				?>
			</div>
			<div class="content_index">
				<form action="index.php" method="post">
					<div class="login"><input class="form-control" name="login" type="text" autofocus placeholder="Введите Ваш логин"></div>
					<div class="pass"><input class="form-control" name="pass" type="password" placeholder="Введите Ваш пароль"></div>
					<div class="submit"><input class="btn btn-default" name="submit" type="submit" value="Авторизоваться"></div>
				</form>
			</div>
		</div>
	</body>
</html>
