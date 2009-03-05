<? if ( !defined("_THUMBALIZR") ) die ("no access");

class thumbalizrRequest {	
	function thumbalizrRequest($thumbalizr_config,$thumbalizr_defaults) { 
		$this->api_key=$thumbalizr_config['api_key'];
		$this->service_url=$thumbalizr_config['service_url'];
		$this->use_local_cache=$thumbalizr_config['use_local_cache'];
		$this->local_cache_dir=$thumbalizr_config['local_cache_dir'];
		$this->local_cache_expire=$thumbalizr_config['local_cache_expire'];		
		$this->encoding=$thumbalizr_defaults['encoding'];
		$this->quality=$thumbalizr_defaults['quality'];
		$this->delay=$thumbalizr_defaults['delay'];
		$this->bwidth=$thumbalizr_defaults['bwidth'];
		$this->mode=$thumbalizr_defaults['mode'];
		$this->bheight=$thumbalizr_defaults['bheight'];
		$this->width=$thumbalizr_defaults['width'];	
	}

	function build_request($url) {
		$this->request_url=
		$this->service_url."?".
		"api_key=".$this->api_key."&".
		"quality=".$this->quality."&".
		"width=".$this->width."&".
		"encoding=".$this->encoding."&".
		"delay=".$this->delay."&".
		"mode=".$this->mode."&".
		"bwidth=".$this->bwidth."&".
		"bheight=".$this->bheight."&".
		"url=".$url;
		$this->local_cache_file=md5($url)."_".$this->bwidth."_".$this->bheight."_".$this->delay."_".$this->quality."_".$this->width.".".$this->encoding;
		$this->local_cache_subdir=$this->local_cache_dir."/".substr(md5($url),0,2);		
	}
	
	
	function request($url) { 
		$this->build_request($url);
		
		if (isset($_REQUEST['debug'])) {
			echo "file path: " . $this->local_cache_subdir."/".$this->local_cache_file . "<br/>";
			echo "file exists: " . file_exists($this->local_cache_subdir."/".$this->local_cache_file);
			echo "<hr />";
		}
		
		if (file_exists($this->local_cache_subdir."/".$this->local_cache_file)) { 
			$filetime=filemtime($this->local_cache_subdir."/".$this->local_cache_file);
			$cachetime=time()-$filetime-($this->local_cache_expire*60*60);
		} else {
			$cachetime=-1;
		}
		if (!file_exists($this->local_cache_subdir."/".$this->local_cache_file) || $cachetime>=0) {
						
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->request_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			ob_start();
			$contents = curl_exec($ch);
			$http_response_body = ob_get_contents();
			$http_response_header = curl_getinfo($ch);
			curl_close($ch);
			ob_end_clean();			
			
			//echo "<hr/>";
			//echo var_dump($http_response_body);
			//echo "<hr/>";
			//echo var_dump($http_response_header);
			
			$this->img= $http_response_body;
			if (($http_response_header["size_download"] > 13432) && ($http_response_header["size_download"] > 0)) {
				$http_response_header['status'] = "OK";
			}
			$this->headers= $http_response_header;		
			$this->save();
		} else {			
			$this->img= file_get_contents($this->local_cache_subdir."/".$this->local_cache_file);
			$this->headers['url']= $url;
			$this->headers['status']= 'LOCAL';		
		}
	}
	
	function save() { 
//		echo var_dump($this->headers);
//		exit;
		
		if ($this->img && $this->use_local_cache===TRUE && $this->headers['status']=="OK") {
			
//			echo "SAVE!";
			
			if (!file_exists($this->local_cache_subdir)) { mkdir($this->local_cache_subdir); }
	 		$fp=fopen($this->local_cache_subdir."/".$this->local_cache_file,'w');
	 		fwrite($fp,$this->img);
	 		fclose($fp);
		}
	}
	
	function output($sendHeader = true,$destroy = true) {  
		if ($this->img) {
			if ($sendHeader) {
				if ($this->encoding=="jpg") {
					header("Content-type: image/jpeg");
				} else {
					header("Content-type: image/png");
				}
			}
			echo $this->img;				
			if ($destroy) {
				$this->img= false;
			}
		} else {
			return false;
		}
	}

}
?>