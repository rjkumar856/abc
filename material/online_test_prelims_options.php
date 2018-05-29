<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    $query = "SELECT *,os.id as SubID FROM online_test_questions_prelims oq INNER JOIN online_test_prelims_question_set ots ON ots.question_set=oq.question_type LEFT JOIN online_test_subjects os ON os.id=oq.subject_id WHERE ots.status='1' GROUP BY oq.subject_id ASC";
			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
			   $json['subjects'][]=$object['title'];
			    
			    $query_result = "SELECT * FROM online_test_questions_prelims WHERE subject_id='$object[SubID]' GROUP BY question_type ORDER BY date_added DESC";
    			$stmt_result = $user->runQuery($query_result);
    			$stmt_result->execute();
			    for($j = 1;  $object_result = $stmt_result->fetch(PDO::FETCH_ASSOC); $j++){
				$json['items'][] = array("id"=>$object_result['id'],"subject"=>$object['title'],"subject_id"=>$object_result['subject_id'],"question_id"=>$object_result['question_type'],
				"question_type"=> "Test ".$object_result['question_type'],"date_added"=>$object_result['date_added']);
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