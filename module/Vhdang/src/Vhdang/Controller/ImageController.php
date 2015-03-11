<?php
namespace Vhdang\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
 

use Vhdang\Model\Image;
 
use Vhdang\Model\FileUpload; 
use Zend\View\Model\JsonModel;


class ImageController extends AbstractActionController {
    
    protected $_imageTable;
    protected $_dbAdapter;
	protected $_defaultImgDir = 'public/uploads/images';
    
    
    public function getDbAdapter(){
    	if (! $this->_dbAdapter) {
			$sm = $this->getServiceLocator ();
			$adapter = $sm->get ( 'ZendDbAdapter' );
			$this->_dbAdapter = $adapter;
		}
		return $this->_dbAdapter;
	}
	
	public function getImageDir() {
		$config = $this->getServiceLocator ()->get ( 'config' );
		if (isset ( $config ['file_upload'] ['config'] )) {
    		$dir = $config ['file_upload'] ['config'] ['image_dir'];
    		return $dir;
    	} else {
    		return $this->_defaultImgDir;
    	}
    }
   
    
    public function getImageTable(){
    	if (!$this->_imageTable){
    		$this->_imageTable = new \Vhdang\Model\ImageTable($this->getDbAdapter());
    	}
    	return $this->_imageTable;
    }
    
    public function getUserImagesAction(){
       $userid = $this->UserAuthPlugin()->getUserLogonId();
       
    }
    
    public function indexAction(){
    	return $this->getResponse()->setContent(null);
    }
    
    public function addAction(){         
    	 $request = $this->getRequest();
    	 
    	 
    	//if ($request->isXmlHttpRequest()){
    		 
    		 
    		
    		if ( $request->isPost()){ 
    			$file    = $this->getRequest()->getFiles()->toArray();
    			$file = $file['file'];
    			$file['name']	=  $request->getPost('name',null);
    				
    			$fileupload = new FileUpload($file);
    			
    			$fileupload->setDir($this->getImageDir());
    			 
    			if ($fileupload->upload()){
    				//$fileupload->resizeImage(1024,768);
    		
    				//$scale =  100/$fileupload->getImageWidth();
    				
    				$image = new Image();
    				$image->setName($fileupload->getNewFileName());
    				$image->setWidth($fileupload->getImageWidth());
    				$image->setHeight($fileupload->getImageHeight());
    				$image->setDir($this->getImageDir());
    				
				
    				$image_id = $this->getImageTable()->saveImage($image);
    				
    				 
    				
    				$imgdir = $this->getImageDir();
    				$path = substr($imgdir,7,strlen($imgdir)-7)."/".$image->getName();
    				 
    				
    				$imagename = $this->getImageDir() . '/' . $fileupload->getNewFileName();
    				
    				// remove 'public'
    				$imagename = substr($imagename, 6);
    				
    				$result = new JsonModel(array(
    						'success'=>true,
    						'image_id' => $image_id,
    						'image' => $imagename,
    						
    				));
    				return $result;
    				 
    			}
    			
    			$result = new JsonModel(array(
    					'success'=>false,
    					'error'	=> 'Không thể upload',
    			));
    			return $result;
    			
    		}
 	
			$result = new JsonModel ( array (
					'success' => false,
					'error'  => 'error'
			) );
			return $result; 
	}
	
	
	public function deleteAction() {
		
		$request = $this->getRequest ();
		if ($request->isXmlHttpRequest ()) {
				$id = ( int ) $this->getRequest ()->getPost ( 'image_id', - 1 );
				$image = $this->getImageTable()->getImageById($id);
				if ($image) {
					
					$file = $this->getImageDir().'/'.$image->getName();
					
					if (file_exists($file)){
						unlink($file);
					}
					
					$this->getImageTable()->deleteImage($image);
					$result = new JsonModel ( array (
							'success' => 'yes',
					) );
					
					return $result;
					
				} else {
					$result = new JsonModel(array(
	        				'success'=>'no',
	        		));
	        		return $result;
	        	}
        }
        
        $result = new JsonModel(array(
        		 
        ));
        return $result;
    }
    
}