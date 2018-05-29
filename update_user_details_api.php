<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');
ob_start();
include 'db.php';
include 'config.php';

$user = new USER1();
if($_SERVER['REQUEST_METHOD']=="GET" || $_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
		try{
		    $id = htmlentities(trim($_REQUEST['id']));
		    $name = htmlentities(trim($_REQUEST['name']));
	        $email = htmlentities(trim($_REQUEST['email']));
	        $phone = htmlentities(trim($_REQUEST['phone']));
	        $city = htmlentities(trim($_REQUEST['city']));
	        $address = htmlentities(trim($_REQUEST['address']));
	        
		    if(empty($id) | empty($name) || empty($email) || empty($phone) || empty($city)){
			$json = array("response" => 201,"status"=>"error","message"=>"Pls fill all the Required fields");
			json_enc($json );
			exit();
		    }
		    
		    $stmt_check = $user->runQuery("SELECT * FROM user WHERE id='$id'");
		    $stmt_check->execute();
		    if($stmt_check->rowCount()){
		   
            			$stmt = $user->runQuery("UPDATE user SET full_name='$name',email='$email',phone='$phone',city='$city',address='$address' WHERE id='$id'");
            		    $stmt->execute();
            		    $new_user_id = $user->lasdID();
                		$json = array("response"=>200,"status"=>"success","message"=>"User Details updated successfully!");
                		json_enc($json);
                		exit();
                                
		    }else{
                $json = array("response"=>207,"status"=>"error","message"=>"This User ID does not Exist!");
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