<?php
//Copyright (c) 2014, SWITCH
$info = <<<SYNOPSIS
Synopsis
Tries to download the favicon for the (second level) domain name of a given URL.
If available, the favicon mentioned the HTML document of that domain is used.
Otherwise the script tries to guess the typical locations of the favicion 
document.

Output:
If successful, the script returns the path of the downloaded favicon and an 
error code 0.
If unsucessful, it returns 'No favicon found' and error code 1.

Usage: php get-favicon-for-URL.php <directory> <url>
* <directory> Where to save the favicon
* <url> URL for whose domain the favicon shall be downloaded

Examples:
* php get-favicon-for-URL.php https://www.renater.fr/some/path
* php get-favicon-for-URL.php metadata.switchaai/ https://www.switch.ch/aai

SYNOPSIS;

class FaviconFetcher 
{
	public function getFavicon(string $url, string $directory = './'): string
	{
		// Get it from Google instead of doing all the work ourselves
		$domain = $this->getDomainName($url);
		
		//$faviconURL = 'http://g.etfv.co/http://www.'.$domain.'?defaulticon=none';
		$faviconURL = 'http://www.google.com/s2/favicons?domain=www.'.$domain.'';
		
		$content = $this->cURLopen($faviconURL);
		
		if (empty($content) || md5($content) == 'b8a0bf372c762e966cc99ede8682bc71'){
			return 'No favicon found';
		} else {
			$filePath = preg_replace('#\/\/#', '/', $directory.'/'.$domain.'.ico');
			$fp = fopen($filePath, 'w');
			fwrite($fp, $content);
			fclose($fp);
			
			return $filePath;
		}
	}
	
	private function getDomainName(string $url): string
	{
		$components = parse_url($url);
		return $this->getTopLevelDomain($components['host']);
	}
	
	private function getTopLevelDomain(string $host): string
	{
		$hostnameComponents = explode('.', $host);
		if (count($hostnameComponents) >= 2){
			return $hostnameComponents[count($hostnameComponents)-2].'.'.$hostnameComponents[count($hostnameComponents)-1];
		} else {
			return $host;
		}
	}
	
	private function URLopen($url)
	{
		$dh = fopen("$url",'r');
		$result = fread($dh,8192);                                                                                                                            
		return $result;
	} 
	
	private function cURLopen(string $url)
	{
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 1,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HEADER => 0,
		]);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;	
	}
}


?>
