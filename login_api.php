<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');
error_reporting(0);

include 'db.php';
include 'config.php';

$user = new USER1();

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
		try{
		    $username = htmlentities(trim($_REQUEST['username']));
	        $password = htmlentities(trim($_REQUEST['password']));
	        
		    if(empty($username) || empty($password)){
			$json = array("response" => 201, "status" => "Invalid Data");
			json_enc($json );
			exit();
		    }
		    
			$stmt = $user->runQuery("SELECT * FROM user WHERE email='$username' OR phone='$username' LIMIT 1");
			$stmt->execute();
			if($stmt->rowCount()){
        			for($i=0;$object=$stmt->fetch(PDO::FETCH_ASSOC);$i++){
        			    if($object['password'] == md5($password)){
        			        
        			        if($object['status'] == 1){
        			            
        			            $stmt_details = $user->runQuery("SELECT *, us.id AS customer_id FROM user us WHERE us.userid='$username'");
			                    $stmt_details->execute();
			                    if($object_details=$stmt_details->fetch(PDO::FETCH_ASSOC)){
        			                $json = array("response"=>200,"status"=>"success","message"=>"Logged in successfully!","id"=>$object_details['customer_id'],
        			                "user_id"=>$object_details['userid'],"full_name"=>$object_details['full_name'],"email"=>$object_details['email'],"phone"=>$object_details['phone'],
        			                "address_1"=>$object_details['address_1'],"address_2"=>$object_details['address_2'],"city_name"=>$object_details['city'],
        			                "state_name"=>'Karnataka',"country_name"=>'India',"role"=>explode(",",$object_details['role']));
                                    json_enc($json);
                                    exit();
			                    }
            			    }else{
                                $json = array("response"=>207,"status"=>"error","message"=>"Username was disabled. Please contact Service Provider!");
                                json_enc($json);
                                exit();
                                }
        			        
        			    }else{
                            $json = array("response"=>205,"status"=>"error","message"=>"Invalid Password!");
                            json_enc($json);
                            exit();
                            }
        			}
        			
        			json_enc($json);
		        }else{
                    $json = array("response"=>204,"status"=>"error","message"=>"Invalid Username!");
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