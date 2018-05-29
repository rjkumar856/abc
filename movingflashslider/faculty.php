<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    
		    $query = "SELECT * FROM faulty_details ORDER BY date_added ASC";
		    $stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				$json['items'][] = array("id"=>$object['id'],"name"=>$object['name'],"experience"=>$object['experience'],"qualification"=>$object['qualification'],
					"expertise"=>$object['expertise'],"role"=>$object['role'], "image"=>DIR_SYSTEM."assets/files/faculty/".$object['image'],
					
					"date_added"=>$object['date_added'],"date_modified"=>$object['date_modified']);
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