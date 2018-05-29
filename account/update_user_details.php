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
		    $email = htmlentities(trim($_REQUEST['email']));
	        $mobile = htmlentities(trim($_REQUEST['mobile']));
	        $full_name = htmlentities(trim($_REQUEST['full_name']));
	        $address = htmlentities(trim($_REQUEST['address']));
	        
		    if(empty($username) || empty($email) || empty($mobile) || empty($full_name) || empty($address)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data. Pleas Fill all the datas!");
			json_enc($json );
			exit();
		    }
		   
			$stmt = $user->runQuery("SELECT * FROM user WHERE id='$username'");
		    $stmt->execute();
		    if($stmt->rowCount()){
		        
		                $stmt_details = $user->runQuery("UPDATE user SET full_name='$full_name',email='$email',phone='$mobile' WHERE id='$username'");
                        $stmt_details->execute();
                        $stmt_details = $user->runQuery("UPDATE profile SET address_1='$address' WHERE id='$username'");
                        $stmt_details->execute();
                        
                		$json = array("response"=>200,"status"=>"success","message"=>"User Details updated successfully!");
                		json_enc($json);
                		exit();

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