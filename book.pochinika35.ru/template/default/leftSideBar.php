
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
			<div class="navigation-left"><a class="fancybox fancybox.iframe" href="inc/avr.php">АВР</a></div>
			<div class="navigation-left"><a href="workshop.php?adopted">Мастерская</a></div>
			<div class="navigation-left"><a href="cert.php?acting">Сертификаты</a></div>
			<div class="navigation-left"><a href="#">Контакты</a></div>
			<div class="navigation-left"><a href="resellers.php">Реселлеры</a></div>
			<div class="saits">
				<ul>
					<li>Сайты
						<ul>
							<li class="sidebar_title"><a href="saits/">Список сайтов</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="direktoram">
				<ul>
					<li>Директорам
						<ul>
							<li class="sidebar_title"><a href="#">Хранилище паролей</a></li>
							<li><a href="#">Пользователи</a></li>
							<li><a href="#">Счета</a></li>
							<li><a href="#">Контрагенты</a></li>
							<li><a href="#">Поставить CMS</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="my_finansi">
				<ul>
					<li>Мои финансы
						<ul>
							<li class="sidebar_title"><a href="#">Добавить безнал</a></li>
							<li><a href="#">История доходов</a></li>
						</ul>
					</li>
				</ul>
				<div class="my_dohod">Мой доход: <?php echo $my_dohod.' руб.'; ?></div>
			</div>
			<div class="partner">
				<div>Ваш партнерский номер:</div>
				<div class="bablo"><?php echo $partner_id; ?></div>
				<div>Баланс:</div>
				<div class="bablo"><?php echo $partner_balans.' руб.'; ?></div>
				<div>Оборот:</div>
				<div><?php echo $partner_oborot.' руб.'; ?></div>
			</div>
		</div>