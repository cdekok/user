<?php
namespace Cept\User\Controller;

class UserController extends \Phalcon\Mvc\Controller {
    
    /**
     * Login user
     */
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
                    return $this->response->redirect($this->url->get(['for' => 'user-profile']));
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
     * User profile only logged in users can see this
     */
    public function profileAction() {
        if (!$this->getIdentity()->isLoggedIn()) {
            $this->flashSession->error('You have to be logged in first');           
            return $this->response->redirect($this->url->get(['for' => 'user-login']));
        }
    }
        
    /**
     * User profile only logged in users can see this
     */
    public function logoutAction() {       
        $this->getIdentity()->logout();
        $this->flashSession->error('You are logged out');        
        return $this->response->redirect($this->url->get(['for' => 'user-login']));
    }

    /**
     * @return \Cept\User\Auth\Auth
     */
    protected function getAuth() {
        return $this->getDI()->get('\Cept\User\Auth\Auth');
    }
    
    /**
     * @return \Cept\User\Auth\Identity
     */
    protected function getIdentity() {
        return $this->getDI()->get('\Cept\User\Auth\Identity');
    }
        
    /**
     * @return \Cept\User\Model\UserRepo
     */
    protected function getUserRepo() {
        return $this->getDI()->get('\Cept\User\Model\UserRepo');
    }
    
    /**
     * @return \Cept\User\Model\RoleRepo
     */
    protected function getRoleRepo() {
        return $this->getDI()->get('\Cept\User\Model\RoleRepo');
    }
}