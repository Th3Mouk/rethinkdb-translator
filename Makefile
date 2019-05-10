cs:
	vendor/bin/phpcs

fix_cs:
	vendor/bin/phpcbf

stan:
	vendor/bin/phpstan analyse

security:
	vendor/bin/security-checker security:check composer.lock

ci: cs stan security
