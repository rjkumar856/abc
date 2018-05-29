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
		    $name = htmlentities(trim($_REQUEST['name']));
	        $email = htmlentities(trim($_REQUEST['email']));
	        $phone = htmlentities(trim($_REQUEST['phone']));
		    $password = md5(trim($_REQUEST['password']));
	        $city = htmlentities(trim($_REQUEST['city']));
	        $address = htmlentities(trim($_REQUEST['address']));
	        $course_type = htmlentities(trim($_REQUEST['course_type']));
	        
		    if(empty($name) || empty($email) || empty($phone) || empty($password) || empty($city) || empty($course_type)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data");
			json_enc($json );
			exit();
		    }
		    
		    $stmt_check = $user->runQuery("SELECT * FROM user WHERE email='$email'");
		    $stmt_check->execute();
		    if($stmt_check->rowCount() == 0){
		        
		        $stmt_check_mail = $user->runQuery("SELECT * FROM user WHERE phone='$phone'");
    		    $stmt_check_mail->execute();
    		    if($stmt_check_mail->rowCount() == 0){
		   
			$stmt = $user->runQuery("INSERT INTO user(userid,full_name,email,phone,password,city,address,course_type,status) 
			VALUES('$email','$name','$email','$phone','$password','$city','$address','$course_type','1')");
		    $stmt->execute();
		    $new_user_id = $user->lasdID();
        		$json = array("response"=>200,"status"=>"success","message"=>"Registered successfully!","id"=>$new_user_id);
        		json_enc($json);
        		exit();
        		
		      }else{
                $json = array("response"=>208,"status"=>"error","message"=>"This Mobile Number already Exist!");
                json_enc($json);
                exit();
                }
                                
		    }else{
                $json = array("response"=>207,"status"=>"error","message"=>"This Email ID already Exist!");
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