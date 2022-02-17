Документація українською мовою
доступна [за посиланням](https://github.com/lis-dev/nova-poshta-api-2/blob/master/README.ua.md)

[![Build Status](https://travis-ci.com/lis-dev/nova-poshta-api-2.svg?branch=master)](https://travis-ci.com/lis-dev/nova-poshta-api-2)

# Nova Poshta API 2.0

Класс предоставляет доступ к функциям API 2.0 службы доставки Новая Почта

# Подготовка

## Получение ключа API

Для использования API необходимо:

* зарегистрироваться на сайте [Новой Почты](http://novaposhta.ua)
* На [странице настроек](https://my.novaposhta.ua/settings/index#apikeys) в личном кабинете сгенерировать ключ для
  работы с API

После получения ключа API предоставляется возможность использовать все методы
класса [официальной из документации](https://my.novaposhta.ua/data/API2-200215-1622-28.pdf)

## Установка последней версии класса для работы с API

### Git

Необходимо выполнить в командной строке

```git
git clone https://github.com/lis-dev/nova-poshta-api-2
```

### Composer

Необходимо создать файл ``composer.json`` со следующим содержанием

```json
{
  "require": {
    "lis-dev/nova-poshta-api-2": "~0.1.0"
  }
}
```

и запустить из командной строки команду ``php composer.phar install`` или ``php composer.phar update``
Или выполнить в командной строке

```
composer require lis-dev/nova-poshta-api-2
```

### Альтернативная установка

Необходимо скачать архив по ссылке

```
https://github.com/lis-dev/nova-poshta-api-2/archive/master.zip
```

# Форматы данных

Для входящих данных используются PHP массивы, ответ сервера может быть получен в следующих форматах:

* `PHP array` по умолчанию
* `JSON`
* `XML`

# Использование

## Подключение класса при установке через composer

```php
require __DIR__ . '/vendor/autoload.php';
```

## Подключение класса при альтернативной установке

```php
require '<path_to_dir>/src/NovaPoshta.php';
```

## Создание экземпляра класса

Класс по умолчанию находится в namespace `\LisDev`. При создании экземпляра класса необходимо или использовать Full
Qualified Class Name:

```php
$np = new \LisDev\NovaPoshta('Ваш_ключ_API_2.0');
```

или указать используемый namespace в секции use:

```php
use LisDev\NovaPoshta;
...
$np = new NovaPoshta('Ваш_ключ_API_2.0');
```

Более подробную информацию по работе с namespace можно
получить [на сайте документации php](https://www.php.net/manual/ru/language.namespaces.rationale.php)

## Создание экземпляра класса (с расширенными параметрами)

Рекомендуется использовать, если необходимо получать данные на языке, отличном от русского, выбрасывать Exception при
ошибке запроса, или при отсутствии установленной библиотеки curl на сервере.

Для расширенной настройки используется класс `\LisDev\Config`. Настройка возможна как через параметры
конструктора `Config`, так и через методы-сеттеры.

```php
$config = new \LisDev\Config(
    \LisDev\Constants\Language::RU,
    \LisDev\Constants\Connection::CURL
);
$config
    ->setLanguage(\LisDev\Constants\Language::EN)
    ->setConnectionType(\LisDev\Constants\Connection::FILE)
    ->setTimeout(10)
    ->setFormat(\LisDev\Constants\Format::JSON)
    ->setThrowError(true);
$np = new \LisDev\NovaPoshta('Ваш_ключ_API_2.0', $config);
```

## Вызов метода модели и получение результатов

* Произвольный метод (не реализованный явно в SDK)

```php
$params = array(
  'Имя_параметра_1' => 'Значение_параметра_1',
  'Имя_параметра_2' => 'Значение_параметра_2',
);
$result = $np
	->model('Имя_модели')
	->method('Имя_метода', $params);
```

* Реализованный метод (названия совпадают с [документацией](https://devcenter.novaposhta.ua/docs/services/), список
  реализованных моделей и методов находится в конце README.md)

```php
$result = $np->Common()->getMessageCodeText();
$result = $np->InternetDocument()->getDocumentList($params);
// И так далее
```

## Получение информации о трек-номере

```php
$result = $np
    ->InternetDocument()
    ->documentsTracking('59000000000000');
```

## Получение сроков доставки

```php
// Получение кода города, откуда будет отправление, по названию города и области
$sender_city = $np
    ->Address()
    ->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
// Получение кода города, куда нужно доставить отправление,  по названию города и области
$recipient_city = $np
    ->Address()
    ->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
// Дата отправки груза
$date = date('d.m.Y');
// Получение ориентировочной даты прибытия груза между складами в разных городах
$result = $np
    ->InternetDocument()
    ->getDocumentDeliveryDate($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $date);	
```

## Получение стоимости доставки

```php
// Получение кода города, откуда будет отправление, по названию города и области
$sender_city = $np
    ->Address()
    ->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
// Получение кода города, куда нужно доставить отправление,  по названию города и области
$recipient_city = $np
    ->Address()
    ->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
// Вес товара
$weight = 7;
// Цена в грн
$price = 5450;
// Получение стоимости доставки груза с указанным весом и стоимостью между складами в разных городах 
$result = $np
    ->InternetDocument()
    ->getDocumentPrice($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $weight, $price);
```

## Генерирование новой электронной накладной

```php
// Перед генерированием ЭН необходимо получить данные отправителя
// Получение всех отправителей
$senderInfo = $np
    ->Counterparty()
    ->getCounterparties('Sender', 1, '', '');
// Выбор отправителя в конкретном городе (в данном случае - в первом попавшемся)
$sender = $senderInfo['data'][0];
// Информация о складе отправителя
$senderWarehouses = $np
    ->Address()
    ->getWarehouses($sender['City']);
// Генерирование новой накладной
$result = $np
    ->InternetDocument()
    ->newInternetDocument(
    // Данные отправителя
      array(
          // Данные пользователя
          'FirstName' => $sender['FirstName'],
          'MiddleName' => $sender['MiddleName'],
          'LastName' => $sender['LastName'],
          // Вместо FirstName, MiddleName, LastName можно ввести зарегистрированные ФИО отправителя или название фирмы для юрлиц
          // (можно получить, вызвав метод getCounterparties('Sender', 1, '', ''))
          // 'Description' => $sender['Description'],
          // Необязательное поле, в случае отсутствия будет использоваться из данных контакта
          // 'Phone' => '0631112233',
          // Город отправления
          // 'City' => 'Белгород-Днестровский',
          // Область отправления
          // 'Region' => 'Одесская',
          'CitySender' => $sender['City'],
          // Отделение отправления по ID (в данном случае - в первом попавшемся)
          'SenderAddress' => $senderWarehouses['data'][0]['Ref'],
          // Отделение отправления по адресу
          // 'Warehouse' => $senderWarehouses['data'][0]['DescriptionRu'],
      ),
      // Данные получателя
      array(
          'FirstName' => 'Сидор',
          'MiddleName' => 'Сидорович',
          'LastName' => 'Сиродов',
          'Phone' => '0509998877',
          'City' => 'Киев',
          'Region' => 'Киевская',
          'Warehouse' => 'Отделение №3: ул. Калачевская, 13 (Старая Дарница)',
      ),
      array(
          // Дата отправления
          'DateTime' => date('d.m.Y'),
          // Тип доставки, дополнительно - getServiceTypes()
          'ServiceType' => 'WarehouseWarehouse',
          // Тип оплаты, дополнительно - getPaymentForms()
          'PaymentMethod' => 'Cash',
          // Кто оплачивает за доставку
          'PayerType' => 'Recipient',
          // Стоимость груза в грн
          'Cost' => '500',
          // Кол-во мест
          'SeatsAmount' => '1',
          // Описание груза
          'Description' => 'Кастрюля',
          // Тип доставки, дополнительно - getCargoTypes
          'CargoType' => 'Cargo',
          // Вес груза
          'Weight' => '10',
          // Объем груза в куб.м.
          'VolumeGeneral' => '0.5',
          // Обратная доставка
          'BackwardDeliveryData' => array(
              array(
                  // Кто оплачивает обратную доставку
                  'PayerType' => 'Recipient',
                  // Тип доставки
                  'CargoType' => 'Money',
                  // Значение обратной доставки
                  'RedeliveryString' => 4552,
              )
          )
      )
);
```

## Получение складов в определенном городе

```php
// В параметрах указывается город и область (для более точного поиска)
$city = $np
    ->Address()
    ->getCity('Киев', 'Киевская');
$result = $np
    ->Address()
    ->getWarehouses($city['data'][0]['Ref']);
```

# Реализованные модели и методы для работы с ними

## Модель InternetDocument

* `save`
* `update`
* `delete`
* `getDocumentPrice`
* `getDocumentDeliveryDate`
* `getDocumentList`
* `getDocument`
* `printDocument`
* `printMarkings`
* `documentsTracking`
* `newInternetDocument`
* `generateReport`

## Модель Counterparty

* `save`
* `update`
* `delete`
* `cloneLoyaltyCounterpartySender`
* `getCounterparties`
* `getCounterpartyAddresses`
* `getCounterpartyContactPersons`
* `getCounterpartyByEDRPOU`
* `getCounterpartyOptions`

## Модель ContactPerson

* `save`
* `update`
* `delete`

## Модель Address

* `save`
* `update`
* `delete`
* `getCities` (+ `getCity`)
* `getStreet`
* `getWarehouses` (+ `getWarehouse`)
* `getAreas` (+ `getArea`)
* `findNearestWarehouse`
* `getWarehouseTypes`

## Модель Common

* `getTypesOfCounterparties`
* `getBackwardDeliveryCargoTypes`
* `getCargoDescriptionList`
* `getCargoTypes`
* `getDocumentStatuses`
* `getOwnershipFormsList`
* `getPalletsList`
* `getPaymentForms`
* `getTimeIntervals`
* `getServiceTypes`
* `getTiresWheelsList`
* `getTraysList`
* `getTypesOfPayers`
* `getTypesOfPayersForRedelivery`
* `getMessageCodeText`

# Тесты

Актуальные тесты и примеры использования класса находятся в файле `tests/NovaPoshtaApi2Test.php`

Для запуска тестов локально необходимо выполнить в командной строке

```
composer install
NOVA_POSHTA_API2_KEY=Ваш_ключ_API_2.0 vendor/phpunit/phpunit/phpunit tests
```

В случае, если вы используете более актуальную версию `PHP`, нежели `v5.*` - тесты могут не запустится по причине
несовместимости `PHPUnit v4.*` с более современными версиями `PHP`. В таком случае, вы можете запустить тесты при
помощи `Docker Compose` (не забудьте установить зависимости `composer` заново, через контейнер). Сделать это можно
следующим образом:

```shell
sudo rm -Rf vendor/ && sudo rm -Rf composer.lock
docker compose up -d
docker exec -it php5.3 composer install
docker exec -it php5.3 sh -c "NOVA_POSHTA_API2_KEY=Ваш_ключ_API_2.0 vendor/phpunit/phpunit/phpunit tests"
```