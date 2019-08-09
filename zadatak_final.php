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
	
	private $host;
	private $user;
	private $pass;
	private $db_name;

	private function __construct($host, $user, $pass )
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self($host , $user, $pass);
		}
		return self::$instance;
	}

	public function connect($host, $user, $pass,$database_name)
	{
		$this->connection = @mysql_connect($host, $user, $pass) or die(mysql_error());
		$this->db_name = @mysql_select_db($database_name, $this->connection) or die(mysql_error());
	}
	

	public function disconnect()
	{
		mysql_close($this->connection);
	}

	public function query($sql)
	{
        $result = mysql_query($sql);
       
		if(is_resource($result)){
		$count = mysql_num_rows($result);
			if($count>0){
       			$row = mysql_fetch_object($result); 
				return $row;
			}
		}
		
	}

}

class People
{
	public $id;
	public $name;
	public $last_name;
	public $age;

	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getLastName(){
		return $this->last_name;
	}
	public function setLastName($last_name){
		$this->last_name = $last_name;
	}
	public function getAge(){
		return $this->age;
	}
	public function setAge($age){
		$this->age = $age;
	}

	public function load($id)
	{
		$rowset = DBWrapper::getInstance()->Query("SELECT * FROM user WHERE id = " . $id);
		foreach ($rowset as $key=>$row) {
			$this->$key = $row;
		}
		return $this;
	}

	public function save()
	{
		if ($this->id) {
			DBWrapper::getInstance()->Query("UPDATE user SET name = '" . $this->name . "' WHERE id = " . $id);
		} else {
			DBWrapper::getInstance()->Query("INSERT INTO user SET name='$this->name', last_name='$this->last_name', age='$this->age'");
		}
	}
	public function load_via_webservice($id){
		$rowset = DBWrapper::getInstance()->Query("SELECT * FROM user WHERE id = " . $id);
		foreach ($rowset as $key=>$row) {
			$this->$key = $row;
		}
		echo json_encode(array($rowset));
		return $this;
	}

	public function show_output_in_a_table($person){
	   echo "<table>";
       echo "<tr>";
       echo "<th>First Name</th>";
       echo "<th>Last Name</th>";
       echo "<th>Age</th>";
       echo "</tr>";
	   echo "<td>" . $this->getName(). "</td>"; 
	   echo "<td>" . $this->getLastName(). "</td>"; 
	   echo "<td>" . $this->getAge(). "</td>";           
	   echo "</tr>";
	   echo "</table>";
	}
}
function break_line(){
	echo "<br>";
}

DBWrapper::getInstance()->connect('mysql1.000webhost.com', 'a4761197_haris', '123haris123','a4761197_walter');
echo "Citanje korisnika iz baze: ";
$person = new People();
$person = $person->load(141);
$person->show_output_in_a_table($person);

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(141);
break_line();

break_line();
echo "Dodavanje korisnika u bazu" . PHP_EOL;
$person = new People();
$person->setName("Haris");
$person->setLastName("Hajdarevic");
$person->setAge(23);
$person->save();
$person->show_output_in_a_table($person);
break_line();

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(142);
break_line();

break_line();
echo "Citanje korisnika iz baze" . PHP_EOL;
$person = new People();
$person = $person->load(142);
$person->show_output_in_a_table($person);
break_line();

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(142);
break_line();

break_line();
echo "Izmjena imena korisnika Haris u Demir" . PHP_EOL;
$person->id = 142;
$person->name = 'Demir';
$person->save();
$person->show_output_in_a_table($person);
break_line();

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(142);
break_line();

break_line();
echo "Izmjena korisnika Demir " . PHP_EOL;
$person->id = 142;
$person->name = 'Test';
$person->age = '88';
$person->save();
$person->show_output_in_a_table($person);
break_line();

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(142);
break_line();

break_line();
echo "Dodavanje novog korisnika u bazu" . PHP_EOL;
$person = new People();
$person->setName("Ja");
$person->setLastName("sam dodan");
$person->setAge(35);
$person->save();
$person->show_output_in_a_table($person);
break_line();

break_line();
echo "Ispis u json formatu:" . PHP_EOL;
$person->load_via_webservice(143);
break_line();
?>



