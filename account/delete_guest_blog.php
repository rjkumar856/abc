<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    if(!empty($_REQUEST)){
		    $id = htmlentities(trim($_REQUEST['id']));;
	        $cust_id = htmlentities(trim($_REQUEST['userid']));
	        
		    if(empty($id) || empty($cust_id)){
			$json = array("response"=>201,"status"=>"error","message"=>"Please send all the Required Fields!");
			echo json_encode($json);
			exit;
		    }
		    
		    $query = "SELECT * FROM guest_blog gb WHERE gb.id='$id' AND cust_id='$cust_id'";
		    $stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			        $stmt_details = $user->runQuery("DELETE FROM guest_blog WHERE id='$id' AND cust_id='$cust_id'");
                    $stmt_details->execute();
    			    $json = array("response" => 200, "status" => "success", "message"=>"Guest Blog Deleted Successfully!");
    			    echo json_encode($json);
			exit;
		}else{
		    $json = array("response" => 204, "status" => "success", "message"=>"This blog is not Written By You");
		    echo json_encode($json);
		    exit;
		}
		
		    }else{
                $json = array("response"=>203,"status"=>"error","message"=>"Empty Data");
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