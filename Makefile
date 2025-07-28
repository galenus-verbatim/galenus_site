.PHONY: php-dev
php-dev:
	php -S localhost:8080

.PHONY: eleventy
eleventy:
	cd gv-static && pnpm exec eleventy --serve
