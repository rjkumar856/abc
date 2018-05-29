<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include 'db.php';
include 'config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "GET"){
		try{
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
				$json['items'][] = array("id"=>"1","image"=>DIR_SYSTEM."assets/images/bg/slider1.jpg");
				$json['items'][] = array("id"=>"2","image"=>DIR_SYSTEM."assets/images/bg/slider2.jpg");
				$json['items'][] = array("id"=>"3","image"=>DIR_SYSTEM."assets/images/bg/slider3.jpg");
			echo json_encode($json);
			exit;
	
		
		}
		catch(PDOException $ex){
			$json = array("response" => 202, "status" => "error","message" =>$ex->getMessage());
			echo json_encode($json);
			exit;
		}
}
else
{
$json = array("response" => 201, "status" => "error","message" => "Wrong Method");
echo json_encode($json);
exit;
}
?>