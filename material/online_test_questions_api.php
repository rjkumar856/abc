<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    if(!empty($_REQUEST)){
		try{
		    
		    $subject_id = htmlentities(trim($_REQUEST['subject_id']));
	        $question_id = htmlentities(trim($_REQUEST['question_id']));
	        
	        if(empty($subject_id) || empty($question_id)){
			$json = array("response" => 201, "status" => "Invalid Data");
			echo json_encode($json);
			exit;
		    }
		    
		    $query = "SELECT *,otp.date_added as dateAdded  FROM online_test_questions_prelims otp INNER JOIN online_test_prelims_question_set ots ON ots.question_set=otp.question_type WHERE otp.subject_id='$subject_id' AND otp.question_type='$question_id' AND ots.status='1' LIMIT 100";
			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			    for($j = 0;  $object_result = $stmt->fetch(PDO::FETCH_ASSOC); $j++){
				$json['items'][] = array("id"=>$object_result['id'],"subject_id"=>$object_result['subject_id'],"question_id"=>$object_result['question_type'],
				"question_type"=> "Test ".$object_result['question_type'],
				"question_title"=>$object_result['question_title'],
				"option1"=>$object_result['option1'],
				"option2"=>$object_result['option2'],
				"option3"=>$object_result['option3'],
				"option4"=>$object_result['option4'],
				"correct_answer"=>$object_result['correct_answer'],
				"answer_description"=>$object_result['answer_description'],
				"date_added"=>$object_result['dateAdded']);
			   
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
    }else
    {
    $json = array("response"=>203,"status"=>"error","message"=>"Empty Data");
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