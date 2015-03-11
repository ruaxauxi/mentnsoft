<?php

namespace Vhdang\Model;

use Zend\Validator\File\Size;
use Zend\File\Transfer\Adapter\Http;
class FileUpload {
	protected $_file;
	protected $_defaultDir = 'public/uploads/images';
	
	protected $_dir;
	
	protected $_fileSize = 2097152; // 2MB
	
	protected $_adapter;
	protected $_newFileName;
	
	protected $_errors = array();
	
	/**
	 * @param Ambigous <string, multitype:, mixed, multitype:string > $_newFileName
	 */
	public function setNewFileName($_newFileName) {
		$this->_newFileName = $_newFileName;
	}

	public function __construct($file = null) {
		$this->_file = $file;
	}
	
	/**
	 *
	 * @return the $_file
	 */
	public function getFile() {
		return $this->_file;
	}
	
	/**
	 *
	 * @param field_type $_file        	
	 */
	public function setFile($_file) {
		$this->_file = $_file;
	}
	
	public function setDir($_dir){
		$this->_dir = $_dir;
	}
	
	public function getDir() {
		
		if ($this->_dir){
			return $this->_dir;
		}else{
			return $this->_defaultDir;
		}
		
		
	}
	
	public function getNewFileName(){
		return $this->_newFileName;
	}
	
	public function getError(){
		if (count($this->_errors)){
			return $this->_errors;
		}else{
			return null;
		}
	}
	
	/**
	 * valids file before uploading
	 * @return boolean
	 */
	public function isValid(){
		if ($this->_file){
			$size = new Size(array('max'=>$this->_fileSize)); //maximum 2MB
			
			$adapter = new Http();
				
			$adapter->setValidators(array($size),$this->_file['name']);
			if (!$adapter->isValid()){
				$this->_errors[] = 'Kích thước tập tin không lớn hơn ' . (int)($this->_fileSize/(1024*1024)) .'MB.';
			}
				
			$extensions = new \Zend\Validator\File\Extension(array('extension'=>array('jpg','png')));
			$adapter = new Http();
			$adapter->setValidators(array($extensions),$this->_file['name']);
				
			if (!$adapter->isValid()){
				$this->_errors[] = 'Định dạng ảnh không thích hợp.';
			}
			
			
			if ($this->getError()){
				return false;
			}else{
				$this->_adapter = $adapter;
				return true;
			}
		}else{
			return false;
		}
		 
	}
	
	/**
	 * upload file to server
	 * @return string filename|NULL
	 */
	public function upload(){
		
		if ($this->isValid()){
		 	
			$filename = $this->_file['name'];
			$file_ext = substr($filename, strlen($filename)-4, 4);
			$this->_adapter->setDestination($this->getDir());
			$this->_adapter->addFilter('File\Rename',
					array('target' =>$this->_adapter->getDestination().'/'.date('dmYHis').$file_ext,
							'overwrite' => true,
							'randomize'	=> true,
					));
			
			if ($this->_adapter->receive()) {
				$this->_newFileName = $this->_adapter->getFileName(null,false);
				return true;
			}else{
				return null;
			}
			
		} 
		 
	}
	
	public function cropImage($x1,$y1,$w,$h){
		
		if ($this->getNewFileName() && file_exists($this->getDir().'/'.$this->getNewFileName())){
			$image = $this->getDir().'/'.$this->getNewFileName();
			
			list($imagewidth, $imageheight, $imageType) = getimagesize($image);
			$imageType = image_type_to_mime_type($imageType);
			
			if ($imagewidth > $w){
				$scale = $w/$imagewidth;
					
			}else{
				$scale = 1;
					
			}
			
			$newImageWidth = ceil($w * $scale);
			$newImageHeight = ceil($h * $scale);
			$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($image);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($image);
					break;
				case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($image);
					break;
			}
			imagecopyresampled($newImage,$source,0,0,$x1,$y1,$newImageWidth,$newImageHeight,$w,$h);
			switch($imageType) {
				case "image/gif":
					imagegif($newImage,$image);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					imagejpeg($newImage,$image,90);
					break;
				case "image/png":
				case "image/x-png":
					imagepng($newImage,$image);
					break;
			}
			 
			return $image;
		}
		
	}
	
	public function resizeImage($width,$height){
		//function resizeImage($image,$width,$height,$scale) {
		
		if ($this->getNewFileName() && file_exists($this->getDir().'/'.$this->getNewFileName())){
			$image = $this->getDir().'/'.$this->getNewFileName();
			
			$file_size = filesize($image); // in bytes
			$max_size  = 102400;// 262144; // 256KB
			
			$quality = 75;
			/* if ($file_size > $max_size){
				$quality = (int)($max_size/$file_size)*100;
			} */
		
			
			list($imagewidth, $imageheight, $imageType) = getimagesize($image);
			$imageType = image_type_to_mime_type($imageType);
			
			$imageTypes = array(
					'image/gif',
					'image/jpeg',
					'image/jpg',
					'image/png',
					'image/x-png'
			);
			
			if ($imagewidth > $width){
				$scale = $width/$imagewidth;
				 
			}else{
				$scale = 1;
				 
			}
			
			$w = $imagewidth>$width ? $width:$imagewidth;
			$h = $imageheight>$height ? $height : $imageheight;
			
			$newImageWidth = ceil($imagewidth * $scale);
			$newImageHeight = ceil($imageheight * $scale);
			$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
			
			ImageColorTransparent($newImage, ImageColorAllocate($newImage, 0, 0, 0));
			 
			ImageAlphaBlending($newImage, false);
			
			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($image);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($image);
					break;
				case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($image);
					break;
			}
			
		
			imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$w,$h);
			
			switch($imageType) {
				case "image/gif":
					imagegif($newImage,$image);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					imagejpeg($newImage,$image,$quality);
					break;
				case "image/png":
				case "image/x-png":
					imagepng($newImage,$image);
					break;
			}
			
			return $image;
		}	
		
	}
	
	
	function getImageHeight() {
		if ($this->getNewFileName()){
			$image = $this->getDir().'/'.$this->getNewFileName();
			$size = getimagesize($image);
			$height = $size[1];
			return $height;
		}else{
			return false;
		}
		
	}
 
	function getImageWidth() {
		if ($this->getNewFileName()){
			$image = $this->getDir().'/'.$this->getNewFileName();
			$size = getimagesize($image);
			$width = $size[0];
			return $width;
		}else{
			return false;
		}
		
	}
	
}