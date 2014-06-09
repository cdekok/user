<?php
namespace Cept\User\Auth;

class Identity {
    
    /**
     * @var \Cept\User\Model\User
     */
    protected $user;
    
    /**
     *
     * @var \Cept\User\Model\UserRepo
     */
    protected $userRepo;

    /**
     *
     * @var string
     */
    protected $username;

    /**
     * @var \Phalcon\Session\Bag
     */
    protected $session;
    
    public function __construct(\Cept\User\Model\UserRepo $userRepo, \Phalcon\Session\BagInterface $session) {
        $this->userRepo = $userRepo;
        $this->session = $session;
    }

    /**
     * Start identity session
     * 
     * @param \Cept\User\Model\User $user
     */
    public function startSession(\Cept\User\Model\User $user) {
        $this->user = $user;
        $this->getSession()->set('username', $user->getUsername());
    }
    
    /**
     * Get user
     * 
     * @return \Cept\User\Model\User
     */
    public function getUser() {
        if (!$this->user) {
            $this->user = $this->userRepo->getByUsernameOrEmail($this->getSession()->get('username'));
        }
        return $this->user;
    }
    
    /**
     * Check if the user is logged in
     * 
     * @return boolean
     */
    public function isLoggedIn() {
        return (boolean)$this->getSession()->get('username');
    }

    /**
     * Log out user
     */
    public function logout() {
        $this->getSession()->destroy();
    }

    /**
     * Get session
     * @return \Phalcon\Session\Bag
     */
    protected function getSession() {
        return $this->session;
    }
}