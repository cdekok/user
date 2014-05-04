<?php
namespace Cept\User\Controller;

class UserController extends \Phalcon\Mvc\Controller {
    
    public function loginAction()
    {        
        $form = new \Cept\User\Form\Login();
        
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                // Login
            }
        }
        $this->view->setVar('form', $form);
    }
}