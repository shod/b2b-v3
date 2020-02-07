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
				'sale2@vendee.by',
				'ok@vendee.by'
			],
			'questionEmails' => [
				'admin@vendee.by',
				'sale@vendee.by',
				'ok@vendee.by'
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
			'up_domain' => 'http://136.244.83.35', /**/
			'migom_name' => 'VENDEE', //'Migom.by'
			'redirect_domain' => 'vendee.by',

			'STATIC_URL' => '//media.tengrowth.com',
			'STATIC_URL_FULL' => 'http://media.tengrowth.com',
			'seller_old_tariff' => [4150,2366,1976,1782,1269],
			'allow_login_admin_user_ip' => ['213.184.249.110', ''],
		], require(__DIR__ . '/components/params.php')   
);
