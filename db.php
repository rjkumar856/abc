<?php
error_reporting(0);

class Database1{
private $host = "localhost";
private $db_name = "iasshiks_application";
private $username = "iasshiks_shiksha";
private $password = "Igate@123";
public $conn;

public function dbConnection1()
{

$this->conn = null;    
try
{
$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
}
catch(PDOException $exception)
{
echo "Connection error: " . $exception->getMessage();
}

return $this->conn;
}

}

class USER1{
private $conn;

public function __construct(){
$database = new Database1();
$db = $database->dbConnection1();
$this->conn = $db;
}

public function runQuery($sql){
$stmt = $this->conn->prepare($sql);
return $stmt;
}

public function lasdID(){
$stmt = $this->conn->lastInsertId();
return $stmt;
}

public function redirect($url)
{
header("Location: $url");
}

public function get_client_ip()
		{
    		$ipaddress = '';
    		if (isset($_SERVER['HTTP_CLIENT_IP'])){
        		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    		}
    		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    		}
    		else if(isset($_SERVER['HTTP_X_FORWARDED'])){
        		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    		}
    		else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
        		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    		}
    		else if(isset($_SERVER['HTTP_FORWARDED'])){
        		$ipaddress = $_SERVER['HTTP_FORWARDED'];
    		}
    		else if(isset($_SERVER['REMOTE_ADDR'])){
        		$ipaddress = $_SERVER['REMOTE_ADDR'];
    		}
    		else{
        		$ipaddress = 'UNKNOWN';
    		}
    		return $ipaddress;
		}

}

?>