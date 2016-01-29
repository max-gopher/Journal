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
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Журнал внутреннего учета</title>
		<meta http-equiv="Content-Language" content="ru">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/css/bootstrap.css" rel="stylesheet">
		<link type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/css/bootstrap-datepicker3.css" rel="stylesheet">
		<link type="text/css" href="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/css/bootstrap-clockpicker.css" rel="stylesheet">
		<link type="text/css" href="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/css/bootstrap-select.css" rel="stylesheet">
		<link type="text/css" href="<?= $SERVER['HTTP_HOST'] ?>/template/default/css/style.css" rel="stylesheet">
		<?php
		//if(isset($_GET['visov']) || isset($_GET['needJQ'])){}
		?>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/js/bootstrap.js"></script>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/js/bootstrap-clockpicker.js"></script>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/js/bootstrap-select.js"></script>
		<script type="text/javascript" src="<?= $SERVER['HTTP_HOST'] ?>/template/default/ball/locales/bootstrap-datepicker.ru.min.js"></script>
		<script src="<?= $SERVER['HTTP_HOST'] ?>/js/jquery.easing.1.3.js"></script>
		<script src="<?= $SERVER['HTTP_HOST'] ?>/js/smallwindow.js"></script>
		<script src="<?= $SERVER['HTTP_HOST'] ?>/js/checkbox.js"></script>
		<script>
			// Отображение выбранного документа для создания /inc/menu/document.php
			$(document).ready(function () {
				$('#namedoc').change(function() {
					var dock = $('#namedoc').val();
					if (dock != 0) {
						$('div[data=1]').css("display","none");
						$('#'+ dock).css("display","block");
					}else {	
						$('#aktrab').css("display","none");
					}
				});
				$('#sandbox-container .input-group.date').datepicker({
					language: "ru",
					multidate: false,
					autoclose: true,
					todayHighlight: true,
					format: "yyyy-mm-dd",
					minuteStepping:10
				});
				$('.clockpicker').clockpicker({
					autoclose: true
				});
				// определение откуда клиент из vk или из журнала
				if($("#selectfio").val() == "vk") {
					$("#fio input").removeAttr("required");
					$("#fio input").val("");
					$("#fio").css("display", "none");
					$("#vkfio select").attr("required", true);
					$("#vkfio").css("display", "block");
				}else{
					$("#vkfio select").removeAttr("required");
					$("#vkfio select option").removeAttr("selected");
					$("#vkfio").css("display", "none");
					//$("#fio input").attr("required", true);
					$("#fio").css("display", "block");
				}
				// при Выборе откуда будет клиент
				$("#selectfio").change(function(){
					if($(this).val() == "vk") {
						$("#fio input").removeAttr("required");
						$("#fio input").val("");
						$("#fio").css("display", "none");
						$("#vkfio select").attr("required", true);
						$("#vkfio").css("display", "block");
						
					}else{
						$("#vkfio select").removeAttr("required");
						$("#vkfio select option").removeAttr("selected");
						$("#vkfio").css("display", "none");
						$("#fio input").attr("required", true);
						$("#fio").css("display", "block");
					}
				});
				// При выборе организации для создания счета
				$("select[name='firm']").change(function(){
					var firm = $("select[name='firm']").val();
					$.post("<?= $SERVER['HTTP_HOST'] ?>/inc/afunction.php?schet", {firm : firm} , function(data){
					if(data.length>0){
						$("#r").html(data);
					}
					// Выбор чекбоксов для счета
					var chkSelector = 'tr td:nth-child(1) :checkbox';
					
					$('#checker').click(function() {
						$('#celebs ' + chkSelector)
						.prop('checked', $(this).prop('checked'));	
					});
					
					$('#celebs ' + chkSelector).click(function(e) {
						var $table = $(this).parents('table');
						var lastRow = $table.data('lastRow');
						var thisRow = $(this).parents('tr').index();
						
						if (lastRow !== undefined && e.shiftKey) {
							var numChecked = 0;
							var start = lastRow < thisRow ? lastRow : thisRow;
							var end = lastRow > thisRow ? lastRow : thisRow;
						$table
						.find(chkSelector)
						.slice(start, end)
						.prop('checked', true);
    					}
    					$table.data('lastRow', thisRow);
					});
				}); // Конец выбора организации
				}); // Конец непойми чего, но без него ошибка!))))
				
				// При отмечании вызова исполненным
				$('#cert').change(function(){
					var vid = $("input[name='vid']").val();
					useCertForVisov(vid);
				});
				
				// При вводе ФИО клиента
				$("input[name='namek']").keyup(function(){
					var fio = $("input[name='namek']").val();
					if(fio != "") {
					$.ajax({
						type: "POST",
						url: "<?= $SERVER['HTTP_HOST'] ?>/inc/afunction.php?aselect",
						data: {"fio": fio},
						success: function(response){
							if(response != "" && response != "<div>/inc/afunction.php?aselect</div>"){
								$("#presult").css("display", "block");
								$("#result").html(response);
								//alert(response.toString());
							}else if(response == "" || response == "<div>/inc/afunction.php?aselect</div>"){
								$("#presult").css("display", "none");
							}
						}
					});
					}
					console.log($('#result').html());
					//alert("Test");
					// Если совпадений не найдено
					if(fio == "") {
						$("#presult").css("display", "none");
					}
				});
				$('#result').on('click', 'li', function(){
  					//console.log($(this).text());
					$("input[name='namek']").val($(this).text());
					$("#presult").css("display", "none");
				});
				// При потере фокуса скрываем подсказки ФИО
				/*$("input[name='namek']").blur(function(){
					if ($("#result li").click()) {
						console.log(this);
						//$("#presult").css("display", "none");
					}else{
						$("#presult").css("display", "none");
						//console.log(this);
					}
				});*/
			}); // Конец READY
			//Функция добавления новых полей для выполненных работ и цены в акте выполненных работ
			function addFields() {
				var prep = $('#filds div:last input:last').attr("name");
				var prepi = prep.split('_');
				var i = prepi[1];
				i++;
				$('#filds').append('<div class="mtop10"><input class="form-control" type="text" name="raboty_'+i+'" placeholder="Выполненные работы"><input class="form-control" type="text" name="kol_'+i+'" placeholder="Количество"><input class="form-control" type="text" name="cena_'+i+'" placeholder="Цена"></div>');
			}
			//Функция добавления новых полей для выполненных работ, количества и цены при отмечании вызова исполненным
			function addFieldsVisov() {
				var prep = $('#filds div:last input:last').attr("name");
				var prepi = prep.split('_');
				var i = prepi[1];
				i++;
				$('#filds').append('<div class="mtop10"><input class="form-control" type="text" name="raboty_'+i+'" placeholder="Выполненные работы"><input class="form-control" oninput="prechek()" type="text" name="kol_'+i+'" placeholder="Количество"><input class="form-control" oninput="prechek()" type="text" name="cena_'+i+'" placeholder="Цена"><input class="form-control" name="summa_'+i+'" disabled type="text" placeholder="Сумма"></div>');
			}
			
			function submitAktrab() {
				var work = new Array();
				var kol = new Array();
				var price = new Array();
				var prep = $('#filds div:last input:last').attr("name");
				var prepi = prep.split('_');
				var i = prepi[1];
				var user = $('input[name="user"]').val();
				var who = $('input[name="komu"]').val();
				for(var s = 0; s <= i; s++) {
					work.push($('input[name="raboty_'+s+'"]').val());
					kol.push($('input[name="kol_'+s+'"]').val());
					price.push($('input[name="cena_'+s+'"]').val());
				}
				$("#r").css("display", "block");
				$.post("<?= $SERVER['HTTP_HOST'] ?>/inc/print/akt_vip_rab.php", {who : who, work : work, price : price, user : user, kol : kol} , function(data){
					if(data.length>0){
						$("#r").html(data);
					}
				});
			}
		</script>
		<script>
			// Возможность добавления нового аппарата и производителя в форме оформления мастерской
			function newEl(){
				document.getElementById('selectdevice').style.display="none";
				document.getElementById('newdevice').style.display="block";
			}
			function newBrend() {
				document.getElementById('selectbrend').style.display="none";
				document.getElementById('newbrend').style.display="block";
			}
		</script>
		<script>
			// Выбор юриков и физиков для оформления вызова
			function fisik(){
				$('#select').css("display","none");
				$('#fisik').css("display","block");
			}
			function yurik(){
				$('#select').css("display","none");
				$('#urik').css("display","block");
			}
		</script>
		<script>
			function genSchet() {
				var idVisov = [];
				var idRaboty = [];
				var money = [];
				$( ':checkbox:checked[data-rid]' ).each(function(){
					idRaboty.push($(this).attr("data-rid"));
					//idVisov.push($(this).attr("data-vid"));
					idVisov.push($($($($(this).parent()).parent()).children("td[data-vid]")).attr("data-vid"));
					//money.push($(this).attr("data-money"));
					money.push($($($($(this).parent()).parent()).children("td[data-money]")).attr("data-money"));
				});
				//console.log(idRaboty);
				//console.log(idVisov);
				//console.log(money);
				var org = $('select[name="firm"]').val();
				$.post("<?= $SERVER['HTTP_HOST'] ?>/inc/afunction.php?schet_write", {idVisov : idVisov, idRaboty : idRaboty, org : org, money : money} , function(data){
					if(data.length>0){
						$("#r").html(data);
					}
				});
			}
		</script>
	</head>
	<body>
		<div class="container-fluid">
			<?php
				// Формирование тайтлов для страниц
				$modal_title_start = '<div class="row pagetitle"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">';
				$modal_title_end = '</div></div>';
			?>