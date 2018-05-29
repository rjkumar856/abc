<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include 'db.php';
include 'config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    if(isset($_REQUEST['date'])){
		    $date = trim($_REQUEST['date']);
		    }
		    
		    $query = "SELECT * FROM quiz_questions";
		    if(isset($_REQUEST['date']) && $date){
		        $query .= " WHERE date='$date'";
		    }else{
		        $query .=" WHERE date=(SELECT date FROM quiz_questions ORDER BY date_added DESC LIMIT 1) ";
		    }
		    $query .=" ORDER BY date_added DESC LIMIT 10";

			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				$json['items'][] = array("id"=>$object['id'],"question_type"=>$object['question_type'],"date"=>$object['date'],"subject_id"=>$object['subject_id'],
					"question_title"=>preg_replace('/\s\s+/', '',nl2br($object['question_title'])),"option1"=>preg_replace('/\s\s+/', '',nl2br($object['option1'])),
					"option2"=>preg_replace('/\s\s+/', '',nl2br($object['option2'])),"option3"=>preg_replace('/\s\s+/', '',nl2br($object['option3'])),
					"option4"=>preg_replace('/\s\s+/', '',nl2br($object['option4'])),
					"correct_answer"=>$object['correct_answer'],"answer_description"=>preg_replace('/\s\s+/', '',nl2br($object['answer_description'])),"date_added"=>$object['date_added']);
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