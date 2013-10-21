<?php
 @set_time_limit(0);
class SidereelCurl {
	var $curl;
    var $manual_follow;
    var $redirect_url;
	var $cookiefile = null;

	function SidereelCurl() {
		$this->curl = curl_init();
		$header = array();
		$header[] = "Accept: text/xml,application/xml,application/xhtml+xml;charset=UTF-8";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: utf-8;ISO-8859-1;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: "; // browsers keep this blank.
		$header[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
		$header[] = "X-Requested-With: XMLHttpRequest";

		
		//@curl_setopt($this->curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.14) Gecko/2009082707 Firefox/3.0.14 (.NET CLR 3.5.30729)');
		@curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
		@curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
		@curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		@curl_setopt($this->curl, CURLOPT_ENCODING, 'gzip,deflate');
		@curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
		@curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		@curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt($this->curl, CURLOPT_HEADER, true);
		@curl_setopt($this->curl, CURLOPT_TIMEOUT, 100000);
	
        $this->setRedirect();
	}
	
	function grabberHeaders(){
		$header = array();
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: utf-8;ISO-8859-1;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: "; // browsers keep this blank.
		$header[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
		$header[] = "X-Requested-With: XMLHttpRequest";
		@curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
	}
	
	function setTwitter($username,$password){
		@curl_setopt($this->curl, CURLOPT_HEADER, false);
		@curl_setopt($this->curl, CURLOPT_USERPWD, "$username:$password");	
	}
	
	function setCookieFile($file){
        if (file_exists($file)) {
			
        } else {
            $handle = fopen($file, 'w+') or print('The cookie file could not be opened. Make sure this directory has the correct permissions');
            fclose($handle);
        }
		@curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
		@curl_setopt($this->curl, CURLOPT_COOKIEJAR, $file);
		@curl_setopt($this->curl, CURLOPT_COOKIEFILE, $file);
		$this->cookiefile = $file;
	}
	
	function setCookieFileDontStart($file){
        if (file_exists($file)) {
			
        } else {
            $handle = fopen($file, 'w+') or print('The cookie file could not be opened. Make sure this directory has the correct permissions');
            fclose($handle);
        }
		@curl_setopt($this->curl, CURLOPT_COOKIESESSION, false);
		@curl_setopt($this->curl, CURLOPT_COOKIEJAR, $file);
		@curl_setopt($this->curl, CURLOPT_COOKIEFILE, $file);
		$this->cookiefile = $file;
	}
	
	function getCookies(){
	  $contents = file_get_contents($this->cookiefile);
	  $cookies = array();
	  if ($contents){
	    $lines = explode("\n",$contents);
		if (count($lines)){
		  foreach($lines as $key=>$val){
			$tmp = explode("\t",$val);
			if (count($tmp)>3){
			  $tmp[count($tmp)-1] = str_replace("\n","",$tmp[count($tmp)-1]);
			  $tmp[count($tmp)-1] = str_replace("\r","",$tmp[count($tmp)-1]);
			  $cookies[$tmp[count($tmp)-2]]=$tmp[count($tmp)-1];
			}
		  }
		}
	  }
	  return $cookies;
	}

	function setSideReelHeaders($ref,$cooks){
		$header[]="Host: beta.sidereel.com";
		$header[]="User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3";
		$header[]="Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$header[]="Accept-Language: en-gb,en;q=0.5";
		$header[]="Accept-Encoding: gzip,deflate";
		$header[]="Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[]="Keep-Alive: 115";
		$header[]="Connection: keep-alive";
		$header[]='If-None-Match: "370b53e866e10e51fe6c79e27e3bf1ad"';
		$header[]="Cache-Control: max-age=0";
		
		@curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		@curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
		@curl_setopt($this->curl, CURLOPT_REFERER, $ref);
		@curl_setopt($this->curl, CURLOPT_COOKIE, "SRSESSIONID=".@$cooks['SRSESSIONID'].";");
		@curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
		@curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookiefile);
		@curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookiefile);
	}

	function setContentType(){
		$header[]="Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
		$header[]="X-Requested-With: XMLHttpRequest";
		$header[]="Accept: application/json";
		$header[]="Accept-Language: en-gb,en;q=0.5";
		$header[]="Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[]="User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)";
		//$header[]="Referer: $referer";
		$header[]="Keep-Alive: 115";
		$header[]="Accept-Encoding: gzip,deflate";
		$header[]="Pragma: no-cache";
		//$header[]="Host: www.sidereel.com";
		//$header[]="Cookie: SRSESSIONID=".@$cooks['SRSESSIONID'];
		//$header[]="Content-Length: ".strlen($req)-1;
		//$header[]=$req;

		@curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
	}

	function setYidioHeaders($referrer,$titlestring,$cookies){
		$header[] = "Host: www.yidio.com";
		$header[] = "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6";
		$header[] = "Accept: */*";
		$header[] = "Accept-Language: en-gb,en;q=0.5";
		$header[] = "Accept-Encoding: gzip,deflate";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Keep-Alive: 115";
		$header[] = "Connection: keep-alive";
		$header[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
		$header[] = "X-Requested-With: XMLHttpRequest";
		$header[] = "Referer: $referrer";
		
		$cookiestring = '';
		if (count($cookies)){
		  foreach($cookies as $key=>$val){
		    $cookiestring.=" $key=$val;";
		  }
		}
		
		$header[] = "Cookie: $cookiestring";
		$header[] = "Pragma: no-cache";
		$header[] = "Cache-Control: no-cache";
		$header[] = $titlestring;
		@curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
	}

	function setheaders($sess){
	 //$header[] = "Referer: $ref";
	 $header[] = "Cookie: SRSESSIONID=$sess; __csref=http%3A//www.sidereel.com/_home;";
	 $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	 $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
	 $header[] = "Accept-Language: en-gb,en;q=0.5";
	 $header[] = "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)";
	 $header[] = "Host: analytics.linksynergy.com";
	 $header[] = "Connection: keep-alive";
	 $header[] = "Keep-Alive: 300";
	
	 @curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
	}

	function setdatamode(){
	  @curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, 1);
	}
	
	function nodatamode(){
	  @curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, 0);
	}
	
	function noheaders(){
	  @curl_setopt($this->curl, CURLOPT_HEADER, false);
	}
	
	function close() {
	  curl_close($this->curl);
	}
	
	function getInfo(){
	  return curl_getinfo($this->curl);
	}
	
	function setAccount($username,$password){
		curl_setopt($this->curl, CURLOPT_USERPWD, "$username:$password");	
	}
	
	function getInstance() {
		static $instance;
		if (!isset($instance)) {
			$curl = new Curl;
			$instance = array($curl);
		}
		return $instance[0];
	}

    function setTimeout($connect, $transfer) {
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $connect);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $transfer);
    }

    function getError() {
        return curl_errno($this->curl) ? curl_error($this->curl) : false;
    }

    function disableRedirect() {
        $this->setRedirect(false);
    }

    function setRedirect($enable = true) {
        if ($enable) {
	        $this->manual_follow = !@curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        } else {
	        @curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
            $this->manual_follow = false;
        }
    }

    function getHttpCode() {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

	function auth($user, $pass) {
		curl_setopt($this->curl, CURLOPT_USERPWD, "$user:$pass");
	}

	function makeQuery($data) { 
		if (is_array($data)) {
			$fields = array();
			foreach ($data as $key => $value) {
 				$fields[] = $key . '=' . urlencode($value);
			}
			$fields = implode('&', $fields);
		} else {
			$fields = $data;
		}

		return $fields;
	}
	
    // FOLLOWLOCATION manually if we need to
	function maybeFollow($page) {
        if (strpos($page, "\r\n\r\n") !== false) {
            list($headers, $page) = explode("\r\n\r\n", $page, 2);
        }

        $code = $this->getHttpCode();
        if ($code > 300 && $code < 310) {
            preg_match("#Location: ?(.*)#i", $headers, $match);
            $this->redirect_url = trim($match[1]);

	        if ($this->manual_follow) {
                return $this->get($this->redirect_url);
            }
        } else {
            $this->redirect_url = '';
        }
            
	    return $page;
	}
	
	function sideReelPost($url, $data) {
		$fields = array();
		foreach($data as $key => $val){
			if (is_array($val)){
				foreach($val as $k => $v){
					$fields[]=$key."=".urlencode($v);
				}
			} else {
				$fields[]=$key."=".urlencode($val);
			}
		}
		
		$fields = implode("&",$fields);
		
		@curl_setopt($this->curl, CURLOPT_URL, $url);
		@curl_setopt($this->curl, CURLOPT_POST, true);
		@curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
		$page = curl_exec($this->curl);
			
		$error = curl_errno($this->curl);	
		if ($error != CURLE_OK || empty($page)) {
			return false;
		}

		curl_setopt($this->curl, CURLOPT_POST, false);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');
		
		return $this->maybeFollow($page);
	}
	
	
	function post($url, $data) {
		$fields = $this->makeQuery($data);
		
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
		$page = curl_exec($this->curl);
			
		$error = curl_errno($this->curl);	
		if ($error != CURLE_OK || empty($page)) {
			return false;
		}

		curl_setopt($this->curl, CURLOPT_POST, false);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');
		
		return $this->maybeFollow($page);
	}
	
	function get($url, $data = null) {
        curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
		if (!is_null($data)) {
            $fields = $this->makeQuery($data);
            $url .= '?' . $fields;
        }

		curl_setopt($this->curl, CURLOPT_URL, $url);
		$page = curl_exec($this->curl);
		
		$error = curl_errno($this->curl);

		if ($error != CURLE_OK || empty($page)) {
			return false;
		}
		
		return $this->maybeFollow($page);
	}
}

?>
