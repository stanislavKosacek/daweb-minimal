clear-cache:
	php bin/console clear-cache

npm-build:
	npm run-script build

clear-cache-force:
	rm -Rf temp/cache

make migrations:
	php bin/console migrations:continue

make migrations-create:
	php bin/console migrations:create structures $(filter-out $@,$(MAKECMDGOALS))
