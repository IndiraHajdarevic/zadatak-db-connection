<?php

/**
 * Tvoj zadatak je da prepraviš kod tako da uključuje sledeće stavke:
 * - Nasledjivanje je implementirano.
 * - DBWrapper klasa moze praviti razlicite instance koji se mogu konektovati na razlicite DB servere u isto vrijeme ( Mysql, SQLite... )
 * - Instanca klase people moze da se učita na stranici ( par polja ispisati ), i preko webServisa ( json izlaz ) .
 *
 * Bilo bi dobro da pratiš standarde pisanja koda sa: http://www.php-fig.org
 */

class DBWrapper
{
	private static $instance;
	private $connection;

	private function __construct()
	{
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function connect($host, $user, $pass)
	{
		$this->connection = @mysql_connect($host, $user, $pass) or die(mysql_error());
	}

	public function disconnect()
	{
		mysql_close($this->connection);
	}

	public function query($sql)
	{
		return array();
	}
}

class People
{
	public $id;
	public $name;

	public function load($id)
	{
		$rowset = DBWrapper::getInstance()->Query("SELECT * FROM user WHERE id = " . $id);
		foreach ($rowset as $row) {
			// ...
		}
	}

	public function save()
	{
		if ($this->id) {
			DBWrapper::getInstance()->Query("UPDATE user SET name = '" . $this->name . "' WHERE id = " . $id);
		} else {
			DBWrapper::getInstance()->Query("INSERT INTO user (name) VALUES('" . $this->name . "')");
		}
	}
}

DBWrapper::getInstance()->connect('localhost', 'test', 'test');

$person = new People();
$person->load(1);