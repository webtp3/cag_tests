# CAG Tests

Dies ist die CAG Tests erlaubt euch Standard Unit-, Acceptance- und  Functional- Tests für Typo3 Installationen durchzuführen. [cag_tests](https://bitbucket.org/connecta-ag/cag_project) eingebettet sein.

## Die Features

folgende Tests werden von der Extension durchgeführt
* Tests stammen aus der dev Version des typo3 core
* Erweiterung der Tests für Eigene Extensions 


```bash
git clone git@bitbucket.org:web-tp3/cag_tests.git
cd cag_tests/
rm -rf .git .gitignore
composer install
git init
```


## Einbinden der cag_tests Extension

erfordert folgende Pakete im composer.json und ggf. auch seitens des Servers installiert und aktiviert
```bash
"require": { 

 		"typo3/cms": "dev-TYPO3_8-7",
 		"helhum/typo3-console": "^5",
 		"helhum/dotenv-connector": "^2",
 		"helhum/config-loader": "^0.8",
 		"georgringer/news":"*"
 
 },
 	
"require-dev": {

 		"devlog/devlog": "dev-master",
 		"deployer/deployer": "^6",
 		"consolidation/robo": "^1",
 		"phpunit/phpunit": "*",
 		"codeception/codeception":"*",
 		"typo3/testing-framework": "8.x-dev",
 		"friendsofphp/php-cs-fixer": "^2.13@dev",
 		"se/selenium-server-standalone":"~2.53",
 		"typo3/cms-styleguide" :"~8.0.8",
 		"ext-soap": "*",
 		"phpunit/php-invoker":"^1.1",
 		"nimut/testing-framework": "^3.0@dev"
 },
```

Vorgesehen sind die Tests auf einer Entwicklungsumgebung -> require-dev


[Tests-Erweiterung](https://bitbucket.org/web-tp3/cag_tests/) herunterladen.

Setup
https://getcomposer.org/ should be available on the system already, see its documentation for installation details. For functional tests, a database connection and credentials should be at hand.


## Enthaltene Funktionalitäten
### PHPUnit Testing

TYPO3 >=8.7
```bash
 php vendor/phpunit/phpunit/phpunit --configuration web/typo3conf/ext/cag_tests/Tests/Build/UnitTests.xml --teamcity
```

for Deprecated Units
```bash
 php vendor/phpunit/phpunit/phpunit --configuration web/typo3conf/ext/cag_tests/Tests/Build/UnitTestsDeprecated.xml --teamcity
``` 

###  Functional Testing 
(phpunit & testing-framework)

####Difference between unit and functional tests
Since TYPO3 6.2, additionally to unit tests, you can also write functional tests for TYPO3.

Unit tests should test only one small piece of code, and should not modify the environment (files, database). However with functional tests you can test the complete functionality.

With TYPO3 CMS version 6.2 the functional test execution and its required setup was streamlined. See Blueprints/StandaloneUnitTests for more details.


https://wiki.typo3.org/Functional_testing


shell script:

Execute all functional tests

TYPO3 >=8.7
```bash
 php vendor/phpunit/phpunit/phpunit --configuration web/typo3conf/ext/cag_tests/Tests/Build/FunctionalTests.xml --teamcity
``` 
###  Acceptance Testing 
(codeception & chromedriver)

https://wiki.typo3.org/Acceptance_testing

####Acceptance Testing since TYPO3 v8
Since the very early version of TYPO3 v8, the core ships with Acceptance tests based on Codeception, which are executed with chromedriver as headless Chrome browser.

As Fetch the TYPO3 sources and installed composer dependencies and start the chromedriver and the PHP HTTP server:

```bash
mkdir -p typo3temp/var/tests 
./bin/chromedriver --url-base=/wd/hub >/dev/null 2>&1 &
php -S 0.0.0.0:8000 >/dev/null 2>&1 &
sleep 3;
```

```bash
typo3DatabaseName='c1_cag_tests' typo3DatabaseHost='localhost' typo3DatabaseUsername='username' typo3DatabasePassword='pw' \
 vendor/codeception/codeception/codecept run Acceptance -c web/typo3conf/ext/cag_tests/Tests/Build/AcceptanceTests.yml
``` 

**TODO:** Beispiel Tests für eigene Extensions!