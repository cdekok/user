<?php
namespace Cept\User\Controller;

class UserController extends \Phalcon\Mvc\Controller {
    
    public function loginAction()
    {        
        $form = new \Cept\User\Form\Login();
        
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            if ($form->isValid($post)) {
                // Login
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                
            }
        }
        $this->view->setVar('form', $form);
    }
}