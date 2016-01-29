<?php
require_once __DIR__.'/core/config.php';
require_once __DIR__.'/core/model.php';
require_once __DIR__.'/core/view.php';
require_once __DIR__.'/core/controller.php';
require_once __DIR__.'/core/route.php';
Route::start($homeForSite=$_SERVER['REQUEST_URI']); // запускаем маршрутизатор