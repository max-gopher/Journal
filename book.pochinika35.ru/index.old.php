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
	unset($_SESSION['login']);
}
// LOGOTIP
$select_logo = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_logo = $select_logo->fetch_assoc();
$logo = $fetch_logo['logo'];
// END LOGOTIP
?>

<?php
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
echo '<html>';
echo '<head>';
echo '<title>Журнал внутреннего учета</title>';
echo '<meta http-equiv="Content-Language" content="ru">';
echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">';
echo '<style type="text/css">';
echo 'html{background-color:#ccc;height:100%;}';
echo 'body{width:800px;height: 900px;margin:0px auto 0px auto;background-color:#fff;border:#bbb solid 2px}';
echo 'input:focus{outline:none;}';
echo '.header{margin:20px;}';
echo '.header img{margin:0 0 0 0;}';
echo '.content{width:100%;margin-top:20px;}';
echo '.content form{width:100%;margin-top:20px;}';
echo '.login{width:201px;margin:0 auto 10px auto;}';
echo '.login input{width:200px;border-radius:10px;padding:5px;}';
echo '.login input:focus{border:1px solid green;box-shadow:0 0 10px green;}';
echo '.pass{width:201px;margin:0 auto 10px auto;}';
echo '.pass input{width:200px;border-radius:10px;padding:5px;}';
echo '.pass input:focus{border:1px solid green;box-shadow:0 0 10px green;}';
echo '.submit{width:100px;margin:0 auto;}';
echo '.submit input{padding:5px;background:#2a416d;color:#fff;border-radius:10px;}';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<div class="header"><center><img style="width: 300px" title="ПочиникА" alt="ПочиникА" src="'.$logo.'"></center></div>';

if(isset($_POST['login']) && isset($_POST['pass'])){
	$user = $_POST['login'];
	$pass = sha1($_POST['pass']);
	$get_users = $connect_db->query("SELECT * FROM `users` WHERE login='$user' AND pass='$pass'");
	if ($get_users->num_rows == 1){
		$res = $get_users->fetch_array(MYSQLI_ASSOC);	
			$_SESSION['login'] = $user;
		if(isset($_SESSION['login'])){
			//echo ' Сессия '.$_SESSION['login'];
			echo <<<HTML
			<script language="JavaScript" type="text/javascript">
<!-- 
location="visov.php" 
//--> 
</script>
HTML;
		}
			
	}else{
		echo '<center><img alt="Наша служба безопасности связалась с компетентными службами" title="Наша служба безопасности связалась с компетентными службами" src="img/fsb.jpg"><h1>За Вами уже выехали!</h1></center>';	
	}
}

echo '<div class="content">';
echo '<form action="index.php" method="post">';
echo '<div class="login"><input name="login" type="text" autofocus placeholder="Введите Ваш логин"></div>';
echo '<div class="pass"><input name="pass" type="password" placeholder="Введите Ваш пароль"></div>';
echo '<div class="submit"><input name="submit" type="submit" value="Авторизоваться"</div>';
echo '</form>';
echo '</div>';
echo '</body>';
echo '</html>';
?>