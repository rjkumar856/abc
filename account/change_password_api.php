<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');
ob_start();

include '../db.php';
include '../config.php';

$user = new USER1();
if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
		try{
		    $username = htmlentities(trim($_REQUEST['userid']));
		    $old_password = htmlentities(trim($_REQUEST['old_password']));
	        $new_password = md5(trim($_REQUEST['new_password']));
	        
		    if(empty($username) || empty($old_password) || empty($new_password)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data. Pleas Fill all the datas!");
			json_enc($json );
			exit();
		    }
		   
			$stmt = $user->runQuery("SELECT * FROM user WHERE id='$username'");
		    $stmt->execute();
		    if($stmt->rowCount()){
		    for($i = 0; $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
		        
		        if($object['password'] == md5($old_password)){
		            
		                $stmt_pass = $user->runQuery("UPDATE user SET password='$new_password' WHERE id='$username'");
                        $stmt_pass->execute();
                        
                		$json = array("response"=>200,"status"=>"success","message"=>"Password has been changed successfully!");
                		json_enc($json);
                		exit();
		        }else{
		            $json = array("response" => 205,"status"=>"error","message"=>"Wrong Old Password!");
        			json_enc($json );
        			exit();
		        }
		    }
		    }else{
		        $json = array("response" => 204,"status"=>"error","message"=>"Invalid User ID!");
    			json_enc($json );
    			exit();
		    }
		        
		}catch(PDOException $ex){
			$json = array("response"=>206,"status"=>"error","message"=>$ex->getMessage());
			json_enc($json);
			exit();
		}
    }else
    {
    $json = array("response"=>203,"status"=>"error","message"=>"Empty Data!");
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