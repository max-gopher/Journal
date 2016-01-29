<?php
include('head.inc');
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
if($_SESSION['partner_id'] != '0') {
    echo '<script language="JavaScript" type="text/javascript">
<!-- 
location="/inc/resellers/index.php" 
//--> 
</script>';
}
?>

<?php
$idv = $_GET['id'];
if(isset($_POST['money']) && isset($_POST['kvitancia'])) {
	$idv = $_POST['id'];
	$moneyv = $_POST['money'];
	$kvitanciav = $_POST['kvitancia'];
	$idcert = $_POST['idcert'];
	$koplate = $_POST['koplate'];
	$statusv = 'Исполнено';
	$razdel = 'visovi';
	$work = $_POST['work'];
	$kol = $_POST['kol'];
	$price = $_POST['price'];
	$select_visov = $connect_db->query("SELECT * FROM `visovi` WHERE `id` = '$idv' LIMIT 1");
	$fetch_visov = $select_visov->fetch_assoc();
	if($fetch_visov['vkgroup'] = 1) {
		$sumsale = $moneyv-($moneyv/100*$fetch_visov['sale']);
	}else{
		$sumsale = $moneyv;
	}
	if(!empty($idcert)) {
		$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `idcert` = '$idcert' LIMIT 1");
		if($select_cert->num_rows == 0) {
			$koplate = $sumsale;
			$connect_db->query("UPDATE `visovi` SET `status` = '$statusv', `kvitancia` = '$kvitanciav', `money` = '$moneyv', `idcert` = '$idcert', `sumsale` = '$sumsale', `koplate` = '$koplate' WHERE `id` = '$idv'");
		}else {
			$fetch_cert = $select_cert->fetch_assoc();
			if($fetch_cert['ostatok'] >= $sumsale) {
				$ostatok = $fetch_cert['ostatok'] - $sumsale;
				$connect_db->query("UPDATE `cert` SET `ostatok` = '$ostatok' WHERE `idcert` = '$idcert'");
				$koplate = 0;
				$connect_db->query("UPDATE `visovi` SET `status` = '$statusv', `kvitancia` = '$kvitanciav', `money` = '$moneyv', `idcert` = '$idcert', `sumsale` = '$sumsale', `koplate` = '$koplate' WHERE `id` = '$idv'");
			}
			if($fetch_cert['ostatok'] < $sumsale) {
				$koplate = $sumsale - $fetch_cert['ostatok'];
				$ostatok = 0;
				$connect_db->query("UPDATE `cert` SET `ostatok` = '$ostatok' WHERE `idcert` = '$idcert'");
				$connect_db->query("UPDATE `visovi` SET `status` = '$statusv', `kvitancia` = '$kvitanciav', `money` = '$moneyv', `idcert` = '$idcert', `sumsale` = '$sumsale', `koplate` = '$koplate' WHERE `id` = '$idv'");
			}
		}
	}else {
		$koplate = $sumsale;
		//echo 'Статус: '.$statusv.'<br>';
		echo 'Статус: '.$idcert.'<br>';
		$connect_db->query("UPDATE `visovi` SET `status` = '$statusv', `kvitancia` = '$kvitanciav', `money` = '$moneyv', `idcert` = '$idcert', `sumsale` = '$sumsale', `koplate` = '$koplate' WHERE `id` = '$idv'");
	}
	for ($i = 0; $i < count($work) && $i < count($price); $i++){
		$add_aktswork = $connect_db->prepare("INSERT INTO `raboty` (`razdel`, `subid`, `name`, `kol`, `price`) VALUE (?,?,?,?,?)");
		$add_aktswork->bind_param("sisis", $razdel, $idv, $work[$i], $kol[$i], $price[$i]);
		$add_aktswork->execute();
		$add_aktswork->close();
	};
	echo <<<HTML
			<script language="JavaScript" type="text/javascript">
				<!-- 
					setTimeout(function () {
						parent.jQuery.fancybox.close();
					}, 1000); 
				//--> 
			</script>	
HTML;
}
?>
<?php 
$title = 'Отметить выполненным';
?>
<?php echo $modal_title_start.$title.$modal_title_end; ?>

<script>
	function submitVisov(id) {
		var work = new Array();
		var kol = new Array();
		var price = new Array();
		var prep = $('#filds div:last input:last').attr("name");
		var prepi = prep.split('_');
		var i = prepi[1];
		var idcert = $('select[name="idcert"]').val();
		var money = $('input[name="money"]').val();
		var kvitancia = $('input[name="kvitancia"]').val();
		for(var s = 0; s <= i; s++) {
			work.push($('input[name="raboty_'+s+'"]').val());
			kol.push($('input[name="kol_'+s+'"]').val());
			price.push($('input[name="cena_'+s+'"]').val());
		}
		$("#r").css("display", "block");
		$.post("<?= $SERVER['HTTP_HOST'] ?>/inc/performance.php", {id:id, work:work, price:price, kol:kol, idcert:idcert, money:money, kvitancia:kvitancia} , function(data){
			if(data.length>0){
				$("#r").html(data);
			}
		});
	}
	function useCertForVisov(vid) {
		var idcert = $('#cert').val();
		var summa = $('#summa').val();
		$.post("<?= $SERVER['HTTP_HOST'] ?>/inc/usecert.php?ajaxcert", {idcert : idcert, summa : summa, vid : vid} , function(data){
			if(data.length>0){
				$("#koplate").css("display", "block");
				$("#koplate input").val(data);
				$("#mesages").css("display", "block");
			}else{
				$("#koplate").css("display", "none");
				$("#mesages").css("display", "none");
			}
		});
	}
	function prechek(vid) {
		var kol;
		var price;
		var prep = $('#filds div:last input:last').attr("name");
		var prepi = prep.split('_');
		var i = prepi[1];
		for(var s = 0; s <= i; s++) {
			if(s == 0) {
				var summa = 0;
				var cena = 0;
			}
			kol = $('input[name="kol_'+s+'"]').val();
			price = $('input[name="cena_'+s+'"]').val();
			cena = kol * price;
			$('input[name="summa_'+s+'"]').val(cena);
			summa = summa + cena;
			console.log(summa);
			//useCertForVisov(vid);
		}
		$('input[name="money"]').val(summa);
		useCertForVisov(vid);
	}
</script>

<div class="row popap">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<form method="post">
			<div class="mtop10">
				<input class="form-control" name="kvitancia" type="text" placeholder="Укажите номер квитанции">
			</div>
			<div class="mtop10">
				<div class="mtop10">
					<div class="btn btn-primary mtop10" onclick="addFieldsVisov()">Добавить поля</div>
					<div id="filds">
						<div class="mtop10">
							<input class="form-control" type="text" name="raboty_0" placeholder="Выполненные работы">
							<input class="form-control" oninput="prechek(<?php echo $_GET['id']; ?>)" type="text" name="kol_0" placeholder="Количество">
							<input class="form-control" oninput="prechek(<?php echo $_GET['id']; ?>)" type="text" name="cena_0" placeholder="Цена">
							<input class="form-control" name="summa_0" disabled type="text" placeholder="Сумма">
						</div>
					</div>
				</div>
			</div>
			<div class="mtop10">
				<label>Итого:</label>
				<input class="form-control" id="summa" name="money" disabled type="text" required placeholder="Сумма без скидки">
			</div>
			<input type="hidden" name="vid" value="<?php echo $_GET['id']; ?>">
			<div class="mtop10">
				<select class="form-control" id="cert" name="idcert">
					<option value="0">Выберите сертификат</option>
					<?php select_cert($status = 2); ?>
				</select>
			</div>
			<div id="mesages" style="display:none;">
				<div class="bs-callout bs-callout-danger">
					<h4>Внимание:</h4>
					<p>
						После изменения цены или количества услуги, требуется перевыбор сертификата!	
					</p>
  				</div>
			</div>
			<div id="koplate" class="mtop10" style="display: none;">
				<label>К оплате:</label>
				<input class="form-control" disabled value="" name="koplate" type="text">
			</div>
			<div class="mtop10">
				<div class="btn btn-success" id="submit" name="submit" onclick="submitVisov(<?php echo $_GET['id']; ?>)" >Отправить</div>
			</div>
		</form>
		<div id="r"></div>
	</div>
</div>

<?php include('footer.inc'); ?>