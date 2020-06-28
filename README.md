
## Bus Stop Locator API

**Installation**
1. Clone repository
2. Add `bus-stop-locator-api` to your web server
3. Create database named "bus_stop_locator"
4. Copy `.env.example` to `.env`
4. Update `.env` and set `DB_USERNAME` and `DB_PASSWORD` 
5. Run the following in your terminal
    ```
    $ cd bus-stop-locator-api
    $ composer install
    $ php artisan jwt:secret
    $ php artisan migrate --seed
    ```


**Usage**
1. Login using the following credentials:
> Email: jdoe@gmail.com
> Password: password


