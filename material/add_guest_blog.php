<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "GET"){
		try{
	        $cust_id = preg_replace('/\s\s+/', '',nl2br(htmlentities(trim($_REQUEST['cust_id']))));
            $title = preg_replace('/\s\s+/', '',nl2br(htmlentities(trim($_REQUEST['title']))));
            $description = preg_replace('/\s\s+/', '',nl2br(htmlentities(trim($_REQUEST['description']))));
            $prod = '';
            $ip = preg_replace('/\s\s+/', '',nl2br(htmlentities(trim($_REQUEST['clientcode']))));
	        
		    if(empty($id)){
			$json = array("response"=>201,"status"=>"error","message"=>"Please enter Blog ID");
			echo json_encode($json);
			exit;
		    }
		    
		    $query = "SELECT *, gb.id as BlogID, us.id as UserID FROM guest_blog gb INNER JOIN user us ON us.id=gb.cust_id WHERE gb.id='$id'";
		    $stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				$json['items'][] = array("id"=>$object['BlogID'],"title"=>preg_replace('/\s\s+/', '',nl2br(htmlentities(trim($object['title']),ENT_SUBSTITUTE | ENT_QUOTES | ENT_COMPAT | ENT_XHTML, 'UTF-8'))),
				"description"=>preg_replace('/\s\s+/', '',nl2br(htmlentities(trim(strip_tags($object['description'])),ENT_SUBSTITUTE | ENT_QUOTES | ENT_COMPAT | ENT_XHTML, 'UTF-8'))),
				"slug"=>$object['slug'], "user_id"=>$object['UserID'],"full_name"=>$object['full_name'],"date_added"=>$object['date_added'],"date_modified"=>$object['date_modified']);
			}
			echo json_encode($json);
			exit;
		}else{
		    $json = array("response" => 204, "status" => "success", "message"=>"Data Not Available");
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