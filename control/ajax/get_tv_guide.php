<?php

session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || (!isset($_POST['date']) && !isset($_GET['date']))){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/curl.php");

$curl = new Curl();

if (isset($_POST['date'])){
	$date = $_POST['date'];
} else {
	$date = $_GET['date'];
}

$month = date("n-Y",strtotime("+1 day", strtotime($date)));


$page = $curl->get("http://www.pogdesign.co.uk/cat/".$month);

if ($page){
	$dom = new DOMDocument();
	@$dom->loadHTML($page);
	
	$tds = $dom->getElementsByTagName('td');
	if ($tds->length){
		
		$matchstring = 'd_'.date("j_n_Y",strtotime("+1 day",strtotime($date)));
		
		for($i=0;$i<$tds->length;$i++){
			if ((($tds->item($i)->getAttribute("class")=='day') || ($tds->item($i)->getAttribute("class")=='today')) && ($tds->item($i)->getAttribute("id")==$matchstring)){
						
				$divs = $tds->item($i)->getElementsByTagName('p');
				$counter = 0;
				$episodes = array();
				for($j=0;$j<$divs->length;$j++){
					
					$links = $divs->item($j)->getElementsByTagName('a');
					
					$show = 0;
					$season = 0;
					$episode = 0;
					
					for($k=0;$k<$links->length;$k++){
						$rel = $links->item($k)->getAttribute("rel");
						
						if ($rel && substr_count($links->item($k)->getAttribute("href"),"/cat/")){
							$show = trim($links->item($k)->textContent);
							
						}
						preg_match("/S\: (\d+) \- Ep\: (\d+)/i",$links->item($k)->textContent,$matches);
						if (count($matches) && isset($matches[1]) && isset($matches[2])){
							$season = $matches[1];
							$episode = $matches[2];
						}
					}
					
					if ($show && $season && $episode){
						$episodes[$counter]['show'] = $show;
						$episodes[$counter]['season'] = $season;
						$episodes[$counter]['episode'] = $episode;
						$counter++;
					}
				}			
				
				
				if (count($episodes)){
					?>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th width="50%">Show</th>
								<th width="25%">Episode</th>
								<th width="25%">Action</th>
							</tr>
						</thead>
						<tbody>	
					<?php
					$counter = 0;
					foreach($episodes as $key => $val){
						$cleanshow = trim(strtolower($val['show']));
						$cleanshow = mysql_real_escape_string($cleanshow);
						$cleanshow = preg_replace("/[^a-zA-Z0-9]/","%",$cleanshow);
						
						$e = mysql_query("SELECT id as showid FROM shows WHERE title LIKE '%\"$cleanshow\"%'") or die(mysql_error());
						if (mysql_num_rows($e)==0){
							$urlshow = urlencode($val['show']);
							$action = "<a href='index.php?menu=shows_new&title[en]=$urlshow'>Add show</a>";
						} else {
							extract(mysql_fetch_array($e));
							
							$check = mysql_query("SELECT * FROM episodes WHERE show_id='$showid' AND season='{$val['season']}' AND episode='{$val['episode']}'") or die(mysql_error());
							if (mysql_num_rows($check)){
								$action = "Already have (<a href='index.php?menu=episodes&show_id=$showid&season={$val['season']}&episode={$val['episode']}'>Edit</a>)";
							} else {							
								$action = "<a href='index.php?menu=episodes&show_id=$showid&season={$val['season']}&episode={$val['episode']}'>Add episode</a>";
							}
						}
						if ($counter%2==0){ $class = "class='alt-row'"; } else { $class = ''; }
						$counter++;
?>

		<tr <?php print($class); ?>>
			<td><?php print($val['show']); ?></td>
			<td><?php print("Season: {$val['season']}, Episode: {$val['episode']}"); ?></td>
			<td><?php print($action); ?></td>
		</tr>

<?php
					}
?>
	</tbody>
</table>

<?php
				} else {
					print("<center>Can't find any information for this date</center>");
				}
				
				break;
			}
		}
	} else {
		print("<center>Unexpected error occured, please try again</center>");
	}
	
} else {
	print("<center>Unexpected error occured, please try again</center>");
}

?>