Документация на русском языке доступна [по ссылке](https://github.com/lis-dev/nova-poshta-api-2/blob/master/README.md)

[![Build Status](https://travis-ci.com/lis-dev/nova-poshta-api-2.svg?branch=master)](https://travis-ci.com/lis-dev/nova-poshta-api-2)

# Nova Poshta API 2.0
Клас надає доступ до функцій API 2.0 служби доставки Нова Пошта

# Підготовка
## Отримання ключа API
Для використання API потрібно: 
* зареєструватися на сайті [Нової Пошти](https://novaposhta.ua)
* На [сторінці налаштувань](https://my.novaposhta.ua/settings/index#apikeys) в особистому кабінеті сгенерувати ключ для роботи з API 

Після отримання ключа API надається можливість використовувати всі методи класу [офіційний сайт з документації](https://my.novaposhta.ua/data/API2-200215-1622-28.pdf)

## Установка останньої версії класу для роботи з API
### Git
Необхідно виконати в командному рядку
```git
git clone https://github.com/lis-dev/nova-poshta-api-2
```
### Composer
Необхідно створити файл ``composer.json`` з наступним змістом
```json
{
    "require": {
        "lis-dev/nova-poshta-api-2": "~0.1.0"
    }
}
```
і запустити з командного рядку команду ``php composer.phar install`` чи ``php composer.phar update``
Або виконати в командному рядку
```
composer require lis-dev/nova-poshta-api-2
```
### Альтернативна установка
Необхідно завантажити архів за посиланням
```
https://github.com/lis-dev/nova-poshta-api-2/archive/master.zip
```
# Формати даних
Для вхідних даних використовуються PHP масиви, відповідь сервера може бути отримана в форматі:
* як PHP масив
* JSON
* XML

# Використання 
## Підключення класу при установці через composer
```php
require __DIR__ . '/vendor/autoload.php';
```

## Підключення класа за альтернативній установці
```php
require '<path_to_dir>/src/Delivery/NovaPoshtaApi2.php';
```

## Створення екземпляру класа
Клас знаходиться в namespace `\LisDev\Delivery`. При створенні екземпляру класу необхідно
або використовувати Full Qualified Class Name:
```php
$np = new \LisDev\Delivery\NovaPoshtaApi2('Ваш_ключ_API_2.0');
```
або вказати namespace що використовується у секції use:
```php
use LisDev\Delivery\NovaPoshtaApi2;
...
$np = new NovaPoshtaApi2('Ваш_ключ_API_2.0');
```

Більш детальну інформацію по роботі з namespace можно отримати [на сайті документації php](https://www.php.net/manual/ru/language.namespaces.rationale.php)

## Створення екземпляра класу (з розширеними параметрами)
Якщо потрібно отримувати дані на мові, відмінної від російської, виключати Exception при помилці запросу, або за відсутності установленої бібліотеки curl на сервері
```php
$np = new NovaPoshtaApi2(
	'Ваш_ключ_API_2.0',
	'ru', // Мова повертаємих даних: ru (default) | ua | en
	FALSE, // При помилці в запросі виключати Exception: FALSE (default) | TRUE
	'curl' // Використовуємий механізм запросу: curl (defalut) | file_get_content
);
```

## Отримання інформації про статус трек-номеру
```php
$result = $np->documentsTracking('59000000000000');
```

## Отримання термінів доставки
```php
// Отримання коду міста за його назвою та області
$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
// Отримання коду міста за його назвою та області
$recipient_city = $np->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
// Дата відправки вантажу
$date = date('d.m.Y');
// Отримання приблизної дати прибуття вантажу між складами в різних містах
$result = $np->getDocumentDeliveryDate($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $date);	
```
## Отримання вартості доставки
```php
// Отримання коду міста за його назвою та області
$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
// Отримання коду міста за його назвою та області
$recipient_city = $np->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
// Вага вантажу
$weight = 7;
// Ціна в грн
$price = 5450;
// Отримання вартості доставки вантажу з зазначеною вагою та вартістю між складами в різних містах
$result = $np->getDocumentPrice($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $weight, $price);
```
## Генерація нової поштової накладної
```php
// Перед генерацією ЕН необхідно отримати дані відправника
// Отримання всіх відправників
$senderInfo = $np->getCounterparties('Sender', 1, '', '');
// Вибір відправника у конкретному місті (у цьому випадку - першому за списком)
$sender = $senderInfo['data'][0];
// Інформація склада відправника
$senderWarehouses = $np->getWarehouses($sender['City']);
// Генерація нової ЕН
$result = $np->newInternetDocument(
    // Дані відправника
    array(
        // Дані користувача
        'FirstName' => $sender['FirstName'],
        'MiddleName' => $sender['MiddleName'],
        'LastName' => $sender['LastName'],
        // Замість FirstName, MiddleName, LastName можна ввести зареєстровані ПІБ відправника чи найменування фірми для юридичних особ
        // (можна отримати за допомогою методу getCounterparties('Sender', 1, '', ''))
        // 'Description' => $sender['Description'],
        // Необов'язкове поле, у випадку відсутності будуть використовуватися з даних контакта
        // 'Phone' => '0631112233',
        // Місто відправлення
        // 'City' => 'Белгород-Днестровский',
        // Область відправлення
        // 'Region' => 'Одесская',
        'CitySender' => $sender['City'],
        // Відділення відправника по ID (у цьому випадку - перше у списку)
        'SenderAddress' => $senderWarehouses['data'][0]['Ref'],
        // Відділення за адресою
        // 'Warehouse' => $senderWarehouses['data'][0]['DescriptionRu'],
    ),
    // Дані отримувача
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
        // Дата відправлення
        'DateTime' => date('d.m.Y'),
        // Тип доставки, додатково - getServiceTypes()
        'ServiceType' => 'WarehouseWarehouse',
        // Тип оплати, додатково - getPaymentForms()
        'PaymentMethod' => 'Cash',
        // Хто сплачує доставку
        'PayerType' => 'Recipient',
        // Вартість вантажу в грн
        'Cost' => '500',
        // Кількість місць
        'SeatsAmount' => '1',
        // Опис вантажу
        'Description' => 'Кастрюля',
        // Тип доставки, додатково - getCargoTypes
        'CargoType' => 'Cargo',
        // Вага вантажу
        'Weight' => '10',
        // Об'єм вантажу в м^3
        'VolumeGeneral' => '0.5',
        // Зворотня доставка
        'BackwardDeliveryData' => array(
            array(
                // Хто оплачує зворотню доставку
                'PayerType' => 'Recipient',
                // Тип доставки
                'CargoType' => 'Money',
                // Значення зворотньої доставки
                'RedeliveryString' => 4552,
            )
        )
    )
);
```
## Отримання складів в зазначеному місті
```php
// В параметрах вказується місто і область (для більш точного пошуку)
$city = $np->getCity('Киев', 'Киевская');
$result = $np->getWarehouses($city['data'][0]['Ref']);
```
## Виклик довільного метода
```php
$result = $np
	->model('Имя_модели')
	->method('Имя_метода')
	->params(array(
		'Имя_параметра_1' => 'Значение_параметра_1',
		'Имя_параметра_2' => 'Значение_параметра_2',
	))
	->execute();
```

# Реалізовані методи для роботи з моделями

## Модель InternetDocument
* save
* update
* delete
* getDocumentPrice
* getDocumentDeliveryDate
* getDocumentList
* getDocument
* printDocument
* printMarkings
* documentsTracking
* newInternetDocument
* generateReport

## Модель Counterparty
* save
* update
* delete
* cloneLoyaltyCounterpartySender
* getCounterparties
* getCounterpartyAddresses
* getCounterpartyContactPersons
* getCounterpartyByEDRPOU
* getCounterpartyOptions

## Модель ContactPerson
* save
* update
* delete

## Модель Address
* save
* update
* delete
* getCities
* getStreet
* getWarehouses
* getAreas
* findNearestWarehouse

## Модель Common
* getTypesOfCounterparties
* getBackwardDeliveryCargoTypes
* getCargoDescriptionList
* getCargoTypes
* getDocumentStatuses
* getOwnershipFormsList
* getPalletsList
* getPaymentForms
* getTimeIntervals
* getServiceTypes
* getTiresWheelsList
* getTraysList
* getTypesOfPayers
* getTypesOfPayersForRedelivery

# Тести
Актуальні тести та приклади використання класу знаходяться у файлі `tests/NovaPoshtaApi2Test.php`

Для запуску тестів локально необхідно виконати в командному рядку
```
composer install
NOVA_POSHTA_API2_KEY=Ваш_ключ_API_2.0 vendor/phpunit/phpunit/phpunit tests
```
