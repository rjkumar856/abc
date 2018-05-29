<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    $query = "SELECT * FROM online_test_optional GROUP BY subject ASC";
			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
			   $json['subjects'][]=$object['subject'];
			    
			    $query_result = "SELECT * FROM online_test_optional WHERE subject='$object[subject]' ORDER BY date_added DESC";
    			$stmt_result = $user->runQuery($query_result);
    			$stmt_result->execute();
			    for($j = 0;  $object_result = $stmt_result->fetch(PDO::FETCH_ASSOC); $j++){
				$json['items'][] = array("id"=>$object_result['id'],"subject"=>$object_result['subject'],"title"=>htmlentities($object_result['title']),
				"file_url"=> DIR_SYSTEM."assets/files/optional/".$object_result['file_url'],"date_added"=>$object_result['date_added']);
			    }
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