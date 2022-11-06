<?php

class SiteProApi
{
	public $success = false;
	public $message = '';
	
	function setUsername($username)
	{
		$this->username = $username;
	}

	function setPassword($password)
	{
		$this->password = $password;
	}
	
	function setApiUsername($username)
	{
		$this->apiUsername = $username;
	}

	function setApiPassword($password)
	{
		$this->apiPassword = $password;
	}

	function setDomain($domain)
	{
		$this->domain = $domain;
	}

	function setApiURL($url)
	{
		$this->apiUrl = $url;
	}

	function setUploadDir($dir)
	{
		$this->dir = $dir;
	}

	function setHostname($hostname)
	{
		$this->hostname = $hostname;
	}

	function run()
	{
		$data_string = json_encode([
			"type" => "external",
			"username" => $this->username,
			"password" => $this->password,
			"domain" => $this->domain,
			"baseDomain" => $this->domain,
			"apiUrl" => $this->apiUrl,
			"uploadDir" => $this->dir
		]);
		$ch = curl_init($this->hostname.'/api/requestLogin');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->apiUsername.':'.$this->apiPassword);
		$headers = array(
		    'Content-type: application/json'
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		$json = json_decode($result, true);
		if(isset($json['error']))
		{
			$this->success = false;
			$this->message = $json['error']['message'];
		}
		elseif(isset($json['url'])) {
			$this->success = true;
			$this->message = $json['url'];
		}
		return true;
	}

	function isSuccessful()
	{
		return $this->success;
	}

	function getMessage()
	{
		return $this->message;
	}
}

?>