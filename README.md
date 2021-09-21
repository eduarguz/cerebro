<p align="center"><img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/881e9519707461.562deefac8827.jpg" width="400"></p>

## Cerebro

This project is the solution to a technical challenge proposed by the mercadolibre team.

## About Cerebro

This project is developed using [Laravel](https://laravel.com/) (Laravel is a web application framework for php). For this reason, from the beginning it comes with many files that facilitate the development of web applications.

## How to read this code

In order to check if a DNA is mutant DNA we will be using:

**API**

- [routes/api.php](./routes/api.php) 
  - Defines `/mutant` and `/stats` api routes.

- [app/Http/Controllers/MutantController.php](app/Http/Controllers/MutantController.php)
  - Handles incoming HTTP Requests
  - Creates DNA test record in database
  - Calls our `MutantTester` to see if the DNA is mutant DNA
  - Responds with http response

- [app/MutantTester.php](app/MutantTester.php) 
  - Receives DNA Dataset
  - Performs early validations for non standard DNA structures
  - Validates ocurrences of known sequences by rows, columns and diagonals

**Tests**

- [tests/Feature/CheckMutantTest.php](./tests/Feature/CheckMutantTest.php)
  - Feature tests for `/mutant` endpoint

- [tests/Feature/StatsTest.php](./tests/Feature/StatsTest.php)
  - Feature tests for `/stats`

- [coverage/phpunit/html/index.html](./coverage/phpunit/html/index.html)
  - Html generated report for test coverage.
  - Open the file in your browser to see the report
  - TLDR; 100% Coverage*
  - *Some unused framework files were ignored.

## Installing

You need to have a web server with php 7.4

There are many options to achieve this:
- The easiest way is [Laravel Valet](https://laravel.com/docs/8.x/valet#introduction)
- You may use [Homestead](https://laravel.com/docs/8.x/homestead#introduction)
- Or [Laragon](https://laragon.org/)
- ...

**Clone this repo**

```
git clone https://github.com/eduarguz/cerebro
```

**Install dependencies**

```
composer install
```

**Create environment**

```
cp .env.example .env
```

Defaults are just fine for testing but you may tweak any change you need.

**Run local server**

```
php artisan serve
```

Your server will be running locally at `http://127.0.0.1:8000`


## Using this API

You can use any http request maker, in this case we will use [curl](https://curl.se/)

Remember to change the `{BASE_URL}`
- Local testing `http://127.0.0.1:8000`
- Live endpoint `meli.casshi.com`

**Check Mutant DNA**

```
curl --location --request POST 'http://127.0.0.1:8000/mutant' \
--header 'Content-Type: application/json' \
--data-raw '{
    "dna": [
        "AAGCCA",
        "CAGTGC",
        "TTAAGT",
        "CGTAGG",
        "CCTCTA",
        "TCACTC"
    ]
}'
```

**Get Stats**

```
curl --location --request GET 'http://127.0.0.1:8000/stats'
```

