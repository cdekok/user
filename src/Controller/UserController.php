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
                if ($this->getAuth()->authenticate($post['username'], $post['password'])) {
                    // Redirect to success page  
                    $this->flashSession->success('Logged in');
                } else {                    
                    // Show error message                    
                    $messages = $this->getAuth()->getMessages();
                    foreach ($messages as $msg) {                        
                        $this->flashSession->error($msg);
                    }
                }
            }
        }
        $this->view->setVar('form', $form);
    }
    
    /**
     * @return \Cept\User\Auth\Auth
     */
    protected function getAuth() {
        return $this->getDI()->get('\Cept\User\Auth\Auth');
    }    
}