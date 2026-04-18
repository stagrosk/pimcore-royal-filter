# Installation
### !!! check if your user has group www-data and docker !!!
### 1. Before project installation you need to run traefik container
```
cd /var/www    
git clone git@github.com:alpin11/traefik-docker.git
cd traefik-docker
docker network create traefik       # this will create network
docker-compose up -d
```

### 2. Create and init project with correct rights
```
cd /var/www 
mkdir <projectdir>
sudo chown `whoami`:www-data <projectdir> -R
sudo setfacl -R -m u:www-data:rwx -m g:www-data:rwx <projectdir>
sudo setfacl -dR -m u:www-data:rwx -m g:www-data:rwx <projectdir>
sudo chmod -R g+s /var/www/<projectdir>
cd <projectdir>
git init
git remote add origin <origin>      # example: git@github.com:alpin11/royal-filter-pimcore.git
git pull
git checkout master 				# or development branch in BT case
```

### 3. Run docker container installations
`docker-compose up -d`

### 4. Go inside of container and install composer packages
```
docker exec -it <projectname-php> bash	# php container name - (stocker-php or stocker-php-debug)
composer install
exit
```

### 5. Go outside of container to set correct rights on project folder
```
cd /var/www/<projectdir>
sudo setfacl -R -m user:`whoami`:rwX -m mask:rwX .
sudo setfacl -dR -m user:`whoami`:rwX -m mask:rwX .
```

### 6. Last time go inside of container to set rights for docker root user
```
cd /var/www/html
setfacl -R -m user:33:rwX -m mask:rwX .;
setfacl -dR -m user:33:rwX -m mask:rwX .;
setfacl -R -m user:100:rwX ./public;
setfacl -dR -m user:100:rwX ./public;
setfacl -R -m user:100:rwX ./var;
setfacl -dR -m user:100:rwX ./var;
```

### This should be done and all files created / edited should be with correct rights

# 7. Setup project ENV files and configurations
Create `.env` file from `.env_example` file and Copy `.env.docker` to `.env.docker.local`
in local file is necessary to enabled debug so PHP_DEBUG=1

# 8. Debug mode enabling
go `var/config` create `debug-mode.php` with content of

```
<?php
return [
    "active" => TRUE,
    "ip" => "",
    "devmode" => TRUE
];
```

# 9. install pimcore - `run in command line of project` - in docker image ofc
```
./vendor/bin/pimcore-install \
   --admin-username=admin \
   --admin-password=admin \
   --mysql-host-socket=royal-filter-db \
   --mysql-username=pimcore \
   --mysql-password=pimcore \
   --mysql-database=pimcore \
   --ignore-existing-config
```

# 10. DONE
now project should be running under traefik url defined in docker-compose.yml in webserver section
```
    royal-filter.local-127-0-0-1.nip.io - for normal without debug mode enabled
    royal-filter-debug.local-127-0-0-1.nip.io - for debug mode enabled
```

# DB copy by docker image 
#### pimcore install is creating DB itself so this is just commands to handle existing DB from devel/production

### Dump db to file
run `docker exec -i royal-filter-mysql mysqldump --host=<IP> --user=dumper --password <DBNAME> --skip-add-locks --skip-comments --single-transaction --no-create-db --no-tablespaces | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' > ~/Downloads/royal-filter_database.dump` - there is dumper password necessary

#### Actual real dump
run `docker exec -i royal-filter-mysql mysqldump --host=localhost --user=pimcore --password pimcore --skip-add-locks --skip-comments --single-transaction --no-create-db --no-tablespaces --ignore-table=pimcore.object_localized_cs_product_de | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' > /var/www/bordel/royal-filter_database.dump`

### Load db to local db from file
run `docker exec -i royal-filter-mysql mysql -upimcore -ppimcore pimcore < royal-filter_database.dump`

# Additional commands:

Clear caches: `rm -rf var/cache; php bin/console cache:clear; php bin/console pimcore:cache:clear;`

Change admin password inside PHP docker container: `php bin/console pimcore:user:reset-password admin`

Rebuild classes: `php bin/console pimcore:deployment:classes-rebuild -c -d`

Install assets: `php bin/console assets:install web --symlink --relative`

Install for Chrome Headless -> https://stackoverflow.com/questions/67407104/error-while-loading-shared-libraries-libgbm-so-1-cannot-open-shared-object-fil

If is create/drop database not working because of permissions then is necessary:
- stop containers
- save actual db to dump or download production db dump to local
- remove or move folder `var/mysql-data`
- run containers - will initialize multiple databases and setup correct rights on created db
- done

---

# Classification Store Translations

Pimcore natívne nepodporuje preklady pre názvy Classification Store groups a keys. Preto bola implementovaná vlastná stratégia pomocou Pimcore Translation systému.

## Ako to funguje

1. **Automatické vytváranie prekladov** - Keď sa pristupuje k produktu alebo kolekcii cez API, `ClassificationStoreTranslationService` automaticky kontroluje, či existujú preklady pre každú classification store group/key
2. **Ak preklady neexistujú** - Automaticky sa vytvoria v Pimcore prekladoch s anglickou hodnotou ako default pre všetky jazyky
3. **Prefixes** - Preklady používajú prefixy:
   - `cs_group_` pre groups (napr. `cs_group_body`)
   - `cs_key_` pre keys (napr. `cs_key_height`)

## Konzolové príkazy

### Preklad cez DeepL API

```bash
# Náhľad čo sa bude prekladať (bez zmien)
php bin/console app:translate-classification-store --dry-run

# Preložiť všetky chýbajúce preklady
php bin/console app:translate-classification-store

# Preložiť len do konkrétneho jazyka
php bin/console app:translate-classification-store --language=de
```

### Manuálna správa prekladov

Preklady je možné spravovať aj priamo v Pimcore admin:
1. Marketing → Translations → Shared Translations
2. Filtrovať podľa kľúča `cs_group_` alebo `cs_key_`
3. Upraviť preklady pre jednotlivé jazyky

## Relevantné súbory

- `src/Service/ClassificationStoreTranslationService.php` - Služba pre správu prekladov
- `src/Command/TranslateClassificationStoreCommand.php` - Konzolový príkaz pre DeepL preklad
- `src/Pimcore/DataObject/Calculator/ParametersConfigCalculator.php` - Kalkulátor pre Product metadata
- `src/Pimcore/Model/DataObject/Collection.php` - Collection model s prekladmi

## API Response formát

```json
{
  "group": "body",
  "groupId": 7,
  "groupTranslations": {
    "en": { "name": "Filter body dimensions" },
    "de": { "name": "Filterkörper Abmessungen" },
    "sk": { "name": "Rozmery tela filtra" }
  },
  "key": "height",
  "keyId": 2,
  "keyTranslations": {
    "en": { "name": "Height" },
    "de": { "name": "Höhe" },
    "sk": { "name": "Výška" }
  },
  "value": "115 mm",
  "rawValue": 115,
  "unit": "mm"
}
```

## Konfigurácia

DeepL API kľúč sa nastavuje v `.env` súbore:
```
DEEPL_AUTH_KEY=your-deepl-api-key
```
