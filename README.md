 /**
  * @description Felhasználóhoz tartozó telefonszámok kezelése
  * @author Juhász István <juhasz87istvan@gmail.com>
  * @datetime 2021.05.16.
  * @version v1.0.0
  */

/* * * * * * *
*            *
* Telepítés  *
*            *
*/ * * * * * *     
0. git clone https://github.com/Juhist/phonenumber-app.git
1.  composer install
2.  Env másolása example-ből .env.example -> .env
3. .env paraméterezése
4. docker-compose up futtatása
    (Jelenleg: php artisan serve -t is futtatni kell)
5. php artisan migrate
6. php artisan migrate:fresh --seed

/* * * * * * * *
*              *
* API hívások  *
*              *
*/ * * * * * * *   
- endpoint -ok a routes/api.php -ban kerültek meghatározásra
- felhasznált modell -ek az app/Models/ könyvtárban
- felhasznált controller -ek az app/Http/Controllers/ könyvtárban

/* * * * * * * * * *
*                  *
* GraphQL hívások  *
*                  *
*/ * * * * * * * * *
- config/graphql.php -ban vannak definiálva a sémák
- app/GraphQL/ mappában belül találhatók a forráskódok: types,queries,mutations

/* * * * * * * * * * * * * * * * * * * *
*                                      *
* Teszthez Postman collection hívások  *
*                                      *
*/ * * * * * * * * * * * * * * * * * * *
- database/migrations-ban a modellekhez tartozik 2 db migration 
- database/seeders/DatabaseSeeder.php feltölti a táblákat teszt adatokkal
- PhoneNumber.postman_collection.json -ben találhatóak az elkészített végponthívások
- Külön mappában a két megvalósításhoz tartozó végpontok

/* * * * * * * * * * * * * *
*                          *
* Felhasznált alkalmazások *
*                          *
*/ * * * * * * * * * * * * *
- Visual Studio COde
- Docker Desktop
    - WSL 2
    - Linux distribution, Linus kernel
    - PostgreSQL image
- Windows Terminal
- GUI PgAdmin

/* * * * * * * * * * * * * *
*                          *
* Működés rövid leírása    *
*                          *
*/ * * * * * * * * * * * * *
- A felhasználó létrehozásakor megadható a telefonszám is, amely alapértelmezett telefonszámként kerül elmentésre.
- Ezt követően a felhasználó azonosítóhoz létrehozott telefonszámoknál megadható, hogy alapértelmezett legyen-e. Amennyiben igen, akkor a végpont a korábban alapértelmezttnek vett telefonszám státuszát módosítja és az új telefonszámot rögzíti alapértelmezettnek.
- Ez igaz a telefonszám módosító végpontnál is. Amennyiben alapértelmezett státusszal küldjük be, de addig nem az volt az alapértelmezett, akkor a korábbit módosítja és ezt menti el a kapott státusszal.
- Egy alapértelmezett telefonszám nem törölhető egészen addig, amíg annak státusza módosításra nem került.
- A felhasználóhoz tartozó végpontok értelemszerűen működnek. Egy felhasználó törlésekor a hozzá tartozó telefonszámok is törlésre kerülnek.