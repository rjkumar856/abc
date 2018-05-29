<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

include '../db.php';
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD'] == "POST"){
		try{
		    $query = "SELECT * FROM monthly_current_affairs";
		    $stmt = $user->runQuery($query);
			$stmt->execute();
			if($stmt->rowCount()){
			    $json = array("response" => 200, "status" => "success", "message"=>"Data Available");
			for($i = 0;  $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
			    $json['year'][]=$object['year'];
			    $sql_result = "SELECT * FROM monthly_current_affairs WHERE year='$object[year]'";
                $stmt_result = $user->runQuery($sql_result);
                $stmt_result->execute();
                for($j = 0;  $row_object =$stmt_result->fetch(PDO::FETCH_ASSOC); $j++){
				$json['items'][$object['year']][] = array("id"=>$row_object['id'],"title"=>$row_object['title'],"month"=>$row_object['month'],"year"=>$row_object['year'],
					"file_url"=>DIR_SYSTEM."assets/files/currentaffair/".$row_object['file_url'],"date_added"=>$row_object['date_added'],"date_modified"=>$row_object['date_modified']);
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