<?php 

session_start();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/settings.class.php");
require_once("../../includes/gapi.class.php");

$settings = new Settings();
$account = $settings->getSetting("analytics");

$return = array();

$data = 0;

if ($account){
	$return['message'] = "OK";
	
	if (!isset($_GET['from']) || !$_GET['from']){
		$from = date("Y-m-d",strtotime("29 days ago"));
	} else {
		$from = $_GET['from'];
	}
	
	if (!isset($_GET['to']) || !$_GET['to']){
		$to = date("Y-m-d");
	} else {
		$to = $_GET['to'];
	}
	
	if (!isset($_GET['report_type'])){
		$report_type = "country";
	} else {
		$report_type = $_GET['report_type'];
	}
	
	$ga = new gapi($account->username,$account->password);
	$check = $ga->authenticateUser($account->username,$account->password);	
	if ($check){
		
		if ($report_type=="country"){
			$res = $ga->requestReportData($account->profile,array('country'),array('pageviews','visits'),array("-visits"),null,$from,$to);
			$data = array();
			
			if (count($res)){
				foreach($res as $key => $val){
					$data[] = array("label" => $val->dimensions['country'], "data" => $val->metrics['visits']);
					if (count($data)>=12){
						break;
					}
				}
			}
		} elseif ($report_type=="daily"){
			$res = $ga->requestReportData($account->profile,array('date'),array('pageviews','visits'),array("date"),null,$from,$to);
			$data_pv = array();
			$data_visits = array();
			if (count($res)){
				$counter = 0;
				foreach($res as $key => $val){
					$date = $val->dimensions['date'][0].$val->dimensions['date'][1].$val->dimensions['date'][2].$val->dimensions['date'][3]."-".$val->dimensions['date'][4].$val->dimensions['date'][5]."-".$val->dimensions['date'][6].$val->dimensions['date'][7];
					$data_pv[] = array(strtotime($date)* 1000,$val->metrics['pageviews']);
					$data_visits[] = array(strtotime($date)* 1000,$val->metrics['visits']);
					$counter++;
				}
			}
			
			
			$data = array("visits" => $data_visits, "pageviews" => $data_pv,"min_date" => $from, "max_date" => $to);
		} elseif ($report_type=="referrer"){
			$res = $ga->requestReportData($account->profile,array('source'),array('pageviews','visits'),array("-pageviews"),null,$from,$to);
			if (count($res)){
				$data = '<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th class="optional">Referrer</th>
									<th class="optional">Pageviews</th>
									<th class="optional">Visits</th>
								</tr>
							</thead>
						<tbody>';
				
				$counter = 0;
				foreach($res as $key => $val){
					$counter++;
					$referrer = $val->dimensions['source'];
					$page_views = number_format($val->metrics['pageviews'],0);
					$visits = number_format($val->metrics['visits'],0);
					
					if ($referrer!='(direct)'){
						$data .= "	<tr>
										<td>$referrer</td>
										<td>$page_views</td>
										<td>$visits</td>
									</tr>"; 
					}
					
					if ($counter>=23){
						break;
					}
				}
				
				$data .= '</tbody></table>';
			}
		}
		
	} else {
		$return['message'] = "AUTHERROR";
	}
	
} else {
	$return['message'] = "AUTHERROR";
}

$return['data'] = $data;

print(json_encode($return));