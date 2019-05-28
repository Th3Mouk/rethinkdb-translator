cs:
	vendor/bin/phpcs

fix_cs:
	vendor/bin/phpcbf

stan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/phpunit --bootstrap vendor/autoload.php tests

security:
	vendor/bin/security-checker security:check composer.lock

ci: cs stan security
