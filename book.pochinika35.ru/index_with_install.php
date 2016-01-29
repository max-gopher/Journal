<?
//Проверяем наличие файла настроек
if(!file_exists($_SERVER[DOCUMENT_ROOT]."/config.php")){
	//Еслифайла нет, предлагаем установку CMS и завершаем работу скрипта
	echo '<p style="width:300px;border:1px solid #ff0000;background-color:#fdcaca;color:#ff0000;padding:20px;margin:20px auto 20px auto;font-family:Tahoma,Arial,FreeSans,Garuda,Utkal,sans-serif;">Файл настроек не найден или к нему нет доступа. Возможно CMS не установлена. Провертеправа на файл config.php</p><p styl="width:300px;border: 1px solid #00ff00;background-color:#eaffdd;color:00a000;padding:20px;margin:0 auto 0 auto;font-family:Tahoma,Arial,FreeSans,Garuda,Utkal,sans-serif;">Для установки системы перейдите по ссылке<a href="/install.php">http://'.$SERVER[SERVER_NAME].'/install.php</a>.<br><br>Связаться с разработчиком можно по адресу <a href="mailto:djin85_85@mail.ru">djin85_85@mail.ru</a></p>';
	exit();
}
//Если файл настроек на месте, включаем его и продолжаем вывод страницы
else{
	include $_SERVER[DOCUMENT_ROOT].'/config.php';
	//Включаем шапку; файл header.php находится в папке /template в папке с 
	//темой оформления. Переменная в тути к файлу для возможности изменения 
	//темы.
	include $_SERVER[DOCUMENT_ROOT].'/template'.$template.'/header.php';
	//Здесь выводим содержимое страницы; файл content.php находится в папке /template в папке с темой оформления
	include $_SERVER[DOCUMENT_ROOT].'/template'.$template.'/content.php';
	//Включаем подвал; файл footer.php находится в папке /template в папке с темой оформления
	include $_SERVER[DOCUMENT_ROOT].'/template'.$template.'/footer.php';
}
?>