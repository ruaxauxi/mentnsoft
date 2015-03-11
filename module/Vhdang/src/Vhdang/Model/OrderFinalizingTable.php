<?php

namespace Vhdang\Model;

 
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;

class OrderFinalizingTable extends AbstractTableGateway{

    public $table = 'admin_order';
    public $adapter;
    
    function __construct(Adapter $adapter) {
    	$this->adapter = $adapter;
    }
    
   /*  protected $_orderno;
    protected $_admin;
    protected $_orderdate;
    protected $_store_id;
    protected $_store_name;
    protected $_items;
    protected $_total_web;
    protected $_total_web1;
    protected $_discount;
    protected $_tax;
    protected $_ship_us;
    protected $_total_final;
    protected $_creditcard;
    protected $_holder; */
    
    public function getOrdersByCreditCard($creditcard, $finalized = 0){
        $select = new Select($this->table);
        
        $select->columns(array(
        	'orderno'  => 'orderno',
            'admin'     => 'admin',
            'orderdate' => 'orderdate',
            'store_id'  => 'store_id',
            'items'     => 'items',
            'total_web' => 'total_web',
            'total_web1' => 'total_web1',
            'discount'      => 'discount',
            'tax'       => 'tax',
            'ship_us'   => 'ship_us',
            'total_final'   => 'total_final',
            'creditcard'    => 'creditcard',
            'holder'        => 'holder',            
        ))->join(array('s'  => 'store'), "$this->table.store_id = s.id",array(
        	'store_name'   => 'name'
        ))->where(array(
        	'finalized'    => $finalized,
            'creditcard'   => $creditcard
            
        ))->order(array(
        	'orderdate'    => 'DESC'
        ));
        
        $resultSet = $this->selectWith($select);
        $items = $resultSet->toArray();
        $list = array();
        
        $cancelledItemTable = new CancelledItemsTable($this->adapter);
        $cancelledOrderTable = new CancelledOrdersTable($this->adapter);
        $orderdetailTable = new AdminOrderDetailsTable($this->adapter);
        
        
        foreach ($items as $row) {
        	$item = new OrderFinalizing();
        	$item->setData($row);
        	
        	
        	$cancelledItem = $cancelledItemTable->getTotalItemByOrderno($item->getOrderno());
        	if ($cancelledItem){
        	    $item->setCancelled_items($cancelledItem);
        	}else{
        	    $item->setCancelled_items(0);
        	}
        	
        	$totalCancel = $cancelledOrderTable->getTotalWeb1ByOrderNo($item->getOrderno());
        	
        	if ($totalCancel){
        	    $item->setTotal_cancelled($totalCancel);
        	}else{
        	    $item->setTotal_cancelled(0);
        	}
        	
        	$service = $orderdetailTable->getTotalServiceByOrderno($item->getOrderno());
        	$totalFinal = $orderdetailTable->getTotalFinalByOrderno($item->getOrderno());
        	
        	$item->setPaid($totalFinal - $service - $item->getTotal_cancelled());
        	
        	$list[] = $item;
        }
        return $list;
        
    }
    
    
    public function getOrdersByShipmentId($shipment_id, $finalized = 0){
    	$select = new Select($this->table);
    
    	$select->columns(array(
    			'orderno'  => 'orderno',
    			'admin'     => 'admin',
    			'orderdate' => 'orderdate',
    			'store_id'  => 'store_id',
    			'items'     => 'items',
    			'total_web' => 'total_web',
    			'total_web1' => 'total_web1',
    			'discount'      => 'discount',
    			'tax'       => 'tax',
    			'ship_us'   => 'ship_us',
    			'total_final'   => 'total_final',
    			'creditcard'    => 'creditcard',
    			'holder'        => 'holder',
    	))->join(array('so'  => 'shipment_order'), "$this->table.orderno = so.orderno", array(
    			'f_items'    => 'items',
    			'f_total_web'   => 'total_web',
    			'f_total_final' => 'total_final'
    	))->join(array('s'  => 'store'), "$this->table.store_id = s.id",array(
    			'store_name'   => 'name'
    	))->where(array(
    			'finalized'    => $finalized,
    			'shipment_id'   => $shipment_id
    
    	));
    
    	$resultSet = $this->selectWith($select);
    	$items = $resultSet->toArray();
    	$list = array();
    	foreach ($items as $row) {
    		$item = new OrderFinalizing();
    		$item->setData($row);
    		$list[] = $item;
    	}
    	return $list;
    
    }
}