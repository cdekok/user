<?php
namespace Cept\User;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{
    public function registerAutoloaders() {
    }
    
    public function registerServices($di) {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace(__NAMESPACE__."\Controller");
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            
            $view->setViewsDir(realpath(__DIR__.'/..//view/'));
            return $view;
        });
    }
}
