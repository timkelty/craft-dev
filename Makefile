.PHONY: checkout-package checkout-packages
COMPOSER ?= ddev composer
GIT ?= git
PACKAGE ?= $(REPO)

checkout-package:
	@if [ -z "$(REPO)" ]; then \
		echo "Error: REPO variable is required."; \
		echo "Usage: make checkout-package REPO=vendor/repo [BRANCH=branch-name]"; \
		exit 1; \
	fi

	@if [ -d "./packages/$(REPO)" ]; then \
		echo "Repository $(REPO) already exists, updating..."; \
		cd ./packages/$(REPO) && \
		$(GIT) stash && \
		$(GIT) checkout $(BRANCH) && \
		$(GIT) pull; \
	else \
		echo "Cloning $(REPO) into ./packages/$(REPO)"; \
		if [ -n "$(BRANCH)" ]; then \
			$(GIT) clone -b $(BRANCH) https://github.com/$(REPO).git ./packages/$(REPO); \
		else \
			$(GIT) clone https://github.com/$(REPO).git ./packages/$(REPO); \
		fi; \
	fi
	@$(COMPOSER) config repositories.local --json '{"type": "path", "url": "packages/*/*", "options": { "symlink": true } }'
	@$(COMPOSER) update $(PACKAGE)

checkout-packages:
	@$(MAKE) checkout-package REPO=craftcms/cms BRANCH=4.15
	@$(MAKE) checkout-package REPO=craftcms/commerce BRANCH=4.x
	@$(MAKE) checkout-package REPO=craftcms/ckeditor BRANCH=3.x
	@$(MAKE) checkout-package REPO=craftcms/cloud-extension-yii2 PACKAGE=craftcms/cms BRANCH=1.x
