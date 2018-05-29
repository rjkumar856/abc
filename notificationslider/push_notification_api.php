<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    $query = "SELECT * FROM push_notification ORDER BY date_added DESC LIMIT 1";
		    $stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available","to"=>"/topics/news");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				$json['notification'] = array("title"=>$object['title'],"body"=>$object['body'],"date_added"=>$object['date_added']);
				
				$json['data'] = array("id"=>$object['id'],"date"=>date('d-m-Y',strtotime($object['date_added'])),"time"=>date('G:i:s',strtotime($object['date_added'])));
			}
			echo json_encode($json);
			exit;
		}else{
		    $json = array("response" => 204, "status" => "success", "message"=>"Data Not Available");
		    echo json_encode($json);
		    exit;
		}
		
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