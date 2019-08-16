# GoogleBigQueryService
This Service is used to abstrain the use of the BigQuery from Google using php language

### Dependencis
To use this Service you shoul first install the offial library from Google:

```sh
$ composer require google/cloud-bigquery
```

### Usage
To start you sould firts create a dataset and a table:

```php
require_once 'vendor/autoload.php';
require_once 'BigQueryService.php';

# Instanciating the object to be used in the our application
$bigQuery = new BigQueryService("AlarmaAe-b01ab0a3aa4c.json");

# Struturing the schema of the table. 
$schema = [
	[
		"name" => "name",
	  "type" => "string"
	],
	[
		"name" => "age",
	    "type" => "integer"
	]
];

# Creating the dataset and the table
$bigQuery->createSchema("datasetName", "tableName", $schema);
```
