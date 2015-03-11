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
use Vhdang\Model\QuestionTable;
use Vhdang\Model\AddressTable;

class IndexController extends AbstractActionController
{
    
    protected $_dbAdapter;
    
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
     
       if ($this->UserAuthPlugin()->isLogin() && (!$this->UserAuthPlugin()->isAdmin())){
           $nick = $this->UserAuthPlugin()->getNick();
           $questionTable = new QuestionTable($this->getDbAdapter());
           
           $totalUnread = $questionTable->getTotalUnread($nick);

           $addressTable = new AddressTable($this->getDbAdapter());
           
           
           
           $address = $addressTable->getCurrentAddress($nick);
           $outofdate = (floor((strtotime(date('Y-m-d')) - strtotime($address->getDatecreated()))/(60*60*24)) > 20);
           
           $updateAddress = ($address->getId() == null || $outofdate);
           
       }else{
           $totalUnread = 0;
           $updateAddress=false;
       }
       
       
        return new ViewModel(array(
        	'totalUnread' => $totalUnread,
            'updateAddress' => $updateAddress
        ));
    }
}
