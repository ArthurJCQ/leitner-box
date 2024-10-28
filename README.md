# Leitner Box
This app is a tutorial for some clean architecture concepts.

Leitner Box allows you to boost the way you learn new things with flash cards and daily tests.

## Installation
Use docker compose to launch postresql db and mailpit
```shell
docker-compose up -d
```

Use symfony command to start dev server
```shell
symfony server:start
```

Then, create database & run migrations with doctrine console.

Mailpit should be available at http://localhost:1025

## Usage
Create some Cards, and run the following command to receive an email in mailpit.

Click on the link in the email to start the daily test.

If you type the right answer: the delay before next test will increase for this card. (The delay ladder is: [1, 3, 7, 15, 30, 60])
If you type a wrong answer: the initial test date is reset, and the card will be presented to you again the next day.

Card need to be active and have an initial test date to be able to be tested.

## Code quality
A coding standard lib is installed on this project, and checks are run on each PR.

To run phpcs:
```shell
./vendor/bin/phpcs src
```

To run phpstan:
```shell
./vendor/bin/phpstan analyse --level=max src
```

To run phpmd:
```shell
./vendor/bin/phpmd src text phpmd.xml
```
