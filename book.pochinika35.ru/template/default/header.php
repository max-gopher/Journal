<?php
session_start();
/* Блок для header.php */
/* Для подключения к базе */
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
$seleck_fiouser = $connect_db->query("SELECT * FROM users WHERE login = '$login'");
$fiousers = $seleck_fiouser->fetch_array(MYSQLI_ASSOC);
$fiousers_name = $fiousers["name"];
$fiousers_ot = $fiousers["otchestvo"];
$fiouser = $fiousers_name.' '.$fiousers_ot.'!';
// LOGOTIP
$select_logo = $connect_db->query("SELECT * FROM `settings` LIMIT 1"); 
$fetch_logo = $select_logo->fetch_assoc();
$logo = $fetch_logo['logo'];
// END LOGOTIP
/*if($_SESSION['partner_id'] == '0000000000' && isset($_GET['resellers_id'])) {
	$_SESSION['partner_id'] = $_GET['resellers_id'];
}*/
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
<title>Журнал внутреннего учета</title>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/ball/css/bootstrap.css" rel="stylesheet">
<link type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link media="screen" type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/css/style.css" rel="stylesheet">
<link media="screen" type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/css/menu.css" rel="stylesheet">
<link type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/fancybox/jquery.fancybox.css" rel="stylesheet">
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/ball/js/jquery-1.11.3.js"></script>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/ball/js/bootstrap.js"></script>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/template/default/ball/js/tab.js"></script>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script> -->
<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/js/jquery.easing.1.3.js"></script>
<script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/fancybox/jquery.fancybox.pack.js"></script>
<script>
$(document).ready(function() {
	$('.fancybox').fancybox({
		width : '70%',
		height : '70%',
		title: false,
		autoSize : false,
		afterClose  : function() { 
        	location.reload();
        }
	});// конец fancybox
	
	$('.fancyboxNotReload').fancybox({
		width : '70%',
		height : '70%',
		title: false,
		autoSize : false,
		/*afterClose:function(){location.reload();}*/
	});// конец fancybox
    
    /*$('.fancybox, .fancyboxNotReload').click(function(e){
        e.preventDefault();
        var sessionLogin = '<?php echo $_SESSION['login']; ?>';
        var path = $(this).attr("href");
        function rel() { 
        	location.reload();
        }
        var wreload;
        if($(this).hasClass("fancyboxNotReload")) {
            wreload = "";
        }else {
            wreload = rel;
        }
        $.ajax({
            url: "/inc/afunction.php?testses",
            type: "POST",
            dataType: "text",
            data: {sessionLogin : sessionLogin},
            success: function(data) {
                if(data == "1") {
                    $.fancybox.open([{href:path, type:'iframe', afterClose:wreload, autoSize:false, title:false, width:'70%', height:'70%'}]);
                }else {
                   location="index.php"; 
                }
            }
        });
            //$.fancybox.open([{href:path, type:'iframe', afterClose:reload, autoSize:false, title:false, width:'70%', height:'70%'}]);
//            location="index.php"
    })*/
	
	// Проверка пользователя для архива документов
	// Обработка данных при загрузке
	var user = $("#arhiv_select_users").val();
	var doc = $("#arhiv_select_document").val();
	$.post("/inc/afunction.php?arhiv", {user : user, doc : doc} , function(data){
		if(data.length > 0){
			$("#arhive_table").html(data);
		}
	});
	// Обработка данных при выборе пользователя
	$("#arhiv_select_users").change(function(){
		var user = $("#arhiv_select_users").val();
		var doc = $("#arhiv_select_document").val();
		$.post("/inc/afunction.php?arhiv", {user : user, doc : doc} , function(data){
			if(data.length > 0){
				$("#arhive_table").html(data);
			}
		});	
	}); // Конец выборки архива по пользователю
	$("#arhiv_select_document").change(function(){
		var user = $("#arhiv_select_users").val();
		var doc = $("#arhiv_select_document").val();
		$.post("/inc/afunction.php?arhiv", {user : user, doc : doc}, function(data){
			if(data.length > 0){
				$("#arhive_table").html(data);
			}
		});
	});
}); // конец  ready
</script>

<script>
	// Отображаем и прячем девайсы на складе в списке наменклатуры
	function devList(dev){
		if($(dev).css('display') == 'none') {
			$(dev).css("display","table-row");
		}else{
			$(dev).css("display","none");
		}
	}
	
	// Ajax для добавления комплектующих в чек
	function addToBask(addinchek){
		$.post("/inc/addinchek.php", {addinchek : addinchek}, function(data){
			if(data.length>0){
				$("#bask").html(data);
			}
		})
	}
	// Уменьшить колличество в корзине
	function fromBask(fromcheck){
		$.post("/inc/addinchek.php?frombask", {fromcheck : fromcheck}, function(data){
			if(data.length>0){
				$("#bask").html(data);
			}
		})
	}
	
	// Удаляем позицию из заказа
	function delFromBask(delfromcheck){
		$.post("/inc/addinchek.php?delfrombask", {delfromcheck : delfromcheck}, function(data){
			if(data.length>0){
				$("#bask").html(data);
			}
		})
	}
	
	// Помечаем счет оплаченным
	function oplachen(id) {
		$.post("/inc/afunction.php?oplacheno", {id: id}, function(data){
			if(data.length>0){
				$("#scheta").html(data);
			}
		})
	}
</script>

</head>
<body>	
	<?php if ($fiousers["doljnost"] == 'Администратор' || $fiousers["doljnost"] == 'Старший инженер') {
	include($_SERVER['DOCUMENT_ROOT'].'/admin/toolbar.php');
	$mtop = ' mtop55';
	}else{
		$mtop = '';
	} ?>
	<div class="container-fluid<?php echo $mtop; ?>">
		<!--<header>-->
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
					<div class="logo"><img class="img-responsive" alt="logo" title="logo" src=" <?php echo $logo; ?> "></div>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
					<div class="welcom">
					    <p>Здравствуйте,<? echo ' '.$fiouser ?></p>
						<div class="multisearch">
							<form action="result-search.php" method="post">
								<input class="form-control" type="text" name="multisearch" placeholder="Поиск: Спроси меня (не реализован)">
							</form>
						</div>
					</div>
					<?php
					//var_dump($_SESSION['login']);
                        $pathTemplate = $_SERVER['DOCUMENT_ROOT'].'/template/default/';
                        if($_SESSION['partner_id'] == '0' && !isset($_GET['resellers_id'])) {
                            $pathTemplate .= 'menu.php';
                            require("$pathTemplate");
                        }else if(($_SESSION['login'] == 'maxim' || $_SESSION['login'] == 'oleg') && isset($_GET['resellers_id'])) {
							$pathTemplateM .= 'menu.php';
							require("$pathTemplateM");
							$pathTemplateT .= 'tabs.php';
							require("$pathTemplateT");
						}else{
                            $pathTemplate .= 'tabs.php';
                            require("$pathTemplate");
                        }
                    ?>
				</div>
			</div>
		<!--</header>-->
		<div class="row">
		<?php
        $pathTemplate = $_SERVER['DOCUMENT_ROOT'].'/template/default/';
		if($_SESSION['partner_id'] == '0' && !isset($_GET['resellers_id'])) {
            $pathTemplate .= 'leftSideBar.php';
            require("$pathTemplate");
        }else{
            $pathSide = $_SERVER['DOCUMENT_ROOT'].'/inc/resellers/resellerSideBar.php';
            require("$pathSide");
        }
        ?>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">