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
		    $user_id = trim($_REQUEST['user_id']);
		    $udf9=trim($_REQUEST['udf9']);
		    $package_details=json_decode($_REQUEST['udf9']);
	        $package_id = $package_details->package_id;
	        $package_type = $package_details->package_type;

        	        $payment_response=json_encode($_REQUEST);
        	        $mmp_txn = trim($_REQUEST['mmp_txn']);
	                $mer_txn = trim($_REQUEST['mer_txn']);
	                $amt = trim($_REQUEST['amt']);
	                $prod = trim($_REQUEST['prod']);
	                $clientcode = trim($_REQUEST['clientcode']);
	                $date = trim($_REQUEST['date']);
	                $bank_txn = trim($_REQUEST['bank_txn']);
	                $f_code = trim($_REQUEST['f_code']);
	                $bank_name = trim($_REQUEST['bank_name']);
	                $discriminator = trim($_REQUEST['discriminator']);
	                
	                $desc = trim($_REQUEST['desc']);
	                $merchant_id = trim($_REQUEST['merchant_id']);
	                $status = trim($_REQUEST['status']);
	                
	        if(empty($user_id) || empty($package_id) || empty($package_type) || empty($payment_response)){
			$json = array("response" => 201,"status"=>"error","message"=>"Invalid Data");
			json_enc($json );
			exit();
		    }
	                
            if($f_code == "Ok" || $f_code="success_00"){ $status=1; }else if ($f_code == "C"){ $status=2; }else{ $status=3; }
	                
	$stmt = $user->runQuery("INSERT INTO wvc_transaction (user_id,package_type,package_id,mmp_txn,mer_txn,amt,prod,bank_txn,f_code,clientcode,bank_name,merchant_id,date,discriminator,descr,udf9,status,payment_response) VALUES 
	('$user_id','$package_type','$package_id','$mmp_txn','$mer_txn','$amt','$prod','$bank_txn','$f_code','$clientcode','$bank_name','$merchant_id','$date','$discriminator','$desc','$udf9','$status','$payment_response')");
	$stmt->execute();
	$transaction_id=$user->lasdID();
    	        
            	        if ($f_code == "Ok" || $f_code="success_00"){
            	            $Due_date= date('Y-m-d', strtotime('first day of +1 months'));
            	            $status=1;
            	            $payment_Type='Online';
            	            
            	            $stmt = $user->runQuery("INSERT INTO wvc_payment(user_id,transaction_id,Type,Package_type,Package_id,Amount,Due_date,payment_response,Status) VALUES ('$user_id','$transaction_id','$payment_Type','$package_type','$package_id','$amt','$Due_date','$payment_response','$status')");
            	            $stmt->execute();
            	            
            	            if($package_details->package_type == 1){
            	            //$update_due_date_details = $this->Account_details->update_due_date_broadband($customer_details);
            	            }else{
            	            //$update_due_date_details = $this->Account_details->update_due_date_cable($customer_details);
            	            }
            	            
            	            $json = array("response"=>200,"status"=>"success","message"=>"Your Enquiry has been sent successfully!");
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