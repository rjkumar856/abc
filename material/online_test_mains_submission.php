<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');
ob_start();

include 'db.php';
include 'config.php';

$user = new USER1();
if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
		try{
		    $user_id = htmlentities(trim($_REQUEST['user_id']));
	        $question_id = htmlentities(trim($_REQUEST['question_id']));
	        $file = trim($_REQUEST['file']);
	        
		    if(empty($user_id) || empty($question_id) || empty($file)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data");
			json_enc($json );
			exit();
		    }
		    
		    $file_name = time().".pdf";
		    $target_path = "../../assets/files/mains/".$file_name;
        	file_put_contents($target_path, base64_decode($imageToUpload3));
        	if(is_file($target_path)){
			$file_name;
			}
		        
		        $stmt_check_mail = $user->runQuery("INSERT INTO online_test_mains_submission(user_id,question_id,file_url,ip) 
			VALUES('$user_id','$question_id','$file_name','')");
    		    if($stmt_check_mail->execute()){
        		$json = array("response"=>200,"status"=>"success","message"=>"Online Test submitted successfully!");
        		json_enc($json);
        		exit();
        		
		      }else{
                $json = array("response"=>208,"status"=>"error","message"=>"Some server error occurs. Try later!");
                json_enc($json);
                exit();
                }
		        
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