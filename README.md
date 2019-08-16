# GoogleBigQueryService
This Service is used to abstrain the use of the BigQuery from Google using php language

### Dependencis
To use this Service you shoul first install the offial library from Google:

```sh
$ composer require google/cloud-bigquery
```

### Inicial usage
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

### Insert data
after creating the dataset and the table, we ready to insert data:

```php
require_once 'vendor/autoload.php';
require_once 'BigQueryService.php';

# Instanciating the object to be used in the our application
$bigQuery = new BigQueryService("AlarmaAe-b01ab0a3aa4c.json");

# The name of dataset
$bigQuery->setDatasetId("datasetName");

# The name of table
$bigQuery->setTable("tableName");

# Inserting data in our table. You shoul pass an associative array to the insert method
$bigQuery->insert(["name" => "Nicolas tesla", "age" => 87]);
```

### Selecting data from our table
To select data is very simple. You just need pass your SQL query to the query method. You need pass the  especial string "{table} "
between the SQL query. This especial string  will be repleced into the class in a format that the BigQuery understand!:

```php
require_once 'vendor/autoload.php';
require_once 'BigQueryService.php';

# Instanciating the object to be used in the our application
$bigQuery = new BigQueryService("AlarmaAe-b01ab0a3aa4c.json");

# The name of dataset
$bigQuery->setDatasetId("datasetName");

# The name of table
$bigQuery->setTable("tableName");

# Your SQL query
$collections = $bigQuery->query("SELECT * FROM {table}");

foreach ($collections as $data) {
    echo "Name: " .$data["name"] . "<br>";
    echo "Age:  " .$data["age"]  . "<br>";
}

```
