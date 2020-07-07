<?php

return array_merge(
		[
			'adminEmail' => 'admin@vendee.by',
			'saleManager' => 'sale@vendee.by',
			'noreplyEmail' => 'noreply@vendee.by',
			'supportEmail' => 'support@vendee.by',
			'reportEmail' => 'editor@vendee.by',
			'fromEmail' => 'support@vendee.by',
			'saleEmails' => [
				'sale@vendee.by',
				'sale2@vendee.by'
			],
			'questionEmails' => [
				'admin@vendee.by',
				'sale@vendee.by'
			],
			'testEmails' => [
				"admin@vendee.by", "shod@vendee.by"
			],
			'b2bEmails' => [
				'sale@vendee.by',
				'admin@vendee.by',
				"shod@vendee.by"
			],
			'migom_url' => 'https://vendee.by',
			'b2b_url' => 'https://b2b.vendee.by',
			'migom_domain' => 'vendee.by', /**/			
			'up_domain' => 'http://95.179.162.93', /**/			
			'migom_name' => 'VENDEE', //'Migom.by'
			'redirect_domain' => 'vendee.by',

			'STATIC_URL' => '//media.vendee.by',
			'STATIC_URL_FULL' => 'http://media.vendee.by',
			'seller_old_tariff' => [4150,2366,1976,1782,1269],
			'allow_login_admin_user_ip' => ['213.184.249.110', '46.53.250.7'],
			'currency' => 'Руб.',
			'phones' => [
				'info' => '+375 (29) 112-45-45',
			],
			'tariff_version' => 3,
			
		], require(__DIR__ . '/components/params.php')   
);

