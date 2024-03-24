# Currency Converter

## How to start server
https://symfony.com/download - install symfony CLI to start server, or put this code in your existing docker setup

Start web server using command `symfony server:start` it's built in symfony server

## Service provider
Service provider that I intergated name is `ExchangeRates`, please don't make many requests cause they are going to
block you for 20mins if there are many calls to them

## How to use currency converter

Request via this URL: `http://localhost:8000/currency/converter/exchangerates?currencyFrom=EUR&currencyTo=USD&amount=10`

* `currencyFrom` - currency what we want to convert
* `currencyTo` - currency to what we want to convert
* `amount` - amount of `currencyFrom`

## Some notes about this demo
- There are Unit Tests for majority of the code, except controller, and DTOs normally they should be covered
- I didn't cover Controller with Unit\Functional tests, cause of time limit I have for this demo, normally it have 
to be covered by Unit/Functional tests
- I didn't add docker, again by time limit
- I didn't add list of currency pairs that are available, because service provides all currencies
- **In some parts in code I added comments, please read them they are important in context**
- The rest we can discuss in technical interview, I can explain and show everything I've done and how I see it