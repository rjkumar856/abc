<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
    		try{
		    $username = htmlentities(trim($_REQUEST['userid']));
		    $month = htmlentities(trim($_REQUEST['month']));
		    $year = htmlentities(trim($_REQUEST['year']));
		    $type = htmlentities(trim($_REQUEST['type']));
		    
		    if(empty($username) || empty($month) || empty($year) || empty($type)){
			$json = array("response" => 201, "status" => "Invalid Data");
			json_enc($json );
			exit();
		    }
		    
		    $json = array("response" => 200, "status" => "succes", "message"=>"Data Not Available","items"=>'');
		    
				$stmt = $user->runQuery("SELECT *,wp.id as PaymentID,cp.name as cp_name FROM wvc_payment wp INNER JOIN wvc_transaction wt ON wt.id=wp.transaction_id LEFT JOIN cable_package cp ON cp.id=wp.Package_id LEFT JOIN internet_pack ip ON ip.id=wp.Package_id 
				WHERE wp.user_id='$username' AND wp.Package_type='$type' AND YEAR(wp.Date_added) ='$year' AND MONTH(wp.Date_added)='$month' LIMIT 1");
				$stmt->execute();
				for($i = 0; $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				    $json['message']="Data Available";
					$json['items'][]= array("id"=>$object['PaymentID'], "user_id"=>$object['user_id'],"transaction_id"=>$object['mer_txn'],"mmp_txn"=>$object['mmp_txn'],"Type"=>$object['Type'],"Package_type"=>($object['Package_type']==1)?"Broadband":"Cable",
					"Package_id"=>$object['Package_id'],"Package_name"=>($object['Package_type']==2)?$object['cp_name']:$object['Speed']."-".$object['Data_Transfer']." @Rs.".$object['Traiff'],"Amount"=>$object['Amount'],"Status"=>$object['Status'],"Paid_on"=>$object['Date_added'],"bank_txn"=>$object['bank_txn'],
					"bank_name"=>$object['bank_name'],"discriminator"=>$object['discriminator'],"desc"=>$object['descr'],"Paid_on"=>$object['Date_added']);
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
$json = array("response"=>204,"status"=>"error","message"=>"Wrong method!");
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