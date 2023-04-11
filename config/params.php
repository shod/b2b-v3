<?php

return array_merge(
		[
			'adminEmail' => 'shod@maxi.by',
			'saleManager' => 'sale@maxi.by',
			// 'saleManagerPhone' => [
			// 	'info' => '+375 (29) 101-23-23',
			// 	'ahref' => '+375291012323',
			// ],
			'saleManagerPhone' => [],
			'noreplyEmail' => 'noreply@maxi.by',
			'supportEmail' => 'support@maxi.by',
			'supportSkype' => 'admin.migom',
			'reportEmail' => 'report@maxi.by',
			'fromEmail' => 'noreply@maxi.by',
			'saleEmails' => [
				'sale@maxi.by',
				'test-bwlcu6ccx@srv1.mail-tester.com' 			
			],
			'questionEmails' => [
				'support@maxi.by',
				'sale@maxi.by',
				'test-bwlcu6ccx@srv1.mail-tester.com' 
				
			],
			'testEmails' => [
				"shod@maxi.by"
			],
			'b2bEmails' => [
				'support@maxi.by',
				'sale@maxi.by',
				'test-bwlcu6ccx@srv1.mail-tester.com' 
			],
			'migom_url' => 'https://maxi.by',
			'b2b_url' => 'https://b2b.maxi.by',
			'migom_domain' => 'maxi.by', /**/			
			'up_domain' => 'http://95.179.162.93', /**/			
			'migom_name' => 'MAXI.BY', //'Migom.by'
			'redirect_domain' => 'maxi.by',

			'STATIC_URL' => '//media.maxi.by',
			'STATIC_URL_FULL' => 'https://media.maxi.by',
			'seller_old_tariff' => [4150,2366,1976,1782,1269],
			'allow_login_admin_user_ip' => ['213.184.249.110', '46.53.250.7'],
			'currency' => 'ТЕ',
			'phones' => [
				'info' => '+375 (29) 101-23-23',
				'ahref' => '+375291012323',
			],
			'tariff_version' => 3,
			'recaptcha_sitekey' => '6LeM8GkjAAAAALycMMYGnj_pTg3xDfK4xfhw5OA7',
			'recaptcha_secret' => '6LeM8GkjAAAAAHOmGz3lPfkitIkf_5o5nHk4xQfW',			
			
		], require(__DIR__ . '/components/params.php')   
);

