<?php
namespace Cept\User;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{
    public function registerAutoloaders() {
    }
    
    public function registerServices($di) {
        //Registering the view component
        if ($di->get('view')) {
            $view = $di->get('view');
        } else {
            $view = new \Phalcon\Mvc\View();
        }
        $view->setViewsDir(realpath(__DIR__.'/..//view/'));
    }
}
