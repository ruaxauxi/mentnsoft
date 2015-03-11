<?php

namespace Vhdang\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
use Zend\Paginator\Paginator;

use Vhdang\Model\Customer;
 
use Vhdang\Model\AdminTable;
use Vhdang\Model\CustomerTable;
use Vhdang\Model\BalanceTable;
use Vhdang\Model\Balance;
use Zend\View\Model\JsonModel;
use Vhdang\Model\CityTable;
use Vhdang\Model\AddressTable;
use Vhdang\Model\Address;
use Vhdang\Model\TransferTable;

use Vhdang\Model\XRatesTable;
use Vhdang\Model\Transfer;
use Vhdang\Model\TransactionTable;
use Vhdang\Model\Transaction;
use Vhdang\Model\ShippingMethodTable;
 

class UserController extends AbstractActionController {
    
    protected $_userTable;
    protected $_dbAdapter;
   
    const waiting = 'waiting';
    const received = 'received';
    const add = '+';
    const minus = '-';
    const service = 7;
    const shipping = 11;
    
    public function getDbAdapter(){
        if (!$this->_dbAdapter){
            $sm = $this->getServiceLocator();
            $adapter = $sm->get('ZendDbAdapter');
            $this->_dbAdapter = $adapter;
        }
        return $this->_dbAdapter;
    }
    
    public function getUserTable(){
        if (!$this->_userTable){
            $this->_userTable = new \Vhdang\Model\CustomerTable($this->getDbAdapter()) ;
        }
        return $this->_userTable;
    }
    
    
   
    
    // admin changes customer's password
    public function changepasswordAction(){
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()){
            $nick = $request->getPost('nick');
            $newpasswd = $request->getPost('passwd');
            $customerTable = new CustomerTable($this->getDbAdapter());
            $customer = $customerTable->getUserById($nick);
            $customer->setPassword($newpasswd);
            
            $success = $customerTable->saveUser($customer);
            $view = new JsonModel(array(
                'success'   => $success
            ));
            
            return $view;
            
        } else{
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function changeserviceAction(){
    	$request = $this->getRequest();
    	if ($request->isXmlHttpRequest()){
    		$nick = $request->getPost('nick');
    		$newservice= $request->getPost('service');
    		$customerTable = new CustomerTable($this->getDbAdapter());
    		$customer = $customerTable->getUserById($nick);
    		$customer->setService($newservice);
    
    		$success = $customerTable->saveUser($customer);
    		$view = new JsonModel(array(
    				'success'   => $success
    		));
    
    		return $view;
    
    	} else{
    		return $this->redirect()->toRoute('home');
    	}
    }
    
    public function changeshippingAction(){
    	$request = $this->getRequest();
    	if ($request->isXmlHttpRequest()){
    		$nick = $request->getPost('nick');
    		$newshipping= $request->getPost('shipping');
    		$customerTable = new CustomerTable($this->getDbAdapter());
    		$customer = $customerTable->getUserById($nick);
    		$customer->setShipping($newshipping);
    
    		$success = $customerTable->saveUser($customer);
    		$view = new JsonModel(array(
    				'success'   => $success
    		));
    
    		return $view;
    
    	} else{
    		return $this->redirect()->toRoute('home');
    	}
    }
    
    // customers change password themselves;
    public function changepasswdAction(){
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()){
        	$nick = $request->getPost('nick');
        	$oldpass = $request->getPost('oldpass');
        	$newpasswd = $request->getPost('passwd');
        	$customerTable = new CustomerTable($this->getDbAdapter());
        	$valid = $customerTable->checkPassword($nick, $oldpass);
        	$msg = "";
        	if ($valid){
        	    $customer = $customerTable->getUserById($nick);
        	    $customer->setPassword($newpasswd);
        	    $success = $customerTable->saveUser($customer);
        	    $msg = "Đã đổi mật khẩu mới.";
        	}else{
        	    $success = 0;
        	    $msg = "Mật khẩu cũ không đúng.";        	    
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
    
    
    public function transferAction(){
        
        $request = $this->getRequest();
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
        
        if ($request->isPost() && $request->getPost('nick')){
            $data = (array) $request->getPost();
            
            $transfer = new Transfer();
            $transfer->setData($data);
            $transfer->setNick($this->UserAuthPlugin()->getNick());
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
        
        $transfers = $transferTable->getTransferByNick($this->UserAuthPlugin()->getNick());
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($transfers));
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        $user = $this->getUserTable()->getUserById($this->UserAuthPlugin()->getNick());
        $balanceTable = new BalanceTable($this->getDbAdapter());
        $balance = $balanceTable->getBalanceById($user->getBalance_id());
                     
        $view = new ViewModel(array(
            'errmsg'    => $errmsg,
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
    
    public function addressAction(){
        
        $cityTable = new CityTable($this->getDbAdapter());
        $city = $cityTable->fetchAll();
        
        $addressTable = new AddressTable($this->getDbAdapter());
        $address = $addressTable->getCurrentAddress($this->UserAuthPlugin()->getNick());
        
        $shippingMethodTable = new ShippingMethodTable($this->getDbAdapter());
        $shippingMethods = $shippingMethodTable->fetchAll();
      
        $request = $this->getRequest();
        $msgErr = "";
        $msgSuccess = "";
        if ($request->isPost()){
            $data = (array) $request->getPost();
            $address = new Address();
            $address->setData($data);
            $success = $addressTable->addAddress($address);
            if ($success){
                $msgSuccess = "Đã cập nhật";
            }else{
                $msgErr = "Không thể cập nhật địa chỉ, vui lòng thử lại.";
            }
        }
        
        return new ViewModel(array(
            'shippingMethods' => $shippingMethods,
        	'city' => $city,
            'address'   => $address,
            'msgErr'   => $msgErr,
            'msgSuccess' => $msgSuccess 
        ));
    }
	

    public function indexAction(){
        return new ViewModel();
    }
    
    public function customerAction(){
        $request = $this->getRequest();
        $userlist = $this->getUserTable()->fetchAll();
        $nick = $request->getPost('nicks',null);
        $users = array();
        if ($nick){
            $item = $this->getUserTable()->getUserById($nick);
            $users[] = $item;
        }else{
            $users = $this->getUserTable()->fetchAll();
        }
        $user = new \stdClass();
        $errmsg = '';
        $user->username =$request->getPost('username') ;
        $user->password = $request->getPost('password') ;
        $user->usertype = $request->getPost('usertype'); ;
        
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
        
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($users));        
        
        $i = $itemsPerPage;
        // show all
        if ($itemsPerPage == -1){
        	$i = $paginator->getTotalItemCount();
        }
        
        $paginator->setCurrentPageNumber($page)
        ->setItemCountPerPage($i)
        ->setPageRange(7);
        
        
        $view = new ViewModel(array(
        		'user'  => $user,
                'userlist'  => $userlist,
        		'errmsg'    => $errmsg,
                'page' => $page,
                'row'   => $itemsPerPage,
                'paginator' => $paginator,
                'isAjaxRequest' => $request->isXmlHttpRequest()
        ));
        
        
        $view->setTerminal($request->isXmlHttpRequest());
        return $view;
       
    }
  
    public function customeraddAction(){
        $errmsg = '';
        $request = $this->getRequest();
        
        if ($request->isXmlHttpRequest() && $request->isPost()){
           
            $username = $request->getPost('username');
            
            $username = preg_replace('/[^a-zA-Z0-9]/s', '', $username);
            
            $password = $request->getPost('password');
                 
            // valid  
            if (strlen($username) <= 2 || strlen($username) > 30){
                $errmsg = 'Tên đăng nhập quá ngắn hoặc quá dài.';
            }elseif(strlen($password) <= 2 || strlen($password) > 30){
                $errmsg = 'Mật khẩu đăng nhập quá ngắn hoặc quá dài.';
            }else{  

                
                $valid = false;                
                $adminTable = new AdminTable($this->getDbAdapter());
                $customerTable = new CustomerTable($this->getDbAdapter());
                $valid = ($adminTable->isValidNick($username) && $customerTable->isValidNick($username));
                
                if (!$valid){
                  $errmsg = 'Tên đăng nhập đã được sử dụng';
                   
                   return new JsonModel(array(
                   		'success'   => 0,
                   		'errormsg'  => $errmsg
                   ));
                }else{
                    
                       /*  $admin = new Admin();
                        $admin->setNick($username);
                        $admin->setPassword($password);
                        
                        $adminTable->addUser($admin);
                        return $this->redirect()->toRoute('user', array('action'=> 'add')); */
                     
                        $customer = new Customer();
                        
                        $balanceTable = new BalanceTable($this->getDbAdapter());
                        $balance = new Balance();
                        $balance->setCredit(0);
                        $balance->setId($balanceTable->saveBalance($balance));
                        
                        $customer->setBalance_id($balance->getId());
                        $customer->setNick($username);
                        $customer->setPassword($password);
                        $customer->setLastupdated(date('Y-m-d H:i:s'));
                        $customer->setService($this::service);
                        $customer->setShipping($this::shipping);                        
                        $customerTable->addUser($customer);
                                                
                       return new JsonModel(array(
                       	    'success'   => 1,
                       ));
                    
                }  
            }
            
            return new JsonModel(array(
                'success'   => 0,
                'errormsg'  => $errmsg
            ));
            
        }else{
            return $this->redirect()->toRoute('user', array('action'=> 'customer'));
        }
        
    }
    
    
    public function loginAction(){
        
        if ($this->UserAuthPlugin()->isLogin()){
           return $this->redirect()->toRoute('home');
        }
        
        $request = $this->getRequest();        
        
        $errmsg = null;
        if ($request->isPost()){
            $username = $request->getPost('username',1) ;
            $password = $request->getPost('password','1');
            
            $remember = (int)$request->getPost('rememberme',0);
            if ($username && $password && $this->UserAuthPlugin()->authenticate($username, $password, $remember)){
               return $this->redirect()->toRoute('home');
            }else{
                $errmsg = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        }
        $user = new Customer();
        return new ViewModel(array(
        		'user'  => $user,
            'errmsg'    => $errmsg,
        ));
    }
    
    public function logoutAction(){
        $this->UserAuthPlugin()->clearData();        
        return $this->redirect()->toRoute('home',array('action' => 'index'));
    }
}