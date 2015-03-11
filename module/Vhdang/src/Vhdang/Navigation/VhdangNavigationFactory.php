<?php
namespace Vhdang\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VhdangNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new VhdangNavigation();
        return $navigation->createService($serviceLocator);
    }
}