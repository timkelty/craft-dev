.PHONY: checkout-package checkout-packages
COMPOSER ?= ddev composer
GIT ?= git

checkout-package:
	@if [ -z "$(PACKAGE)" ]; then \
		echo "Error: PACKAGE variable is required."; \
		echo "Usage: make checkout-package PACKAGE=vendor/repo [BRANCH=branch-name]"; \
		exit 1; \
	fi

	@if [ -d "./packages/$(PACKAGE)" ]; then \
		echo "Repository $(PACKAGE) already exists, updating..."; \
		cd ./packages/$(PACKAGE) && \
		$(GIT) stash && \
		$(GIT) checkout $(BRANCH) && \
		$(GIT) pull; \
	else \
		echo "Cloning $(PACKAGE) into ./packages/$(PACKAGE)"; \
		if [ -n "$(BRANCH)" ]; then \
			$(GIT) clone -b $(BRANCH) https://github.com/$(PACKAGE).git ./packages/$(PACKAGE); \
		else \
			$(GIT) clone https://github.com/$(PACKAGE).git ./packages/$(PACKAGE); \
		fi; \
	fi
	$(COMPOSER) config repositories.local --json '{"type": "path", "url": "packages/*/*", "options": { "symlink": true } }'
	$(COMPOSER) update $(PACKAGE)

checkout-packages:
	@$(MAKE) checkout-package PACKAGE=craftcms/cms BRANCH=4.15
	@$(MAKE) checkout-package PACKAGE=craftcms/commerce BRANCH=4.x
	@$(MAKE) checkout-package PACKAGE=craftcms/ckeditor BRANCH=3.x
	@$(MAKE) checkout-package PACKAGE=craftcms/cloud-extension-yii2 BRANCH=1.x
