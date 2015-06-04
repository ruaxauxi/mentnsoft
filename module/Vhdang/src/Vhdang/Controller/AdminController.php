<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Vhdang\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;

use Vhdang\Model\CustomerOrderTable;
use Zend\View\Model\JsonModel;
use Vhdang\Model\StoreTable;
use Vhdang\Model\CustomerTable;
use Vhdang\Model\XRatesTable;
use Vhdang\Model\XRates;
use Vhdang\Model\Store;
use Vhdang\Model\AdminOrderTable;
use Vhdang\Model\AdminOrder;
use Vhdang\Model\AdminTable;
use Vhdang\Model\CreditCardTable;
use Zend\Session\Container;
use Vhdang\Model\MySession;
use Vhdang\Model\ShipmentOrderTable;
use Vhdang\Model\Shipment;
use Vhdang\Model\ShipmentTable;
use Vhdang\Model\ShipmentOrder;
use Vhdang\Model\OrderImageTable;
use Vhdang\Model\OrderImage;
use Vhdang\Model\AdminOrderDetails;
use Vhdang\Model\OrderDetailsImageTable;
use Vhdang\Model\AdminOrderDetailsTable;
use Vhdang\Model\OrderDetailsImage;
use Vhdang\Model\Transaction;
use Vhdang\Model\TransactionTable;
use Vhdang\Model\TransferTable;
use Vhdang\Model\BalanceTable;
use Vhdang\Model\CustomerOrder;
use Vhdang\Utils\String;
use Zend\Escaper\Escaper;
use Vhdang\Model\ShippingWeightTable;
use Vhdang\Model\ShippingWeight;
use Vhdang\Model\CancelledItems;
 
use Vhdang\Model\CancelledItemsTable;
use Vhdang\Model\BackorderedItemsTable;
use Vhdang\Model\BackorderedItems;
use Vhdang\Model\CancelledOrdersTable;
use Vhdang\Model\CancelledOrders;
use Vhdang\Model\BackOrderedTable;
use Vhdang\Model\BackOrdered;
use Vhdang\Model\OrderFinalizingTable;
use Vhdang\Model\QuestionTable;
use Vhdang\Model\Question;
use Vhdang\Model\AnswerTable;
use Vhdang\Model\Answer;
use Vhdang\Model\TongKetDung;
use Vhdang\Model\TongThuKH;
use Vhdang\Model\ShippingFeeTable;
use Vhdang\Model\ShippingFee;
use Vhdang\Model\AdditionalFeeTable;
use Vhdang\Model\AdditionalFee;
use Vhdang\Model\ViewShippingFee;
use Vhdang\Model\ViewAdditionalFee;
use Vhdang\Model\Summarize;
use Vhdang\Model\ChiTietTongThuKH;
use Vhdang\Model\ShippingFeeDetail;
use Vhdang\Model\ChiTietTongTienHang;
use Vhdang\Model\GiaoDichKhac;
use Vhdang\Model\TongKetTable;
use Vhdang\Model\TongKet;
use Vhdang\Model\CreditCard;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use Vhdang\Model\ImageTable;
use Zend\I18n\View\Helper\DateFormat;
use Vhdang\Model\AddressTable;
use Vhdang\Model\ShippingMethodTable;
use Vhdang\Model\Transfer;
use Vhdang\Model\ExpressShippingFeeTable;
use Vhdang\Model\ExpressShippingFee;
 
class AdminController extends AbstractActionController
{
    protected $_dbAdapter;
    const waiting = 'waiting';
    const checked = 'checked';
    const incomplete = 'incomplete';
    
    const received = 'received';
    const add = '+';
    const minus = '-';
    const cancelled = 'cancelled';
    const partiallycancelled = 'partially cancelled';
    const backordered = "backordered";
    const partiallybackordered = "partially backordered";
    const arrived = 'arrived';
 
    const bought = "bought";
    const ontheway= "on the way";
    
    const dung_service = 0.03; // dung service 3%
    const dung_shipping = 0.6;// dung shipping = 0.6;
    
    const cancelledOrders_ID = 18; // it's used to move this line to the top.

    public function getDbAdapter()
    {
        if (! $this->_dbAdapter) {
            $sm = $this->getServiceLocator();
    		$adapter = $sm->get('ZendDbAdapter');
    		$this->_dbAdapter = $adapter;
    	}
    	return $this->_dbAdapter;
    }
    
    public function indexAction()
    {
        
        return $this->redirect()->toRoute('home',array('action'=>'login'));
        
        return new ViewModel(array(
        		
        ));
    }
    
    /**
     * @abstract nhap thong tin chuyen khoan  cho kh
     * 
     */
    public function usertransferAction(){
        $request = $this->getRequest();
        
        $customerTable = new CustomerTable($this->getDbAdapter());
        
        $admin = $this->UserAuthPlugin()->getNick();
        
        $nick = $request->getPost('nicks',null);
        if (!$nick){
            $nick = $request->getPost('nick',null);
        }
       
        
        $transferTable = new TransferTable($this->getDbAdapter());
        
        
        $rateTable = new XRatesTable($this->getDbAdapter());
        $rate = $rateTable->getCurrentRate();
        $errmsg = "";
        
        $tid = $this->params()->fromRoute('tid',0);
        
        if ($tid){
        	$transfer = $transferTable->getTransferById($tid);
        	if ($transfer && $transfer->getStatus() =='waiting'){
        		$transferTable->deleteTransfer($tid,$this::waiting);
        		if ($request->isXmlHttpRequest()){
        			return new JsonModel(array(
        					'success' => 1,
        					'errormsg'  => ''
        			));
        		}
        	}else{
        		if ($request->isXmlHttpRequest()){
        			return new JsonModel(array(
        					'success' => 0,
        					'errormsg'  => 'Không thể xóa.'
        			));
        		}
        	}
        }
        
        if ($request->isPost() && $nick && $request->getPost('btnAdd',null)){
        	$data = (array) $request->getPost();
        
        	$transfer = new Transfer();
        	$transfer->setData($data);
        	$transfer->setNick($nick);
        	$transfer->setStatus($this::waiting);
        	$transfer->setX_rate($rate->getRate());
        	if ($transfer->isValid()){
        		$transferTable->saveTransfer($transfer);
        		if ($request->isXmlHttpRequest()){
        			return new JsonModel(array(
        					'success' => 1,
        					'errormsg'  => ''
        			));
        		}
        	}else{
        		if ($request->isXmlHttpRequest()){
        			return new JsonModel(array(
        					'success' => 0,
        					'errormsg'  => 'Không thể lưu, kiểm tra lại dữ liệu.'
        			));
        		}else{
        			$errmsg = "Không thể lưu, kiểm tra lại dữ liệu.";
        		}
        	}
        }
         
        
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $nicks = $customerTable->fetchAll();
        
        $transfers = $transferTable->getTransferByNick($nick);
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transfers));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        
        $user = $customerTable->getUserById($nick);
        $balanceTable = new BalanceTable($this->getDbAdapter());
        $balance = $balanceTable->getBalanceById($user->getBalance_id());
                     
        $view = new ViewModel(array(
            'errmsg'    => $errmsg,
            'nick' => $nick,
            'nicks' => $nicks,
            'user'  => $user,
            'page' => $page,
            'row'   => $itemsPerPage,
            'paginator' => $paginator,
            'isAjaxRequest' => $request->isXmlHttpRequest(),
            'rate'  => $rate,
            'balance'   => $balance->getCredit()
        ));
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    /**
     * 
     * @abstract goi tinh nhan cho KH
     * @return \Zend\View\Model\ViewModel
     */
    public function sendmessageAction(){
        $request = $this->getRequest();
        $questionTable = new QuestionTable($this->getDbAdapter());
        
        $admin = $this->UserAuthPlugin()->getNick();
        
        $nick = $request->getPost('nick',null);
        
        $customerTable = new CustomerTable($this->getDbAdapter());
        
        
        if ($request->isXmlHttpRequest() && $request->isPost() && $request->getPost('btnSubmit')){
        
        	$content = $request->getPost('content');
        
        	$content = preg_replace("/[\n]/","<br/>",$content);
        	$content = String::removeBBCode($content);
        	$content = String::makelink($content);
        
        
        	$subject = $request->getPost('subject');
        
        	$question = new Question();
        
        
        	$question->setSubject($subject);
        	$question->setContent($content);
        	$question->setAnswer(1);
        	$question->setRead(0);
        	$question->setDatecreated(date("Y-m-d H:i:s"));
        	$question->setNick($nick);
        	$question->setAdmin($admin); 
        
        	$questionTable->save($question);
        }
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        
        $paginator = $questionTable->getPM($nick);
        $nicks = $customerTable->fetchAll();        
        
        
        // $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($questions));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        
        $view = new ViewModel(array(
        		'paginator' => $paginator,
                'nicks' => $nicks,
                'nick'  => $nick,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    public function viewallcustomeraddressAction(){
        
        $request = $this->getRequest();
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shippingmetthodTable = new ShippingMethodTable($this->getDbAdapter());
        
        $shipment_id = $request->getPost('shipment_id',null);;
        $ship_method = $request->getPost('ship_method',null);
        
        $search_nick = $request->getPost('nick',null);
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $addressTable = new AddressTable($this->getDbAdapter());
        $list = array();
        $list = $addressTable->getCustomersAddress($shipment_id,$ship_method,$search_nick);
        $shipments = $shipmentTable->fetchAll(1);
        $shippingMethods = $shippingmetthodTable->fetchAll();
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
        
        
        
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
                'shipments' => $shipments,
                'shipment_id'   => $shipment_id,
                'method'   => $ship_method,
                'shippingmethods'   => $shippingMethods,
                'search_nick' => $search_nick,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    public function loadmorecustomertransAction(){
        
    	$request = $this->getRequest();
    	$page = (int) $request->getPost('page',1);
    	$limit = 30;
    	$offset = $limit * $page;
    	$nick = $request->getPost('nick',null);
    	
    	if ($request->isXmlHttpRequest() && $nick){
    		
    		$customerTable = new CustomerTable($this->getDbAdapter());
    		$customer = $customerTable->getUserByNick($nick);
    
    		if ($customer){
    			$transactionTable = new TransactionTable($this->getDbAdapter());
    			$transactions = $transactionTable->getTransactionByBalanceId($customer->getBalance_id(),$limit,$offset);
    			$orderDetail = new AdminOrderDetailsTable($this->getDbAdapter());
    			$transferTable = new TransferTable($this->getDbAdapter());
    			$list = array();
    			foreach($transactions as $trans){
    				if ($trans->getOrder_detail_id()){
    					$detail = $orderDetail->getOrderById($trans->getOrder_detail_id());
    					$trans->orderdetail = $detail; // add two more propterties
    					$trans->transfer = null;
    				}elseif($trans->getTransfer_id()){
    					$transfer = $transferTable->getTransferById($trans->getTransfer_id());
    					$trans->orderdetail = null;
    					$trans->transfer = $transfer;
    				}else{
    					$trans->orderdetail = null;
    					$trans->transfer = null;
    				}
    				$list[] = $trans;
    			}
    			 
    			$view = new ViewModel(array(
    			        'user'   => $customer,
    					'transactions'  => $list
    			));
    			 
    			$view->setTerminal(true);
    			return $view;
    			 
    		}else{
    			return null;
    		}
    
    
    		 
    		 
    	}else{
    		return $this->redirect()->toRoute('home');
    	}
    
    }
    
    
    public function viewcustomerbalanceAction(){
        
       
        $request = $this->getRequest();
        
        
        $nick = $request->getPost('nick',null);
         
        $userTable = new CustomerTable($this->getDbAdapter());
        
        $customers = $userTable->fetchAll();
        
        $user = $userTable->getUserByNick($nick);
        
        
        $limit = 30;
        $offset = 0;
        
        	$transactionTable = new TransactionTable($this->getDbAdapter());
        	$transactions = $transactionTable->getTransactionByBalanceId($user->getBalance_id(),$limit,$offset);
        
        	$orderDetail = new AdminOrderDetailsTable($this->getDbAdapter());
        	$transferTable = new TransferTable($this->getDbAdapter());
        	$list = array();
        	foreach($transactions as $trans){
        		if ($trans->getOrder_detail_id()){
        			$detail = $orderDetail->getOrderById($trans->getOrder_detail_id());
        			$trans->orderdetail = $detail; // add two more propterties
        			$trans->transfer = null;
        		}elseif($trans->getTransfer_id()){
        			$transfer = $transferTable->getTransferById($trans->getTransfer_id());
        			$trans->orderdetail = null;
        			$trans->transfer = $transfer;
        		}else{
        			$trans->orderdetail = null;
        			$trans->transfer = null;
        		}
        		$list[] = $trans;
        	}
        	 
        	$balanceTable = new BalanceTable($this->getDbAdapter());
        	$balance = $balanceTable->getBalanceById($user->getBalance_id());
        	$view = new ViewModel(array(
        	        'user' => $user,
        	       'customers' => $customers,
        			'transactions'  => $list,
        			'balance'   => $balance
        	));
        	 
        	return $view;
        
        
    }
    
    public function viewcustomerorderedAction(){
         
        $request = $this->getRequest();
        
        
        $nick = $request->getPost('nick',null);
         
        $userTable = new CustomerTable($this->getDbAdapter());
        
        $customers = $userTable->fetchAll();
        
        $user = $userTable->getUserByNick($nick);
        
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
         
        $shipment_id = $request->getPost('shipment_id',null);
        $status = $request->getPost('status',null);
        
        
         
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
         
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
         
        $shipments = $shipmentTable->getShipmentListByNick($nick);
        $statuses = $orderDetailsTable->getStatusByNick($nick);
         
          
        $orderDetails = $orderDetailsTable->getDetailsByNick($nick,$shipment_id,$status);
        $detailImageTable = new OrderDetailsImageTable($this->getDbAdapter());
        $list = array();
        foreach($orderDetails as $item){
        	$images = $detailImageTable->getOrderDetailImages($item->getId());
        	$item->images = $images;
        	$list[] = $item;
        }
         
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
         
         
         
        $view = new ViewModel(array(
                'customers' => $customers,
                'shipments' => $shipments,
                'shipment_id'   => $shipment_id,
                'status'    => $status,
                'statuses'    => $statuses,
                'user'  => $user,
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    /**
     * 
     * @abstract xem thong tin dat hang cua khach hang
     * @return \Zend\View\Model\ViewModel
     */
    public function viewcustomerorderAction(){
        
        $request = $this->getRequest();
        
        
        $orderTable = new CustomerOrderTable($this->getDbAdapter());
               
        $nick = $request->getPost('nick',null);
         
        $userTable = new CustomerTable($this->getDbAdapter());
        
        $customers = $userTable->fetchAll();

        $user = $userTable->getUserByNick($nick);
         
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
         
        $orders = $orderTable->customerListAll($user->getNick(),null,"DESC");
         
        
       // $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
        $paginator = $orderTable->customerListAll($user->getNick(),null,"DESC");
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
         
        $view =  new ViewModel(array(
        		'user'    => $user,
        		'customers'  => $customers,
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    public function viewcustomertransferAction(){
        
        $request = $this->getRequest();
        $transferTable = new TransferTable($this->getDbAdapter());
         
        $nick = $request->getPost('nick',null);
         
        $userTable = new CustomerTable($this->getDbAdapter());

        $user = $userTable->getUserByNick($nick);
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
         
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $transfers = $transferTable->getTransferByNick($nick);
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transfers));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
         
        $balanceTable = new BalanceTable($this->getDbAdapter());
        $balance = $balanceTable->getBalanceById($user->getBalance_id());
        $customers = $userTable->fetchAll();
         
        $view = new ViewModel(array(
        		'balance' => $balance,
                'user'  => $user,
                'customers' => $customers,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'paginator' => $paginator,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		 
        		 
        ));
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    
    /**
     * @abstract xoa bo ta ca cac transfer cua khach hang theo m-Y
     */
    public function deletecustomerordersAction(){
    
    	$request = $this->getRequest();
    
    	$customerOrderTable = new CustomerOrderTable($this->getDbAdapter());
    	
    	$dY = $customerOrderTable->getMonthAndYear();
    
    	$month = $request->getPost('month',null);
    	$year = $request->getPost('year',null);
    	if ($request->isXmlHttpRequest() && $month && $year){
    		$total = $customerOrderTable->deleteTransferByMonYear($month, $year);
    
    		return new JsonModel(array(
    				'total' => $total,
    				'success' => 1
    		));
    	}
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($dY));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    
    
    
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /**
     * @abstract xoa bo ta ca cac transfer cua khach hang theo m-Y
     */
    public function deleteotherstransAction(){
    
    	$request = $this->getRequest();
    
    	$transactionTable = new TransactionTable($this->getDbAdapter());
    	$dY = $transactionTable->getMonthAndYear();
    
    	$month = $request->getPost('month',null);
    	$year = $request->getPost('year',null);
    	if ($request->isXmlHttpRequest() && $month && $year){
    		$total = $transactionTable->deleteTransferByMonYear($month, $year);
    
    		return new JsonModel(array(
    				'total' => $total,
    				'success' => 1
    		));
    	}
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($dY));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    
    
    
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /**
     * @abstract xoa bo ta ca cac transfer cua khach hang theo m-Y
     */
    public function deletetransfersAction(){
        
        $request = $this->getRequest();
        
        $transferTable = new TransferTable($this->getDbAdapter());
        $dY = $transferTable->getMonthAndYear();
        
        $month = $request->getPost('month',null);
        $year = $request->getPost('year',null);
        if ($request->isXmlHttpRequest() && $month && $year){
            $total = $transferTable->deleteTransferByMonYear($month, $year);
            
            return new JsonModel(array(
            	'total' => $total,
                'success' => 1
            ));
        }
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($dY));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
        
        
        
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
        
    }
    
    
    public function admindeleteshipmentAction(){
        $request = $this->getRequest();
        
        $shipment_id = $request->getPost('shipment_id',null);
        
        if ($request->isXmlHttpRequest() && $shipment_id ){
            
            $orderTable = new AdminOrderTable($this->getDbAdapter());
            $imageTable = new ImageTable($this->getDbAdapter());
            $shipmentTable = new ShipmentTable($this->getDbAdapter());
            
            $total_image = $imageTable->deleteByShipmentId($shipment_id);
            
            $totalOrder = $orderTable->deleteOrderByShipmentId($shipment_id);
            $ok = $shipmentTable->deleteShipment($shipment_id);
            
            
            $view = new JsonModel(array(
            	'totalorder' => $totalOrder,      
                'total_image' => $total_image,
                'success' =>   $ok      
            ));
            
            return $view;
        }
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->fetchAll(1);
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($shipments));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
        
        
        
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    /**
     *
     * @abstract Export transactions
     *
     */
    public function exporttransactionsAction(){
    	$request = $this->getRequest();
    
    	if ($request->getPost('btnExport')){
    		$fM = $request->getPost('fMonth',null);
    		$fY = $request->getPost('fYear',null);
    		$tM = $request->getPost('tMonth',null);
    		$tY = $request->getPost('tYear',null);
    
    
    		$dateFrom = "$fY-$fM-1 00:00:00";
    		$nDay = cal_days_in_month(CAL_GREGORIAN, $tM, $tY);
    		$dateTo = "$tY-$tM-$nDay 23:59:59";
    
    		$transactionTable = new TransactionTable($this->getDbAdapter());
    		$trans = $transactionTable->ExportTransfer($dateFrom, $dateTo);
    
    		$objPHPExcel = new PHPExcel();
    
    		// Set document properties
    		$objPHPExcel->getProperties()->setCreator("VH Dang")
    		->setLastModifiedBy("VH Dang")
    		->setTitle('Backup TT CK')
    		->setSubject("Office 2007 XLSX Document")
    		->setDescription('Backup du lieu transactions từ 01-'.$fM.'-'.$fY.' đến '.$nDay.'-'.$tM.'-'.$tY );
    
    		$objPHPExcel->setActiveSheetIndex(0);
    		$objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
    		$objPHPExcel->getActiveSheet()->setCellValue('A2','Transactions từ 01-'.$fM.'-'.$fY.' đến '.$nDay.'-'.$tM.'-'.$tY);
    
    		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
    		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14)->setBold(true);
    
    		 
    
    		$objPHPExcel->getActiveSheet()
    		->setCellValue('A4','STT')
    		->setCellValue('B4','Id')
    		->setCellValue('C4','Trans Date')
    		->setCellValue('D4','Nick')
    		->setCellValue('E4','OrderNo')
    		->setCellValue('F4','Order Detail ID')
    		->setCellValue('G4','Transfer ID')
    		->setCellValue('H4','Shipment ID')
    		->setCellValue('I4','Type')
    		->setCellValue('J4','Amount')
    		->setCellValue('K4','Credit')
    		->setCellValue('L4','Note')
    		;
    
    
    		$i = 5;
    		foreach($trans as $t){
    			$objPHPExcel->getActiveSheet()
    			->setCellValue('A'.$i,$i)
    			->setCellValue('B'.$i,$t->getId())
    			->setCellValue('C'.$i,date("m/d/Y H:i:s",strtotime($t->getTrans_date())))
    			->setCellValue('D'.$i,$t->getNick())
    			->setCellValue('E'.$i,$t->getOrderno())
    			->setCellValue('F'.$i,$t->getOrder_detail_id())
    			->setCellValue('G'.$i,$t->getTransfer_id())
    			->setCellValue('H'.$i,$t->getShipment_id())
    			->setCellValue('I'.$i,$t->getType())
    			->setCellValue('J'.$i,number_format($t->getAmount(),2,".",','))
    			->setCellValue('K'.$i,number_format($t->getCredit(),2,".",','))
    			->setCellValue('L'.$i,$t->getNote());
    
    			$i++;
    		}
    
    		for($j = 5;$j<=$i;$j++){
    			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
    			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
    			$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
    			$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
    			$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
    			$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
    
    		}
    
    		$styleArray = array(
    				'borders' => array(
    						'allborders' => array(
    								'style' => PHPExcel_Style_Border::BORDER_THIN,
    								'color' => array('rgb' => '000000'),
    						),
    				),
    		);
    
    		$objPHPExcel->getActiveSheet()->getStyle('A4:L'.($i -1))->applyFromArray($styleArray);
    
    		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    		$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
    
    		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    		$objPHPExcel->setActiveSheetIndex(0);
    
    		// Redirect output to a client’s web browser (Excel5)
    		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    		//header('Content-Type: application/vnd.ms-excel');
    		header('Content-Disposition: attachment;filename="transactions-01-'.$fM.'-'.$fY.'--'.$nDay.'-'.$tM.'-'.$tY.'.xlsx"');
    
    		header('Cache-Control: max-age=0');
    		// If you're serving to IE 9, then the following may be needed
    		header('Cache-Control: max-age=1');
    
    		// If you're serving to IE over SSL, then the following may be needed
    		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    		header ('Pragma: public'); // HTTP/1.0
    
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    		$objWriter->save('php://output');
    		exit;
    
    	}
    
    	 
    
    }
    
    
    /**
     * 
     * @abstract Export thong tin chuyển khoản 
     * 
     */
    public function exporttransAction(){
        $request = $this->getRequest();
        
        if ($request->getPost('btnExport')){
            $fM = $request->getPost('fMonth',null);
            $fY = $request->getPost('fYear',null);
            $tM = $request->getPost('tMonth',null);
            $tY = $request->getPost('tYear',null);
            
            
            $dateFrom = "$fY-$fM-1 00:00:00";
            $nDay = cal_days_in_month(CAL_GREGORIAN, $tM, $tY);
            $dateTo = "$tY-$tM-$nDay 23:59:59";
            
            $transferTable = new TransferTable($this->getDbAdapter());
            $trans = $transferTable->ExportTransfer($dateFrom, $dateTo);
            
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("VH Dang")
            ->setLastModifiedBy("VH Dang")
            ->setTitle('Backup TT CK')
            ->setSubject("Office 2007 XLSX Document")
            ->setDescription('Backup du lieu từ 01-'.$fM.'-'.$fY.' đến '.$nDay.'-'.$tM.'-'.$tY );
            
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->mergeCells("A2:L2");
            $objPHPExcel->getActiveSheet()->setCellValue('A2','Thông tin chuyển khoản từ 01-'.$fM.'-'.$fY.' đến '.$nDay.'-'.$tM.'-'.$tY);
            
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14)->setBold(true);
            
           
            
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A4','STT')
                        ->setCellValue('B4','Id')
                        ->setCellValue('C4','Submit')
                        ->setCellValue('D4','Nick')
                        ->setCellValue('E4','Rate')
                        ->setCellValue('F4','Trans Date')
                        ->setCellValue('G4','RefNo')
                        ->setCellValue('H4','Type')
                        ->setCellValue('I4','VND')
                        ->setCellValue('J4','USD')
                        ->setCellValue('K4','Note')
                        ->setCellValue('L4','Status')
            ;
            
            
            $i = 5;
            foreach($trans as $t){
                $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$i,$i)
                            ->setCellValue('B'.$i,$t->getId())
                            ->setCellValue('C'.$i,date("m/d/Y H:i:s",strtotime($t->getDatecreated())))
                            ->setCellValue('D'.$i,$t->getNick())
                            ->setCellValue('E'.$i,number_format($t->getX_rate(),2,".",','))
                            ->setCellValue('F'.$i,date('m/d/Y',strtotime($t->getTrans_date())))
                            ->setCellValue('G'.$i,$t->getRefno())
                            ->setCellValue('H'.$i,$t->getTrans_type())
                            ->setCellValue('I'.$i,number_format($t->getVnd(),2,".",','))
                            ->setCellValue('J'.$i,number_format($t->getUsd(),2,".",','))
                            ->setCellValue('K'.$i,$t->getNote())
                            ->setCellValue('L'.$i,$t->getStatus());
                
                $i++;
            }
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');

            }
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            
            $objPHPExcel->getActiveSheet()->getStyle('A4:L'.($i -1))->applyFromArray($styleArray);
            
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ttck-01-'.$fM.'-'.$fY.'--'.$nDay.'-'.$tM.'-'.$tY.'.xlsx"');
            
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
            
        }
        
       
        
    }
    
    
    public function exportAction(){
        
        $request = $this->getRequest();
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipment_id = $request->getPost('shipment_id',Null);
        $shipment = $shipmentTable->getShipmentById($shipment_id);
        
       if ($shipment_id && $shipment->getId() == $shipment_id){            
            
           
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("VH Dang")
            ->setLastModifiedBy("VH Dang")
            ->setTitle($shipment->getShip_name())
            ->setSubject("Office 2007 XLSX Document")
            ->setDescription('Backup du lieu '. $shipment->getShip_name());
            
            $shipmentSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $shipmentSheet->setTitle('Shipment');
            
            
            $shipmentSheet->setCellValue('A1',"Export Date:");
            $shipmentSheet->setCellValue('B1',date("m/d/Y H:i:s"));
            
            $shipmentSheet->setCellValue('A3',"Shipment ID:");
            $shipmentSheet->setCellValue('B3',$shipment->getId());
            
            $shipmentSheet->setCellValue('A4',"Name:");
            $shipmentSheet->setCellValue('B4',$shipment->getShip_name());
            
            $shipmentSheet->setCellValue('A5',"Date:");
            $shipmentSheet->setCellValue('B5',$shipment->getShip_date());
            
            $shipmentSheet->setCellValue('A6',"Note:");
            $shipmentSheet->setCellValue('B6',$shipment->getNote());
            
            $shipmentSheet->setCellValue('A7',"Weight:");
            $shipmentSheet->setCellValue('B7',$shipment->getWeight());
            
           
            // add Shipment Sheet at 0
            $objPHPExcel->addSheet($shipmentSheet,0);
            
            // format Shipment Sheet
            $objPHPExcel->getSheet(0)->getStyle('B3')->getFont()->setBold(true);
            $objPHPExcel->getSheet(0)->getStyle('B4')->getFont()->setBold(true);
            
            
            
            $orderTable = new AdminOrderTable($this->getDbAdapter());
            $orders = $orderTable->getOrderInShipment($shipment_id);
            
            $orderSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $orderSheet->setTitle('Orders');
            
            $orderSheet->mergeCells('A2:P2');
            $orderSheet->setCellValue('A2','DANH SÁCH CÁC ORDER TRONG ĐỢT');
            
            $orderSheet->setCellValue('A4','STT')
                        ->setCellValue('B4','OrderNo')
                        ->setCellValue('C4','Admin')
                        ->setCellValue('D4','Store')
                        ->setCellValue('E4','Description')
                        ->setCellValue('F4','Credit Card')
                        ->setCellValue('G4','Holder')
                        ->setCellValue('H4','Order Date')
                        ->setCellValue('I4','Date Created')
                        ->setCellValue('J4','#Items')
                        ->setCellValue('K4','Total Web')
                        ->setCellValue('L4','Discount')
                        ->setCellValue('M4','Total Web1')
                        ->setCellValue('N4','Tax')
                        ->setCellValue('O4','Ship US')
                        ->setCellValue('P4','Total Final')            
            ;
            
            $i = 5;
            foreach($orders as $o){
                $orderSheet->setCellValue('A'.$i,$i-4)
                ->setCellValue('B'.$i,$o->getOrderno())
                ->setCellValue('C'.$i,$o->getAdmin())
                ->setCellValue('D'.$i,$o->getStore_name())
                ->setCellValue('E'.$i,$o->getDescription())
                ->setCellValue('F'.$i,$o->getCreditcard())
                ->setCellValue('G'.$i,$o->getHolder())
                ->setCellValue('H'.$i,date('m/d/Y',strtotime($o->getOrderdate())))
                ->setCellValue('I'.$i,date('m/d/Y H:i:s',strtotime($o->getDatecreated())))
                ->setCellValue('J'.$i,$o->getItems())
                ->setCellValue('K'.$i,number_format($o->getTotal_web(),2,".",','))
                ->setCellValue('L'.$i,number_format($o->getDiscount(),2,".",','))
                ->setCellValue('M'.$i,number_format($o->getTotal_web1(),2,".",','))
                ->setCellValue('N'.$i,number_format($o->getTax(),2,".",','))
                ->setCellValue('O'.$i,number_format($o->getShip_us(),2,".",','))                    
                ->setCellValue('P'.$i,number_format($o->getTotal_final(),2,".",","));
                
                
                                
                $i++;
            }
            
            $objPHPExcel->addSheet($orderSheet,1);
            
            // format Orders Sheet
            $objPHPExcel->getSheet(1)->getStyle('A2')->getFont()->setSize(16)->setBold(true);    
            
            for($j = 5;$j<=$i;$j++){
                $objPHPExcel->getSheet(1)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
                $objPHPExcel->getSheet(1)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
                $objPHPExcel->getSheet(1)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
                $objPHPExcel->getSheet(1)->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            }
            
            $objPHPExcel->getSheet(1)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(1)->getStyle('A4:P'.($i -1))->applyFromArray($styleArray);
            
            
            // order details
            $orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
            
            $details = $orderDetailTable->getOrderDetailInShipment($shipment_id);
            
            $orderDetailSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $orderDetailSheet->setTitle('Order Details');
            
            $orderDetailSheet->mergeCells('A2:P2');
            $orderDetailSheet->setCellValue('A2','DANH SÁCH CHI TIẾT ORDER TRONG ĐỢT');
            
            $orderDetailSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Order Detail ID')
            ->setCellValue('C4','Nick')
            ->setCellValue('D4','OrderNo')
            ->setCellValue('E4','Order Date')
            ->setCellValue('F4','Description')
            ->setCellValue('G4','#Item')
            ->setCellValue('H4','Discount')
            ->setCellValue('I4','Service')
            ->setCellValue('J4','Tax')
            ->setCellValue('K4','Extra Fee')
            ->setCellValue('L4','Ship US')
            ->setCellValue('M4','Total Web')
            ->setCellValue('N4','Total Web1')
            ->setCellValue('O4','Total Final')
            ->setCellValue('P4','Note')
            ;
            
            $i = 5;
            foreach($details as $o){
            	$orderDetailSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,$o->getId())
            	->setCellValue('C'.$i,$o->getNick())
            	->setCellValue('D'.$i,$o->getOrderno())
            	->setCellValue('E'.$i,date('m/d/Y',strtotime($o->getOrderdate())))
            	->setCellValue('F'.$i,$o->getDescription())
            	->setCellValue('G'.$i,$o->getItems())
            	->setCellValue('H'.$i,number_format($o->getDiscount(),2,".",','))
            	->setCellValue('I'.$i,number_format($o->getService(),2,".",','))
            	->setCellValue('J'.$i,number_format($o->getTax(),2,".",','))
            	->setCellValue('K'.$i,number_format($o->getExtra_fee(),2,".",','))
            	->setCellValue('L'.$i,number_format($o->getShip_us(),2,".",','))
            	->setCellValue('M'.$i,number_format($o->getTotal_web(),2,".",','))
            	->setCellValue('N'.$i,number_format($o->getTotal_web1(),2,".",','))
            	->setCellValue('O'.$i,number_format($o->getTotal_final(),2,".",','))
            	->setCellValue('P'.$i,$o->getNote());
            	
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($orderDetailSheet,2);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(2)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(2)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(2)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(2)->getStyle('c'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(2)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(2)->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            }
            
            $objPHPExcel->getSheet(2)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(2)->getStyle('A4:P'.($i -1))->applyFromArray($styleArray);
            
            
            // Cancelled Orders
            
            $cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
            
            $cOrders = $cancelledOrderTable->fetchAllByShipment($shipment_id);
            
            $cOrderSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $cOrderSheet->setTitle('Cancelled Orders');
            
            $cOrderSheet->mergeCells('A2:G2');
            $cOrderSheet->setCellValue('A2','DANH SÁCH CHI TIẾT ORDER BI CANCEL TRONG ĐỢT');
            
            $cOrderSheet->setCellValue('A4','STT')
            ->setCellValue('B4','ID')
            ->setCellValue('C4','OrderNo')
            ->setCellValue('D4','Admin')             
            ->setCellValue('E4','#Item')
            ->setCellValue('F4','Total')
            ->setCellValue('G4','Total Web1')             
            ;
            
            $i = 5;
            foreach($cOrders as $o){
            	$cOrderSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,$o->getId())
            	->setCellValue('C'.$i,$o->getOrderno())
            	->setCellValue('D'.$i,$o->getAdmin())
            	->setCellValue('E'.$i,$o->getItems())             
            	->setCellValue('F'.$i,number_format($o->getTotal(),2,".",','))
            	->setCellValue('G'.$i,number_format($o->getTotal_web1(),2,".",',')) 
            	;
            	 
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($cOrderSheet,3);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(3)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(3)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(3)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(3)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(3)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(3)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(3)->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            }
            
            $objPHPExcel->getSheet(3)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(3)->getStyle('A4:G'.($i -1))->applyFromArray($styleArray);
            
            
            // Cancelled Items
            
            $cancelledItemTable = new CancelledItemsTable($this->getDbAdapter());
            $citems = $cancelledItemTable->fetchAllByShipment($shipment_id);
            
            $cItemsSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $cItemsSheet->setTitle('Cancelled Items');
            
            $cItemsSheet->mergeCells('A2:F2');
            $cItemsSheet->setCellValue('A2','DANH SÁCH CHI TIẾT ITEM BI CANCEL TRONG ĐỢT');
            
            $cItemsSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Order Detail ID')
            ->setCellValue('C4','OrderNo')
            ->setCellValue('D4','Nick')
            ->setCellValue('E4','#Item')
            ->setCellValue('F4','Total')
           
            ;
            
            $i = 5;
            foreach($citems as $o){
            	$cItemsSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,$o->getOrder_detail_id())
            	->setCellValue('C'.$i,$o->getOrderno())
            	->setCellValue('D'.$i,$o->getNick())
            	->setCellValue('E'.$i,$o->getItems())
            	->setCellValue('F'.$i,number_format($o->getTotal(),2,".",','))
            	
            	;
            
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($cItemsSheet,4);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(4)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(4)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(4)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(4)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(4)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(4)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');

            }
            
            $objPHPExcel->getSheet(4)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(4)->getStyle('A4:G'.($i -1))->applyFromArray($styleArray);
            
            
            // Shipping Weight 
            
            $shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
            $sWeight = $shippingWeightTable->getShippingWeightByShippmentId($shipment_id);
            
            $sWeightSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $sWeightSheet->setTitle('Shipping Weight');
            
            $sWeightSheet->mergeCells('A2:F2');
            $sWeightSheet->setCellValue('A2','DANH SÁCH CHI TIẾT SHIPPING WEIGHT TRONG ĐỢT');
            
            $sWeightSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Nick')
            ->setCellValue('C4','Weight')
            ->setCellValue('D4','Price')
            ->setCellValue('E4','Total')
            ->setCellValue('F4','Note')
            
            ;
            
            $i = 5;
            foreach($sWeight as $o){
            	$sWeightSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,$o->getNick())
            	->setCellValue('C'.$i,number_format($o->getWeight(),2,".",',')) 
            	->setCellValue('D'.$i,number_format($o->getPrice(),2,".",','))        	 
            	->setCellValue('E'.$i,number_format($o->getTotal(),2,".",',')) 
            	->setCellValue('F'.$i,$o->getNote())
            	;
            
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($sWeightSheet,5);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(5)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(5)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(5)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(5)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(5)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(5)->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(5)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            }
            
            $objPHPExcel->getSheet(5)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(5)->getStyle('A4:F'.($i -1))->applyFromArray($styleArray);
            
            // Shipping Fee
            
            $shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
            $sFee = $shippingFeeTable->getShippingFeeByShipmentId($shipment_id);
            
            $sFeeSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $sFeeSheet->setTitle('Shipping Fee');
            
            $sFeeSheet->mergeCells('A2:F2');
            $sFeeSheet->setCellValue('A2','CHI TIET CIH PHI SHIP HANG TRONG ĐỢT');
            
            $sFeeSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Date')
            ->setCellValue('C4','Admin')
            ->setCellValue('D4','Weight')            
            ->setCellValue('E4','Total')
            ->setCellValue('F4','Note')
            
            ;
            
            $i = 5;
            foreach($sFee as $o){
            	$sFeeSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,date('m/d/Y',strtotime($o->getDate())))
            	->setCellValue('C'.$i,$o->getAdmin())
            	->setCellValue('D'.$i,number_format($o->getWeight(),2,".",','))            	
            	->setCellValue('E'.$i,number_format($o->getTotal(),2,".",','))
            	->setCellValue('F'.$i,$o->getNote())
            	;
            
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($sFeeSheet,6);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(6)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(6)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(6)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(6)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(6)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(6)->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(6)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            }
            
            $objPHPExcel->getSheet(6)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(6)->getStyle('A4:F'.($i -1))->applyFromArray($styleArray);
            
            
            // Additional Fee
            
            $additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
            $aFee = $additionalFeeTable->getAdditionalFeeByShipmentId($shipment_id);
            
            $aFeeSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $aFeeSheet->setTitle('Additional Fee');
            
            $aFeeSheet->mergeCells('A2:E2');
            $aFeeSheet->setCellValue('A2','CHI TIET CIH PHI PHAT SINH TRONG ĐỢT');
            
            $aFeeSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Date')
            ->setCellValue('C4','Admin')
            ->setCellValue('D4','Total')
            ->setCellValue('E4','Note')
            
            ;
            
            $i = 5;
            foreach($aFee as $o){
            	$aFeeSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,date('m/d/Y',strtotime($o->getDate())))
            	->setCellValue('C'.$i,$o->getAdmin())
            	->setCellValue('D'.$i,number_format($o->getTotal(),2,".",','))
            	->setCellValue('E'.$i,$o->getNote())
            	;
            
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($aFeeSheet,7);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(7)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(7)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(7)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(7)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(7)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(7)->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	//$objPHPExcel->getSheet(6)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            }
            
            $objPHPExcel->getSheet(7)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(7)->getStyle('A4:E'.($i -1))->applyFromArray($styleArray);
            
            
            // Tong Ket
            
            $tongketTable = new TongKetTable($this->getDbAdapter());
            $tongket = $tongketTable->getTongketByShipment($shipment_id);
            
            $tongketSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $tongketSheet->setTitle('Tong Ket');
            
            $tongketSheet->mergeCells("A2:F2");
            $tongketSheet->setCellValue("A2","TỔNG KẾT ĐÃ THANH TOÁN TRONG ĐỢT");
            
            $tongketSheet->setCellValue('A4',"Tổng thu KH:");
            $tongketSheet->setCellValue('B4',number_format($tongket->getTongthu_kh(),2,".",','));
            
            $tongketSheet->setCellValue('A5',"Shipping Fee (Kg):");
            $tongketSheet->setCellValue('B5',number_format($tongket->getShipping_fee_kg(),2,".",','));
            
            $tongketSheet->setCellValue('A6',"Shipping Fee (USD):");
            $tongketSheet->setCellValue('B6',number_format($tongket->getShipping_fee_usd(),2,".",','));
             
            $tongketSheet->setCellValue('A7',"Linh Tinh:");
            $tongketSheet->setCellValue('B7',number_format($tongket->getLinhtinh(),2,".",','));
            
            $tongketSheet->setCellValue('A8',"Tổng Tien Hang:");
            $tongketSheet->setCellValue('B8',number_format($tongket->getTienhang(),2,".",','));
            
            $tongketSheet->setCellValue('A9',"Tổng Shipping (Kg):");
            $tongketSheet->setCellValue('B9',number_format($tongket->getTongshipping_kg(),2,".",','));
            
            
            $tongketSheet->setCellValue('A10',"Tổng Shipping (USD):");
            $tongketSheet->setCellValue('B10',number_format($tongket->getTongshipping_usd(),2,".",','));

            $tongketSheet->setCellValue('A11',"Tổng Chi Phi:");
            $tongketSheet->setCellValue('B11',number_format($tongket->getTongchiphi(),2,".",','));
            
            $tongketSheet->setCellValue('A12',"Tổng GD Khac:");
            $tongketSheet->setCellValue('B12',number_format($tongket->getGiaodichkhac(),2,".",','));
            
            $tongketSheet->setCellValue('A13',"Tổng DUNG:");
            $tongketSheet->setCellValue('B13',number_format($tongket->getTongdung(),2,".",','));
            
            $tongketSheet->setCellValue('A14',"SUM:");
            $tongketSheet->setCellValue('B14',number_format($tongket->getSum(),2,".",','));
            
            // add Shipment Sheet at 0
            $objPHPExcel->addSheet($tongketSheet,8);
            
            // format Tong ket Sheet
            $objPHPExcel->getSheet(8)->getStyle('A2')->getFont()->setBold(true);
            $objPHPExcel->getSheet(8)->getStyle('B4')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B5')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B6')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B7')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B8')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B9')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B10')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B11')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B12')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B13')->getAlignment()->setVertical('center')->setHorizontal('right');
            $objPHPExcel->getSheet(8)->getStyle('B14')->getAlignment()->setVertical('center')->setHorizontal('right');
            
            
            
            // Transactions 
            
            
            $transactionTable = new TransactionTable($this->getDbAdapter());
            
            $trans = $transactionTable->getAllByShipmentId($shipment_id);
            
            $transSheet = new \PHPExcel_Worksheet($objPHPExcel);
            $transSheet->setTitle('Transactions');
            
            $transSheet->mergeCells('A2:K2');
            $transSheet->setCellValue('A2','CHI TIET CAC GIAO DICH TRONG ĐỢT');
            
            $transSheet->setCellValue('A4','STT')
            ->setCellValue('B4','Date')
            ->setCellValue('C4','Admin')
            ->setCellValue('D4','Nick')
            ->setCellValue('E4','OrderNo')
            ->setCellValue('F4','Order Detail Id')
            ->setCellValue('G4','Shipment ID')
            ->setCellValue('H4','+/-')
            ->setCellValue('I4','Amount')
            ->setCellValue('J4','Credit')
            ->setCellValue('K4','Note')
            
            ;
            
            $i = 5;
            foreach($trans as $o){
            	$transSheet->setCellValue('A'.$i,$i-4)
            	->setCellValue('B'.$i,date('m/d/Y H:i:s',strtotime($o->getTrans_date())))
            	->setCellValue('C'.$i,$o->getAdmin())
            	->setCellValue('D'.$i,$o->getNick())
            	->setCellValue('E'.$i,$o->getOrderno())
            	->setCellValue('F'.$i,$o->getOrder_detail_id())
            	->setCellValue('G'.$i,$o->getShipment_id())
            	->setCellValue('H'.$i,$o->getType())
            	->setCellValue('I'.$i,number_format($o->getAmount(),2,".",','))
            	->setCellValue('J'.$i,number_format($o->getCredit(),2,".",','))
            	->setCellValue('K'.$i,$o->getNote())
            	;
            
            
            	$i++;
            }
            
            $objPHPExcel->addSheet($transSheet,9);
            
            //FORMAT ORDER DETAIL SHEET
            $objPHPExcel->getSheet(9)->getStyle('A2')->getFont()->setSize(16)->setBold(true);
            
            for($j = 5;$j<=$i;$j++){
            	$objPHPExcel->getSheet(9)->getStyle('A'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(9)->getStyle('B'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(9)->getStyle('C'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(9)->getStyle('D'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(9)->getStyle('E'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            	$objPHPExcel->getSheet(9)->getStyle('F'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(9)->getStyle('G'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(9)->getStyle('H'.$j)->getAlignment()->setVertical('center')->setHorizontal('center');
            	$objPHPExcel->getSheet(9)->getStyle('I'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(9)->getStyle('J'.$j)->getAlignment()->setVertical('center')->setHorizontal('right');
            	$objPHPExcel->getSheet(9)->getStyle('K'.$j)->getAlignment()->setVertical('center')->setHorizontal('left');
            }
            
            $objPHPExcel->getSheet(9)->getStyle('A2')->getAlignment()->setVertical('center')->setHorizontal('center');
            
            
            $styleArray = array(
            		'borders' => array(
            				'allborders' => array(
            						'style' => PHPExcel_Style_Border::BORDER_THIN,
            						'color' => array('rgb' => '000000'),
            				),
            		),
            );
            $objPHPExcel->getSheet(9)->getStyle('A4:K'.($i -1))->applyFromArray($styleArray);
            
            
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$shipment->getShip_name().'.xlsx"');
            
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
        
       
        $shipments = $shipmentTable->fetchAll(1);
     
        
        $view = new ViewModel(array(
        		'shipments'    => $shipments,        		   
                'shipment_id'   => $shipment_id,     	 
        		'isAjaxRequest' => $request->isXmlHttpRequest()
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function shipmentpaidAction(){
        
        
        $request = $this->getRequest();
        
        $shipment_id  = $request->getPost('shipment_id');
        $tongthukh  = $request->getPost('tongthukh');
        $tongthuweight  = $request->getPost('tongthuweight');
        $tongthushipping  = $request->getPost('tongthushipping');
        $lotamtinh  = $request->getPost('lotamtinh');
        $tongtienhang  = $request->getPost('tongtienhang');
        $tongshippingweigh  = $request->getPost('tongshippingweigh');
        $tongshipping  = $request->getPost('tongshipping');
        $tongchiphi  = $request->getPost('tongchiphi');
        $tonggiaodichkhac  = $request->getPost('tonggiaodichkhac');
        $tongdung  = $request->getPost('tongdung');
        $sum  = $request->getPost('sum');
        
        $admin = $this->UserAuthPlugin()->getNick();
        
        $tongketTable = new TongKetTable($this->getDbAdapter());
        
        
        $tk = new TongKet();
        
        $tk->setShipment_id($shipment_id);
        $tk->setTongthu_kh($tongthukh);
        $tk->setShipping_fee_kg($tongthuweight);
        $tk->setShipping_fee_usd($tongthushipping);
        $tk->setLinhtinh($lotamtinh);
        $tk->setTienhang($tongtienhang);
        $tk->setTongshipping_kg($tongshippingweigh);
        $tk->setTongshipping_usd($tongshipping);
        $tk->setTongchiphi($tongchiphi);
        $tk->setGiaodichkhac($tonggiaodichkhac);
        $tk->setTongdung($tongdung);
        $tk->setSum($sum);
        $tk->setAdmin($admin);
        
        $isok = $tongketTable->saveTongKet($tk);
        if ($isok){
            $shipmentTable = new ShipmentTable($this->getDbAdapter());
            $shipment = $shipmentTable->getShipmentById($shipment_id);
            $shipment->setPaid(1);
            
            $isok = $shipmentTable->saveShipment($shipment);
        }
        
        $result = array(
        	
        );
        return new JsonModel(array(
        	'success'  => $isok
        ));
        
    }
    
    public function deladditionalfeeAction(){
    	$request = $this->getRequest();
    
    	$shipment_id = $request->getPost('shipment_id',null);
    
    	if ($shipment_id && $request->getPost('btnDel',null)){
    		$additionalFee = new AdditionalFeeTable($this->getDbAdapter());
    		$id = $request->getPost('id',null);
    		$additionalFee->deleteAdditionalFee($id);
    	}
    
    	$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    
    	$list = $additionalFeeTable->getAdditionalFeeByShipmentId($shipment_id);
    	 
    	$view = new ViewModel(array(
    			'list'  => $list,
    			'shipment_id'   => $shipment_id ,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    	));
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    public function additionalfeeAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $request->getPost('shipment_id',null);
    
    	if ($shipment_id && $request->getPost('btnSave',null)){
    		$admin = $this->UserAuthPlugin()->getNick();
    		$note = $request->getPost('note');
    		$date = $request->getPost('date');
    		$total = $request->getPost('total');
    		$total = String::formatNumber($total);
    
    		$additionalFee = new AdditionalFee();
    		$additionalFee->setAdmin($admin);
    		$additionalFee->setTotal($total);
    		$additionalFee->setDate($date);
    		$additionalFee->setNote($note);
    		$additionalFee->setShipment_id($shipment_id);
    
    		$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    
    		$additionalFeeTable->insertAdditionalFee($additionalFee);
    
    
    
    	}
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getshippedOrders();
    
    
    	$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    
    	$list = $additionalFeeTable->getAdditionalFeeByShipmentId($shipment_id);
    	 
    	$view = new ViewModel(array(
    			'shipments'    => $shipments,
    			'list'  => $list,
    			'shipment_id'   => $shipment_id ,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    	));
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    public function delshippingfeeAction(){
        $request = $this->getRequest();
        
        $shipment_id = $request->getPost('shipment_id',null);
        
        if ($shipment_id && $request->getPost('btnDel',null)){
            $shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
            $id = $request->getPost('id',null);
            $shippingFeeTable->deleteShippingFee($id);
        }
        
        $shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
        
        $list = $shippingFeeTable->getShippingFeeByShipmentId($shipment_id);
         
        $view = new ViewModel(array(        		 
        		'list'  => $list,
        		'shipment_id'   => $shipment_id ,
        		'isAjaxRequest' => $request->isXmlHttpRequest()
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    public function expressshippingfeeAction(){
        $request = $this->getRequest();
        
        $shipment_id = $request->getPost('shipment_id',null);
     
    
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        
        $shipments = $shipmentTable->fetchAll(1);
        
        $shipment = $shipmentTable->getShipmentById($shipment_id);
        
        $admin = $this->UserAuthPlugin()->getNick();
        $expressShippingFeetable = new ExpressShippingFeeTable($this->getDbAdapter());
       
        $nicks = array();
        $list = array();
        $lastInserId=-1;
        if ($request->isPost() && $shipment_id){
            
           
            $nicks = $expressShippingFeetable->getNicks($shipment_id);
            
            if ($request->getPost("btnSave") && $shipment_id && $request->isXmlHttpRequest()){
                $nick = $request->getPost('nick');
                $fee =String::formatNumber($request->getPost("fee"));
                $note = $request->getPost('note');
            
                $rateTable = new XRatesTable($this->getDbAdapter());
                $rate = $rateTable->getCurrentRate();
                $xrate = $rate->getRate();
                $usd = round($fee/$xrate,2);
                
                $expresshippingfee = new ExpressShippingFee();
                $expressShippingFeetable = new ExpressShippingFeeTable($this->getDbAdapter());
                $expresshippingfee->setNick($nick);
                $expresshippingfee->setFee($fee);
                $expresshippingfee->setUsd($usd);
                $expresshippingfee->setXrate($xrate);
                $expresshippingfee->setNote($note);
                $expresshippingfee->setAdmin($admin);
                $expresshippingfee->setShipment_id($shipment_id);
                
                $order_detail_ids = $request->getPost('order_detail_ids');
                $cancellItemTable = new CancelledItemsTable($this->getDbAdapter());
                $customerTable = new CustomerTable($this->getDbAdapter());
                $balanceTable = new BalanceTable($this->getDbAdapter());
                $orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
                $transactionTable = new TransactionTable($this->getDbAdapter());
                
                $total_web1 = $request->getPost('total_web1',0);
                
                $total_web1 = String::formatNumber($total_web1);
                
                $total_final = $request->getPost('total_final',0);
                $total_final = String::formatNumber($total_final);
                
               
                 
                  /// aaaaa
                  
                
                     
                 
                        $customer = $customerTable->getUserByNick($nick);
                
                        $credit = - $usd; // tru ra 
                        // update balance
                        $balanceTable->updateBalance($customer->getBalance_id(), $credit);
                        $transaction = new Transaction();
                
                        $transaction->setAdmin($admin);
                        $transaction->setBalance_id($customer->getBalance_id());
                        $transaction->setAmount($credit);
                
                        $balance = $balanceTable->getBalanceById($customer->getBalance_id());
                
                        $transaction->setCredit($balance->getCredit());
                        $transaction->setDate(date("Y-m-d"));
                        $transaction->setShipment_id($shipment_id);
                        $transaction->setType($this::minus);
                        $transaction->setCheck(0);
                        $note =  'Phí CPN trong nước, đợt "'. $shipment->getShip_name(). '"; Phí '. $fee . "VND, tỷ giá: ". $xrate.'; Note: "' . $note . '"';
                        
                
                        $transaction->setNote($note);
                        // update transaction
                        $transactionTable->saveTransaction($transaction);
                                 
                        $lastInserId =  $expressShippingFeetable->saveExpressShippingFee($expresshippingfee);
            }
            
            $list = $expressShippingFeetable->getAll($shipment_id);
        }
         
      
        $view = new ViewModel(array(
            'shipments'    => $shipments,
            'shipment'  => $shipment, 
            'nicks' => $nicks,
            'list'  => $list,
            'lastInertedID'    => $lastInserId, 
            'isAjaxRequest' => $request->isXmlHttpRequest()
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function shippingfeeAction(){
        
        $request = $this->getRequest();
        
        $shipment_id = $request->getPost('shipment_id',null);
        
        if ($shipment_id && $request->getPost('btnSave',null)){
            $admin = $this->UserAuthPlugin()->getNick();
            $note = $request->getPost('note');
            $date = $request->getPost('date');
            $weight = $request->getPost('weight');
            $total = $request->getPost('total');
            $total = String::formatNumber($total);
            
            $shippingFee = new ShippingFee();
            $shippingFee->setAdmin($admin);
            $shippingFee->setTotal($total);
            $shippingFee->setWeight($weight);
            $shippingFee->setDate($date);
            $shippingFee->setNote($note);
            $shippingFee->setShipment_id($shipment_id);
                        
            $shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
            
            $shippingFeeTable->insertShippingFee($shippingFee);
            
            
            
        }
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->getshippedOrders();
        
        
        $shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
        
        $list = $shippingFeeTable->getShippingFeeByShipmentId($shipment_id);
         
        $view = new ViewModel(array(
        		'shipments'    => $shipments,
        		'list'  => $list,
        		'shipment_id'   => $shipment_id ,
        		'isAjaxRequest' => $request->isXmlHttpRequest()
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    public function viewsummarizeAction(){
    	$request = $this->getRequest();
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getshippedOrders();
     
    	$cancelledItemTable = new CancelledItemsTable($this->getDbAdapter());
    	$shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
    
    	$adminOrderTable = new AdminOrderTable($this->getDbAdapter());
    	$cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
    	
    	$shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
    	$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    	
    	$transactionTable = new TransactionTable($this->getDbAdapter());
    	
    	$list = array();
    
    	foreach($shipments as $shipment){
    		$item = new Summarize();
    		$item->setShipment_id($shipment->getId());
    		$item->setShipment_name($shipment->getShip_name());
    		$item->setPaid($shipment->getPaid());

    		// tong thu kh
    		$tong_order = $shipmentTable->getTongThuOrdersDetails($shipment->getId());
    		$tong_cancel = $cancelledItemTable->getTotalByShipmentId($shipment->getId());    		
    		$tongthukh = $tong_order - $tong_cancel;
    		
    		// tong thu shipping fee
    		$tongthushippingFee = $shippingWeightTable->getTotalShippingWeightByShipmentId($shipment->getId());
    		$tongthushippingweight = $shippingWeightTable->getTotaWeightByShipmentId($shipment->getId());
    		
    		// lo tam tinh
    		$nick_linhtinh  = 'linhtinh';
    		$nick_double = 'double';
    		$lotamtinh = $shipmentTable->getLoTamTinh($shipment->getId(),$nick_linhtinh ) +
    		             $shipmentTable->getLoTamTinh($shipment->getId(),$nick_double );
    		
    		// tong tien hang
    		
    		$tongthuOrder =  $adminOrderTable->getTotalFinalByShipmentID($shipment->getId());
    		$tong_cancel_order = $cancelledOrderTable->getTotalFinalByShipmentId($shipment->getId());
    		
    		$tongtienhang = $tongthuOrder - $tong_cancel_order;
    		
    		
    		// tong chi phi shipping
    		
    		$tongchiphishipping =  $shippingFeeTable->getTotalByShipmentId($shipment->getId());
    		$tongchiphishippingweight = $shippingFeeTable->getTotalWeightByShipmentId($shipment->getId());
    		  
    		//  tong chi phi khac
    		
    		$tongchiphi = $additionalFeeTable->getTotalByShipmentId($shipment->getId());
    		
    		// chi phi cua DUNG
    		
    		$total_web1 = $adminOrderTable->getTotalWeb1ByShipmentID($shipment->getId());
    		$total_itemcancel = $cancelledItemTable->getTotalByShipmentId($shipment->getId());
    		$total_ordercancel  = $cancelledOrderTable->getTotalByShipmentId($shipment->getId());
    		
    		//$totalShippingweight = self::dung_shipping * $shippingWeightTable->getTotalShippingWeightByShipmentId($shipment->getId());
    		$totalShippingweight = self::dung_shipping * $shipment->getWeight();
    		$service = ($total_web1 - $total_itemcancel )*self::dung_service;
    		 
    		$dungtotal = $service + $totalShippingweight;
    		
    		// Giao dich khac
    		
    		// tien cong chi cho khach hang
    		// giao dich khac co the nam trong shipment hoac nam trong orderno
    		$tonggdkhac_shipment = $transactionTable->getTotalByShipment($shipment->getId(),'+');
    		$tonggdkhac_orders    = $transactionTable->getTotalByOrderInShipment($shipment->getId(),'+');
    		$tonggiaodichkhac_chi = $tonggdkhac_shipment  + $tonggdkhac_orders;
    		
    		// tien tru thu khach hang
    		$tonggdkhac_shipment_thu = $transactionTable->getTotalByShipment($shipment->getId(),'-');
    		$tonggdkhac_orders_thu    = $transactionTable->getTotalByOrderInShipment($shipment->getId(),'-');
    		$tonggiaodichkhac_thu = $tonggdkhac_shipment_thu  + $tonggdkhac_orders_thu;
    		
    		
    		// giao dich khac con lai bang tien thu (tru kh) - tien tra lai cho kh (cong vao)
    		// tong gd khac am khi thu lon hon chi (de thong ke theo cong thuc tru ra giao dich khac)
    		$tonggiaodichkhac =  -($tonggiaodichkhac_thu -  $tonggiaodichkhac_chi);
    		
    		 
    		
    		$item->setTongthukh($tongthukh);          // 1
    		$item->setTongthushipping($tongthushippingFee); // 2
    		$item->setTongthuweight($tongthushippingweight);
    		$item->setLotamtinh($lotamtinh);          // 3
    		$item->setTongtienhang($tongtienhang);    // 4
    		
    		$item->setTongshipping($tongchiphishipping);     // 5
    		$item->setTongshippingweigh($tongchiphishippingweight);
    		
    	    $item->setTongchiphi($tongchiphi);         // 6

    	    $item->setTonggiaodichkhac($tonggiaodichkhac);             // 7	  
    	    $item->setTongdung($dungtotal);            //8
    	    
    		$item->setTotal($tongthukh + $tongthushippingFee - $lotamtinh - $tongtienhang - $tongchiphishipping - $tongchiphi -  $tonggiaodichkhac - $dungtotal);
    	    
    		$list[] = $item;
    	}
    	 
    	 
     
    	 
    	
    
    	// paging
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    
    
    
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    /*
     * Xem tong chi phi
    *   */
    
    public function viewadditionalfeeAction(){
    	$request = $this->getRequest();
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getshippedOrders();
    	 
    	$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    	 
    
    
    	$list = array();
    
    	foreach($shipments as $shipment){
    		$item = new ViewAdditionalFee();
    		$item->setShipment_id($shipment->getId());
    		$item->setShipment_name($shipment->getShip_name());
    		$total =  $additionalFeeTable->getTotalByShipmentId($shipment->getId());
    
    		$item->setTotal($total);
    		 
    		$list[] = $item;
    	}
    
    	// paging
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    
    
    
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /*
     * Xem tong shipping
     *   */
    
    public function viewshippingfeeAction(){
    	$request = $this->getRequest();
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getshippedOrders();
    	
    	$shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
    	
        
    
    	$list = array();
    
    	foreach($shipments as $shipment){
    		$item = new ViewShippingFee();
    		$item->setShipment_id($shipment->getId());
    		$item->setShipment_name($shipment->getShip_name());
    		$total =  $shippingFeeTable->getTotalByShipmentId($shipment->getId());
    
    		$item->setTotal($total);
    		 
    		$list[] = $item;
    	}
    	 
    	// paging
    	 
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    	 
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    	 
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
    	 
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    	 
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	 
    	 
    	 
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    			 
    	));
    	 
    	 
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    /*
     * Tổng tien hang  = sum(order) - sum(cancel) */
    
    public function tongtienhangAction(){
        $request = $this->getRequest();
        
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getshippedOrders();
    	$adminOrderTable = new AdminOrderTable($this->getDbAdapter());
        $cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
        
        
    	$list = array();
    	 
    	foreach($shipments as $shipment){
    		$item = new TongThuKH();
    		$item->setShipment_id($shipment->getId());
    		$item->setShipment_name($shipment->getShip_name());
    		$total =  $adminOrderTable->getTotalFinalByShipmentID($shipment->getId());
            $cancel = $cancelledOrderTable->getTotalFinalByShipmentId($shipment->getId());
            
            
    		$item->setTotal($total);    		 
    	    $item->setCancel($cancel);
    	    $item->setTotal_final($total-$cancel);
    
    		$list[] = $item;
    	}
    	
    	// paging
    	
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    	
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    	 
    	
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
    	
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    	
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	
    	
    	
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    	
    	));
    	
    	
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    	 
    }
    
    /**
     * Xem  chi tiet tong ket DUNG
     *
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
     */
    public function chitiettongketdungAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	
    	$cancelledOrderTable = new CancelledItemsTable($this->getDbAdapter());
    	/* 
    	$total_web1 = $adminOrderTable->getTotalWeb1ByShipmentID($shipment->getId());
    	$total_itemcancel = $cancelledItemTable->getTotalByShipmentId($shipment->getId());
    	$total_ordercancel  = $cancelledOrderTable->getTotalByShipmentId($shipment->getId());
    	
    	//$totalShippingweight = self::dung_shipping * $shippingWeightTable->getTotalShippingWeightByShipmentId($shipment->getId());
    	$totalShippingweight = self::dung_shipping * $shipment->getWeight();
    	$service = ($total_web1 - $total_itemcancel )*self::dung_service;
    	 
    	$dungtotal = $service + $totalShippingweight;
    	 */
    	
    	$orders = $shipmentOrderTable->getShipmentOrder($shipment_id);
    	 
    	$list = array();
    	
    	foreach($orders as $order){
    	    $item = new TongKetDung();
    	    
    	    $o = $orderTable->getOrderByNo($order->getOrderno());
    	    $cancel = $cancelledOrderTable->getTotalByOrderno($order->getOrderno());
    	    $service = ($o->getTotal_web1() - $cancel )*self::dung_service;
    	    $totalShippingweight = self::dung_shipping * $shipment->getWeight();
    	    $dungtotal = $service + $totalShippingweight;
    	    
    	    
    	    $item->setShippingweight($shipment->getWeight());
    	    $item->setOrderno($order->getOrderno());
    	    $item->setTotal_web1($o->getTotal_web1());
    	    $item->setTotalItemCancel($cancel);
    	    $item->setService($service);
    	    $item->setShipping($totalShippingweight);
    	    $item->setTotal($dungtotal);
    	    
    	    $list[]  = $item;
    	    
    	}
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    /**
     * Xem  chi tiet giao dich khac theo dot
     *
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
     */
    public function chitietgiaodichkhacAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	$transactionTable = new TransactionTable($this->getDbAdapter());
    	$trans = $transactionTable->getTransactionsByShipmentId($shipment_id);
        
    	$list = array();
    	
    	foreach($trans as $tran){
    	    $item = new GiaoDichKhac();
    	    $item->setAdmin($tran->getAdmin());
    	    $item->setNick($tran->getNick());
    	    $item->setDate($tran->getDate());
    	    $item->setNote($tran->getNote());
    	    $item->setTotal($tran->getAmount());
    	    $item->setType($tran->getType());
    	    $item->setOrderno($tran->getOrderno());
    	    
    	    $list[] = $item;
    	}
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    
    /**
     * Xem  chi tiet tong chi phi theo dot
     * 
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
     */
    public function chitiettongchiphiAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    	 
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	$shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());

    	$additionalFeeTable = new AdditionalFeeTable($this->getDbAdapter());
    	
    	$list = $additionalFeeTable->getAdditionalFeeByShipmentId($shipment_id);
    
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    /*
     * Xem thong tin chi tiet tong  shipping fee theo dot (admin nhap)
    *
    */
    
    public function chitiettongshippingfeeAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	$shippingFeeTable = new ShippingFeeTable($this->getDbAdapter());
    	
    	$list = $shippingFeeTable->getShippingFeeByShipmentId($shipment_id);
    	 
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /*
     * Xem thong tin chi tiet shipping fee theo dot
    *
    */
    
    public function chitietlinhtinhAction(){
    
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	$nick = array('linhtinh','double');
    	
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	
    	$list = $shipmentTable->getListLoTamTinh($shipment_id, $nick);
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /*
     * Xem thong tin chi tiet shipping fee theo dot
     * 
     */
    
    public function shippingfeedetailAction(){
    
    	$request = $this->getRequest();
    	 
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	 
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    	 
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    	 
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	$shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
    	
    	$shippingweight = $shippingWeightTable->getShippingWeightByShippmentId($shipment_id);
    	 
    	$list = array();
    	 
    	foreach($shippingweight as $i){    		 
    			
    		$item = new ShippingFeeDetail();
    		$item->setNick($i->getNick());
    		$item->setPrice($i->getPrice());
    		$item->setWeight($i->getWeight());
    		$item->setTotal($i->getTotal());
    		$item->setNote($i->getNote());
    			
    		$list[] = $item;
    	}
    
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    
    /*
     * Xem Tổng tien hang theo dot = sum(order) - sum(cancel) */
    
    public function chitiettongtienhangAction(){
    
    	$request = $this->getRequest();
    	 
    	$shipment_id = $this->params()->fromRoute('id');
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	 
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    	 
    	if (!$shipment_id == $shipment->getId()){
    		return $this->redirect()->toRoute('home');
    	}
    	
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    		return $this->redirect()->toRoute('home');
    	}
    	 
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	$cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
    	
    	$orders = $shipmentOrderTable->getShipmentOrder($shipment_id);
    	 
    	$list = array();
    	 
    	foreach($orders as $order){
    		//$total = $orderDetailTable->getTotalFinalByOrderno($order->getOrderno());
    		//$cancel = $cancelledItemTable->getTotalByOrderno($order->getOrderno());
    		$orderItem = $orderTable->getOrderByNo($order->getOrderno());
    		$total = $orderItem->getTotal_final();
    		$cancel = $cancelledOrderTable->getTotalByOrderNo($order->getOrderno());
    			
    		$item = new ChiTietTongTienHang();
    		$item->setOrderno($order->getOrderno());
    		$item->setTotal($total);
    		$item->setCancel($cancel);
    		$item->setTotal_final($total-$cancel);
    			
    		$list[] = $item;
    	}
    
    
    	$view =  new ViewModel(array(
    			'list' => $list,
    			'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /*
     * Xem Tổng thu của kh theo dot = sum(order details) - sum(cancel) */
    
    public function chitiettongthukhAction(){
    
    	$request = $this->getRequest();
    	
    	$shipment_id = $this->params()->fromRoute('id');
       	
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    	
    	if (!$shipment_id == $shipment->getId()){
    	    return $this->redirect()->toRoute('home');
    	}
    	
    	if ($this->UserAuthPlugin()->getUserGroup() == 'group3' && $shipment_id <=23){
    	    return $this->redirect()->toRoute('home');
    	}
    	
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    	$cancelledItemTable = new CancelledItemsTable($this->getDbAdapter());
    	
    	$orders = $shipmentOrderTable->getShipmentOrder($shipment_id);
    	
    	$list = array();
    	
    	foreach($orders as $order){
    	    $total = $orderDetailTable->getTotalFinalByOrderno($order->getOrderno());
    	    $cancel = $cancelledItemTable->getTotalByOrderno($order->getOrderno());
    	    
    	    $item = new ChiTietTongThuKH();
    	    $item->setOrderno($order->getOrderno());
    	    $item->setTotal($total);
    	    $item->setCancel($cancel);
    	    $item->setTotal_final($total-$cancel);
    	    
    	    $list[] = $item;
    	}
    	 
    	 
    	$view =  new ViewModel(array(
    			'list' => $list,
    	        'shipment' => $shipment,
    			'isAjaxRequest' => $request->isXmlHttpRequest()
    			 
    	));
    	 
    	 
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    
    }
    
    /* 
     * Tổng thu của kh = sum(order details) - sum(cancel) */
    
    public function tongthukhAction(){
        
        $request = $this->getRequest();
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->getshippedOrders();
        
        $cancelledItemTable = new CancelledItemsTable($this->getDbAdapter());
        $shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
        
        $list = array();
        $nick_linhtinh = 'linhtinh';
        $nick_double = 'double';
        foreach($shipments as $shipment){
            $item = new TongThuKH();
            $item->setShipment_id($shipment->getId());
            $item->setShipment_name($shipment->getShip_name());
            $total = $shipmentTable->getTongThuOrdersDetails($shipment->getId());
            $cancel = $cancelledItemTable->getTotalByShipmentId($shipment->getId());
            $shippingFee = $shippingWeightTable->getTotalShippingWeightByShipmentId($shipment->getId());
            $lotamtinh = $shipmentTable->getLoTamTinh($shipment->getId(),$nick_linhtinh )+ $shipmentTable->getLoTamTinh($shipment->getId(),$nick_double );
            
            
            $item->setTotal($total);
            $item->setCancel($cancel);
            $item->setShipping_fee($shippingFee);
            $item->setTotal_final($total-$cancel);
            $item->setLotamtinh($lotamtinh);
            
            $list[] = $item;
        }
        
        
        // paging
         
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
         
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
         
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
         
         
         
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        		 
        ));
         
         
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
        
    }
    
   
    
    public function tongketdungAction(){
        
        $request = $this->getRequest();
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->getshippedOrders();
        
        $adminOrderTable = new AdminOrderTable($this->getDbAdapter());
        $cancelledItemTable = new CancelledItemsTable($this->getDbAdapter());
        $cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
        $shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
        
                
        $list = array();
        
        foreach($shipments as $shipment){
            $item = new TongKetDung();
            $item->setShipment_id($shipment->getId());
            $item->setShipment_name($shipment->getShip_name());
            
            $total_web1 = $adminOrderTable->getTotalWeb1ByShipmentID($shipment->getId());
            $total_itemcancel = $cancelledItemTable->getTotalByShipmentId($shipment->getId());
            $total_ordercancel  = $cancelledOrderTable->getTotalByShipmentId($shipment->getId());
            
            
            
            $item->setTotal_web1($total_web1);
            $item->setTotalItemCancel($total_itemcancel);
            $item->setTotalOrderCancel($total_ordercancel);
           
            
            //$totalShippingweight = self::dung_shipping * $shippingWeightTable->getTotalShippingWeightByShipmentId($shipment->getId());
            $totalShippingweight = self::dung_shipping * $shipment->getWeight();
            $service = ($total_web1 - $total_itemcancel )*self::dung_service;
            
            $total = $service + $totalShippingweight;
            $item->setShippingweight($shipment->getWeight());
            $item->setService($service);
            $item->setShipping($totalShippingweight);
            $item->setTotal($total);
            
            $list[] = $item;
        }
        
        
        // paging
         
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
         
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
         
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($list));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
         
         
         
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        		 
        ));
         
         
        $view->setTerminal($request->isXmlHttpRequest());
        return $view; 
        
    }
    
    public function cancelorderAction(){
        
        $request = $this->getRequest();
        
        $orderTable = new AdminOrderTable($this->getDbAdapter());
        $orders  = $orderTable->getOrderNotShipped();
        
        $orderno = $request->getPost('orders');
        
        $orderTable = new AdminOrderTable($this->getDbAdapter());
        $order  = $orderTable->getOrderByNo($orderno);
        
        $orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
       
         
        if ($request->isXmlHttpRequest()){
           
            
            $order_detail_ids = $request->getPost('order_detail_ids');
            $cancellItemTable = new CancelledItemsTable($this->getDbAdapter());            
            $customerTable = new CustomerTable($this->getDbAdapter());
            $balanceTable = new BalanceTable($this->getDbAdapter());
            $orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
            $transactionTable = new TransactionTable($this->getDbAdapter());
            
            $total_web1 = $request->getPost('total_web1',0);
            
            $total_web1 = String::formatNumber($total_web1);
            
            $total_final = $request->getPost('total_final',0);
            $total_final = String::formatNumber($total_final);
            
            foreach($order_detail_ids as $id){
                
                $orderDetail = $orderDetailTable->getOrderById($id);
                
                $detail_note = $request->getPost($id.'_note'). PHP_EOL .$orderDetail->getNote();
                
                $cancelledItem = new CancelledItems();
                
                $items = $request->getPost($id.'_totalItems');
                $update = $request->getPost($id.'_totalUpdate');
                $nick = $request->getPost($id.'_nick');
                
                if ($items > 0 && $update>0){
                    $cancelledItem->setItems($items);
                    $cancelledItem->setTotal($update);
                    
                    $cancelledItem->setNick($nick);
                    
                    $cancelledItem->setOrder_detail_id($id);
                    $cancelledItem->setShipment_id('');
                    
                    // update cancelled items
                    $cancellItemTable->saveItem($cancelledItem);
                    
                    $customer = $customerTable->getUserByNick($nick);
                    
                    $credit = $cancelledItem->getTotal();
                    // update balance
                    $balanceTable->updateBalance($customer->getBalance_id(), $credit);
                    $transaction = new Transaction();
                    
                    $transaction->setAdmin($this->UserAuthPlugin()->getNick());
                    $transaction->setBalance_id($customer->getBalance_id());
                    $transaction->setAmount($credit);
                    
                    $balance = $balanceTable->getBalanceById($customer->getBalance_id());
                    
                    $transaction->setCredit($balance->getCredit());
                    $transaction->setDate(date("Y-m-d"));
                    $transaction->setOrder_detail_id($id);
                    $transaction->setType($this::add);
                    $transaction->setCheck(0);
                    $note =  'Update balance: Cancel '. $cancelledItem->getItems() .' item(s) '. "<br/>". $detail_note;
                     
                    
                    // cancelled item
                    if ($cancelledItem->getItems() == $orderDetail->getItems()){
                    	$note =  'Update Order Store '. $order->getStore_name(). " date " . date("d-m-Y",strtotime($order->getOrderdate())). ' cancelled.';
                    	$orderDetail->setStatus($this::cancelled);
                    
                    }else{
                    	$orderDetail->setStatus($this::partiallycancelled);
                    }
                    
                    $orderDetail->setNote($detail_note);
                    $orderDetail->setChecked(1);
                    $orderDetail->setFinish(1);
                    $orderDetailTable->updateOrderDetails($orderDetail);
                    
                    $transaction->setNote($note);
                    // update transaction
                    $transactionTable->saveTransaction($transaction);
                    
                    //update order
                    // set new Total_web1 
                   
                }
                
                
            }// end for
            
            $order->setTotal_web1($total_web1);
            $order->setTotal_final($total_final);
            
            $orderTable->updateOrder($order);
            
        }// XmlRequest
        
        $orderDetails = $orderDetailsTable->getOrderDetailsByOrderNoNotCancel($order->getOrderno());
        $orderItemCancelled = $orderDetailsTable->getOrderDetailsByOrderNoItemCancelled($order->getOrderno());
        
       $view = new ViewModel(array(
            'orders'		=> $orders,
            'orderno'       => $orderno,
            'order'         => $order,
            'details'	=> $orderDetails,
            'itemcancelled' =>$orderItemCancelled,
            'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
       
       $view->setTerminal($request->isXmlHttpRequest());
       return $view;
    }
    
    public function viewanswerAction(){
    	$request = $this->getRequest();
    	$id = $this->params()->fromRoute('id',null);
    	$questionTable = new QuestionTable($this->getDbAdapter());
    	$q = $questionTable->getQuestionById($id);
        
    
    	if (!$q){
    		return $this->redirect()->toRoute('admin',array('action'=>'question'));
    	}
    	
    	
    
    	$answerTable = new AnswerTable($this->getDbAdapter());
    	 
    
    	if ( $request->isXmlHttpRequest() &&  $request->isPost() && $request->getPost('btnSubmit')){
    		$question_id = $request->getPost('question_id');
    		
    		
    		$content = $request->getPost('content');
    		$content = preg_replace("/[\n]/","<br/>",$content);
    		$content = String::removeBBCode($content);
    		$content = String::makelink($content);
    
    		$answer  = new Answer();
    		$nick = $this->UserAuthPlugin()->getNick();
    		$answer->setContent($content);
    		$answer->setNick($nick);
    		$answer->setQuestion_id($question_id);
    		$answerTable->save($answer);
    
    		// answered by admin
    		if ($nick != $q->getNick()){
    			$q->setRead(0);
    			$q->setAnswer(1);
    			$questionTable->save($q);
    		}else{
    			$q->setRead(1);
    			$q->setAnswer(0);
    			$questionTable->save($q);
    		}
    
    	}
    
    
    	$answers = $answerTable->getAnswerByQuestionId($id);
    
    	$view = new ViewModel(array(
    			'q' => $q,
    			'a' => $answers,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    	));
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function delquestionAction(){
    	$request = $this->getRequest();
    
    	if (!$request->isXmlHttpRequest()){
    		return $this->redirect()->toRoute('admin');
    	}
    
    	$id = $this->params()->fromRoute('id',null);
    
    	$questionTable = new QuestionTable($this->getDbAdapter());
    
    	return new JsonModel(array(
    			'success'  => $questionTable->deleteQuestion($id)
    	));
    
    }
    
    public function questionAction(){
    
    	$request = $this->getRequest();
    	$questionTable = new QuestionTable($this->getDbAdapter());
    	if ($request->isXmlHttpRequest() && $request->isPost() && $request->getPost('btnSubmit')){
    
    		$content = $request->getPost('content');
    		
    		$content = preg_replace("/[\n]/","<br/>",$content);
    		$content = String::removeBBCode($content);
    		$content = String::makelink($content);
    		$subject = $request->getPost('subject');
    
    		$question = new Question();
    
    		$question->setSubject($subject);
    		$question->setContent($content);
    		$question->setAnswer(0);
    		$question->setRead(1);
    		$question->setDatecreated(date("Y-m-d H:i:s"));
    		$question->setNick($this->UserAuthPlugin()->getNick());
    
    		$questionTable->save($question);
    	}
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 30;
    	}
    
        
        $totalUnreply = $questionTable->getTotalUnreply();
        $totalReply = $questionTable->getTotalReply();
        $totalQ = $questionTable->getTotalQuestion();
        
        
        // default is unreply
        $type = $request->getPost('type','unreply');
    
    	//$questions = $questionTable->getAll();
    
        
            
    	$paginator = $questionTable->getAll(null,$type);
    	
    	//$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($questions));
    	
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    
    
    	$view = new ViewModel(array(
    			'paginator' => $paginator,
    	        'type' => $type,
    	      
    	       'totalReply'    => $totalReply,
    	       'totalUnreply'  => $totalUnreply,
    	       'totalQuestion' => $totalQ,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    	));
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    public function finalizeorderajaxAction(){
        $request = $this->getRequest();
        $orderno = $this->params()->fromRoute('id');
        if ($request->isXmlHttpRequest()){
            $orderTable = new AdminOrderTable($this->getDbAdapter());
            $order = $orderTable->getOrderByNo($orderno);
            $order->setFinalized(1);
            $view = new JsonModel(array(
            	'success'  => $orderTable->updateOrder($order)
            ));
            return $view;
        }else{
            return $this->redirect()->toRoute('admin');
        }
        
    }
    
    
    public function updatecreditcardAction(){
        
        $request = $this->getRequest();
        $success = 0;
        if ($request->isXmlHttpRequest()){
            $creditcard = $request->getPost('creditcard');
            $holder = $request->getPost('holder');
            $orderno = $request->getPost('orderno');
            $adminorderTable = new AdminOrderTable($this->getDbAdapter());
            $order = $adminorderTable->getOrderByNo($orderno);
            $order->setCreditcard($creditcard);
            $order->setHolder($holder);
            
            $success = $adminorderTable->updateOrder($order);
        }else{
            return $this->redirect()->toRoute('home');
        }
        
        return new JsonModel(array(
        	'success'  => $success
        ));
    }
    
    public function orderfinalizingAction(){
        $request = $this->getRequest();
                
        
        $creditcard = $request->getPost('creditcard');
        
        $orderFinalizingTable = new OrderFinalizingTable($this->getDbAdapter());
        $orders = $orderFinalizingTable->getOrdersByCreditCard($creditcard);
        
        $creditcardTable = new CreditCardTable($this->getDbAdapter());
        $creditcards = $creditcardTable->getAllCreditCard();
        $creditcard = $creditcardTable->getCreditCardById($creditcard);
        
        $view = new ViewModel(array(
        	'orders'   => $orders,
            'creditcards'    => $creditcards,
            'creditcard'   => $creditcard
        ));
        return $view;
        
    }
    
    
   /*  public function orderfinalizingAction(){
    	$request = $this->getRequest();
    
    	$shipment_id = $this->params()->fromRoute('id',null);
    	if (!$shipment_id){
    		$shipment_id = $request->getPost('shipment_id',null);
    	}
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    
    	$shipment = $shipmentTable->getShipmentById($shipment_id);
    
    	if (!($shipment_id && $shipment_id == $shipment->getId())){
    		return $this->redirect()->toRoute('admin',array('action'=>'shipmentfinalizing'));
    	}
    
    	$orderFinalizingTable = new OrderFinalizingTable($this->getDbAdapter());
    	$orders = $orderFinalizingTable->getOrdersByShipmentId($shipment_id);
    
    	$view = new ViewModel(array(
    			'orders'   => $orders
    	));
    	return $view;
    
    } */
    
    public function shipmentfinalizingAction(){
        $request = $this->getRequest();
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->fetchAll(1);
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($shipments));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
        
        
        
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function checkshipmentAction(){
        
        $request = $this->getRequest();
        
        
        $shipment_id = $this->params()->fromRoute('id',null);
        if (!$shipment_id){
            $shipment_id = $request->getPost('shipment_id',null);            
        }
        
       
        
        $orderno = $request->getPost('orderno',null);
        
        $orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
        $shipemtOrderTable = new ShipmentOrderTable($this->getDbAdapter());
        $orderTable = new AdminOrderTable($this->getDbAdapter());
        $cancellItemTable = new CancelledItemsTable($this->getDbAdapter());
        $backorderItemTable = new BackorderedItemsTable($this->getDbAdapter());
        $balanceTable = new BalanceTable($this->getDbAdapter());
        $transactionTable = new TransactionTable($this->getDbAdapter());
        $customerTable = new CustomerTable($this->getDbAdapter());
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipment = $shipmentTable->getShipmentById($shipment_id);
        
       
        
        if (!$shipment_id || $shipment->getChecked()){
            return $this->redirect()->toRoute('admin',array('action' => 'shipped'));
        }
        $unshipped = false;
        $info = "";
        if ($request->isPost() && $orderno != null &&  $request->getPost('btnFinish',null)){   
            
           
            $order_details = (array) $request->getPost('order_detail_id');
           
            $order = $orderTable->getOrderByNo($orderno);
            
            if ($request->getPost('unshipped',null) ){
                
                // remove order from shipemt_orders
                
                $shipemtOrderTable->deleteShipmentOrder($shipment_id, $orderno);
                
                $details = $orderDetailTable->getDetailsByOrderNo($orderno);
                foreach ($details as $item){
                    $item->setStatus($this::bought);
                    $item->setChecked(0);
                    $orderDetailTable->updateOrderDetails($item);
                }
                
                $unshipped = true;
                
            }else{
                 
                // check actions
               
                $total_cancelled = 0;
                $total_backordered = 0;
                
                foreach($order_details as $key => $id){
                
                	$orderDetail = $orderDetailTable->getOrderById($id);
                
                	$action = $request->getPost($id.'_action');
                	$detail_note = $request->getPost($id.'_note'). PHP_EOL . $orderDetail->getNote();
                	$itemNote = $request->getPost($id.'_note');
                	$items = $request->getPost($id.'_totalItems');
                	$update = $request->getPost($id.'_totalUpdate');
                
                	if ($action == 'ok'){
                		 
                		$orderDetail->setStatus($this::arrived);
                		
                		$orderDetail->setNote($detail_note);
                		$orderDetail->setChecked(1);
                		$orderDetail->setFinish(1);
                		$orderDetailTable->updateOrderDetails($orderDetail);
                
                	}
                
                	if ($action == 'cancel'){
                
                	    $total_cancelled += $items;
                	    
                		$cancelledItem = new CancelledItems();
                		$cancelledItem->setItems($items);
                		$cancelledItem->setTotal($update);
                
                
                		$cancelledItem->setNick($orderDetail->getNick());
                		$cancelledItem->setOrder_detail_id($orderDetail->getId());
                		$cancelledItem->setShipment_id($shipment_id);
                
                		// update cancelled items
                		$cancellItemTable->saveItem($cancelledItem);
                
                		$customer = $customerTable->getUserByNick($orderDetail->getNick());
                
                		$credit = $cancelledItem->getTotal();
                		// update balance
                		$balanceTable->updateBalance($customer->getBalance_id(), $credit);
                		$transaction = new Transaction();
                
                		$transaction->setAdmin($this->UserAuthPlugin()->getNick());
                		$transaction->setBalance_id($customer->getBalance_id());
                		$transaction->setAmount($credit);
                
                		$balance = $balanceTable->getBalanceById($customer->getBalance_id());
                
                		$transaction->setCredit($balance->getCredit());
                		$transaction->setDate(date("Y-m-d"));
                		$transaction->setOrder_detail_id($orderDetail->getId());
                		$transaction->setType($this::add);
                		$transaction->setCheck(0);
                		$note =  'Update balance: Cancel '. $cancelledItem->getItems() .' item(s) '. "<br/>". $detail_note;
                		 
                
                		// cancelled item
                		if ($cancelledItem->getItems() == $orderDetail->getItems()){
                			$note =  'Update Order Store '. $order->getStore_name(). " date " . date("d-m-Y",strtotime($order->getOrderdate())). ' cancelled.';
                			$orderDetail->setStatus($this::cancelled);
                
                		}else{
                			$orderDetail->setStatus($this::partiallycancelled);
                		}
                
                		$orderDetail->setNote($detail_note);
                		$orderDetail->setChecked(1);
                		$orderDetail->setFinish(1);
                		$orderDetailTable->updateOrderDetails($orderDetail);
                
                		$transaction->setNote($note);
                		// update transaction
                		$transactionTable->saveTransaction($transaction);
                
                	}
                
                	if ($action == 'backordered'){
                	    
                	    $total_backordered += $items;
                	    
                		$backorderitem = new BackorderedItems();
                		$backorderitem->setItems($items);
                		$backorderitem->setNick($orderDetail->getNick());
                		$backorderitem->setOrder_detail_id($orderDetail->getId());
                		$backorderitem->setShipment_id($shipment_id);
                		$backorderitem->setNote($itemNote);
                		$backorderitem->setTotal($update);
                		// save
                		$backorderItemTable->updateItem($backorderitem);
                
                		// update order details status
                		if ($backorderitem->getItems() == $orderDetail->getItems()){
                			$orderDetail->setStatus($this::backordered);
                		}else{
                			$orderDetail->setStatus($this::partiallybackordered);
                		}
                		 
                		$orderDetail->setNote($detail_note);
                		$orderDetail->setChecked(1);
                		$orderDetail->setFinish(0);
                		$orderDetailTable->updateOrderDetails($orderDetail);
                
                	}	 
                
                }// foreach
                
                if ($total_cancelled > 0){
                	$cancelledOrderTable = new CancelledOrdersTable($this->getDbAdapter());
                	$cancelledOrder = new CancelledOrders();
                	$total = $request->getPost('totalCancel');   
                	$total_web1 =  $request->getPost('totalCancel_web1');  	
                	
                	$cancelledOrder->setOrderno($order->getOrderno());
                	$cancelledOrder->setAdmin($this->UserAuthPlugin()->getNick());
                	$cancelledOrder->setItems($total_cancelled);
                	$cancelledOrder->setTotal($total);
                	$cancelledOrder->setTotal_web1($total_web1);
                	$cancelledOrder->setShipment_id($shipment_id);
                	
                	$cancelledOrderTable->updateItem($cancelledOrder);
                	
                }
                
                if ($total_backordered){
                  
                    $backorderedTable = new BackOrderedTable($this->getDbAdapter());
                    $backordered = new BackOrdered();
                    
                    $backordered->setDate(date('Y-m-d'));
                    $backordered->setItems($total_backordered);
                    $backordered->setOrderno($order->getOrderno());
                    $backordered->setShipment_id($shipment_id);
                    $backordered->setFinish(0);
                    
                    $backorderedTable->updateItem($backordered);  
                }
                
                $order->setChecked(1);
                $orderTable->updateOrder($order);
                
            }// shipped
            
            $info = "Thông tin đã được lưu.";
            $orderno = null;// reset form
        }
        
        
        $orders = $orderTable->getUncheck($shipment_id);
        
        $order = $orderTable->getOrderByNo($orderno);
        
        $shipemtnOrder = $shipemtOrderTable->getShipmentOrderByIds($shipment_id, $orderno);
        $order->shipmentNote = $shipemtnOrder->getNote();
             
        $orderDetails  = $orderDetailTable->getOrderDetailsForChecking($orderno);
        
        $list = array();
        $orderDetailImageTable = new OrderDetailsImageTable($this->getDbAdapter());
        foreach ($orderDetails as $item) {
            $images = $orderDetailImageTable->getOrderDetailImages($item->getId());
            $item->images = $images;
            $list[] = $item;
        }
        
       
        
        $view = new ViewModel(array(
        	'orders'   => $orders,
            'orderno'   => $orderno,
            'info'  => $info,
            'order'     => $order,
            'details'   => $list, 
            'shipment_id'   => $shipment_id,
            'isAjaxRequest' => $request->isXmlHttpRequest()
         ));
         
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    public function shippingweightAction(){
        
        $request = $this->getRequest();
        
        $shipment_id = $request->getPost('shipment_id',null);
        $hidden_shipment_id = $request->getPost('hidden_shipment_id',null);
        if ($hidden_shipment_id && $request->isPost() && $request->getPost('btnSave')){
            $nicks = $request->getPost('nicks');
            $shippingweightTable = new ShippingWeightTable($this->getDbAdapter());
            $customerTable = new CustomerTable($this->getDbAdapter());
            
            foreach($nicks as $nick){
                $shipping_weight = new ShippingWeight();
                $customer = $customerTable->getUserByNick($nick);
                $shipping_weight->setNick($nick);
                $shipping_weight->setShipment_id($hidden_shipment_id);
                $shipping_weight->setNote($request->getPost($nick."_note",null));                
                $weight = $request->getPost($nick.'_weight',0);                 
                $price = $customer->getShipping();
                $total = $weight * $price;
                $shipping_weight->setWeight($weight);
                $shipping_weight->setPrice($price);
                $shipping_weight->setTotal($total);
                $shipping_weight->setDate(date("Y-m-d"));
                $shippingweightTable->saveShippingWeight($shipping_weight);
                
            }
             
        }
        
        if ($hidden_shipment_id && $request->isPost() && $request->getPost('btnFinish')){
        	$nicks = $request->getPost('nicks');
        	$shippingweightTable = new ShippingWeightTable($this->getDbAdapter());
        	$customerTable = new CustomerTable($this->getDbAdapter()); 
        	$balanceTable = new BalanceTable($this->getDbAdapter());      
        	$transactionTable = new TransactionTable($this->getDbAdapter());     
        	$shipmentTable = new ShipmentTable($this->getDbAdapter());   
        	$shipment = $shipmentTable->getShipmentById($hidden_shipment_id);
        	foreach($nicks as $nick){
        		$shipping_weight = new ShippingWeight();
        		$customer = $customerTable->getUserByNick($nick);
        		$shipping_weight->setNick($nick);
        		$shipping_weight->setShipment_id($hidden_shipment_id);
        		$shipping_note = $request->getPost($nick."_note",null);
        		$shipping_weight->setNote($shipping_note);
        		$weight = $request->getPost($nick.'_weight',0);
        		$price = $customer->getShipping();
        		$total = $weight * $price;
        		$shipping_weight->setWeight($weight);
        		$shipping_weight->setPrice($price);
        		$shipping_weight->setTotal($total);
        		$shipping_weight->setDate(date("Y-m-d"));
        		$shippingweightTable->saveShippingWeight($shipping_weight);
        		if ($shipping_weight->getTotal() > 0){
        		    $credit = - $shipping_weight->getTotal();
        		    $balanceTable->updateBalance($customer->getBalance_id(), $credit);
        		    $transaction = new Transaction();
        		    
        		    $transaction->setAdmin($this->UserAuthPlugin()->getNick());
        		    $transaction->setBalance_id($customer->getBalance_id());
        		    $transaction->setAmount($credit);
        		    
        		    $balance = $balanceTable->getBalanceById($customer->getBalance_id());
        		   
        		    
        		    $transaction->setCredit($balance->getCredit());
        		    $transaction->setDate(date("Y-m-d"));
        		    $transaction->setShipment_id($shipment->getId());
        		    $transaction->setType($this::minus);
        		    $transaction->setCheck(0);
        		    $note = $shipment->getShip_name() . ": " . $weight ."kg x ".$price." <br/>".$shipping_note ;        		    
        		    $transaction->setNote($note);
        		    $transactionTable->saveTransaction($transaction);        		    
        		}
        		        
        	}
        	
        	$shipment->setDelivered(1);
        	$shipmentTable->saveShipment($shipment);        	
        	$shippingweightTable->deleteInvalidShippingWeight($shipment->getId());        	
        	return $this->redirect()->toRoute('admin',array('action'=>'shippingweight'));
        	 
        }
        
        
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        $shipments = $shipmentTable->getShipnentcomplete();
      
        
        $shippingWeightTable = new ShippingWeightTable($this->getDbAdapter());
        $list = $shippingWeightTable->getShipmentItemList($shipment_id);
       
        $view = new ViewModel(array(
        	'shipments'    => $shipments,
            'list'  => $list,
            'shipment_id'   => $shipment_id ,
            'isAjaxRequest' => $request->isXmlHttpRequest()        		 
        ));         
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    public function updatebalanceAction(){
        $admin = $this->UserAuthPlugin()->getNick();
        
        $request = $this->getRequest();
        $transactionTable = new TransactionTable($this->getDbAdapter());
        $customerTable = new CustomerTable($this->getDbAdapter());
        if ($request->isXmlHttpRequest() && $request->isPost() && $request->getPost('saveTrans')){
            $nick = $request->getPost('nick');
            $orderno = $request->getPost('orderno',null);
            $shipment_id  = $request->getPost('shipment',null);
            $amount = $request->getPost('usd');
            $note = $request->getPost('note');
            $date = $request->getPost('trans_date');
            $date = date('Y-m-d',strtotime($date));
            $amount = String::formatNumber($amount);
            
            $type = "+";
            if ($amount < 0){
                $type = "-";
            }
            
            $customer = $customerTable->getUserByNick($nick);
            $balanceTable = new BalanceTable($this->getDbAdapter());
            if ($balanceTable->updateBalance($customer->getBalance_id(), $amount)){
                $trans = new Transaction();
                $balance = $balanceTable->getBalanceById($customer->getBalance_id());
                $trans->setAdmin($admin);
                $trans->setBalance_id($customer->getBalance_id());
                $trans->setDate($date);
                $trans->setType($type);
                $trans->setCheck(1); // tinh vao phan tong ket theo dot
                $trans->setAmount(abs($amount));
                $trans->setCredit($balance->getCredit());
                $trans->setNote($note);
                if ($orderno){
                    $trans->setOrderno($orderno);
                }
                if ($shipment_id){
                    $trans->setShipment_id($shipment_id);
                }
                              
                $transactionTable->saveTransaction($trans);
                
                $view = new JsonModel(array(
                    'success'   => 1,
                    'msg'       => "Đã lưu thông tin giao dịch."
                ));
                
                return $view;
            }else{
                $view = new JsonModel(array(
                		'success'   => 0,
                		'msg'       => "Không thể lưu thông tin giao dịch. Kiểm tra lại OrderNo, Nick, ShipmentID"
                ));
                
                return $view;
            }
             
        }
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
         
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $admin_filter = $request->getPost('admin_filter');
        $nick_filter = $request->getPost('nick_filter');
        $date_filter = $request->getPost('date_ftiler');
        
        if ($date_filter){
            $date_filter = date('Y-m-d',strtotime($date_filter));
        }
        
        //$transactions = $transactionTable->getAllAdminTransaction($admin_filter,$nick_filter,$date_filter);
        
        $paginator = $transactionTable->getAllAdminTransactionForPaging($admin_filter,$nick_filter,$date_filter);
        
        
        //$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transactions));
        
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        $adminTable = new AdminTable($this->getDbAdapter());
        $adminOrderTable = new AdminOrderTable($this->getDbAdapter());
        $shipmentTable = new ShipmentTable($this->getDbAdapter());
        
        
        //$adminorders = $adminOrderTable->getOrdersInShipmentNotPaid();
        $adminorders = array();
        $shipments = $shipmentTable->getShipmentNotPaid();
        $admins = $adminTable->fetchAll();
        //$customers =  $customerTable->fetchAll();
         
        $customers = array();
        $adminorders = array();
        $errmsg = "";
        $view = new ViewModel(array(
            'errmsg'    => $errmsg,
            'admins'    => $admins,
            'page' => $page,
            'row'   => $itemsPerPage,
            'paginator' => $paginator,
            'customers' => $customers,
            'adminorders'   => $adminorders,
            'shipments'     => $shipments,
            'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    public function delrequestAction(){
    	$request = $this->getRequest();
    	if ($request->isXmlHttpRequest()){
    		 
    		$errmsg = '';
    		$id = $this->params()->fromRoute('id',0);
    		 
    		$customerOrderTable = new CustomerOrderTable($this->getDbAdapter());
    		$customerOrderTable->deleteOrder($id);
    		 
    		$view = new JsonModel(array(
    				'success'   => 1,
    				'errmsg' => $errmsg,
    		));
    		 
    		return $view;
    	} else{
    		return $this->redirect()->toRoute('home');
    	}
    
    
    }
    
    public function savecustomerrequestAction(){
        
        $request = $this->getRequest();
        
        $orderTable = new CustomerOrderTable($this->getDbAdapter());
        
        $list = array();
        
        if ($request->isPost()){
            //save
            
            $nicks = (array) $request->getPost('nicks');
            $stores = (array) $request->getPost('stores');
            $descriptions = (array) $request->getPost('description');
            $storeTable  = new StoreTable($this->getDbAdapter());
            for($i = 0;$i < count($nicks);$i++){
            	if ($nicks[$i] && $descriptions[$i]){
            		$order = new CustomerOrder();
            		$order->setNick($nicks[$i]);
            		$content = $descriptions[$i]; 
            		//$escape = new Escaper();
            		//$content = $escape->escapeHtml($content);

            		$description = $descriptions[$i];
            		$description = preg_replace("/[\n]/","<br/>",$description);
            		$description = String::removeBBCode($description);            		 
            		$description = String::makelink($description);
            		
            		
            		$order->setDescription($description);
            		$order->setStatus($this::waiting);
            		
            		if ($stores[$i] == -1){
            		    $storeName = String::getDomainName($description);
            		    $store = $storeTable->getStoreByName($storeName);
            		    $order->setStore_id($store->getId());
            		    if ($store->getId() ){
            		        $orderTable->saveOrder($order);
            		    }else{
            		    	$list[$nicks[$i]] = $content;
            		    }
            		}else{
            		    $order->setStore_id($stores[$i]);
            		    $orderTable->saveOrder($order);
            		}
            	}
            }
            
        }
        
        
        $customerTable = new CustomerTable($this->getDbAdapter());
        $customers = $customerTable->fetchAll();
        
        $storeTable = new StoreTable($this->getDbAdapter());
        $stores = $storeTable->fetchAll();
        
        /* echo "page:". $page = (int)$this->params()->fromRoute('page');;
        die; */
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
         
        $orders = $orderTable->getWaitingOrders(null,'waiting');
         
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        $view = new ViewModel(array(
            'customers' => $customers,
            'stores'    => $stores,
            'list'      => $list,
            'paginator' => $paginator,
            'page' => $page,
            'row'   => $itemsPerPage,
            'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
            
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    
    // Administrator are able to change password themselves;
    public function changepasswdAction(){
    	$request = $this->getRequest();
    	if ($request->isXmlHttpRequest()){
    		$nick = $request->getPost('nick');
    		$oldpass = $request->getPost('oldpass');
    		$newpasswd = $request->getPost('passwd');
    		$adminTable = new AdminTable($this->getDbAdapter());
    		$valid = $adminTable->checkPassword($nick, $oldpass);
    		$msg = "";
    		if ($valid){
    			$admin = $adminTable->getUserById($nick);
    			$admin->setPassword($newpasswd);
    			$success = $adminTable->saveUser($admin);
    			$msg = "Đã thay đổi mật khẩu.";
    			
    		}else{
    			$success = 0;
    			$msg = "Mật khẩu không đúng.";
    		}
    		 
    		$view = new JsonModel(array(
    				'success'   => $success,
    				'msg'  => $msg
    		));
    
    		return $view;
    
    	} else{
    
    		$view = new ViewModel(array(
    				 
    		));
    	}
    }
    
     
    public function deltransferAction(){
        $request = $this->getRequest();
        $tid = $this->params()->fromRoute('id',0);
        $transferTable = new TransferTable($this->getDbAdapter());
        
        $transfer = $transferTable->checkTransfer($tid,$this::waiting);
        
        if ($request->isXmlHttpRequest() && $transfer){
                $success = $transferTable->deleteTransfer($tid);
                $view = new JsonModel(array(
                    'success'   => $success,
                    ''
                ));
                return $view;
                
        }else{
    		return $this->redirect()->toRoute('home');
    	}
    }
    
    public function confirmtransferAction(){
    	$request = $this->getRequest();
    	$tid = $this->params()->fromRoute('id',0);
    
    	$transferTable = new TransferTable($this->getDbAdapter());
    
    	$transfer = $transferTable->checkTransfer($tid,$this::waiting);
    	 
    	if ($request->isXmlHttpRequest() && $transfer){
    
    		$success = $transferTable->updateStatus($tid, $this::received);
    
    		if ($success){
    			$balanceTable = new  BalanceTable($this->getDbAdapter());
    			$customerTable = new CustomerTable($this->getDbAdapter());
    			$transactionTable = new TransactionTable($this->getDbAdapter());
    
    			$customer = $customerTable->getUserById($transfer->getNick());
    			 
    			$success = $balanceTable->updateBalance($customer->getBalance_id(),round($transfer->getUsd(),2));
    			if ($success){
    				$transaction = new Transaction();
    				$balance = $balanceTable->getBalanceById($customer->getBalance_id());
    
    				$transaction->setAdmin($this->UserAuthPlugin()->getNick());
    				$transaction->setBalance_id($customer->getBalance_id());
    				$transaction->setAmount(round($transfer->getUsd(),2));
    				$transaction->setCredit(round($balance->getCredit(),2));
    				$transaction->setType($this::add);
    				$transaction->setCheck(0);
    				$transaction->setAmount($transfer->getUsd());
    				$transaction->setTransfer_id($tid);
    				$note = 'Cập nhật CK cho TK "'.$customer->getNick().'" số tiền ' . $transfer->getUsd().' USD';
    
    				$transaction->setNote($note);
    				$transaction->setDate(date('Y-m-d'));
    				$transactionTable->saveTransaction($transaction);
    			}
    
    		}
    
    		return new JsonModel(array(
    				'success' => $success,
    				'errormsg'  => ''
    		));
    
    	}else{
    		return $this->redirect()->toRoute('home');
    	}
    }
    
    
    public function transfermgmAction(){
    
    	$request = $this->getRequest();
    	$transferTable = new TransferTable($this->getDbAdapter());
    	$customerTable = new CustomerTable($this->getDbAdapter());
    	$customers = $customerTable->fetchAll();
    	 
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    	 
    	$nick = $request->getPost('search_nick',null);
    	 
    	$transfers = $transferTable->getConfirmTransfers($this::waiting,$nick);
    	 
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transfers));
    	 
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    	 
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	 
    	$nicks = $transferTable->getNicks('waiting');
    	 
    	 
    	$view = new ViewModel(array(
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'paginator' => $paginator,
    			'customers' => $customers,
    			'nicks' => $nicks,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    
    	));
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    
    public function vieworderAction(){
    	$orderno = $this->params()->fromRoute('id',0);
    	
    	$admin = $this->UserAuthPlugin()->getNick();
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	$order  = $orderTable->getOrderByNo($orderno);
    	 
    	if ($order){
    		$orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
    		$orderDetails = $orderDetailsTable->getOrderDetailsByOrderNo($order->getOrderno());
    		 
    		$success = true;
    		$view = new ViewModel(array(
    				'success'	=> $success,
    				'details'	=> $orderDetails,
    				'order'		=> $order
    				 
    		));
    		 
    		return  $view;
    	}else{
    		return $this->redirect()->toRoute('admin',array('action' => 'order'));
    	}

    	
    	
    }
    
    /**
     * @abstract kiem tra cac orders chua duoc add vao shipment nao 
     * 
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function checkordersAction(){
    	$request = $this->getRequest();
    
        
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    	 
    	 
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	 
    	//$admin = $this->UserAuthPlugin()->getNick();
    	 
    	$week = $request->getPost('week',1);
    	$orders = $orderTable->getOrderNotInShipment($week);
    
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    
    
    
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function orderListAction(){
    	$request = $this->getRequest();
    	 
    	 
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
     
    	$order_search = $request->getPost('orderno',null);
    	
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	
    	//$admin = $this->UserAuthPlugin()->getNick();
    	
    	//get all
    	//$orders = $orderTable->fetchAllForPaging();
    
    	$paginator = $orderTable->fetchAllForPaging(null,null,$order_search);
    	//$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	 
    
    	 
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    	        'order_search' => $order_search,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function deleteshipmentAction(){
    	$request = $this->getRequest();
    
    	$shipment_id = $request->getPost('id',null);
    	if (!$shipment_id){
    		$shipment_id = $this->params()->fromRoute('id',0);    		    	
    	}
    	
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	//$shipment = $shipmentTable->getShipmentById($shipment_id);
    	
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	$shipment_orders = $shipmentOrderTable->getShipmentOrder($shipment_id);
    	
    	// update order details status
    	$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    	foreach($shipment_orders as $item){
    	    $orderDetailTable->updateStatus($item->getOrderno(), $this::bought);
    	}
    	
    	$shipmentTable->deleteShipment($shipment_id);    
    	
    	
    	return $this->redirect()->toRoute('admin',array('action'   => 'shipment'));
    
    }
    
    public function deleteshippedAction(){
    	$request = $this->getRequest();
    
    	$shipment_id = $request->getPost('id',null);
    	if (!$shipment_id){
    		$shipment_id = $this->params()->fromRoute('id',0);
    	}
    	 
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	//$shipment = $shipmentTable->getShipmentById($shipment_id);
    	 
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	$shipment_orders = $shipmentOrderTable->getShipmentOrder($shipment_id);
    	 
    	// update order details status
    	$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    	foreach($shipment_orders as $item){
    		$orderDetailTable->updateStatus($item->getOrderno(), $this::bought);
    	}
    	 
    	$shipmentTable->deleteShipment($shipment_id);
    	 
    	 
    	return $this->redirect()->toRoute('admin',array('action'   => 'shipped'));
    
    }
    
    public function shippedAction(){
    	$request = $this->getRequest();
    	 
    	
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$storeTable = new StoreTable($this->getDbAdapter());
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->fetchAll(1);
    
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($shipments));
    
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	 
    
    	 
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function shipmentAction(){
    	$request = $this->getRequest();
    	
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    
    
    	$storeTable = new StoreTable($this->getDbAdapter());
    
    	$shipmentTable = new ShipmentTable($this->getDbAdapter());
    	$shipments = $shipmentTable->getUnshipped();
    	 
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($shipments));
    	 
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    	 
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	
    	 
    	
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    	        'cancelledO_ID'    => 18,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    			 
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function viewshipmentAction(){
        $request = $this->getRequest();
        $shipment_id = $request->getPost('id',null);
        if (!$shipment_id){
        	$shipment_id = $this->params()->fromRoute('id',0);
        
        }
         
        $shipment = new Shipment();
         
        
        if ($shipment_id){
        	$shipmentTable = new ShipmentTable($this->getDbAdapter());
        	$shipment = $shipmentTable->getShipmentById($shipment_id);
        }
        
        $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
        
        $shipmentOrders =  $shipmentOrderTable->getShipmentOrder($shipment->getId());
        
        
        $list = array();
        $orderImageTable = new OrderImageTable($this->getDbAdapter());
        foreach($shipmentOrders as $item){
        	$images = $orderImageTable->getImageByOrderno($item->getOrderno());
        	$item->images = $images;
        	$list[] = $item;
        }
        
        
        $creditcardTable  = new CreditCardTable($this->getDbAdapter());
        $dungCards = $creditcardTable->getCreditCardByHolder('DUNG');
         
        $cards = array();
        foreach($dungCards as $card){
        	$item = array(
        			'card'    => $card->getCreditcard(),
        			'total'    => 0
        	);
        	$cards[] = $item;
        }
        
        $creditcards = array();
        foreach($cards as $card){
        	$total_final = $card['total'];
        	foreach($list as $item){
        		if ($item->getCreditcard() == $card['card']){
        			$total_final += $item->getTotal_final_o();
        		}
        	}
        	$creditcards[] = array(
        			'card'  =>    $card['card'],
        			'total'    => $total_final,
        	);
        }
        
        $view =  new ViewModel(array(
        		'shipmentOrders'	=> $list,
        		'shipment'		=> $shipment,    
                'cards'         => $creditcards,    		 
        		'isAjaxRequest' => $request->isXmlHttpRequest()
        		 
        ));
         
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function searchshipmentorderAction(){
        $request = $this->getRequest();
        $store_id = $request->getPost('store_id',null);
        $description = $request->getPost('description',null);
        $orderTable = new AdminOrderTable($this->getDbAdapter());
        $orders = $orderTable->getOrderNotShippedByStoreDescription($store_id,$description);
        
        $list = array();
        $orderImageTable = new OrderImageTable($this->getDbAdapter());
        foreach ($orders as $item){
            $images = $orderImageTable->getImageByOrderno($item->getOrderno());
            $item->images = $images;
            $list[] = $item;
        }
        
       
        
        $view =  new ViewModel(array(
        		'orders'	=> $list,        		 
        		'isAjaxRequest' => $request->isXmlHttpRequest()
        		 
        ));
         
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function createshipmentajaxaddAction(){
        $request = $this->getRequest();
        
        if ($request->isXmlHttpRequest()){
            $shipment_id = $request->getPost('id',null);
            if (!$shipment_id){
            	$shipment_id = $this->params()->fromRoute('id',0);
            
            }
             
            $shipment = new Shipment();
             
            
            if ($shipment_id){
            	$shipmentTable = new ShipmentTable($this->getDbAdapter());
            	$shipment = $shipmentTable->getShipmentById($shipment_id);
            }
            
            $orderno = $request->getPost('orders',null);
             
            $success = false;
            if ($shipment_id &&  $request->isPost() && $request->getPost('btnOrderAdd')){
            	if ($orderno){
            		$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
            		$shipmentOrder = new ShipmentOrder();
            		$shipmentOrder->setShipment_id($shipment_id);
            		$shipmentOrder->setOrderno($orderno);
            		$shipmentOrder->setChecked(0);
            		$inserted = $shipmentOrderTable->addShipmentOrder($shipmentOrder);
            
            		if ($inserted){
            			$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
            			$orderDetailTable->updateStatus($orderno, $this::ontheway);
            			 
            			$success = true;
            			 
            		}
            	}
            
            }
            
            if ($success){
                $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
                 
                 
                 
                $shipmentOrders =  $shipmentOrderTable->getShipmentOrder($shipment->getId());
                 
                $list = array();
                $orderImageTable = new OrderImageTable($this->getDbAdapter());
                foreach($shipmentOrders as $item){
                	$images = $orderImageTable->getImageByOrderno($item->getOrderno());
                	$item->images = $images;
                	$list[] = $item;
                }
                
                 
                //$shipmentOrders =  $shipmentOrderTable->getShipmentOrder(1);
                $orderTable = new AdminOrderTable($this->getDbAdapter());
                $orders  = $orderTable->getOrderNotShipped();
                 
                $creditcardTable  = new CreditCardTable($this->getDbAdapter());
                $dungCards = $creditcardTable->getCreditCardByHolder('DUNG');
                 
                
                 
               
                
                $str = "";
                $stt = 0;
                
                $i = 1; 
    	         $total_web = 0;
    	         $total_web1 = 0; 
    	         $total_items  = 0;        			         
    	         $total_final = 0;
    	         
    	         $total_web_dung = 0;
    	         $total_web1_dung = 0;
    	         $total_final_dung = 0;
    	         $total_items_dung = 0;
    	         
    	         $total_dung = 0;
                
                foreach ($list as $item){
                	if ($item->getHolder() == "DUNG"){                
                		$total_dung += $item->getTotal_final_o();
                	}
                
                	$total_web_dung += $item->getTotal_web();
                	$total_web1_dung += $item->getTotal_web1();
                	$total_final_dung += $item->getTotal_final();
                	$total_items_dung += $item->getItems();
                	
                	$total_items += $item->getItems_o();
                	$total_web += $item->getTotal_web_o();
                	$total_web1 += $item->getTotal_web1_o();
                	$total_final += $item->getTotal_final_o();
                	
                	$i++;
                	if ($item->getOrderno() == $orderno){
                		$stt = $i-1;
                		
                		$orderdate = $item->getOrderdate();
                		$storename = $item->getStore_name();
                		$holder = $item->getHolder();
                		$items_o = $item->getItems_o();
                		$discount = number_format($item->getDiscount(),2,'.',',');
                		$total_web_o = number_format($item->getTotal_web_o(),2,'.',',');
                		$total_web1_o = number_format($item->getTotal_web1_o(),2,'.',',');
                		$ship_us = number_format($item->getShip_us(),2,'.',',');
                		$tax = number_format($item->getTax(),2,'.',',');
                		$total_final_o = number_format($item->getTotal_final_o(),2,'.',',');
                		$description = "<div>";
                		if (!empty($item->images)){
                			$images = $item->images;
                			$img = array_shift($images);
                		
                			$description .= "<a class='fancybox' data-fancybox-group='thumb_".$item->getOrderno()."' href='".$img->getPath()."'>".$item->getOrderno()."</a>";
                			foreach($images as $img){
                				$description .="<a class='hidden btn fancybox' data-fancybox-group='thumb_".$item->getOrderno()."' href='".$img->getPath()."'></a>";
                			}
                		}else{
                			$description .=  $item->getOrderno();
                		
                		}
                		
                		$description .= "</div>";                		            
                	}
                }
                
                $cards = array();
                foreach($dungCards as $card){
                	$carditem = array(
                			'card'    => $card->getCreditcard(),
                			'total'    => 0
                	);
                	$cards[] = $carditem;
                }
                
                $creditcards = array();
                foreach($cards as $card){
                	$total_final_car = $card['total'];
                	foreach($list as $listitem){
                		if ($listitem->getCreditcard() == $card['card']){
                			$total_final_car += $listitem->getTotal_final_o();
                		}
                	}
                	$creditcards[] = array(
                			'card'  =>    $card['card'],
                			'total'    => $total_final_car,
                	);
                }
                
                $trSumzarize = '<td colspan="19" id="sumarize">';
                
                foreach($creditcards as $item){
                	$trSumzarize .= "<span class='label right'>".$item['card'].": </span><span class='val'>".number_format($item['total'],2,'.',',')."</span> <br/>";
                }
                 
                $trSumzarize .= "<span class='label right'>Total: </span><span class='val red'>".number_format($total_dung,2,'.',',')."</span> <br/>
	                <span class='label'>Total Web1: </span><span class='val red'>".number_format($total_web1,2,'.',',')."</span> <br/>
	                <span class='label'>Total Final: </span><span class='val red'>".number_format($total_final,2,'.',',')."</span> <br/>";
                $trSumzarize .= "</td>";
                
                 
                
                return new JsonModel(array(
                        'success'   => 1,
                        'orderno'   => $orderno,
                		'stt'  => $stt,
                		//'tr'    => $str,
                		'trSumzarize'   => $trSumzarize,
                		'total_web_dung'  => number_format($total_web_dung,2,".",","),
                		'total_web1_dung'  => number_format($total_web1_dung,2,".",","),
                		'total_final_dung'  => number_format($total_final_dung,2,".",","),
                		'total_items_dung'  => number_format($total_items_dung,0),
                    
                		
                    
                        'description'  => $description,
                        'orderdate'    => $orderdate,
                        'storename'    => $storename,
                        'holder'       => $holder,
                        'items_o'      => number_format($items_o,0),
                        'discount'     => $discount,
                        
                        'ship_us'      => number_format($ship_us,2,".",","),
                        'tax'          => number_format($tax,2,".",","),
                        'total_items'  => number_format($total_items,0,".",","),
                        'total_web'  => number_format($total_web,2,".",","),
                        'total_web1'  =>  number_format($total_web1,2,".",","),
                        'total_final'  =>  number_format($total_final,2,".",","),
                         
                ));
                
            }else{
                return new JsonModel(array(
                		'success'   => 0,                		 
                ));
            }
            
            
        }else{
            return $this->redirect()->toRoute('admin',array('action'=>'shipment'));
        }
        
    }
    
    public function createshipmentAction(){
    	$request = $this->getRequest();
    	 
    	$this->params()->fromRoute('id');
    	
    	$shipment_id = $request->getPost('id',null);
    	if (!$shipment_id){
    		$shipment_id = $this->params()->fromRoute('id',0);
    		
    	}
    	
    	$shipment = new Shipment();
    	
    
    	if ($shipment_id){
    		$shipmentTable = new ShipmentTable($this->getDbAdapter());
    		$shipment = $shipmentTable->getShipmentById($shipment_id);    		
    	}
    	
    	
    	
    	if ($request->isPost() && $request->getPost('btnSave')){    	        	    
    	     $data =(array) $request->getPost();
    		 $shipment = new Shipment();
    		 $shipment->setData($data);
    		 $shipment->setFinish(0);
    		 $shipment->setDelivered(0);
    		 $shipment->setChecked(0);
    		 $shipment->setFinalized(0);
    		 $shipment->setPaid(0);
    		 $shipmentTable = new ShipmentTable($this->getDbAdapter());
    		 $id = $shipmentTable->saveShipment($shipment);
    		 $shipment->setId($id);
    	}
    	// save one by one
    	if ($request->isXmlHttpRequest() && $request->isPost() && $request->getPost('saveRow') && $request->getPost('shipment_id')){
    	    
    	    $orderno = $request->getPost('orderno');
    	    
    	    $shipmentOrder = new ShipmentOrder();
    	    $shipmentOrder->setShipment_id($request->getPost('shipment_id'));
    	    $shipmentOrder->setOrderno($orderno);
    	    $shipmentOrder->setNote($request->getPost("note"));
    	    
    	    $shipmentOrder->setPackage($request->getPost("packageno"));
    	    $shipmentOrder->setTotal_web($request->getPost("total_web"));
    	    $shipmentOrder->setTotal_web1($request->getPost("total_web1"));
    	    $shipmentOrder->setItems($request->getPost("items"));
    	    $shipmentOrder->setTotal_final($request->getPost("total_final"));
    	    
    	    $shipmentOrder->setChecked(0);
    	     
    	    
    	    $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	    
    	    $success = $shipmentOrderTable->saveShipmentOrder($shipmentOrder);
    	    
    	    if ($success){
    	        return new JsonModel(array(
    	        	'success' => 1
    	        ));
    	        
    	    }else{
    	        return new JsonModel(array(
    	        		'success' => 0
    	        ));
    	    }
    	    
    	}
    	
    	if ($shipment_id &&  $request->isPost() && $request->getPost('btnOrderAdd')){
    	    
    		$orderno = $request->getPost('orders',null);
    		if (!$orderno){
    		    $orderno = $request->getPost('hiddenOrdernoAdd',null);
    		}
    		
    		if ($orderno){
    		    $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    		    $shipmentOrder = new ShipmentOrder();
    		    $shipmentOrder->setShipment_id($shipment_id);
    		    $shipmentOrder->setOrderno($orderno);
    		    $shipmentOrder->setChecked(0);
    		    $inserted = $shipmentOrderTable->addShipmentOrder($shipmentOrder);
    		    
    		    if ($inserted){
    		        $orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    		        $orderDetailTable->updateStatus($orderno, $this::ontheway);
    		    }
    		}
    		
    	}
    	
    	$saved = false;
    	if ($shipment_id &&  $request->isPost() && $request->getPost('btnShipeOrderSave')){
    	   
    	   $shipment_orders = array();
    	   // orderno_keys
    	   $keys = $request->getPost('orderno',null);
    	   if ($keys){
    	       foreach($request->getPost('orderno') as $key => $orderno){
    	       	$shipmentOrder = new ShipmentOrder();
    	       	$shipmentOrder->setShipment_id($request->getPost('id'));
    	       	$shipmentOrder->setOrderno($orderno);
    	       	$shipmentOrder->setNote($request->getPost($orderno."_note"));
    	       
    	       	$shipmentOrder->setPackage($request->getPost($orderno."_package"));
    	       	$shipmentOrder->setTotal_web($request->getPost($orderno."_total_web"));
    	       	$shipmentOrder->setTotal_web1($request->getPost($orderno."_total_web1"));
    	       	$shipmentOrder->setItems($request->getPost($orderno."_items"));
    	       	$shipmentOrder->setTotal_final($request->getPost($orderno."_total_final"));
    	       
    	       	$shipmentOrder->setChecked(0);
    	       	$shipment_orders[] = $shipmentOrder;
    	       }
    	        
    	   }
    	   
   

    	  $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	  foreach($shipment_orders as $item){
    	      $shipmentOrderTable->saveShipmentOrder($item);
    	  } 
    	  $saved  = true;   
    	}
    	
    	if ($shipment_id && $request->isPost() && $request->getPost('btnFinish')){
    		  
    		$shipmentTable = new ShipmentTable($this->getDbAdapter());    		
    		$shipment = $shipmentTable->getShipmentById($shipment_id)	;
    		$data =(array) $request->getPost();
    		$shipment->setData($data);
    		$shipment->setId($shipment_id);
    		$shipment->setFinish(1);
    		$shipment->setPaid(0);
    		$completed = $shipmentTable->saveShipment($shipment);
    		
    		/* if ($completed){
    		    $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    		    $shipmentOrders = $shipmentOrderTable->getShipmentOrder($shipment->getId());
    		  
    			
    		} */
    		
    	  
    		return $this->redirect()->toRoute('admin',array('action' => 'shipped'));
    		
    		
    	}
    	
    	$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    	
    	
    	
    	$shipmentOrders =  $shipmentOrderTable->getShipmentOrder($shipment->getId());
    	
    	$list = array();
    	$orderImageTable = new OrderImageTable($this->getDbAdapter());
    	foreach($shipmentOrders as $item){
    	    $images = $orderImageTable->getImageByOrderno($item->getOrderno());
    	    $item->images = $images;
    	    $list[] = $item;
    	}
    	 
    	
    	//$shipmentOrders =  $shipmentOrderTable->getShipmentOrder(1);
    	$orderTable = new AdminOrderTable($this->getDbAdapter());
    	$orders  = $orderTable->getOrderNotShipped();
    	
    	$creditcardTable  = new CreditCardTable($this->getDbAdapter());
    	$dungCards = $creditcardTable->getCreditCardByHolder('DUNG');
    	
    	$cards = array();
    	foreach($dungCards as $card){
    	    $item = array(
    	    	'card'    => $card->getCreditcard(),
    	        'total'    => 0
    	    );
    	    $cards[] = $item;
    	}

    	$creditcards = array();
    	foreach($cards as $card){
    	    $total_final = $card['total'];
    	    foreach($list as $item){
    	        if ($item->getCreditcard() == $card['card']){
    	            $total_final += $item->getTotal_final_o();
    	        }
    	    }
    	    $creditcards[] = array(
    	    	'card'  =>    $card['card'],
    	        'total'    => $total_final, 
    	    );
    	}
    	
    	$storeTable = new StoreTable($this->getDbAdapter());
    	$stores = $storeTable->fetchAll(); 
    	
    	$view =  new ViewModel(array(    		 
    			'shipmentOrders'	=> $list,
    			'shipment'		=> $shipment,
    			'orders'		=> $orders,
    	        'cards'    => $creditcards,
    	        'stores'   => $stores,
    	        'saved'    => $saved,
    	        'isAjaxRequest' => $request->isXmlHttpRequest()
    	
    	));
    	
    	 
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    	
    }
    
    public function shipdelAction(){
    	$request = $this->getRequest();
    	 
    	if ($request->isXmlHttpRequest()){
    		$orderno = $request->getPost('orderno',-1);
    		$shipment_id = $request->getPost('shipment_id',-1);
    		 
    		$shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());
    
    		if ($shipment_id != -1 && $orderno != -1 && $shipmentOrderTable->deleteShipmentOrder($shipment_id, $orderno)){
    		    
                $shipmentOrderTable = new ShipmentOrderTable($this->getDbAdapter());                 
                $shipmentOrders =  $shipmentOrderTable->getShipmentOrder($shipment_id);
                
                $orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
                $details = $orderDetailsTable->getDetailsByOrderNo($orderno);
                 
                foreach ($details as $detail){
                    $detail->setStatus($this::bought);
                    $orderDetailsTable->updateOrderDetails($detail);
                }
                 
                $list = array();
                $orderImageTable = new OrderImageTable($this->getDbAdapter());
                foreach($shipmentOrders as $item){
                	$images = $orderImageTable->getImageByOrderno($item->getOrderno());
                	$item->images = $images;
                	$list[] = $item;
                }
                
                
                //$shipmentOrders =  $shipmentOrderTable->getShipmentOrder(1);
                $orderTable = new AdminOrderTable($this->getDbAdapter());
                $orders  = $orderTable->getOrderNotShipped();
                 
                $creditcardTable  = new CreditCardTable($this->getDbAdapter());
                $dungCards = $creditcardTable->getCreditCardByHolder('DUNG');
                 
                
                 
               
                
                $str = "";
                $stt = 0;
                
               $i = 1; 
    	         $total_web = 0;
    	         $total_web1 = 0; 
    	         $total_items  = 0;        			         
    	         $total_final = 0;
    	         
    	         $total_web_dung = 0;
    	         $total_web1_dung = 0;
    	         $total_final_dung = 0;
    	         $total_items_dung = 0;
    	         
    	         $total_dung = 0;
                
                foreach ($list as $item){
                	if ($item->getHolder() == "DUNG"){                
                		$total_dung += $item->getTotal_final_o();
                	}
                
                	$total_web_dung += $item->getTotal_web();
                	$total_web1_dung += $item->getTotal_web1();
                	$total_final_dung += $item->getTotal_final();
                	$total_items_dung += $item->getItems();
                	
                	$total_items += $item->getItems_o();
                	$total_web += $item->getTotal_web_o();
                	$total_web1 += $item->getTotal_web1_o();
                	$total_final += $item->getTotal_final_o();
                	
                	 
                	 
                }
                
                $cards = array();
                foreach($dungCards as $card){
                	$carditem = array(
                			'card'    => $card->getCreditcard(),
                			'total'    => 0
                	);
                	$cards[] = $carditem;
                }
                
                $creditcards = array();
                foreach($cards as $card){
                	$total_final_car = $card['total'];
                	foreach($list as $listitem){
                		if ($listitem->getCreditcard() == $card['card']){
                			$total_final_car += $listitem->getTotal_final_o();
                		}
                	}
                	$creditcards[] = array(
                			'card'  =>    $card['card'],
                			'total'    => $total_final_car,
                	);
                }
                
                $trSumzarize = '<td colspan="19" id="sumarize">';
                
                foreach($creditcards as $item){
                	$trSumzarize .= "<span class='label right'>".$item['card'].": </span><span class='val'>".number_format($item['total'],2,'.',',')."</span> <br/>";
                }
                 
                $trSumzarize .= "<span class='label right'>Total: </span><span class='val red'>".number_format($total_dung,2,'.',',')."</span> <br/>
	                <span class='label'>Total Web1: </span><span class='val red'>".number_format($total_web1,2,'.',',')."</span> <br/>
	                <span class='label'>Total Final: </span><span class='val red'>".number_format($total_final,2,'.',',')."</span> <br/>";
                $trSumzarize .= "</td>";
                
                 
                
                return new JsonModel(array(
                        'success'   => 'yes',
                        'orderno'   => $orderno,
                		'stt'  => $stt,
                		//'tr'    => $str,
                		'trSumzarize'   => $trSumzarize,
                		'total_web_dung'  => number_format($total_web_dung,2,".",","),
                		'total_web1_dung'  => number_format($total_web1_dung,2,".",","),
                		'total_final_dung'  => number_format($total_final_dung,2,".",","),
                		'total_items_dung'  => number_format($total_items_dung,0),
                     
                        'total_items'  => number_format($total_items,0,".",","),
                        'total_web'  => number_format($total_web,2,".",","),
                        'total_web1'  =>  number_format($total_web1,2,".",","),
                        'total_final'  =>  number_format($total_final,2,".",","),
                         
                ));
    
    		}else{
    			$view = new JsonModel(array(
    					'success'   => 'no',
    					'errmsg' => 'Không thể xóa.',
    			));
    			return $view;
    		}
    	}else{
    		return $this->redirect()->toRoute('home');
    	}
    
    }
    
    public function saveorderAction(){
    		$request = $this->getRequest();
    		
    		$session = new MySession();
    		$orderinfo = $session->getOrderInfo();
    		if (empty($orderinfo)){
    		    return $this->redirect()->toRoute('admin',array('action' => 'orderlist'));
    		}
    		
    		if ($request->isPost()){
    			
    			
    			$order = new AdminOrder();
    			$orderInfo = $session->getOrderInfo();
    			$order->setData($orderInfo);
    			$order->setDescription($orderInfo['order_description']);
    			$order->setAdmin($this->UserAuthPlugin()->getNick());
    			$order->setLastupdated(date('d-m-Y'));
    			$order->setValid(0);
    			$order->setChecked(0);
    			$order->setFinalized(0);
    			
    			$orderTable = new AdminOrderTable($this->getDbAdapter());
    			$valid = true;
    			
    			if ($orderTable->isValidOrderNo($order->getOrderno())){
    				
    				$orderdetails = array();
    				 
    				$nicks = (array) $request->getPost('nicks');
    				array_shift($nicks);
    				$orderdetails['nick'] = $nicks;
    				 
    				$description = (array) $request->getPost('description');
    				array_shift($description);
    				$orderdetails['description'] = $description;
    				 
    				$items =  (array) $request->getPost('items');
    				array_shift($items);
    				$orderdetails['items'] = $items;
    				 
    				$total_web = (array) $request->getPost('total_web');
    				array_shift($total_web);
    				$orderdetails['total_web'] =$total_web;
    				 
    				$total_web1 = (array) $request->getPost('total_web1');
    				array_shift($total_web1);
    				$orderdetails['total_web1'] = $total_web1;
    				 
    				 
    				$ship_us = (array) $request->getPost('ship_us');
    				array_shift($ship_us);
    				$orderdetails['ship_us'] = $ship_us;
    				 
    				$extra_fee = (array) $request->getPost('extra_fee');
    				array_shift($extra_fee);
    				$orderdetails['extra_fee'] = $extra_fee;
    				 
    				 
    				$tax_val = (array) $request->getPost('tax_val');
    				array_shift($tax_val);
    				$orderdetails['tax'] = $tax_val;
    				 
    				$service = (array) $request->getPost('service_final');
    				array_shift($service);
    				$orderdetails['service'] = $service;
    				 
    				$discount = (array) $request->getPost('discount');
    				array_shift($discount);
    				$orderdetails['discount'] = $discount;
    				 
    				$total_final = (array) $request->getPost('total_final');
    				array_shift($total_final);
    				$orderdetails['total_final'] = $total_final;
    				 
    				$images = (array) $request->getPost('images');
    				array_shift($images);
    				$orderdetails['images'] = $images;
    				
    				
    				$i = count($orderdetails['nick']);
    				
    				foreach($orderdetails as $item){
    					if (count($item) != $i){
    						$valid = false;
    					}
    				}
    				 
    				$details = array();
    				 
    				for($k = 0; $k < $i; $k++){
    					$detail = array();
    					foreach($orderdetails as $key => $item){
    						$detail[$key] = $item[$k];
    						$detail['orderno'] = $order->getOrderno();
    						$detail['orderdate'] = $order->getOrderdate();
    						 
    					}
    					$details[] = $detail;
    				}
    			}else{// order is  invalid
    				$valid = false;
    				 
    			}
    			
    			if ($valid){    
    				$success = true;
    				try{
    					
    					if ($orderTable->addOrder($order)){						// save order
    					
    						$orderImages = json_decode($orderInfo['imageList']);
    						$orderImageTable = new OrderImageTable($this->getDbAdapter());
    						if (!empty($orderImages)){
    							foreach($orderImages as $image_id => $item){
    								$item = (array)$item;
    								$orderimage = new OrderImage();
    								$orderimage->setImage_id($image_id);
    								$orderimage->setOrderno($order->getOrderno());
    								$orderimage->setPath($item['image']);
    								$orderImageTable->saveOrderImage($orderimage);
    							}
    						}
    							
    							
    						// order details
    						$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    						$orderdetailImageTable = new OrderDetailsImageTable($this->getDbAdapter());
    						
    						$customerTable = new CustomerTable($this->getDbAdapter());
    						
    						foreach($details as $item){
    							$images = json_decode($item['images']);
    						 
    							$orderdetail = new AdminOrderDetails();
    							$orderdetail->setData($item);
    							$orderdetail->setStatus($this::bought);
    							$orderdetail->setChecked(0);
    							$orderdetail->setFinish(0);
    							
    							// get service percent
    							if ($orderdetail->getService() <= 0){
    							    $cuts  = $customerTable->getUserByNick($orderdetail->getNick());
    							    $service = ($cuts->getService()/100)*$orderdetail->getTotal_web1();
    							    $orderdetail->setService($service);
    							    $total_final = $service + $orderdetail->getTax() + $orderdetail->getShip_us() + $orderdetail->getExtra_fee() + $orderdetail->getTotal_web1();
    							    $orderdetail->setTotal_final($total_final);
    							}
    							
    							$orderDetail_id = $orderDetailTable->addOrderDetail($orderdetail);
    							$orderdetail->setId($orderDetail_id);
    							if (!empty($images)){
    								foreach($images as $image_id => $item){
    									$item = (array)$item;
    									$image = new OrderDetailsImage();
    									$image->setImage_id($image_id);
    									$image->setOrder_details_id($orderdetail->getId());
    									$image->setPath($item['image']);    									 
    									$orderdetailImageTable->saveOrderImage($image);
    								}
    							}
    					
    						}
    					}
    					
    				}catch (\Exception $e){
    					echo $e;
    					$success = false;
    				}
    				
    				if ($success){
    				    // updatee balance here;
    				    $orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
    				    $details = $orderDetailsTable->getOrderDetailsByOrderNo($order->getOrderno());
    					$customerTable = new CustomerTable($this->getDbAdapter());
    					$admin = $this->UserAuthPlugin()->getNick();
    					$balanceTable = new BalanceTable($this->getDbAdapter());
    					$transactionTable = new TransactionTable($this->getDbAdapter());
    				    foreach($details as $item){
    				        $customer = $customerTable->getUserById($item->getNick());
    				        $balance = $balanceTable->getBalanceById($customer->getBalance_id());
    				        $credit = $balance->getCredit() - $item->getTotal_final() ;
    				        $balance->setCredit($credit);
    				        if ($balanceTable->saveBalance($balance)){
    				            $transaction = new Transaction();
    				            $transaction->setAdmin($admin);
    				            $transaction->setBalance_id($balance->getId());
    				            $transaction->setOrder_detail_id($item->getId());
    				            $transaction->setDate(date('Y-m-d'));
    				            $transaction->setType($this::minus);
    				            $transaction->setCheck(0);
    				            $transaction->setAmount($item->getTotal_final());
    				            $transaction->setCredit($credit);
    				            $transaction->setNote("Order - " .$order->getStore_name().': '. $item->getDescription());    				            
    				            $transactionTable->saveTransaction($transaction);
    				        }
    				            
    				    }
    				    
    				}else{
    					$orderTable = new AdminOrderTable($this->getDbAdapter());
    					$orderTable->deleteOrder($order->getOrderno());
    					$orderDetailTable = new AdminOrderDetailsTable($this->getDbAdapter());
    					$orderDetailTable->deleteAllDetails($order->getOrderno());
    				}
    				
    				$session->clearOrderInfo();

    				$orderDetailsTable = new AdminOrderDetailsTable($this->getDbAdapter());
    				$orderDetails = $orderDetailsTable->getOrderDetailsByOrderNo($order->getOrderno());
    				
    				$orderTable = new AdminOrderTable($this->getDbAdapter());
    				$order  = $orderTable->getOrderByNo($order->getOrderno());
    				
    				$view = new ViewModel(array(
    						'success'	=> $success,
    						'details'	=> $orderDetails,
    						'order'		=> $order
    				
    				));
    				
    				return  $view;
    				
    			 
    			}
    		 	
    			
    		}else{// isPost
    			return $this->redirect()->toRoute('admin',array('action' => 'order'));
    		}
    }
    
    public function orderdetailsAction(){
    	
        $request = $this->getRequest();
        
        $nick = $this->UserAuthPlugin()->getNick();
        
        $orderno = '';
        
        $session = new MySession();
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
        if ($request->isPost() && $request->getPost('btnNext')){            
            $data = (array) $request->getPost(); 
            $session->saveOrderInfo($data);
            
        }
        
        $orderInfo = $session->getOrderInfo();    
        
        $order  = new AdminOrder();
        if ($orderInfo){           	
            $store = $storeTable->getStoreById($orderInfo['store_id']);                    
            $order->setData($orderInfo);
            $order->setDescription($orderInfo['order_description']);
            $order->setStore_name($store->getName());
        } else{
        	return $this->redirect()->toRoute('admin',array('action'=>'order'));
        }
       
        
        $stores = $storeTable->fetchAll();
        
        $customerTable = new CustomerTable($this->getDbAdapter());        
        $nicks = $customerTable->fetchAll();        
        
        $view =  new ViewModel(array(
        		/* 'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),*/
        		'stores'    => $stores,
        		'nicks'     => $nicks,
        		'order'     => $order 
                
        ));
        
         
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
        
    }
    
   /*  public function adminordersAction(){
        $request = $this->getRequest();
        
        
        
       $view =  new ViewModel(array(
        		 
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    } */
    
    public function checkordernoAction(){
        $request = $this->getRequest();        
        if ($request->isXmlHttpRequest()){
            $orderno = $request->getPost('orderno',-1);
            $orderTable = new AdminOrderTable($this->getDbAdapter());
            
            if ($orderno != -1 && $orderTable->isValidOrderNo($orderno)){                
                $view = new JsonModel(array(
                		'success'   => 1,
                		'errmsg' => 'Order number hợp lệ.',
                ));
                return $view;
                
            }else{
                $view = new JsonModel(array(
                		'success'   => 0,
                		'errmsg' => 'Order number đã tồn tại.',
                ));
                return $view;
            }
        }else{
            $this->redirect()->toRoute('home');
        }
        
    }
    
    
    
    public function orderdelAction(){
    	$request = $this->getRequest();
    	if ($request->isXmlHttpRequest()){
    		$orderno = $this->params()->fromRoute('id',-1);
    		$orderTable = new AdminOrderTable($this->getDbAdapter());    		    
    		if ($orderno != -1 && $orderTable->deleteOrder($orderno)){
    			$view = new JsonModel(array(
    					'success'   => 1,
    					'errmsg' => 'Đã xóa.',
    			));
    			return $view;
    
    		}else{
    			$view = new JsonModel(array(
    					'success'   => 0,
    					'errmsg' => 'Không thể xóa.',
    			));
    			return $view;
    		}
    	}else{
    		$this->redirect()->toRoute('home');
    	}
    
    }
    public function orderAction(){
        
        $request = $this->getRequest(); 
        $session = new MySession();
        $session->clearOrderInfo();
        
        $admin = $this->UserAuthPlugin()->getNick();
        $adminOrderTable = new AdminOrderTable($this->getDbAdapter());
        
        /* if ($request->isPost() && $request->getPost('btnAddNew',null) ){             
            $order = new AdminOrder();
            $data = (array)$request->getPost();
            $order->setData($data);
            $order->setLastupdated(date('Y-m-d H:i:s'));      
            $order->setStatus($this::incomplete);    
            $order->setAdmin($admin);
            $order->setValid(1);            
            if ($order->isValid() && $adminOrderTable->isValidOrderNo($order->getOrderno())){
                $adminOrderTable->addOrder($order);
            }          
        } */
        
        
        $orderno = $this->params()->fromRoute('id',-1);
        $order = $adminOrderTable->getOrderByNo($orderno,$admin);
       
        $storeTable = new StoreTable($this->getDbAdapter());
        $stores = $storeTable->fetchAll();
       
       $creditcardTable = new CreditCardTable($this->getDbAdapter());
       $creditcards = $creditcardTable->fetchAll();

        $view =  new ViewModel(array(        		     		 
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        	    'stores'   => $stores,
                'creditcards'   => $creditcards
        ));
        
       
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function storesAction(){
        $request = $this->getRequest();
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
        
        $storeName = $request->getPost('name',null);
        
        if ($storeName){
            
            $store = new Store();
            $store->setName($storeName);
            $storeTable->saveStore($store);        
        	if ($request->isXmlHttpRequest()){
        
        		return new JsonModel(array(
        				'success'  => 1,
        				'errormsg'  => ''
        		));        
        	}        
        }
        
        $like = $request->getPost('search',null);
        
        $stores = $storeTable->fetchAll($like);
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($stores));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = '';
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
                'like'  => $like
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function deletecreditcardAction(){
        $request = $this->getRequest();
        
        if ($request->isXmlHttpRequest()){
            $creditcard = $request->getPost('creditcard');
            $creditcardTable = new CreditCardTable($this->getDbAdapter());
            $success = $creditcardTable->deleteCreditCard($creditcard);
            
            return new JsonModel(array(
            		'success'  => $success,
            		'errormsg'  => ''
            ));
        }
        
    }
    
    public function creditcardAction(){
    	$request = $this->getRequest();
    
    	// get current page from url or posted data
    	if ($this->params()->fromRoute('page') != null){
    		$page = (int)$this->params()->fromRoute('page');
    	}elseif($this->getRequest()->getPost('page') != null){
    		$page  = (int)$this->getRequest()->getPost('page');
    	}else{
    		$page = 1;
    	}
    
    	// get item per page from url or posted data
    	if ($this->params()->fromRoute('row') != null){
    		$itemsPerPage = (int)$this->params()->fromRoute('row');
    	}elseif($this->getRequest()->getPost('row') != null){
    		$itemsPerPage  = (int)$this->getRequest()->getPost('row');
    	}else{
    		// default value is 15
    		$itemsPerPage = 15;
    	}
    

    	$creditcardTable = new CreditCardTable($this->getDbAdapter());
    	
    	$creditcard = $request->getPost('creditcard',null);
        $holder = $request->getPost('holder',null);   
    	
    	if ( $request->getPost('btnAddNew') && $creditcard && $holder){
            
    	    $item = new CreditCard();
    	    $item->setCreditcard($creditcard);
    	    $item->setHolder($holder);
    		
    	    if (!$creditcardTable->getCreditCardById($creditcard)){
    	       $success =  $creditcardTable->insertCreditCard($item);
    	       return new JsonModel(array(
    	       		'success'  => $success,
    	       		'errormsg'  => ($success==0?"Không thể thêm, vui lòng thử lại":"")
    	       ));
    	    }else{
    	        return new JsonModel(array(
    	        		'success'  => 0,
    	        		'errormsg'  => "CreditCard đã có."
    	        ));
    	        
    	    }
    	    
    		
    		 
    	}
     
    	
    	$creditcards = $creditcardTable->getAllCreditCard();
    
    	 
    	$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($creditcards));
    	 
    	$i = $itemsPerPage;
    	// show all
    	if ($itemsPerPage == -1){
    		$i = $paginator->getTotalItemCount();
    	}
    	 
    	$paginator->setCurrentPageNumber($page)
    	->setItemCountPerPage($i)
    	->setPageRange(7);
    	$errmsg = '';
    	$view =  new ViewModel(array(
    			'paginator' => $paginator,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    			'errmsg' => $errmsg,
    		
    	));
    
    
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    
    }
    
    public function xratesAction(){
        $request = $this->getRequest();
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        
        
        $xrateTable = new XRatesTable($this->getDbAdapter());
        
        $newXrate = $request->getPost('xrate',0);
        $newXrate = str_replace(',', '', $newXrate);
        $newXrate = (double) $newXrate;
        if ($newXrate > 0){
            $xrate = new XRates();            
            $xrate->setRate($newXrate);
            $xrateTable->addRate($xrate);
            
            if ($request->isXmlHttpRequest()){
                
                return new JsonModel(array(
                	'success'  => 1,
                    'errormsg'  => ''
                ));
                
            }
            
        }
        
        $xrates = $xrateTable->fetchAll();
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($xrates));
        
        
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        $errmsg = ''; 
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),  
                'errmsg' => $errmsg      		 
        ));
        
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
    
    public function approvedcustomerordersAction(){
        $request = $this->getRequest();
        
        
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $nick = $request->getPost('nick',null);
        $store_id = $request->getPost('store',null);
        
        $orderTable = new CustomerOrderTable($this->getDbAdapter());
        //$orders = $orderTable->getAll($nick,$this::checked,null,$store_id);
        
        $paginator = $orderTable->fetchAllForPaging($nick,$this::checked,null,$store_id);
        //$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
         
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        $storeTable = new StoreTable($this->getDbAdapter());
        $stores = $storeTable->fetchAll();
        
        $customerTable = new CustomerTable($this->getDbAdapter());
        $nicks = $customerTable->fetchAll();
        
        $view =  new ViewModel(array(
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
                'stores'    => $stores,
                'nicks'     => $nicks
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
    }
    
    public function ordercheckedAction(){
        $request = $this->getRequest();
        
        if ($request->isXmlHttpRequest()){
            
            $note = $request->getPost('note',null);
            $id = $this->params()->fromRoute('id',0);
            
            $orderTable = new CustomerOrderTable($this->getDbAdapter());
            $order = $orderTable->getOrderById($id);
            if ($order){
                
               $admin = $this->UserAuthPlugin()->getNick();
                
               $order->setNote($note);
               $order->setStatus($this::checked);
               $order->setApprovedby($admin);
               $order->setApproveddate(date('Y-m-d H:i:s'));
               $orderTable->saveOrder($order);
               
               $errmsg = '';
               $view = new JsonModel(array(
               		'success'   => 1,
               		'errmsg' => $errmsg,
               ));
                
               return $view;
            }
            
            $errmsg = 'Không tìm thấy order';
            $view = new JsonModel(array(
            		'success'   => 0,
            		'errmsg' => $errmsg,
            ));
             
            return $view;
            
        }else{
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function customerordersAction(){
        
        $request = $this->getRequest();
        
        $updatenote = $request->getPost('updatenote', null);
        
        if ($updatenote && $request->isXmlHttpRequest()){
            $orderid = $request->getPost('orderid',null);
            $note = $request->getPost('note',null);
            $customerOrderTable = new CustomerOrderTable($this->getDbAdapter());
            
            $order = $customerOrderTable->getOrderById($orderid);
            
            $order->setNote($note);
            
            $customerOrderTable->saveOrder($order);
            
            return new JsonModel(array(
            	'success'=>1
            ));
        }
        
        // get current page from url or posted data
        if ($this->params()->fromRoute('page') != null){
        	$page = (int)$this->params()->fromRoute('page');
        }elseif($this->getRequest()->getPost('page') != null){
        	$page  = (int)$this->getRequest()->getPost('page');
        }else{
        	$page = 1;
        }
        
        // get item per page from url or posted data
        if ($this->params()->fromRoute('row') != null){
        	$itemsPerPage = (int)$this->params()->fromRoute('row');
        }elseif($this->getRequest()->getPost('row') != null){
        	$itemsPerPage  = (int)$this->getRequest()->getPost('row');
        }else{
        	// default value is 15
        	$itemsPerPage = 15;
        }
        
        $nick = $request->getPost('nick',null);
        $store_id = $request->getPost('store',null);
        
        $orderTable = new CustomerOrderTable($this->getDbAdapter());
        $orders = $orderTable->getCustomerRequests($nick,$this::waiting,'ASC',$store_id);
        
         
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
         
        $storeTable = new StoreTable($this->getDbAdapter());
        $stores = $storeTable->fetchAll();
        
        $customerTable = new CustomerTable($this->getDbAdapter());
        $nicks = $customerTable->fetchAll();
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
         
        $view =  new ViewModel(array(        		
        		'paginator' => $paginator,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
                'stores'    => $stores,
                'nicks'     => $nicks
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
}
