<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
        
		    $query = "SELECT DISTINCT subject FROM upsc_materials ORDER BY subject ASC";
			$stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			$json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
			    $json['subject'][]=$object['subject'];
			    $sql_result = "SELECT * FROM upsc_materials WHERE subject='$object[subject]' ORDER BY title ASC";
                $stmt_result = $user->runQuery($sql_result);
                $stmt_result->execute();
                for($j = 0;  $row_object =$stmt_result->fetch(PDO::FETCH_ASSOC); $j++){
                $json['items'][$object['subject']][] = array("id"=>$row_object['id'],"title"=>htmlentities($row_object['title']),"subject"=>$row_object['subject'],
                "file_url"=>$row_object['file_url'],"date_added"=>$row_object['date_added'],"date_modified"=>$row_object['date_modified']);
                }
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