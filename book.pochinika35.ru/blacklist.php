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

echo '<div style="display: inline-block"><a class="fancybox fancybox.iframe" alt="Добавить в черный список" title="Добавить в черный список" href="inc/black_list.php?black"><img width="30px" height="30px" src="img/add.jpg"></a></div>';
echo '<table style="margin-top:10px;" border="1" cellpadding="2">';
echo '<tbody>';
echo '<tr>';
echo '<th>Адрес</th>';
echo '<th>Телефон</th>';
echo '<th>ID</th>';
echo '<th>Причина</th>';
echo '<th>Работы</th>';
echo '<th>Сумма</th>';
echo '<th>Дата</th>';
echo '<th>Действия</th>';
echo '</tr>';
	select_black_list('basic');
echo '</tbody>';
echo '</table>';

include("template/default/footer.php");
?>