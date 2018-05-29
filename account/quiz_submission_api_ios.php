<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');
ob_start();

include '../db.php';
include '../config.php';

$user = new USER1();
if($_SERVER['REQUEST_METHOD']=="GET"){
    if(!empty($_REQUEST)){
		try{
		    $user_id = htmlentities(trim($_REQUEST['user_id']));
	        $question_id = json_encode(explode("n",$_REQUEST['question_id']));
	        $selected_questions = json_encode(explode("n",$_REQUEST['selected_questions']));
		    $selected_answers =  json_encode(explode("n",$_REQUEST['selected_answers']));
	        $correct_answers = json_encode(explode("n",$_REQUEST['correct_answers']));
	        $total_questions = htmlentities(trim($_REQUEST['total_questions']));
	        $answered_questions = htmlentities(trim($_REQUEST['answered_questions']));
	        $corrected_answers = htmlentities(trim($_REQUEST['corrected_answers']));
	        $wrong_answers = htmlentities(trim($_REQUEST['wrong_answers']));
	        $percentage = htmlentities(trim($_REQUEST['percentage']));
	        $marks = htmlentities(trim($_REQUEST['marks']));
	        $ip = $user->get_client_ip();
	        
		    if(empty($user_id) || empty($question_id) || empty($total_questions)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data");
			json_enc($json );
			exit();
		    }

			$stmt = $user->runQuery("INSERT INTO quiz_result_submission(user_id,question_id,selected_questions,selected_answers,correct_answers,total_questions,answered_questions,corrected_answers,wrong_answers,percentage,marks,ip) 
			VALUES('$user_id','$question_id','$selected_questions','$selected_answers','$correct_answers','$total_questions','$answered_questions','$corrected_answers','$wrong_answers','$percentage','$marks','$ip')");
		    $stmt->execute();
        		$json = array("response"=>200,"status"=>"success","message"=>"Quiz Result added successfully!");
        		json_enc($json);
        		exit();
		        
		}catch(PDOException $ex){
			$json = array("response"=>206,"status"=>"error","message"=>$ex->getMessage());
			json_enc($json);
			exit();
		}
    }else
    {
    $json = array("response"=>203,"status"=>"error","message"=>"Empty Data");
    json_enc($json);
    exit();
    }
}
else
{
$json = array("response"=>202,"status"=>"error","message"=>"Wrong method!");
json_enc($json);
}
exit;

function json_enc($str){
	if(is_array($str)){
		echo $json_response = json_encode($str);
	}
	else{
		$response['response'] = $str;
		echo $json_response = json_encode($response);
	}
	return;
}
?>