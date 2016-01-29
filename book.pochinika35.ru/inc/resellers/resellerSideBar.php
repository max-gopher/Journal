<?php
$config = $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once("$config");
?>
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 mtop10">
    <div class="my_finansi">
        <div class="my_dohod">Статистика</div>
    </div>
    <div class="partner">
        <div>Ваш партнерский номер:</div>
        <div class="bablo"><?php echo $_SESSION['partner_id'] != '0'?$_SESSION['partner_id']:$_GET['resellers_id']; ?></div>
        <div>Баланс:</div>
        <div class="bablo"><?php echo oborot($_SESSION['partner_id'] != '0'?$_SESSION['partner_id']:$_GET['resellers_id'],'0','1').' руб.'; ?></div>
        <div>Оборот:</div>
        <div><?php echo oborot($_SESSION['partner_id'] != '0'?$_SESSION['partner_id']:$_GET['resellers_id'],'1','0').' руб.'; ?></div>
    </div>
    <div class="my_finansi mtop10">
        <div class="my_dohod">История выплат</div>
    </div>
    <div class="partner">
        <ul>
            <?php
            foreach(get_viplaty($_SESSION['partner_id'] != '0'?$_SESSION['partner_id']:$_GET['resellers_id']) as $key => $val) {
                echo '<li>'.$key.' - '.$val.' руб.</li>';
            }
            ?>
        </ul>
    </div>
</div>