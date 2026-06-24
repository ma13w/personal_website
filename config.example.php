<?php
declare(strict_types=1);

function portfolio_env(string $name, string $default = ''): string
{
	$value = getenv($name);

	if ($value === false || $value === '') {
		return $default;
	}

	return $value;
}

define('SMTP_HOST', portfolio_env('SMTP_HOST', 'smtp.mail.ovh.net'));
define('SMTP_PORT', (int) portfolio_env('SMTP_PORT', '465'));
define('SMTP_USERNAME', portfolio_env('SMTP_USERNAME'));
define('SMTP_PASS', portfolio_env('SMTP_PASS'));
define('SMTP_FROM_EMAIL', portfolio_env('SMTP_FROM_EMAIL', SMTP_USERNAME));
define('SMTP_FROM_NAME', portfolio_env('SMTP_FROM_NAME', 'Portfolio'));
define('SMTP_TO_EMAIL', portfolio_env('SMTP_TO_EMAIL', SMTP_USERNAME));
