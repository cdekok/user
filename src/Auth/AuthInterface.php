<?php
namespace Cept\User\Auth;

/**
 *
 * @author chris
 */
interface AuthInterface {
    
    /**
     * Authenticate user
     * 
     * @param string $usernameOrEmail
     * @param string $password
     * @return boolean
     */
    public function authenticate($usernameOrEmail, $password);
    
    /**
     * Get messages if user could not be authenticated
     * 
     * @return array
     */
    public function getMessages();
}
