<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    $query = "SELECT * FROM course_details";
			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			$sql_result = "SELECT * FROM course_details WHERE page_id=3";
                $stmt_result = $user->runQuery($sql_result);
                $stmt_result->execute();
                for($j = 0;  $row_object =$stmt_result->fetch(PDO::FETCH_ASSOC); $j++){
				$json['items'][] = array("id"=>$row_object['id'],"page_id"=>$row_object['page_id'],"title"=>$row_object['title'],"contents"=>$row_object['contents'],"date_added"=>$row_object['date_added'],"date_modified"=>$row_object['date_modified']);
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