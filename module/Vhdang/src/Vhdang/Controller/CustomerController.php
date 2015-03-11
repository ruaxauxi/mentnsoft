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
use Zend\Paginator\Paginator;

use Zend\View\Model\ViewModel;
use Vhdang\Model\StoreTable;
use Vhdang\Model\CustomerOrder;
use Vhdang\Model\CustomerOrderTable;

use Vhdang\Utils\String;
use Zend\View\Model\JsonModel;
use Vhdang\Model\AdminOrderDetailsTable;
use Vhdang\Model\Customer;
use Vhdang\Model\CustomerTable;
use Vhdang\Model\TransactionTable;
use Vhdang\Model\Transaction;
use Vhdang\Model\TransferTable;
use Vhdang\Model\OrderDetailsImageTable;
use Vhdang\Model\BalanceTable;
use Vhdang\Model\QuestionTable;
use Vhdang\Model\Question;
use Vhdang\Model\AnswerTable;
use Vhdang\Model\Answer;
use Vhdang\Model\ShipmentTable;


class CustomerController extends AbstractActionController
{
    protected $_dbAdapter;
    const waiting = 'waiting';
    const checked = 'checked';
    
    public function getDbAdapter(){
    	if (!$this->_dbAdapter){
    		$sm = $this->getServiceLocator();
    		$adapter = $sm->get('ZendDbAdapter');
    		$this->_dbAdapter = $adapter;
    	}
    	return $this->_dbAdapter;
    }
    
    
    
    public function indexAction()
    {  
        return new ViewModel();
    }
    
    /**
     * @abstract thong ke cac store dang gom
     * 
     */
    public function storestatAction(){
        
        $request = $this->getRequest();
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
      
         
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
        	// default value is 30
        	$itemsPerPage = 30;
        }
        
        
        $store_id = $request->getPost('store_id',null);
        
        $stats = $storeTable->getStoreStat($store_id);
        
        
      
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($stats));
         
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
                'store_id' => $store_id,
        		'page' => $page,
        		'row'   => $itemsPerPage,
        		'isAjaxRequest' => $request->isXmlHttpRequest(),
        		'errmsg' => $errmsg,
        		 
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
        
        
        
    }
    
    public function viewanswerAction(){
        $request = $this->getRequest();
        $id = $this->params()->fromRoute('id',null);
        
        $nick = $this->UserAuthPlugin()->getNick();
        
        $questionTable = new QuestionTable($this->getDbAdapter());
        $q = $questionTable->getQuestionById($id,$nick);
        
        if (!$q){
            return $this->redirect()->toRoute('customer',array('action'=>'question'));
        }
        
        $q->setRead(1);
        $questionTable->updateRead($q);
        $answerTable = new AnswerTable($this->getDbAdapter());
       
        
        if ( $request->isXmlHttpRequest() &&  $request->isPost() && $request->getPost('btnSubmit')){
            $question_id = $request->getPost('question_id');
            $content = $request->getPost('content');
            
            $content = preg_replace("/[\n]/","<br/>",$content);
            $content = String::removeBBCode($content);
            $content = String::makelink($content);
            
            $answer  = new Answer();
           
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
            return $this->redirect()->toRoute('customer');
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
        $nick = $this->UserAuthPlugin()->getNick();
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
            $question->setNick($nick);
            
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
        
        
        
        $paginator = $questionTable->getAll($nick);
        
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
    	    'page' => $page,
    	    'row'   => $itemsPerPage,
    	    'isAjaxRequest' => $request->isXmlHttpRequest(),
    	));
        $view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    public function loadmoretransAction(){
        $request = $this->getRequest();
        $page = (int) $request->getPost('page',1);
        $limit = 30;
        $offset = $limit * $page;
        if ($request->isXmlHttpRequest()){
            $nick = $this->UserAuthPlugin()->getNick();
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
    
    public function transactionsAction(){
        $nick = $this->UserAuthPlugin()->getNick();
        $customerTable = new CustomerTable($this->getDbAdapter());
        $customer = $customerTable->getUserByNick($nick);
        $limit = 30;
        $offset = 0;
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
             
            $balanceTable = new BalanceTable($this->getDbAdapter());
            $balance = $balanceTable->getBalanceById($customer->getBalance_id());
             $view = new ViewModel(array(
             		'transactions'  => $list,
                    'balance'   => $balance->getCredit()
             ));
             
             return $view;
              
        }else{
            return $this->redirect()->toRoute('home');
        }
        
    }
    
    
    public function vieworderAction(){
    	$nick = $this->UserAuthPlugin()->getNick();
    	$request = $this->getRequest();
    	
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
    	       'paginator' => $paginator,
    	       'shipments' => $shipments,
    	       'shipment_id'   => $shipment_id,
    	       'status'    => $status,
    	       'statuses'    => $statuses,
    			'page' => $page,
    			'row'   => $itemsPerPage,
    			'isAjaxRequest' => $request->isXmlHttpRequest(),
    	));
    	$view->setTerminal($request->isXmlHttpRequest());
    	return $view;
    }
    
    public function orderdeleteAction(){
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
    
    public function orderAction(){
        
        $request = $this->getRequest();
        $orderTable = new CustomerOrderTable($this->getDbAdapter());
        $nick = $this->UserAuthPlugin()->getNick();
        $customerTable = new CustomerTable($this->getDbAdapter());
        $customer = $customerTable->getUserByNick($nick);
        if ($request->isPost()){
            $stores = (array) $request->getPost('stores');
            $descriptions = (array) $request->getPost('description');
            
            for($i = 0;$i < count($stores);$i++){
                if ($stores[$i] && $descriptions[$i]){                    
                    $order = new CustomerOrder();
                    $order->setNick($nick);
                    $description = $descriptions[$i];    
                    
                    $description = $descriptions[$i];
                    $description = preg_replace("/[\n]/","<br/>",$description);
                    $description = String::removeBBCode($description);                    
                    $description = String::makelink($description);                    
                    $order->setDescription($description);
                    $order->setStatus($this::waiting);
                    $order->setStore_id($stores[$i]);
                    $orderTable->saveOrder($order);
                }
            }            
        }
        
        $storeTable = new StoreTable($this->getDbAdapter());
        
        
        $stores = $storeTable->fetchAll();
        
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
         
        $paginator = $orderTable->customerListAll($nick,null,"DESC");
         
       // $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($orders));
         
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
         
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
         
        $view =  new ViewModel(array(
        	'stores'   => $stores,            
            'customer'  => $customer,
            'paginator' => $paginator,
            'page' => $page,
            'row'   => $itemsPerPage,
            'isAjaxRequest' => $request->isXmlHttpRequest(),
        ));
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
    }
}
