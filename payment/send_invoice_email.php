<?php
header('Content-type: application/json');
ini_set('memory_limit', '-1');

require_once('../html2pdf/html2pdf.class.php');
require_once('../db.php');
include '../config.php';
$user = new USER1();

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(!empty($_REQUEST)){
    		try{
		    $username = htmlentities(trim($_REQUEST['userid']));
		    $type = htmlentities(trim($_REQUEST['type']));
		    
		    if(empty($username) || empty($type)){
			$json = array("response" => 201, "status" => "Invalid Data");
			json_enc($json );
			exit();
		    }
		    
		    $json = array("response" => 200, "status" => "succes", "message"=>"Data Not Available","items"=>'');
				$stmt = $user->runQuery("SELECT *,wp.id as PaymentID,cp.name as cp_name FROM wvc_payment wp INNER JOIN user us ON wp.user_id=us.id INNER JOIN wvc_transaction wt ON wt.id=wp.transaction_id LEFT JOIN cable_package cp ON cp.id=wp.Package_id LEFT JOIN internet_pack ip ON ip.id=wp.Package_id 
				WHERE wp.user_id='$username' AND wp.Package_type='$type' ORDER BY wp.Date_added DESC LIMIT 1");
				$stmt->execute();
				for($i = 0; $object = $stmt->fetch(PDO::FETCH_ASSOC); $i++){
				    
			    $package_name=($object['Package_type']==2)?$object['cp_name']:$object['Speed']."-".$object['Data_Transfer']." @Rs.".$object['Traiff'];
			    $package_type=($object['Package_type']==1)?"Broadband":"Cable";
				
				$content='<!DOCTYPE html>
                            <html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <link rel="shortcut icon" href="/assets/images/favicon_1.png" />
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            </head>
                            <body style="width: 100%;margin:0px auto;font-size: 14px;font-family: arial;text-align: left;">
                            <div class="main" style="width: 100%;max-width:750px;margin:auto;padding:10px;">
                            <table style="margin:10px auto;">
                            <tr style="text-align:center"><td><b style="font-weight:bold;"><h2>INVOICE</h2> </b></td></tr>
                            </table>
                            <table style="width:100%;max-width:750px;margin:10px auto;">
                            <tr><th style="font-size: 16px;">World Vision Cable Network</th><td rowspan="3"><img src="https://www.worldvisioncable.in/assets/images/logo.png" width="200"></td></tr>
                            <tr><td>#30, 5th Main Rd, Teachers Colony, 1st Block Koramangala,<br> Koramangala, Bengaluru, Karnataka 560034</td></tr>
                            <tr><td><b style="font-weight:bold;">EMAIL ID : </b>info@worldvisioncable.com <b style="font-weight:bold;">PHONE : </b>080-25534744</td></tr>
                            <tr><td>CIN : U72900KA2000PTC027290<br>GSTIN No : 29AACCA8907B1ZU</td></tr>
                            </table>
                            <hr/>
                            <table cellpadding="5" style="width: 100%;max-width:750px;margin:10px auto;">
                            <tr><td><b>TO :</b><br/>'.$object['full_name'].',<br/>Bangalore<br/><b>Email: </b>'.$object['email'].'<br><b>Mobile: </b>'.$object['phone'].'</td>
                            <td><b>Payment Mode:</b> Cash<br/><b>Network Type:</b>'.$package_type.'<br/>
                            <b>Package Name:</b>'.$package_name.'</td></tr>
                            </table>
                            <table cellpadding="5" border="1" style="width:100%;max-width:750px;margin:10px auto;text-align:left;border-collapse:collapse;font-size: 15px;">
                            <tr><th colspan="2" style="text-align:center;">PAYMENT RECEIPT</th></tr>
                            <tr><td>Received from</td><td>'.$object['full_name'].'</td></tr>
                            <tr><td>User Name</td><td>'.$object['user_id'].'</td></tr>
                            <tr><td>Transaction Ref. no.</td><td>'.$object['mer_txn'].'</td></tr>
                            <tr><td>Transaction ID</td><td>'.$object['mmp_txn'].'</td></tr>
                            <tr><td>Amount</td><td>Rs.'.$object['Amount'].'</td></tr>
                            <tr><td>Transaction Mode</td><td>'.$object['Type'].'</td></tr>
                            <tr><td>Transaction details</td><td>'.$object['descr'].'</td></tr>
                            <tr><td>Date of payment</td><td>'.$object['Date_added'].'</td></tr>
                            </table>
                            <table style="width:100%;max-width:750px;margin:10px auto;font-size: 14px;text-align:left;">
                            <tr><td style="width:100%;">Note: No signature required as this document is generated electronically.</td></tr>
                            </table>
                            </div>
                            </body>
                            </html>';
				
	$html2pdf = new HTML2PDF('P', 'A4', 'fr');
	$html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content);
    $to = $object['email'];
    $from = "World Vision Cable <info@worldvisioncable.in>";
    $subject = "Invoice of Last Bill Payment";

        $message = "<p>Please find the attachment for last month invoice.</p>";
        $separator = md5(time());
        $eol = PHP_EOL;
        $filename = $object['user_id']."-invoice-".time().".pdf";
        $pdfdoc = $html2pdf->Output('', 'S');
        $attachment = chunk_split(base64_encode($pdfdoc));
        
        $headers = "From: " . $from . $eol;
        $headers .= "CC: info@worldvisioncable.in". $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $body = "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= "This is a MIME encoded message." . $eol; //had one more .$eol
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol;
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $attachment . $eol;
        $body .= "--" . $separator . "--";

        if (mail($to, $subject, $body, $headers)) {
            $json = array("response" => 200, "status" => "succes", "message"=>"Mail Send Successfully'");
        } else {
            $json = array("response" => 200, "status" => "succes", "message"=>"Some error occur on sending Mail'");
        }
		}
			json_enc($json);
			exit();
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