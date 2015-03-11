<?php

namespace Vhdang\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SyserrorController extends AbstractActionController
{
	public function indexAction()
	{

		return new ViewModel();
	}

	public function denyAction(){
		return new ViewModel();
	}
}
