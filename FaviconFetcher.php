<?php
namespace FaviconFetcher;

class FaviconFetcher 
{
	/**
	 * @param string $url URL from whose domain will be searched for a favicon
	 * @param string $directory The directory where the favicon should be saved
	 */
	public function getFavicon(string $url, string $directory = './'): string
	{
		$domain = $this->getDomainName($url);
		
		$faviconURL = 'http://www.google.com/s2/favicons?domain=www.' . $domain . '';
		
		$content = $this->cURLopen($faviconURL);
		
		if (empty($content) || md5($content) == 'b8a0bf372c762e966cc99ede8682bc71') {
			return '';
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

		if (count($hostnameComponents) >= 2) {
			return $hostnameComponents[count($hostnameComponents)-2] . '.' . $hostnameComponents[count($hostnameComponents)-1];
		} else {
			return $host;
		}
	}
	
	private function cURLopen(string $url): bool|string
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

