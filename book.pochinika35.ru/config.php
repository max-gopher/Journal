<?php
/*********************************                Доход                 ************************/
//$seleck_visovs = $connect_db->query("SELECT * FROM visovi WHERE login = '$login' && created_at BETWEEN STR_TO_DATE('2008-08-14 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2008-08-23 23:59:59', '%Y-%m-%d %H:%i:%s')");
//$my_visovs = $seleck_visovs->fetch_array(MYSQLI_ASSOC);
//$my_dohod = $my_visovs["data"];
/*********************************      Думай Голова Шапку Куплю        ************************/

/*********************************           Партнерский номер          ************************/
$partner_id = $fiousers["partner_id"];
function newPartnerId() {
    global $connect_db;
    $selectPartnerId = $connect_db->query("SELECT * FROM `users` WHERE `partner_id` <> '0' ORDER BY `id` DESC LIMIT 1");
    if($selectPartnerId->num_rows == '1') {
        $fetchParentId = $selectPartnerId->fetch_assoc();
        $parent_id = $fetchParentId['partner_id'];
        $parent_id++;
    }else{
        $parent_id = '0000000001';
    }
    return $parent_id;
}

function getPartnerId() {

}

/*********************************         Партнерский Баланс           ************************/
//
// Тут должен высчитываться Партнерский баланс - сколько получит инжинер с партнерки
//$partner_balans
/*********************************      Думай Голова Шапку Куплю        ************************/

// Функция очистки перед записью в базу
function clean_input($value) {
	$value = strip_tags($value);
	$value = htmlspecialchars($value,ENT_QUOTES);
	$value = mysql_escape_string($value);
	return $value;
}
// Конец функции перед записью в базу


// Выборка инженеров для добавления нового вызова
function select_ing($name_ing) {
	global $connect_db;
	$ing = "Инженер";
	$sting = "Старший инженер";
	if(empty($name_ing)) {
		$select_ing = $connect_db->query("SELECT * FROM users WHERE `doljnost` = '$ing' || `doljnost` = '$sting'");
		while ($fetch_ing = $select_ing->fetch_assoc()) {
			echo '<option>'.$fetch_ing['familiya'].' '.$fetch_ing['name'].'</option>';
		}
	}else {
		list($familia_ing, $imya_ing) = explode(" ", $name_ing);
		$select_ing = $connect_db->query("SELECT * FROM users WHERE (`doljnost` = '$ing' OR `doljnost` = '$sting') AND `familiya` != '$familia_ing'");
		while ($fetch_ing = $select_ing->fetch_assoc()) {
			echo '<option>'.$fetch_ing['familiya'].' '.$fetch_ing['name'].'</option>';
		}
	}
}
// Конец выборки инжинеров дла добавления нового вызова

// Выборка статусов вызова
function select_status_visov() {
	global $connect_db;
	$select_status_visov = $connect_db->query("SELECT * FROM vstatus");
	while ($fetch_status_visov = $select_status_visov->fetch_assoc()) {
		echo '<option>'.$fetch_status_visov['name'].'</option>';
	}
}
//Конец выборки статусов вызова

// Выборка пользователей для управления пользователями
function select_users($who = 'no', $partner = '0') {
	global $connect_db;
	if ($who == 'no' && $partner == '0') {
		$select_users = $connect_db->query("SELECT * FROM `users`");
		while ($fetch_users = $select_users->fetch_assoc()) {
			echo '<tr><td>'.$fetch_users["familiya"].' '.$fetch_users["name"].' '.$fetch_users["otchestvo"].'</td>';
			echo '<td><a href="edituser.php?id='.$fetch_users["id"].'"><img style="width:25px" align="top" alt="edit" title="Редактировать" src="http://'.$_SERVER['HTTP_HOST'].'/img/edit.jpg"></a><a href="deluser.php?id='.$fetch_users["id"].'"><img style="width:25px" align="top" alt="del" title="Удалить" src="http://'.$_SERVER['HTTP_HOST'].'/img/del.png"></a></td></tr>';
		}
	}
    // Выбор партнеров
    if($who == 'partner' && $partner == '0') {
        $select_users = $connect_db->query("SELECT * FROM `users` WHERE `partner_id` <> '0'");
        return $select_users;
    }

	// Выбор партнера по id
	if($who == 'partner' && !empty($partner) ) {
		$select_users = $connect_db->query("SELECT * FROM `users` WHERE `id` = '$partner'");
		return $select_users;
	}
    
    if($partner == 'yes' || $partner > '0') {
        
        $select_users = $connect_db->query("SELECT * FROM `users` WHERE `partner_id` <> '0'");
		while ($fetch_users = $select_users->fetch_assoc()) {
            if($who == 'no') {
                if ($partner == $fetch_users['partner_id']) {
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                echo '<option '.$selected.' value="'.$fetch_users['partner_id'].'">'.$fetch_users['familiya'].' '.$fetch_users['name'].'</option>';
            }
		}
    }
	// Выбор пользователей для архива документов
	if($who == 'arhiv') {
		$ing = 'Инженер';
		$sting = 'Старший инженер';
		$select_users = $connect_db->query("SELECT * FROM `users` WHERE `doljnost` = '$ing' OR `doljnost` = '$sting'");
		while ($fetch_users = $select_users->fetch_assoc()) {
			echo '<option value="'.$fetch_users['login'].'">'.$fetch_users['familiya'].' '.$fetch_users['name'].'</option>';
		}
	}
}
// Конец выборки пользователей

// Выборка прав (должностей) для добавления пользователя
function select_doljnost_option($userid) {
	global $connect_db;
	if($userid == 0){
		$select_doljnost = $connect_db->query("SELECT * FROM doljnosti");
		while ($select_doljnost1 = $select_doljnost->fetch_assoc()) {
			echo '<option>'.$select_doljnost1["doljnost"].'</option>';
		}
	}else{
		$select_doljnost_user = $connect_db->query("SELECT `doljnost` FROM `users` WHERE `id` = '$userid' LIMIT 1");
		$fetch_doljnost_user = $select_doljnost_user->fetch_assoc();
		echo '<option>'.$fetch_doljnost_user["doljnost"].'</option>';
		
		$select_doljnost = $connect_db->query("SELECT * FROM doljnosti");
		while ($fetch_doljnost = $select_doljnost->fetch_assoc()) {
			if($fetch_doljnost_user["doljnost"] != $fetch_doljnost["doljnost"]){
				echo '<option>'.$fetch_doljnost["doljnost"].'</option>';
			}
		}
	}
}
// Конец выборки прав (должностей) для добавления пользователя

// Выборка прав (должностей) для Управления должностями
function select_doljnost() {
	global $connect_db;
	$select_doljnost = $connect_db->query("SELECT * FROM doljnosti");
	while ($select_doljnost1 = $select_doljnost->fetch_assoc()) {
		echo '<tr><td>'.$select_doljnost1["doljnost"].' '.$select_doljnost1["stavka"].'</td>';
		echo '<td><a href="editdoljnost.php?id='.$select_doljnost1["id"].'"><img style="width:25px" align="top" alt="edit" title="Редактировать" src="../img/edit.jpg"></a><a href="deldoljnost.php?id='.$select_doljnost1["id"].'"><img style="width:25px" align="top" alt="del" title="Удалить" src="../img/del.png"></a></td></tr>';
	}
}
// Конец выборки прав (должностей) для Управления должностями

// Выборка статусов для добавления нового вызова
function select_status() {
	global $connect_db;
	$select_status = $connect_db->query("SELECT * FROM `vstatus` ORDER BY `num` ASC");
	while ($fetch_status = $select_status->fetch_assoc()) {
		echo '<tr><td>'.$fetch_status["name"].'<td>';
		echo '<td><a href="editstatus.php?id='.$fetch_status["id"].'"><img style="width:25px" align="top" alt="edit" title="Редактировать" src="../img/edit.jpg"></a><a href="delstatus.php?id='.$fetch_status["id"].'"><img style="width:25px" align="top" alt="del" title="Удалить" src="../img/del.png"></a></td></tr>';
	}
	
}
// Конец выборки статусов для добавления нового вызова

// Получаем черный список
function select_black_list($where) {
	global $connect_db;
	$select_black_list = $connect_db->query("SELECT * FROM `black_list` ORDER BY `id` DESC");
	while ($fetch_black_list = $select_black_list->fetch_assoc()) {
		if($where == 'visov') {
			echo '<tr><td>'.$fetch_black_list['adres'].'</td><td>'.$fetch_black_list['tel'].'</td></tr>';
		}
		if($where == 'basic') {
			if($fetch_black_list['date'] == '0000-00-00 00:00:00') {
				$fetch_black_list['date'] = 'До нашей эры';
			}
			echo '<tr><td>'.$fetch_black_list['adres'].'</td><td>'.$fetch_black_list['tel'].'</td><td>'.$fetch_black_list['id'].'</td><td>'.$fetch_black_list['cause'].'</td><td>'.$fetch_black_list['work'].'</td><td>'.$fetch_black_list['debt'].'</td><td>'.$fetch_black_list['date'].'</td>';
			echo '<td align="center"><a class="fancybox fancybox.iframe" href="inc/edit.php?black='.$fetch_black_list['id'].'"><img alt="Редактировать" title="Редактировать" src="img/edit.jpg" width="24px" height="24px"></a>';
			echo '<a class="fancybox fancybox.iframe" href="inc/del.php?black='.$fetch_black_list['id'].'"><img alt="Удалить" title="Удалить" src="img/del.png" width="24px" height="24px"></a></td>';
			echo '</tr>';
		}
		
	}
}
// Конец получения черного списка

// Функция для отображения вызовов
function select_visovi($stra = 1, $user_visov = '0', $partner = '0') {
	global $connect_db;
	$login = 'makhtanov';
	$pass = 'libertos';
	$sender = 'Починика';
	$titlemore = 'Подробности записи';
	$num_str = $stra * 20;
	// Учим функцию выводить результаты работ по пользователям (LIMIT с какой начать и сколько выводить)
	if($user_visov == 0) {
        if($partner == 0){
            $select_visovi = $connect_db->query("SELECT * FROM `visovi` ORDER BY `dateforengineer` DESC, `timeforengineer` DESC LIMIT $num_str,20");
        }else{
            $select_visovi = $connect_db->query("SELECT * FROM `visovi` WHERE `partner` = '$partner' ORDER BY `dateforengineer` DESC, `timeforengineer` DESC LIMIT $num_str,20");
        }
	}else{
		$select_user = $connect_db->query("SELECT * FROM `users` WHERE `id` = '$user_visov' LIMIT 1");
		$fetch_user = $select_user->fetch_assoc();
		$user_for_visov = $fetch_user['familiya'].' '.$fetch_user['name'];
		$select_visovi = $connect_db->query("SELECT * FROM `visovi` WHERE `engineer` = '$user_for_visov' ORDER BY `dateforengineer` DESC, `timeforengineer` DESC LIMIT $num_str,20");
	}
	while ($fetch_visovi = $select_visovi->fetch_assoc()) {
		
		if(empty($fetch_visovi['adress'])) {
			// Создаем переменную с адресом физика для таблицы так как в базе это разные поля
			$adres = $fetch_visovi['street'].' д.'.$fetch_visovi['home'].' '.$fetch_visovi['housing'].' кв.'.$fetch_visovi['apartment'];
		}else {
			// Иначе пишем адрес юрика
			$adres = $fetch_visovi['adress'];
		}
		
		// Создаем переменную с адресом для карты так как в базе это разные поля
		$adres_map = $fetch_visovi['street'].' д.'.$fetch_visovi['home'].' '.$fetch_visovi['housing'];
		
		// Разбираем переменную с фамилией и именем инженера для поиска нужного, в таблице пользователей
		list($en_fam, $en_name) = explode(" ", $fetch_visovi['engineer']);
		$en_name_for_print = iconv_substr( $en_name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
		$engineer = $en_fam.' '.$en_name_for_print.'.';
		
		// Ищем юзверя и отжимаем его телефон
		$select_engineer = $connect_db->query("SELECT * FROM users WHERE `name` = '$en_name' && `familiya` = '$en_fam'");
		$fetch_engineer = $select_engineer->fetch_assoc();
		$fone = $fetch_engineer['telefon'];
		
		// Переводим дату вызова в читабельный вид
		list($year, $month, $day) = explode("-", $fetch_visovi['dateforengineer']); // Разбираем дату по переменным
		$date_for_engineer = $day.'-'.$month.'-'.$year; // Собираем читабельную дату из полученных при разборе переменных
		
		// Убираем секунды во времени вызова
		list($hour, $min, $sek) = explode(":", $fetch_visovi['timeforengineer']);
		$timeforengineer = $hour.':'.$min;
		
		// Собираем тело месседжа
		$mes = $date_for_engineer.' '.$timeforengineer.' т.'.$fetch_visovi['fone'].' '.$fetch_visovi['namek'].' '.$adres.' '.$fetch_visovi['problemsk'];
		
		// Ссылка на Яндекс карту
		$map = '<br><a target="_blank" href="http://maps.yandex.ru/?text=Вологда '.$adres_map.'"><img width="35px" height="30px" alt="Посмотреть на карте" title="Посмотреть на карте" src="/img/map.png"></a>';
		
		// А теперь выводим этот конструктор на печать как тело страницы
		echo '<tr id="result_visovi"><td><center><a class="fancyboxNotReload fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?visov='.$fetch_visovi['id'].'&title='.$titlemore.'"><img src="/img/groups.png" width="24px" height="24px"></a></center></td>';
		echo '<td width="80px" id="datee">'.$date_for_engineer.'</td><td id="timee">'.$timeforengineer.'</td><td>'.$adres.' '.$map.'</td><td width="100px">'.$fetch_visovi['namek'].'</td><td>'.$fetch_visovi['fone'].'</td><td>'.$fetch_visovi['problemsk'].'</td><td id="content">'.$engineer.'</td><td>'.$fetch_visovi['status'].'</td>';
        echo '<td>';
		if($partner == '0') {
            echo '<a class="fancybox fancybox.iframe" href="inc/performance.php?visov&id='.$fetch_visovi['id'].'"><img alt="Исполнено" title="Исполнено" src="/img/done.jpg" width="24px" height="24px"></a>';
        }
		echo '<a class="fancyboxNotReload fancybox.iframe" title="Добавить заметку" alt="Добавить заметку" href="/inc/note.php?id='.$fetch_visovi['id'].'&group=2"><img src="/img/notes.jpg" width="24px" height="24px"></a>';
        if($partner == '0') {
            echo '<a class="fancybox fancybox.iframe" href="inc/editvisov.php?id='.$fetch_visovi['id'].'"><img alt="Редактировать" title="Редактировать" src="/img/edit.jpg" width="24px" height="24px"></a>';
            echo '<a class="fancybox fancybox.iframe" href="inc/delvisov.php?id='.$fetch_visovi['id'].'"><img alt="Удалить" title="Удалить" src="/img/del.png" width="24px" height="24px"></a>';
            echo '<a class="fancybox fancybox.iframe" href="inc/black_list.php?black_visov='.$fetch_visovi['id'].'"><img alt="Черный список" title="Черный список" src="/img/ban.jpg" width="24px" height="24px"></a>';
            echo '<a class="fancybox fancybox.iframe" href="http://smsc.ru/sys/send.php?login='.$login.'&psw='.$pass.'&charset=utf-8&phones='.$fone.'&mes='.$mes.'"><img alt="Отправить SMS" title="Отправить SMS" src="/img/sms.jpg" width="24px" height="24px"></a>';
        }    
        echo '</td></tr>';
	}
	// Создание страниц 
	
    if($partner == '0'){
	if($user_visov == 0) {
		$select_last_visov = $connect_db->query("SELECT * FROM `visovi` ORDER BY `id`");
	}else{
		$select_user = $connect_db->query("SELECT * FROM `users` WHERE `id` = '$user_visov' LIMIT 1");
		$fetch_user = $select_user->fetch_assoc();
		$user_for_visov = $fetch_user['familiya'].' '.$fetch_user['name'];
		$select_last_visov = $connect_db->query("SELECT * FROM `visovi` WHERE `engineer` = '$user_for_visov' ORDER BY `id`");
	}
	
	$fetch_last_visov = $select_last_visov->num_rows;
	$col_stranic = $fetch_last_visov / 20;
	$col_stranic = ceil($col_stranic);
	echo '<div class="numsrtanic">';
	for($i=1; $i <= $col_stranic; $i++){
		$i2 = $i-1;
		if($i2 == $stra) {
			$style = 'style="text-decoration:none; color:red;" ';
		}else{
			$style = 'style="" ';
		}
		$geti = $i - 1;
		if($user_visov == 0) {
			echo '<a '.$style.'href="/visov.php?stra='.$geti.'&uservisov='.$user_visov.'">'.$i.'</a>';
		}else{
			echo '<a '.$style.'href="/profil.php?stra='.$geti.'&uservisov='.$user_visov.'">'.$i.'</a>';
		}
	}
	echo '</div>';
    }
}
// Конец функции для отображения вызовов

// Получаем тип аппарата из базы для оформления работы
function select_dev() {
	global $connect_db;
	$select_dev = $connect_db->query("SELECT * FROM `device` ORDER BY `Name` ASC");
	while($fetch_dev = $select_dev->fetch_assoc()) {
		echo '<option>'.$fetch_dev["Name"].'</option>';
	}
	
}
// Конец получения аппарата

// Получаем бренды из базы для оформления работы
function select_brend() {
	global $connect_db;
	$select_brend = $connect_db->query("SELECT * FROM `brends` ORDER BY `Name` ASC");
	while($fetch_brend = $select_brend->fetch_assoc()) {
		echo '<option>'.$fetch_brend["Name"].'</option>';
	}
}
// Конец получения брендов

// Функция для мастерской
function select_work($status, $partner = '0') {
	global $connect_db;
	$titlemore = 'Подробности записи';
    if($partner != '0'){
        $select_work = $connect_db->query("SELECT * FROM `work` WHERE `partner` = '$partner' AND `status` = '$status' ORDER BY `id` DESC");
    }else{
	   $select_work = $connect_db->query("SELECT * FROM `work` WHERE `status` = '$status' ORDER BY `id` DESC");
    }
	if ($select_work->num_rows < 1) {
		echo '<tr><td class="noResults">Этот раздел пока пуст</td></tr>';
	}else{
		echo '<tr>
				<th>More</th>
				<th>Номер</th>
				<th>Дата</th>
				<th>Аппарат</th>
				<th>Клиент</th>
				<th>Инженер</th>
				<th>Действия</th>
			</tr>';
	}
	while($fetch_work = $select_work->fetch_assoc()) {
		
		// Приводим дату в человеческий вид
		list($predate, $time) = explode(" ", $fetch_work['date']); // Отделяем дату от времени
		list($year, $month, $day) = explode("-", $predate); // Разчленяем дату
		$date = $day.'-'.$month.'-'.$year; // Собираем дату 
		
		// Собираем аппарат
		if(empty($fetch_work['brend'])) { // если не выбран производитель
			$device = $fetch_work['device'].' '.$fetch_work['model'];
		}else { // В противном случае выводим все
            $device = $fetch_work['device'].' "'.$fetch_work['brend'].'" '.$fetch_work['model'];
		}
		
		echo '<tr><td><center><a class="fancyboxNotReload fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?workid='.$fetch_work['id'].'&title='.$titlemore.'"><img src="/img/groups.png" width="24px" height="24px"></a></center></td>';
		echo '<td>'.$fetch_work['id'].'</td><td>'.$date.'</td><td>'.$device.'</td><td>'.$fetch_work['fio'].'</td><td>'.$fetch_work['engineer'].'</td>';
		echo '<td><a class="fancyboxNotReload fancybox.iframe" title="Добавить заметку" alt="Добавить заметку" href="/inc/note.php?id='.$fetch_work['id'].'&group=2"><img src="/img/notes.jpg" width="24px" height="24px"></a>';
		if($status == 1 || $status == 2 && $partner == '0') {
			echo '<a target="_blank" title="Квитанция о приеме в ремонт" alt="Квитанция о приеме в ремонт" href="/inc/print/invoice.php?getting='.$fetch_work['id'].'"><img src="/img/receipt.jpg" width="24px" height="24px"></a>';
			echo '<a class="fancybox fancybox.iframe" title="Пометить аппарат отремонтированным" alt="Пометить аппарат отремонтированным" href="/inc/rebuilt.php?id='.$fetch_work['id'].'"><img src="/img/repair.jpg" width="24px" height="24px"></a>';
		}
		if($status == 3 && $partner == '0') {
			echo '<a target="_blank" title="Квитанция о выдочи аппарата" alt="Квитанция о выдочи аппарата" href="/inc/print/invoice.php?delivery='.$fetch_work['id'].'"><img src="/img/sent.jpg" width="24px" height="24px"></a>';
			echo '<a class="fancybox fancybox.iframe" title="Вернуть аппарат на доработку" alt="Вернуть аппарат на доработку" href="/inc/back.php?id='.$fetch_work['id'].'"><img src="/img/back.jpg" width="24px" height="24px"></a>';
			echo '<a class="fancybox fancybox.iframe" title="Использовать сертификат" href="/inc/usecert.php?idwork='.$fetch_work['id'].'"><img src="/img/cert.png"  alt="Использовать сертификат" width="24px" height="24px"></a>';
		}
		if($status == 5 && $partner == '0') {
			echo '<a class="fancybox fancybox.iframe" title="Вернуть аппарат на доработку" alt="Вернуть аппарат на доработку" href="/inc/back.php?id='.$fetch_work['id'].'"><img src="/img/back.jpg" width="24px" height="24px"></a>';
		}
        if ($partner == '0') {
            echo '<a class="fancybox fancybox.iframe" title="Редактировать" alt="Редактировать" href="/inc/editwork.php?id='.$fetch_work['id'].'"><img src="/img/edit.jpg" width="24px" height="24px"></a>';
            echo '<a class="fancybox fancybox.iframe" title="Удалить" alt="Удалить" href="/inc/delwork.php?id='.$fetch_work['id'].'"><img src="/img/del.png" width="24px" height="24px"></a></td></tr>';
        }
	}
}
// Конец функции для мастерской

// Выбираем категории для добавления девайса на склад
function select_cat() {
	global $connect_db;
	$select_cat = $connect_db->query("SELECT * FROM `skladcat` ORDER BY `name` ASC");
	while($fetch_cat = $select_cat->fetch_assoc()) {
		echo '<option>'.$fetch_cat['name'].'</option>';
	}
}

// Выбираем контрагентов
function select_firms(){
	global $connect_db;
	$titlemore = 'Подробности записи';
	$select_firms = $connect_db->query("SELECT * FROM `firms` ORDER BY `firm` ASC");
	while($fetch_firms = $select_firms->fetch_assoc()) {
		echo '<tr><td><a class="fancybox fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?firm='.$fetch_firms['id'].'&title='.$titlemore.'"><img src="img/groups.png" width="24px" height="24px"></a></td>';
		echo '<td>'.$fetch_firms['firm'].'</td><td>'.$fetch_firms['fone'].'</td><td>'.$fetch_firms['email'].'</td>';
		echo '<td><a class="fancybox fancybox.iframe" title="Редактировать" alt="Редактировать" href="/inc/edit.php?firm='.$fetch_firms['id'].'"><img src="img/edit.jpg" width="24px" height="24px"></a>';
		echo '<a class="fancybox fancybox.iframe" title="Удалить" alt="Удалить" href="/inc/del.php?firm='.$fetch_firms['id'].'"><img src="img/del.png" width="24px" height="24px"></a></td></tr>';
	}
}
// Конец выбора контрагентов
// Выбор контрагентов для оформления вызова
function select_urik() {
	global $connect_db;
	$select_firms = $connect_db->query("SELECT * FROM `firms` ORDER BY `firm` ASC");
	while($fetch_firms = $select_firms->fetch_assoc()) {
		echo '<option value="'.$fetch_firms['id'].'">'.$fetch_firms['firm'].'</option>';
	}
}
// Конец выбора контрагентов для оформления вызова

// Функция вывода архива документов
function arhiv($user, $vibor='no', $doc) {
	global $connect_db;
	$titlemore = 'Подробности записи';
	$select_user = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$user' LIMIT 1");
	$fetch_user = $select_user->fetch_assoc();
	$doljnost = $fetch_user['doljnost'];
	$select_doljnost = $connect_db->query("SELECT * FROM `doljnosti` WHERE `doljnost` = '$doljnost' LIMIT 1");
	$fetch_doljnost = $select_doljnost->fetch_assoc();
	// Выбираем чеки, если документ не выбран
	if($doc == 'no') {
		$doc = 'cheki';
	}
	
	if($doc == 'cheki') {
		echo '<thead>
			<tr>
				<th>Дата</th>
				<th>Номер</th>
				<th>Специалист</th>
				<th>Клиент</th>
				<th>Действия</th>
			</tr>
		</thead>
		<tbody>';
	}
	if($doc == 'aktswork') {
		echo '<thead>
			<tr>
				<th>Дата</th>
				<th>Номер</th>
				<th>Специалист</th>
				<th>Клиент</th>
				<th>Сумма</th>
				<th>Действия</th>
			</tr>
		</thead>
		<tbody>';
	}
	
	// Если страницу просматривает инжинер или на странице в селекте выбран старший инжинер, тогда выбираем только его записи.
	if($fetch_doljnost['id'] == '7' || (($fetch_doljnost['id'] == '6' || $fetch_doljnost['id'] == '13') && $vibor == 'yes')) {
		$select_arhiv = $connect_db->query("SELECT * FROM $doc WHERE `user` = '$user' GROUP BY `number` ORDER BY `date` DESC");
	}
	// Если страницу просматривает старший инжинер или админ и не сделан выбор в селекте, тогда показываем все записи.
	elseif(($fetch_doljnost['id'] == '6' || $fetch_doljnost['id'] == '13') && $vibor == 'no') {
		$select_arhiv = $connect_db->query("SELECT * FROM $doc GROUP BY `number` ORDER BY `date` DESC");
	}
	while($fetch_arhiv = $select_arhiv->fetch_assoc()) {
		// Подготавливаем дату
		list($predate, $time) = explode(" ", $fetch_arhiv['date']);
		list($year, $month, $day) = explode("-", $predate);
		$date = $day.'-'.$month.'-'.$year;
		// Получаем ФИО работника
		$user_print = $fetch_arhiv['user'];
		$select_user = $connect_db->query("SELECT * FROM `users` WHERE `login` = '$user_print' LIMIT 1");
		$fetch_user = $select_user->fetch_assoc();
		$en_name = $fetch_user['name'];
		$en_otch = $fetch_user['otchestvo'];
		// От имени и отчества оставляем только первые буквы
		$en_name_for_print = iconv_substr( $en_name, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
		$en_otch_for_print = iconv_substr( $en_otch, 0, 1, 'utf-8' ); // А я в рот бомбил эти кодировки!
		// Собирае воедино эту ересь
		$engineer = $fetch_user['familiya'].' '.$en_name_for_print.'.'.$en_otch_for_print.'.';
		// Выводим таблицу чеков
		if($doc == 'cheki') {
			echo '<tr><td>'.$date.'</td><td>'.$fetch_arhiv['number'].'</td><td>'.$engineer.'</td><td>'.$fetch_arhiv['who'].'</td>';
			echo '<td><a class="fancyboxNotReload fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?doc='.$doc.'&number='.$fetch_arhiv['number'].'&title='.$titlemore.'"><img src="img/groups.png" width="24px" height="24px"></a>
			<a href="#"><span class="glyphicon glyphicon-print"></span></a></td></tr>';
		}elseif($doc == 'aktswork') {
			echo '<tr><td>'.$date.'</td><td>'.$fetch_arhiv['number'].'</td><td>'.$engineer.'</td><td>'.$fetch_arhiv['fiok'].'</td><td>'.$fetch_arhiv['summa'].'</td>';
			echo '<td><a class="fancyboxNotReload fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?doc='.$doc.'&number='.$fetch_arhiv['number'].'&title='.$titlemore.'"><img src="img/groups.png" width="24px" height="24px"></a>
			<a href="/inc/print/akt_vip_rab.php?aktNumber='.$fetch_arhiv['number'].'"><span class="glyphicon glyphicon-print"></span></a></td></tr>';
		}
	}
	echo '</tbody>';
}
// Конец функции вывода архива документов

// Сертификаты
function select_cert($status) {
	global $connect_db;
	
	// Получаем сертификаты для мастерской
	if($status == 2) {
		$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `ostatok` > '0' ORDER BY `idcert` ASC");
		while($fetch_cert = $select_cert->fetch_assoc()) {
			$idcert = $fetch_cert['idcert'];
			echo '<option value="'.$idcert.'">Сертификат №'.$idcert.'</option>';
		}
		///break;
	}
	
	if($status == 1) {
		$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `ostatok` > '0' ORDER BY `idcert` ASC");	
	}else{
		$select_cert = $connect_db->query("SELECT * FROM `cert` WHERE `ostatok` <= '0' ORDER BY `idcert` ASC");
	}
	if($select_cert->num_rows > 0) {
		$start_table = '<table style="margin-top:10px;" border="1" cellpadding="2">
							<tbody>
								<tr>
									<th>More</th>
									<th>ID</th>
									<th>Выдан</th>
									<th>Номинал</th>
									<th>Остаток</th>
									<th>Действия</th>
								</tr>';
		$end_table = '</tbody></table>';
	}else {
		$start_table ='<div class="noResults">В этом разделе пока нет сертификатов';
		$end_table = '</div>';
	}
	
	echo $start_table;
	
	while($fetch_cert = $select_cert->fetch_assoc()) {
		$idcert = $fetch_cert['idcert'];
		$date = $fetch_cert['date'];
		$nominal = $fetch_cert['nominal'];
		$ostatok = $fetch_cert['ostatok'];
		echo '<tr><td><a class="fancybox fancybox.iframe" title="Просмотреть подробности" alt="Просмотреть подробности" href="/inc/more.php?idcert='.$fetch_cert['idcert'].'"><img alt="Просмотреть подробности" src="img/groups.png" width="24px" height="24px"></a></td><td>'.$idcert.'</td><td>'.$date.'</td><td>'.$nominal.'</td><td>'.$ostatok.'</td>';
		echo '<td><a class="fancybox fancybox.iframe" title="Удалить" alt="Удалить" href="/inc/del.php?idcert='.$fetch_cert['idcert'].'"><img src="img/del.png" width="24px" height="24px"></a></td></tr>';
	}
	
	echo $end_table;
	
}
// Конец сертификатам

// Выбор пользователей из группы vk для добавления в мастерскую
function select_vkusers( $klient = 'no') {
	// Получаем id всех участников группы
	$contents = file_get_contents("http://api.vk.com/method/groups.getMembers?group_id=52364172");
	// Превращаем ответ из JSON в ассоциативный массив
	$members = json_decode($contents, true);
	// Превращаем массив в строку разделенную запятыми
	foreach ($members['response']['users'] as $user_array) {
		$list_users .= $user_array.',';
        }
	// Обрезаем последнюю запятую
	$list_users = substr($list_users, 0, -1);
	// Получаем инфу о всех пользователях перечисленных в строке
	$getListVkUsers = 'user_ids='.$list_users.'&fields=maiden_name';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.vk.com/method/users.get');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $getListVkUsers);
        $vkuser = curl_exec($curl);
	// Превращаем ответ из JSON в ассоциативный массив
	$array_vkuser = json_decode($vkuser, true);
	// 
	foreach ($array_vkuser['response'] as $respon) {
            // Устанавливаем пустую переменную для пописка соответствия
            $selected = '';
            // Получвем имя и фамилию участника
            $uchastnik = $respon['first_name'].' '.$respon['last_name'];
            // Если участник переданный в функцию совпадает с участником в списк, то он устанавливается в selected 
            if($uchastnik == $klient) {
		$selected = 'selected';
            }
            // Выводим на печать участника
            echo '<option '.$selected.'>'.$uchastnik.'</option>';
	}
        curl_close($curl);
	/* Записать а если есть, то обновить!
	$zapros = "INSERT INTO `vkusers` (vkid)
            VALUES ('$user_array')
            ON DUPLICATE KEY UPDATE vkid='$user_array'";
	$connect_db->query($zapros);*/
}
// Конец выбора пользователей из группы vk для добавления в мастерскую

// Функция проверки соединения
/*function isSiteAvailable($url) {
    // проверка на валидность представленного url
    if(!filter_var($url, FILTER_VALIDATE_URL)) {
      return 'Вы ввели неверный URL';
    }
    // создаём curl подключение
    $cl = curl_init($url);
    curl_setopt($cl,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($cl,CURLOPT_HEADER,true);
    curl_setopt($cl,CURLOPT_NOBODY,true);
    curl_setopt($cl,CURLOPT_RETURNTRANSFER,true);
    // получаем ответ
    $response = curl_exec($cl);
    curl_close($cl);
    if ($response) return 'Cайт работает!';
    return 'Ой.. С сайтом что-то не так или такого домена не существует.';
}*/

// Получение списка счетов
function select_schet($which) {
    global $connect_db;
    $select_schet = $connect_db->query("SELECT * FROM `scheta` WHERE `status` = '$which'");
    while($fetch_schet = $select_schet->fetch_assoc()) {
	echo '<tr><td>More</td>';
	echo '<td>'.$fetch_schet['data'].'</td>';
	echo '<td>'.$fetch_schet['org'].'</td>';
	echo '<td>'.$fetch_schet['adres'].'</td>';
	echo '<td>'.$fetch_schet['summa'].'</td>';
	echo '<td>'.$fetch_schet['status'].'</td>';
	echo '<td><a title="Распечатать счет" href="/inc/print/schet_print.php?id='.$fetch_schet['id'].'"><img alt="Распечатать" title="Распечатать счет" src="img/add_chek.png" width="24px" height="24px"></a>';
	echo '<img onclick="oplachen('.$fetch_schet['id'].')" alt="Оплачен" title="Пометить оплаченным" src="img/done.jpg" width="24px" height="24px">';
	echo '<a class="fancybox fancybox.iframe" title="Пометить оплаченным частично" href="/inc/afunction.php?chast='.$fetch_schet['id'].'"><img alt="Оплачен частично" title="Пометить оплаченным частично" src="img/chast.png" width="24px" height="24px"></a>';
	echo '<a class="fancybox fancybox.iframe" title="Удалить" href="/inc/scheta/delschet.php?id='.$fetch_schet['id'].'"><img alt="Удалить" title="Удалить счет" src="img/del.png" width="24px" height="24px"></a></td></tr>';
    }
}

// Функция получения данных о контрагенте. 
function selectInfoFirm ($id = '0') {
    global $connect_db;
    (int) $id;
    if(!empty($id)) {
	$select_firm = $connect_db->query("SELECT * FROM `firms` WHERE `id` = '$id' LIMIT 1");
	$fetch_firm = $select_firm->fetch_assoc();
	return $fetch_firm;
    }else {
	return 'Вы не выбрали организацию';
    }	
}

// Функция выбора услуг
function selectPrice() {
    global $connect_db;
    $select_price_cat = $connect_db->query("SELECT * FROM `price_cat`");
    while($fetch_price_cat = $elect_price_cat->fetch_assoc()) {
	echo '<tr><td colspan="4">'.$fetch_price_cat['name'].'</td><tr>';
	$cat_id = $fetch_price_cat['id'];
	$select_price = $connect_db->query("SELECT * FROM `price` WHERE `cat` = '$cat_id'");
	while($fetch_price = $select_price->fetch_assoc()) {
            $number = $fetch_price['id'];
            $name = $fetch_price['name'];
            $cena = $fetch_price['cena'];
            echo '<tr><td>'.$number.'</td><td>'.$name.'</td><td>'.$cena.'</td>';
            echo '<td></td></tr>';
	}
    }
}
// Функция высчитывающая оборот и баланс реселлера
function oborot($partner, $vsego='0', $ostatok='0'){
    global $connect_db;
    $select_visovi = $connect_db->query("SELECT * FROM `visovi` WHERE `partner` = '$partner' AND `status` = 'Исполнено'");
    $select_work = $connect_db->query("SELECT * FROM `work` WHERE `partner` = '$partner' AND `status` = '5'");
    if($select_visovi->num_rows) {
        while($fetch_visovi = $select_visovi->fetch_assoc()) {
            $bablo += $fetch_visovi['koplate']; 
        }
    }
    if($select_work->num_rows) {
        while($fetch_work = $select_work->fetch_assoc()) {
            $bablo += $fetch_work['koplate'];
        }
    }
    if($vsego != '0') {
        $bablo = $bablo/100*40;
        return $bablo;
    }
    if($ostatok != '0') {
        $viplaty = '0';
        $select_viplaty = $connect_db->query("SELECT * FROM `viplaty` WHERE `partner_id` = '$partner'");
        if($select_viplaty->num_rows) {
            while($fetch_viplaty = $select_viplaty->fetch_assoc()) {
                $viplaty += $fetch_viplaty['summa'];
            }
        }
        $balans = $bablo/100*40 - $viplaty;
        return $balans;
    }
}

function get_viplaty($partner_id) {
    global $connect_db;
    $select_viplaty = $connect_db->query("SELECT * FROM `viplaty` WHERE `partner_id` = '$partner_id'");
    if($select_viplaty->num_rows == '0') {
	return 'Данному партнеру еще не производились выплаты.';
    }else {
	while($fetch_viplaty = $select_viplaty->fetch_assoc()) {
            $vdate = date("d-m-Y", strtotime($fetch_viplaty['data']));
            $viplaty = [$vdate => $fetch_viplaty['summa']];
	}
	return $viplaty;
    }
}

//if($_SERVER['REQUEST_URI'] == '/404.php') {
	//$connect_db->query("TRUNCATE TABLE `tezak`");
//}
if ($_SESSION['login'] == 'maxim') {
    //echo '<div>'.$_SERVER['REQUEST_URI'].'</div>';
}
//
?>