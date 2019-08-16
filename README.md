# GoogleBigQueryService
This Service is used to abstrain the use of the BigQuery from Google using php language

### Dependencis
To use this Service you shoul first install the offial library from Google:

```sh
$ composer require google/cloud-bigquery
```

### Usage
To start you sould fisrt create a dataset and an table:

```php
require_once 'vendor/autoload.php';
require_once 'BigQueryService.php';

$bigQuery = new BigQueryService("AlarmaAe-b01ab0a3aa4c.json");
```
