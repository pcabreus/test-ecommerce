# Test for Drink&Co

## Install the project

1. Clone the project and go into the root code:

    ```
    ~$ git clone https://github.com/pcabreus/test-drink-co
    ~$ cd test-drink-co
    ```
   
2. Run the container and go into the container:

   ```
   ~$ docker-compose up -d
   ~$ docker exec -it drink_co_php bash
   ```
   
3. Install dependencies and configurations:
    
   ```
   :/usr/src/app# symfony composer install
   ```
   
## Run tests

You can run `./vendor/bin/simple-phpunit` inside of the container in order to run the test


## Run symfony

Test the exchange with the real api call:
 
    ```
    :/usr/src/app# symfony console app:test
    ```
