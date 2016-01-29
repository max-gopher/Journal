<?
$login='oleg';
$pass='10112012';
$db='newboozilla';
$host='localhost';

// Подключение к базе
$db_connect = new mysqli($host, $user, $pass, $db);
if ($db_connect->connect_errno) {
	echo 'Не подключились';
}

// Запрос с плейсхолдерами
$db_connect->query("CREATE TABLE test (id INT)");
$stmt = $db_connect->prepare("INSERT INTO test (id) VALUE (?)");
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

//$res = $db_connect->query("SELECT id FROM test");
//var_dump($res->fetch_all());
?>