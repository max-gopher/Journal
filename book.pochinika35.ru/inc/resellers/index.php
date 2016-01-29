<?php
$header = $_SERVER['DOCUMENT_ROOT'].'/template/default/header.php';
$config = $_SERVER['DOCUMENT_ROOT'].'/config.php';
require("$header");
include_once("$config");
if(!isset($_SESSION['login'])){
	echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/index.php" 
//--> 
</script>';
}else {
    $partner_id = $_SESSION['partner_id'];
}
// организация доступа для начальства
if(isset($_GET['resellers_id'])) {
    if($_SESSION['login'] == 'maxim' || $_SESSION['login'] == 'oleg') {
        $partner_id = $_GET['resellers_id'];
    }else {
        echo '<script language="JavaScript" type="text/javascript">
<!--
location="/index.php"
//-->
</script>';
    }
}
?>
<div class="tab-content">
    <div class="tab-pane active" id="visovi"> 
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>More</th>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>Адрес</th>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Проблема</th>
                    <th>Исполнитель</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
		        <?php select_visovi($stra, $user_visov, $partner_id); ?>
            </tbody>
        </table>  
    </div>

    <div class="tab-pane" id="master">
        <div class="master_menu">
	        <a class="btn btn-primary" title="Просмотреть что в работе" href="work">Вработе</a>
	        <a class="btn btn-primary" title="Просмотреть готовые" href="ready">Готовые</a>
	        <a class="btn btn-primary" title="Просмотреть выданные" href="finish">Выданные</a>
        </div>
        <table class="table table-striped mtop10">
	        <tbody id="tbody">
                
	        </tbody>
        </table>        
    </div>
    
    <div class="tab-pane" id="upravlenie">
        <div class="well" style="max-width: 400px; margin: 0 auto 10px;">
            <a href="edit.php" class="fancybox fancybox.iframe btn btn-default btn-lg btn-block">Личные данные</a>
            <a href="/index.php?exit" class="btn btn-success btn-lg btn-block">Выход</a>
        </div>
    </div>
</div>

<script>
    $("#master div a").click(function(e) {
        e.preventDefault();
        var which = $(this).attr("href");
        var partner_id = "<?php echo $partner_id; ?>";
        $.post("/inc/afunction.php?master", {which : which, partner_id : partner_id}, function(data){
			if(data.length > 0){
				$("#tbody").html(data);
			}
		});
    });
</script>

<?php include("../../template/default/footer.php"); ?>
