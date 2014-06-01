<?php
namespace Cept\User\Auth;

class Auth implements AuthInterface {
    
    /**
     * @var \Cept\User\Model\UserRepo
     */
    protected $userRepo;
    
    /**
     * @var \Cept\User\Model\Identity
     */
    protected $identity;
    
    /**
     * Error messages
     * 
     * @var array
     */
    protected $messages = [];

    /**
     * Message templates
     * 
     * @var array
     */
    protected $messageTemplate = [
        'user_not_found' => 'User not found',
        'password_incorrect' => 'Password does not match'
    ];
    
    /**
     * 
     * @param \Cept\User\Model\UserRepo $userRepo
     */
    public function __construct(\Cept\User\Model\UserRepo $userRepo, \Cept\User\Auth\Identity $identity) {
        $this->userRepo = $userRepo;
        $this->identity = $identity;
    }
    
    /**
     * Authenticate user
     * 
     * @param string $usernameOrEmail
     * @param string $password
     * @return boolean
     */
    public function authenticate($usernameOrEmail, $password) {
        $user = $this->userRepo->getByUsernameOrEmail($usernameOrEmail, \Cept\User\Model\UserRepo::STATUS_ACTIVE);
        if (!$user) {
            $this->messages[] = $this->messageTemplate['user_not_found'];      
            return false;
        }        
        if (!$this->userRepo->passwordVerify($password, $user->getPassword())) {
            $this->messages[] = $this->messageTemplate['password_incorrect'];
            return false;
        }
        $this->identity->startSession($user);        
        return true;
    }
    
    /**
     * Get messages after authenticate
     * 
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }
}