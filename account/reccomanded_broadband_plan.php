<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin:*');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
    		try{
		    $username = htmlentities(trim($_REQUEST['userid']));
		    if(empty($username)){
			$json = array("response" => 201, "status" => "Invalid Data");
			json_enc($json );
			exit();
		    }
		    
		    $json = array("response" => 200, "status" => "succes", "message"=>"Data Not Available");
				$stmt1 = $user->runQuery("SELECT *, ip.id as packageID, nt.Name as Provider_name, ip.Name as Plan_name FROM internet_pack ip INNER JOIN user_has_broadband ub ON ub.package_id!=ip.id INNER JOIN network_type nt ON nt.id=ip.Network_id WHERE ub.user_id='$username'");
				$stmt1->execute();
				for($i = 0;  $object1 = $stmt1->fetch(PDO::FETCH_ASSOC); $i++)  
				{
				    $json['message']="Data Available";
					$json[]= array("id"=>$object1['packageID'], "id"=>$object1['packageID'], "Provider_name"=>$object1['Provider_name'],"Plan_name"=>($object1['Plan_name'])?$object1['Plan_name']:$object1['Speed']."-".$object1['Data_Transfer']." @Rs.".$object1['Traiff'],"Speed"=>$object1['Speed'],"Data_Transfer"=>$object1['Data_Transfer'],"After_Fup"=>$object1['After_Fup'],
					"Traiff"=>$object1['Traiff'],"GST"=>$object1['GST'],"Total"=>$object1['Total'],"Validity"=>$object1['Validity']);
				}
			json_enc($json);
		}
		catch(PDOException $ex){
			$json = array("response" => 202,"username"=>$username, "status" =>$ex->getMessage());
			json_enc($json);
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