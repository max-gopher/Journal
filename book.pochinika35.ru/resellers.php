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

//$balans = oborot($partner, '0', '1');
if ($_SESSION['login'] == 'maxim'){
    $www = select_users('partner', $_SESSION['partner_id']);
    //var_dump($www);
}

?>
<table class="table table-striped mtop10">
	<tbody>
		<tr>
		    <th>Номер</th>
		    <th>Ф.И.О. партнера</th>
		    <th>Телефон</th>
		    <th>E-mail</th>
		    <th>Баланс</th>
		    <th>Действия</th>
		</tr>
		<?php
        $select_users = select_users('partner', $_SESSION['partner_id']);
        while($fetch_users = $select_users->fetch_assoc()) {
            $fio = $fetch_users['familiya'].' '.$fetch_users['name'].' '.$fetch_users['otchestvo'];
            echo '<tr><td>'.$fetch_users['partner_id'].'</td><td>'.$fio.'</td><td>'.$fetch_users['telefon'].'</td><td>'.$fetch_users['email'].'</td>';
            echo '<td>'.oborot($fetch_users['partner_id'], $vsego='0', $ostatok='1').'</td>
            <td><a title="Выплаты" class="fancybox fancybox.iframe" href="/inc/resellers/viplaty.php?id='.$fetch_users['id'].'"><span class="glyphicon glyphicon-usd"></span></a>
            <a title="Клиенты от реселлера" href="/inc/resellers/index.php?resellers_id='.$fetch_users['partner_id'].'"><span class="glyphicon glyphicon-th-list"></span></a></td></tr>';
        }
        ?>
	</tbody>
</table>

<?php include("template/default/footer.php"); ?>