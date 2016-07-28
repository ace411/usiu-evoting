<?php

class Images
{
	
	/*
	 *Image upload handler class
	 *Uploads single image files to the nominee directory in the img folder in the app directory
	 *Designed by Lochemem Bruno Michael
	*/
	
	private $url = "http://localhost/campusawards/app/img/nominees/";
	private $location;
	private $max_size = 1048576;
	private $extensions = array('png', 'gif', 'jpg');
	
	function __construct()
	{
		$this->location = str_replace('\\', '/', dirname(__DIR__) . '/img/nominees');
		return parent::connectTo();
	}
	
	public function displayLocation()
	{
		return $this->location;
	}
	
	private function scanDirectory()
	{
		return scandir($this->location);
	}
	
	public function uploadImage($img)
	{
		
		if(is_uploaded_file($img['tmp_name'])){
			$ext = explode('.', $img['name']);
			$ext = strtolower($ext[1]);
			if($img['size'] <= $this->max_size){
				if(in_array($ext, $this->extensions)){
					$newname = $this->location . '/' . $img['name'];
					$db_url = $this->url . $img['name'];
					if(move_uploaded_file($img['tmp_name'], $newname)){
						return $db_url;			
					}
				}else {
					return 'File is not supported';
				}
			}else {
				return 'File is too large';
			}			
		}else {
			return 'Please upload a file';
		}
	}
}
