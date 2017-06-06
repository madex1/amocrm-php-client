# amocrm-php-client

PHP клиент для API AmoCRM

## Авторизация

В amo/amo.php укажите свои имя аккаунта, email и api ключ:

```php
public $subdomain = 'taxismartcity'; //default
public $user_login = ''; //default
public $user_hash = ''; //default
```

для авторизации

```php
$amo = new Amo;
$amo->login();
```

## Создание неразобранных

examples/unsorted.php

## Создание сделок и контактов

examples/addlead.php

## Чтобы узнать id полей 

используйте файл
examples/accinfo.php

## Правовая информация

Вы можете копировать, клонировать и дорабатывать эту апишку как вашей душе угодно. Присылайте pull-requestы с улучшениями и новыми примерами. 

Этот код распространяется свободно и как есть.

## Внедрение и интерация с AmoCRM

По вопросам интеграции ваших сайтов с AmoCRM обращайтесь:
* [контакты](http://madex-design.ru/contacts/)
* telegram @madexdesign

