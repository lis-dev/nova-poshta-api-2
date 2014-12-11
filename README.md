# Nova Poshta API 2.0
Класс предоставляет доступ к функциям API 2.0 службы доставки Новая Почта

# Подготовка
## Получение ключа API
Для использования API необходимо: 
* зарегистрироваться на сайте [Новой Почты](http://novaposhta.ua)
* На [странице настроек](https://my.novaposhta.ua/settings/index#apikeys) в личном кабинете сгенерировать ключ для работы с API 

После получения ключа API предоставляется возможность использовать все методы класса [официальной из документации](https://my.novaposhta.ua/data/API2-071114-1736-56.pdf)

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
        "lis-dev/nova-poshta-api-2": "dev-master"
    }
}
```
и запустить из командной строки команду ``php composer.phar install`` или ``php composer.phar update``
### Альтернативная установка
Необходимо скачать архив по ссылке
```
https://github.com/lis-dev/nova-poshta-api-2/archive/master.zip
```
# Форматы данных
Для входящих данных используются PHP массивы, ответ сервера может быть получен в формате:
* как PHP массив
* JSON
* XML

# Использование 
## Создание экземпляра класса
```php
$np = new NovaPoshtaApi2('Ваш_ключ_API_2.0');
```
## Получение информации о трек-номере
```php
$result = $np->documentsTracking('59000000000000');
```
## Получение сроков доставки
```php
$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
$recipient_city = $np->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
$date = date('d.m.Y');
$result = $np->getDocumentDeliveryDate($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $date);	
```
## Получение стоимости доставки
```php
$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
$sender_city_ref = $sender_city['data'][0]['Ref'];
$recipient_city = $np->getCity('Киев', 'Киевская');
$recipient_city_ref = $recipient_city['data'][0]['Ref'];
$weight = 7;
$price = 5450;
$result = $np->getDocumentPrice($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $weight, $price);

```
## Генерирование новой электронной накладной
```php
$result = $np->newInternetDocument(
	array(
		'FirstName' => 'Петр',
		'MiddleName' => 'Петрович',
		'LastName' => 'Петров',
		'Phone' => '0631112233',
		'City' => 'Белгород-Днестровский',
		'Region' => 'Одесская',
		'Warehouse' => 'Отделение №2 (до 30 кг): ул. Дзержинского, 54',
	),
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
		'DateTime' => '13.12.2014',
		'ServiceType' => 'WarehouseWarehouse',
		'PaymentMethod' => 'Cash',
		'PayerType' => 'Recipient',
		'Cost' => '500',
		'SeatsAmount' => '1',
		'Description' => 'Кастрюля',
		'CargoType' => 'Cargo',
		'Weight' => '10',
		'VolumeGeneral' => '0.5',
		'BackwardDeliveryData' => array(
			array(
				'PayerType' => 'Recipient',
				'CargoType' => 'Money',
	 			'RedeliveryString' => 4552,
 			)
		)
	)
);
```
## Получение складов в определенном городе
```php
$city = $np->getCity('Киев', 'Киевская');
$result = $np->getWarehouses($city);
```
## Вызов произвольного метода
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
# Реализованные методы
* __construct()
* setKey()
* getKey()
* setLanguage()
* getLanguage()
* setFormat()
* getFormat()
* model()
* method()
* params()
* execute()
* documentsTracking()
* getCities()
* getWarehouses()
* getWarehouse()
* getStreet()
* getArea()
* getCity()
* __call()
* delete()
* update()
* save()
* getCounterparties()
* cloneLoyaltyCounterpartySender()
* getCounterpartyContactPersons()
* getCounterpartyAddresses()
* getCounterpartyOptions()
* getCounterpartyByEDRPOU()
* getDocumentPrice()
* getDocumentDeliveryDate()
* newInternetDocument()
* printDocument()
* printMarkings()
* findArea()
* findCityByRegion()
* checkInternetDocumentCounterparty()
* checkInternetDocumentParams()
* printGetLink()