<?php include('head.inc'); ?>
<?php
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
$title = 'Добавление заметки';
if(isset($_POST['submit'])){
	$id = $_POST['id'];
	$group = $_POST['group'];
	$who = $_SESSION['login'];
	$text = $_POST['note'];
	
	// Ищем упыря, который оставил заметку
	$select_user_note = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$who' LIMIT 1");
	$fetch_user_note = $select_user_note->fetch_assoc();
	$user = $fetch_user_note['name'].' '.$fetch_user_note['familiya'];
	
	// Записываем заметку в базу
	$add_note = $connect_db->prepare("INSERT INTO `note` (`group`, `idwork`, `who`, `text`) VALUE (?,?,?,?)");
	$add_note->bind_param("iiss", $group, $id, $user, $text);
	$add_note->execute();
	$add_note->close();
}
?>



<?php echo $modal_title_start.$title.$modal_title_end; ?>
<div style="display:inline-block; width:49%;">
	<form action="note.php" method="post">
		<div>
			<textarea name="note" type="text" placeholder="Впишите сюда заметку" autofocus required rows="10" cols="50"></textarea>
		</div>
		<div style="margin-top: 10px;">
			<input name="submit" type="submit" value="Добавить">
			<input name="group" type="hidden" value="<?php echo $_GET['group']; ?>">
			<input name="id" type="hidden" value="<?php echo $_GET['id']; ?>">
		</div>
	</form>
</div>

<?php include('footer.inc'); ?>