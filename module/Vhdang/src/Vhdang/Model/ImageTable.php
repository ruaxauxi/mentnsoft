<?php
namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Vhdang\Model\Image;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\In;

class ImageTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'image';
    public $adapter;
    
    function __construct(Adapter $adapter) {
    	$this->adapter = $adapter;
    }

    /**
     * get all image
     * 
     * @return multitype:\Vhdang\Model\Image
     */
    public function getAll()
    {
        $where = new Where();
         
        $select = new Select($this->table);
        $select->columns(array(
            'id',
            'name' => 'name',
            'height'    => 'height',
            'width'     => 'width',
            'dir'      => 'dir'
        ), true);
        
        $select->where($where);
        
        $resultSet = $this->selectWith($select);
        
        $list = $resultSet->toArray();
        $images = array();
        foreach ($list as $row) {
            $image = new Image();
            $image->setData($row);
            $images[] = $image;
        }
        return $images;
    }
    
    
    public function saveImage(Image $image){
    	$data = array(
    			'name'         => $image->getName(),
    			'width'			=> $image->getWidth(),
    			'height'			=> $image->getHeight(),
    	        'dir'         => $image->getDir(),
    	);
    	 
    	 
    	$id = $image->getId();
    	 
    	if($id == 0){
    		$this->insert($data);
    		$image->setId($this->getLastInsertValue());//update last inserted id
    	}else{
    		if ($this->getImageById($id,'all')){
    			$this->update($data,array('id' => $id
    			));
    		} else {
    			throw new \Exception("Update failed: Image id $id does not exist.");
    		}
    	}
    
    	unset($id);
    	unset($data);
    	return  $image->getId();
    }
	
    
    
    public function getImageById($_id){
    	$_id = (int)$_id;
    
    	$where = new Where(); 
    	 
    
    	$where->equalTo('id', $_id);
    
    	$select = new Select($this->table);
    	$select   -> columns(array(
    			'id',
    			'name' => 'name', 
    			'width'	=> 'width',
    			'height'	=> 'height',
    	        'dir'     => 'dir'   
    	),true);
    
    	$select->where($where);
    	$resultSet = $this->selectWith($select);
    	$row = $resultSet->current();
    		
    	if ($row){
    		$image = new Image();
    		$row = (array) $row;
    		$image->setData($row);
    		return $image;
    	}else{
    		return null;
    	}
    }
    
    
    public function deleteOrderImageByShipment($shipment_id){
        
        $subselect = new Select('order_image');
        $subselect->columns(array(
        		'image_id'
        ))        
        ->join(array('so' => 'shipment_order'), "so.orderno = order_image.orderno",array())
        ->where(array(
        		'so.shipment_id' => $shipment_id
        ));
        
        
        $delete = new Delete($this->table);
        $delete->where->addPredicate(
        		new In('id',$subselect)
        );
         
        $total = $this->deleteWith($delete);
        
        return $total;
    }
    
    public function deleteOrderDetailImageByShipment($shipment_id){
        $subselect = new Select('order_details_image');
        $subselect->columns(array(
        		'image_id'
        ))        
        ->join(array('od' => 'order_details'), "od.id = order_details_image.order_details_id",array())
        ->join(array('so' => 'shipment_order'), "so.orderno = od.orderno",array())
        ->where(array(
        		'so.shipment_id' => $shipment_id
        ));
        
        
        $delete = new Delete($this->table);
        $delete->where->addPredicate(
        		new In('id',$subselect)
        );
         
        $total = $this->deleteWith($delete);
        
        return $total;
    }
    
    
    public function deleteByShipmentId($shipment_id){
        $total = $this->deleteOrderImageByShipment($shipment_id);
        $total += $this->deleteOrderDetailImageByShipment($shipment_id);

        return $total;
        
        
    }
    
    public function deleteImageByID($id){
    	
    	$this->delete(array('id' => $id));
    }
    
    public function deleteImage(Image $image){    	 
        $this->delete(array('id' => $image->getId()));
    }
}