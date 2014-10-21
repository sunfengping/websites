<?php
/**
 *@package API Key Generator
 *@version 1.1
 *@author Sourcegeek
 *
*/

class KeyGenerator {
	/*
	 *@var string
	 *
	*/
	private $user_form;
	
	/**
	 *@var string
	 *
	*/
	private $key_gen;
	
	/**
	 *@var string
	 *
	*/
	private $data_file = 'data.txt';
	
	/**
	 *@var string
	 *
	*/
	private $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	/**
	 *@var int
	 *
	*/
	private $length = 25;
	
	
	/**
	 * public setFile
	 * Sets file to save and validate keys
	 *
	 *@string file
	 *
	*/
	public function setFile($file) {
		$this->data_file = $file;
	}
	
	
	/**
	 * public setChars
	 * Sets characters to be used when generating key
	 *
	 *@string chars
	 *
	*/
	public function setChars($chars) {
		$this->chars = $chars;
	}
	
	
	/**
	 * public setLength
	 * Sets lenght of the key to be generated
	 *
	 *@int length
	*/
	public function setLength($length) {
		$this->length = $length;
	}
	
	/**** Loaders ****/
	/**
	 * public Generate
	 * Generates and saves new key
	 *
	 *@string user
	 *
	*/
	public function generate($user) {
		return self::_generate($user);
	}
	
	
	/**
	 * public Validate
	 * Validates a key corresponding to the user
	 *
	 *@string user
	 *@string key
	 *
	*/
	public function validate($user, $key) {
		return self::_validate($user, $key);
	}
	
	
	/**** /Loaders ****/
	private function _validate($user, $key) {
		if(empty($user) || empty($key))
			return false;
			
		$key = rawurlencode($key);
		
		if(!file_exists($this->data_file))
			return false;
			
		$file = file_get_contents($this->data_file);
		$data = explode("\r\n", $file);
		
		foreach($data as $line) {
			$single = explode(':', $line);
			$dat2[] = $single[0];
			$dat3[] = $single[1];
		}
		
		if(in_array($user, $dat2)) {
			// User exists. Validate key
			$position = array_search($user, $dat2);
			if($dat3[$position] == $key)
				return true;
		}
		return false;
	}
	
	
	private function _generate($user) {
		// Generate the key
		$key = substr(self::_generate_key(), 0, $this->length);
		
		if(!file_exists($this->data_file)) {
			$file = fopen($this->data_file, 'a+');
			$read = @fread($file, filesize($this->data_file));
		}else
			$read = file_get_contents($this->data_file);
			
		$data = explode("\r\n", $read);
		
		foreach($data as $line) {
			$single = explode(':', $line);
			$dat2[] = $single[0];
			$dat3[] = $single[1];
		}
		
		if(in_array($user, $dat2)) {
			// User repeated. Return the existing
			$position = array_search($user, $dat2);
			return rawurlencode($dat3[$position]);
		}elseif(in_array($key, $dat3)) {
			// Different username, same key. Re-call the function
			self::Validate($user, $key);
		}else{
			// Ok.
			$open = fopen($this->data_file, 'a+');
			$key = rawurlencode(htmlentities($key));
			fwrite($open, "$user:$key\r\n");
			fclose($open);
			return $key;
		}
		return false;
	}
	
	
	private function _generate_key() {
		$key = null;
		
		for($i = 0; $i < $this->length; $i++) {
			$random = rand(0, strlen($this->chars) - 1);
			$key .= $this->chars{$random};
		}
		
		return $key;
	}
}