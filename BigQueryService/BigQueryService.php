<?php 
/**
* This Service is used to abstrain the use of the BigQuery from Google using php language. 
* This class already use the cloud-bigquery library from google.

* @author Valdiney França <valdiney.2@hotmail.com>
* @version 0.1
*/

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;

class BigQueryService
{
	private $jsonFile;
	private $datasetId;
	private $tableId;
    
    /*
    * You should pass the path and name of your json file with yours credensials downloaded from google acount
    */
	public function __construct(string $jsonFile) 
	{
		$this->jsonFile = $jsonFile;
	}
    
    /*
    * Creating an instance of the BigQueryClient

    * @return BigQueryClient object
    */
	private function bigQueryClient(): BigQueryClient
	{
		return new BigQueryClient(['keyFilePath' => $this->jsonFile]);
	}
    
    /**
    * This method is used to facility the creation of the your dataset and table.
    * datasetId => Is the name of your dataset.
    * tableid   => Is the name of your table.
    * schema    => Is the schema of your table. Should contain name and type of the fields.

    * @return void
    */
	public function createSchema(string $datasetId, string $tableId, Array $schema)
	{
		# Try create dataset
		try {
			$this->createDataset($datasetId);

		} catch(Exception $e) {
		  echo json_decode($e->getMessage(), true)["error"]["message"];
		  exit;
		}
		
		# Try create table
		try {
			$this->createTable($tableId, $datasetId, $schema);

		} catch(Exception $e) {
		  echo json_decode($e->getMessage(), true)["error"]["message"];
		  exit;
		}
	}
    
    /*
    * This method is use internally in this class to create the dataset.
    * datasetId => Is the name of your dataset.
    * @return void
    */
	private function createDataset(string $datasetId)
	{
		$bigQueryInstance = $this->bigQueryClient();
		$bigQueryInstance->createDataset($datasetId);
	}
    
    /*
    * This method is use internally in this class to create the table.
    * datasetId => Is the name of your dataset.
    * tableid   => Is the name of your table.
    * schema    => Is the schema of your table. Should contain name and type of the fields.

    * @return void
    */
	private function createTable(string $tableId, string $datasetId, Array $schema)
	{
		$bigQueryInstance = $this->bigQueryClient();
	    $dataset = $bigQueryInstance->dataset($datasetId);
	    $dataset->createTable($tableId, ['schema' => ['fields' => $schema]]);
	}
    
    /*
    * Set the tableId.
    * tableid => Is the name of your table.

    * @return void
    */
	public function setTable(string $tableId)
	{
		$this->tableId = $tableId;
	}
     
    /*
    * Set the dataset.
    * dataset => Is the name of your dataset.

    * @return void
    */
	public function setDatasetId(string $datasetId)
	{
		$this->datasetId = $datasetId;
	}

	/*
    * This method is used to store data into your BigQuery lake.
    * data => Is an associative array that shoud have the field name and the value.

    * @return void
    */
	public function insert(Array $data)
	{
		$bigQueryInstance = $this->bigQueryClient();
		$dataset = $bigQueryInstance->dataset($this->datasetId);
        $table = $dataset->table($this->tableId);

		$table->insertRows([['data' => $data]]);
	}
    
    /*
    * This method is used to execute SQL query in your BigQuery lake.
    * query => Is a string with your SQL commands.

    * @return an object with arrays inside.
    */
	public function query(string $query): object
	{
		$bigQueryInstance = $this->bigQueryClient();
		$query = $bigQueryInstance->query($this->interceptingQuery($query));

		return $bigQueryInstance->runQuery($query);
	}
    
    /*
    * This method is used internally in this class to facility the SQL query.
    * This method is used internally in this class to facility the SQL query 
    * that you pass to the insert method. You don´t will need to write the 
    * datasetId and the tableId every time that you write an SQL query.
    * query => The SQL commands passed in query method.

    * @return string
    */
	private function interceptingQuery(string $query): string
	{
		$realPath = $this->datasetId.".".$this->tableId;
		return str_replace("{table}", "`{$realPath}`", $query);
	}
}