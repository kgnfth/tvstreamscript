<?php
class Sidereel{

	var $curl = null;
	var $logged = null;
	
	function __construct($curl = null){
		global $basepath;
		
		if ($curl){
			$this->curl = $curl;
		} else {
			if (!class_exists("Curl")){
				require_once("curl.php");
			}
			
			$this->curl = new Curl();
			$this->curl->setCookieFile($basepath."/cachefiles/sidereelcookie.txt");
		}

	}
	
	public function getThumbnail($show,$season,$episode,$basepath){
		$link = $this->grabURL($show['title']);
		
		if (!$link){
			return false;
		}
		
		$link = $link."/season-$season/episode-$episode"; 
		
		$page = $this->curl->get($link); 
		$imageurl = '';
		if (substr_count($page,"<?xml version")){
			// we have an xml for some reason
			$dom = new DOMDocument();
			@$dom->loadXML($page);
			
			$id = $dom->getElementsByTagName('id');
			if ($id->length){
				$id = $id->item(0)->nodeValue;
			} else {
				$id = '';
			}
			
			$filename = $dom->getElementsByTagName('image-file-name');
			if ($filename->length){
				$filename = $filename->item(0)->nodeValue;
			} else {
				$filename = '';
			}
			

			$divs = $dom->getElementsByTagName('div');
			if ($filename && $id){
				$imageurl = "http://s4.sidereel.com/episodes/$id/carousel/$filename";
			}
		
		} else {
			// html it is
			$dom = new DOMDocument();
			@$dom->loadHTML($page);
			$divs = $dom->getElementsByTagName('div');
			for($i=0;$i<$divs->length;$i++){
				if ($divs->item($i)->getAttribute("class")=='episode-image'){
					$imgs = $divs->item($i)->getElementsByTagName('img');
					if ($imgs->length){
						$imageurl = $imgs->item(0)->getAttribute("src");
					}
				}
			}
		}
		

		if (($imageurl) && (substr_count($imageurl,"noimage")==0) && (substr_count($imageurl,"defaultepisodeimage")==0)){
			$imagecontent = $this->curl->get($imageurl);
			
			$handle = fopen($basepath."/thumbs/ethumb_".$show['id']."_".$season."_".$episode.".jpg","w+");
			fwrite($handle,$imagecontent);
			fclose($handle);

			return "ethumb_".$show['id']."_".$season."_".$episode.".jpg";

		} else {
		
			return false;
		}
	}

	public function getDomain($url){
		$url = strtolower($url);
		$url = str_replace("https://","",$url);
		$url = str_replace("http://","",$url);
		$url = str_replace("www.","",$url);
		$tmp = explode("%3f",$url);
		$url = $tmp[0]; 
		$tmp = explode("%2f",$url);
		$url = $tmp[0];  
		$tmp = explode("/",$url);
		$url = $tmp[0];
		$tmp = explode("?",$url);
		$url = $tmp[0];
		return $url;
	}
	
	public function grabURL($title,$maybe=true){
		
		$title = trim(urldecode($title));
		$searchtitle = str_replace(" ","+",$title);
		$url = "http://www.sidereel.com/_television/search?q=$searchtitle&submit.x=41&submit.y=14";
		
		$page = $this->curl->get($url);
		
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$divs = $dom->getElementsByTagName('div');
		$url = "";
		$maybe_url = "";
		for($i=0;$i<$divs->length;$i++){
			if ($divs->item($i)->getAttribute("class")=="title"){
				$as = $divs->item($i)->getElementsByTagName('a');
				if ($as->length){
					$linkurl = $as->item(0)->getAttribute("href");
					$linktitle = $as->item(0)->textContent;
					
					
					if ($linkurl[0]=="/"){
						$linkurl = "http://www.sidereel.com".$linkurl;
					}
					
					if (!$maybe_url) $maybe_url = $linkurl;
					
					if (preg_replace("/[^a-z0-9]/","",strtolower($title))==preg_replace("/[^a-z0-9]/","",strtolower($linktitle))){
						
						$url = $linkurl;
						break;
					} elseif (preg_replace("/[^a-z]/","",strtolower($title))==preg_replace("/[^a-z]/","",strtolower($linktitle))){
						$maybe_url = $linkurl;
					}
					
					//break;
				}
			}
		}
		
		if ((!$url) && ($maybe)){
			$url=$maybe_url;
		}
		
		if (!$url){
			preg_match("/\d{4}/",$title,$matches);
			if (count($matches)){
				$url = $this->grabURL(trim(str_replace($matches[0],"",$title)),$maybe);
			}
		}
		
		if (!$url && substr_count(strtolower($title)," and ")){
			$url = $this->grabURL(str_replace(" and "," & ",strtolower($title)));
		}
				
		return $url;
	}

	public function getEmbeds($title,$season,$episode,$domainfilters,$limit,$checkfake=null){
		
		$sidereel_url = $this->grabURL($title);
		
		$link = $sidereel_url."/season-$season/episode-$episode/search";
		
		$p = 1;
		$embeds = array();
		$counter = 1;
		
		$this->curl->setAjax();
		while(1){
		
			$pagelink = $link."?page=".$p;
			$page = $this->curl->get($pagelink);
			
			$info = $this->curl->getInfo();
			if ($info['http_code']==404){
				return $embeds;
			}
			
			$dom = new DOMDocument();
			@$dom->loadHTML($page);
			$divs = $dom->getElementsByTagName('div');
			
			$founddiv = 0;
			$foundembeds = array();
			
			for($i=0;$i<$divs->length;$i++){
				if (substr_count($divs->item($i)->getAttribute("class"),"link-results")){
					$founddiv = 1;
				
					$ul = $divs->item($i)->getElementsByTagName('ul')->item(0);
					$lis = $ul->getElementsByTagName('li');
					
					if ($lis->length==0){
						return $embeds;
					}
					
					for($j=0;$j<$lis->length;$j++){
						$links = $lis->item($j)->getElementsByTagName('a');
						for($k=0;$k<$links->length;$k++){
							if (substr_count($links->item($k)->getAttribute("class"),"direct-link")){
								$url = $links->item($k)->getAttribute("href");
								$domain = $this->getDomain($url);
								
								print($domain."<br />");
								if (in_array($domain,$domainfilters)){
									if ($domain=='megavideo.com'){ 
										
										// megavideo
										if (substr_count($url,"?v=")){
											$tmp = explode("?v=",$url);
											$id = $tmp[1];
										} elseif (substr_count($url,"?d=")) {
											$megapage = $this->curl->get($url);
											$tmp = explode('flashvars.embed = "',$megapage);
											$megapage = @$tmp[1];
											$tmp = explode('";',$megapage);
											$embed = urldecode($tmp[0]);		 
											$tmp = explode("http://www.megavideo.com/v/",$embed);
											$embed = @$tmp[1];
											$tmp = explode('">',$embed);
											$id = $tmp[0];		 
										} else {
											$id = '';
										}

										if ($id){
										
									
											if ($checkfake){
												$mega = new Megavideo($id);
												$duration = @$mega->get(duration);
											} else {
												$duration = "15:00";
											}
											
											$check = $this->curl->get("http://www.megavideo.com/?v=$id");
											if ((substr_count($check,"This video has been removed")>0) || (substr_count($check,"This video is unavailable"))){
												$duration = '10:00';
											}
											
											if (($duration>"10:00") || (!$duration)){
												$embeds[$counter]=array();
												$embeds[$counter]['info']='';
												$embeds[$counter]['embed']="<object width=\"420\" height=\"382\"><param name=\"movie\" value=\"http://www.megavideo.com/v/$id\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.megavideo.com/v/$id\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"420\" height=\"382\"></embed></object>";
												$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);;
												$counter++;
											}
										}
									} elseif ($domain=='divxden.com') {
										// divxden
										$url = str_replace("http://","",$url);
										$url = explode("/",$url);
										$id = @$url[1];
										if ($id){
											$embed = '<IFRAME SRC="http://www.divxden.com/embed-'.$id.'-width-653-height-362.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH=653 HEIGHT=362></IFRAME>';
											if (!in_array($embed,$foundembeds)){
												$foundembeds[]=$embed;
												$embeds[$counter]=array();
												$embeds[$counter]['value']=urlencode($embed);
												$embeds[$counter]['info']='';
												$embeds[$counter]['embed']=$embed;
												$counter++;
											}
										}
									} elseif ($domain=='zshare.net'){
										// zshare
										$zs = $this->curl->get($url);
										if (substr_count($zs,"Error 404")==0){			
											preg_match("/http:\/\/www\.zshare\.net\/videoplayer\/([^\"\']*)/", $zs, $matches, PREG_OFFSET_CAPTURE, 3);
											$id = @$matches[1][0];
											if ($id){					
												$embed = "http://www.zshare.net/videoplayer/".$id;
												$embed = "<iframe src='$embed' width='660' height='420' scrolling='no' frameborder='0' style='width:100%'></iframe>";
												if (!in_array($embed,$foundembeds)){
													$foundembeds[]=$embed;
													$embeds[$counter]=array();
													$embeds[$counter]['info']="All zShare link requires activation(meaning you have to visit the link at least once to activate the video). Please <a href='$embed' target='_blank'>click here</a> to activate this video";
													$embeds[$counter]['embed']=$embed;
													$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);
													$counter++;
												}
											}
										}
										
									} elseif ($domain=='fancast.com') {
										//fancast
										$embed = str_replace("/videos","/embed?skipTo=0",$url);
										$embed = "<iframe src='$embed' width='420' height='382' scrolling='no' frameborder='0'></iframe>";
										if (!in_array($embed,$foundembeds)){
											$foundembeds[]=$embed;
											$embeds[$counter]=array();
											$embeds[$counter]['info']='';
											$embeds[$counter]['embed']=$embed;
											$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);
											$counter++;
										}
									} elseif ($domain=='videobb.com') {
										if (substr_count($url,"/video/")){
											$id = explode("/video/",$url);
											$id = $id[1];
											$embed = "<object id='vbbplayer' width='425' height='344' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' ><param name='movie' value='http://www.videobb.com/e/$id' ></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.videobb.com/e/$id' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='425' height='344'></embed></object>";
											if (!in_array($embed,$foundembeds)){
												$foundembeds[]=$embed;
												$embeds[$counter]['info']='';
												$embeds[$counter]['embed']=$embed;
												$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);
												$counter++;
											}
										}
									} elseif ($domain=='vidxden.com') {
										$link = str_replace("http://","",$url);
										$tmp = explode("/",$link);
										if (count($tmp)==3){
											$videoid = $tmp[1];
											$embed = "<IFRAME SRC='http://www.vidxden.com/embed-$videoid-width-653-height-362.html' FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH=653 HEIGHT=362></IFRAME>";
											$foundembeds[]=$embed;
											$embeds[$counter]['info']='';
											$embeds[$counter]['embed']=$embed;
											$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);
											$counter++;
										}
									} elseif ($domain=='novamov.com') {
										if (substr_count($url,"novamov.com/video/")==0){
											$url = '';
										}
										
										if ($url){
										    $url = str_replace("http://","",$url);
											$tmp = explode("/",$url);
											$videoid = $tmp[2];
											$embed = "<iframe style='overflow: hidden; border: 0; width: 555px; height: 430px' src='http://embed.novamov.com/embed.php?width=555&height=480&v=$videoid' scrolling='no'></iframe>";
											$foundembeds[]=$embed;
											$embeds[$counter]['info']='';
											$embeds[$counter]['embed']=$embed;
											$embeds[$counter]['value']=urlencode($embeds[$counter]['embed']);
											$counter++;
										}
									} else {
										print($domain." - ".$url."<br />");
									}
								}
								
								if ($counter>$limit){
									return $embeds;
								}
							}
						}
					}
					
					break;
				}
			}
			
			if (!$founddiv){
				break;
			}
			
			$p++;
		}
		return $embeds;
	}

	public function getEpisodeDetails($link){
		global $basepath;
		
		$this->curl->setAjax();
		$this->curl->header(true);
		$this->curl->cookiefile = $basepath."/cachefiles/sidereelcookie.txt";
		$this->curl->setCookieFile($basepath."/cachefiles/sidereelcookie.txt");
		
		$page = $this->curl->get($link);
		
		$title = '';
		$description = '';
		
		if ($page){
			if (substr_count($page,"DOCTYPE html")){
				
				// html version
				$dom = new DOMDocument();
				@$dom->loadHTML($page);
				
				$divs = $dom->getElementsByTagName('div');
				for($i=0;$i<$divs->length;$i++){
					if (substr_count($divs->item($i)->getAttribute("class"),"episode-summary")){
						$hs = $divs->item($i)->getElementsByTagName("h2");
						if ($hs->length){
							$title = trim($hs->item(0)->textContent);
						}
						
						$description = trim(str_replace($title,"",$divs->item($i)->textContent));
					}
				}
			} else {
				// xml version
				$dom = new DOMDocument();
				@$dom->loadXML($page);

				$summaries = $dom->getElementsByTagName('summary');
				if ($summaries->length){
					$description = $summaries->item(0)->nodeValue;
				} else {
					$description = 'No description';
				}
				
				$titles = $dom->getElementsByTagName('title');
				if ($titles->length){
					$title = $titles->item(0)->nodeValue;
				} else {
					$title = '';
				}
				
				$title = @trim($title);
				$description = @trim($description);
			}		
		}
		
		return array("title"=>$title,"description"=>$description);
	}
		
	public function sidereelURL($title,$sidereel_url=null){
		if ($sidereel_url){
			$title = str_replace("http://www.sidereel.com/","",$sidereel_url);
			$title = explode("/",$title);
			$title = $title[0];
		} else {
			$title = stripslashes($title);
			$title = ucwords(urldecode($title));
			$title = str_replace("&","%26",$title);
			$title = str_replace(":","%3A",$title);
			$title = preg_replace("/[^A-Za-z0-9 \'\.\%]/","",$title);
			$title = str_replace(" ","_",$title);
			 
			$title = str_replace("_Of_","_of_",$title);
			$title = str_replace("_To_","_to_",$title);
			$title = str_replace("_In_","_in_",$title);
			$title = str_replace("_With_","_with_",$title);
			$title = str_replace("_The_","_the_",$title);
			$title = str_replace("_A_","_a_",$title);
			$title = str_replace("_For_","_for_",$title);
			$title = str_replace("_At_","_at_",$title);
		}
		
		// exceptions
		if (substr_count(strtolower($title),"american dad")){ $title="American_Dad!"; }
		
		return $title;
	}
	
	public function checkLogged($username){
		$page = $this->curl->get("http://www.sidereel.com/_webapi/personalize/home_pages/index/");
		
		$page = json_decode($page,true);
		if (isset($page['VanityName']['vanity_name']) && $page['VanityName']['vanity_name']==$username){
			$this->logged = true;
			return true;
		} else {
			return false;
		}
	}
	
	public function login($username,$password){
		global $basepath;
		
		$this->curl->header(true);
		$page = $this->curl->get("http://www.sidereel.com");
		
		$token = '';
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$inputs = $dom->getElementsByTagName('input');
		for($i=0;$i<$inputs->length;$i++){
			if ($inputs->item($i)->getAttribute("name")=="authenticity_token"){
				$token = $inputs->item($i)->getAttribute("value");
				break;
			}
		}
		
		if (!$token){
			preg_match('/\\\"=="\\\" type=\\\"hidden\\\" value=\\\"([^\\\"]+)/i',$page,$matches);
			if (isset($matches[1]) && $matches[1]){
				$token = $matches[1];
			}
		}
				
		if (!$token){
			return false;
		}
		
		$this->curl->setSsl();
		$page = $this->curl->plainPost("https://www.sidereel.com/users/sign_in",array("authenticity_token" => $token,"user[email]"=>$username,"user[password]"=>$password, "commit" => "Login"));
		$page = $this->curl->get("https://www.sidereel.com/users?message=true");
		$page = $this->curl->get("http://www.sidereel.com/_webapi/personalize/home_pages/index/");
		
		$page = json_decode($page,true);
		
		if (isset($page['VanityName']['vanity_name'])){
			$this->logged = true;
			return true;
		} else {
			return false;
		}
		
		return true;	
	}
	
	public function post($url,$title,$season,$episode,$episodetitle,$answer,$auth_token,$challenge,$sidereel_url=null){
		global $basepath;
		$title = trim($title);
		$perma = $this->sidereelURL($title,$sidereel_url);
		if (!$episodetitle){
			$episodetitle = "Season $season, episode $episode";
		}
		
		$link = "http://www.sidereel.com/".$perma."/season-$season/episode-$episode/links";

		$data = array(urlencode("link[url]") => $url,
				  urlencode("link[title]") => $episodetitle,
				  "authenticity_token" => $auth_token,
				  "recaptcha_response_field" => $answer,
				  "recaptcha_challenge_field" => $challenge,
				  "send" => "Send");
		
		$page = $this->curl->post($link,$data);
		return $page;
		
	}

	public function checkIfExists($title,$season,$episode,$sidereel_url=null){
		global $basepath;
		
		$title = trim($title);
		$perma = $this->sidereelURL($title,$sidereel_url);

		$link = "http://www.sidereel.com/".$perma."/season-$season/episode-$episode/search";
		
		
		$page = $this->curl->get($link); 
		$info = $this->curl->getInfo();
		
		
		if (($info['http_code']!=200) || (strtolower($info['url'])!=strtolower($link))){
			return 0;
		} else {
			return 1;
		}
	}

	public function getInnerHtml( $node ) { 
	    $innerHTML= ''; 
	    $children = $node->childNodes; 
	    foreach ($children as $child) { 
	        $innerHTML .= $child->ownerDocument->saveXML( $child ); 
	    } 
	
	    return $innerHTML; 
	}
	
	public function submit($show,$season,$episode,$data){
		$sidereel_url = $data['sidereel_url'];
		unset($data['sidereel_url']);

		$this->curl->setAjax();

		$page = $this->curl->post($sidereel_url."/season-".$season."/episode-".$episode."/links",$data);
		return $page;
	}

	public function getCaptcha($title,$season,$episode,$sidereel_url=null){
		global $basepath;
		
		if (!$sidereel_url){
			$sidereel_url = $this->grabURL(trim($title));
			if (!$sidereel_url){
				
				return false;
			}
		}
		
		
		$this->curl->header(true);
		$page = $this->curl->get($sidereel_url."/season-$season/episode-$episode");
		
		
		
		preg_match("/\/_webapi\/personalize\/episodes\/show\/(\d+)\?tv_show_id\=(\d+)/i",$page,$matches);
		
		if (isset($matches[1]) && isset($matches[2])){
			$episode_id = $matches[1];
			$show_id = $matches[2];
			
			$page = $this->curl->get("http://www.sidereel.com/fragments/episode_guide_entry/".$episode_id);
			
			$d = new DOMDocument();
			@$d->loadHTML($page);
			$token = '';
			$forms = $d->getElementsByTagName('form');
			for($j=0;$j<$forms->length;$j++){
				if ($forms->item($j)->getAttribute("class")=='add-link-form'){
					$inputs = $forms->item($j)->getElementsByTagName('input');
					for($k=0;$k<$inputs->length;$k++){
						if ($inputs->item($k)->getAttribute("name")=='authenticity_token'){
							$token = $inputs->item($k)->getAttribute("value");
							break;
						}
					}
				}
			}
			
			$page = $this->curl->get("http://www.google.com/recaptcha/api/challenge",array("k"=>"6LcYsroSAAAAAHvzdJ4D9oa7MVJeDM7C6k4BOSB1","ajax"=>1,"cachestop"=>0.29582001275642333));
			
			
			$tmp = explode("challenge : '",$page);
			if (count($tmp)<2){ 
				return false;
			}
			
			$tmp = $tmp[1];
			$tmp = explode("',",$tmp);
			$challenge = $tmp[0];	

			$img = $this->curl->get("http://www.google.com/recaptcha/api/image?c=$challenge");
			$rand = rand(0,100000);
			
			$handle = fopen("$basepath/cachefiles/".$rand.".jpg","w+");
			fwrite($handle,$img);
			fclose($handle);
			
			$ret = array();
			$ret['image'] = $rand;
			$ret['authenticity_token'] = $token;
			$ret['recaptcha_challenge_field'] = $challenge;
			$ret['sidereel_url'] = $sidereel_url;
			
			return $ret;
			
			
		} else {
			return false;
		}

	}
	 
}

?>