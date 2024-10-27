WORKSPACE ?= $(PWD)
REPORTS_DIR ?= build/reports

audit: phpcs phpmd phpstan ## Run static code analysis
.PHONY: audit

phpcs: ## Run PHP_CodeSniffer
	vendor/bin/phpcs --standard=phpcs.xml --extensions=php --ignore=vendor --cache=.phpcs-cache $(EXTRA_ARGS) .
phpcs-ci: prepare-ci ## Run PHP_CodeSniffer (CI)
	EXTRA_ARGS="--report=junit --report-file=$(REPORTS_DIR)/phpcs.junit.xml" $(MAKE) phpcs
.PHONY: phpcs phpcs-ci

PHPMD_FORMAT ?= text
phpmd: ## Run PHP Mess Detector
	 vendor/bin/phpmd src $(PHPMD_FORMAT) phpmd.xml --suffixes=php $(EXTRA_ARGS)
phpmd-ci: prepare-ci ## Run PHP Mess Detector (CI)
	PHPMD_FORMAT="github" $(MAKE) phpmd
.PHONY: phpmd phpmd-ci

PHPSTAN_LEVEL ?= max
phpstan: ## Run PHPStan
	vendor/bin/phpstan analyse --configuration=phpstan.neon --memory-limit=-1 --level=$(PHPSTAN_LEVEL) $(EXTRA_ARGS) src tests
phpstan-ci: prepare-ci ## Run PHPStan (CI)
	EXTRA_ARGS="--error-format=github --no-progress" $(MAKE) phpstan
.PHONY: phpstan phpstan-ci

prepare-ci:
	@mkdir -p build/reports
.PHONY: prepare-ci

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
.DEFAULT_GOAL := help
