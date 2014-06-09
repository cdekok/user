<?php
namespace Cept\User\Model;

class User {
    
    use \Phapp\Hydrate\HydrateTrait;
    
    /**
     * Username
     * @var string
     */
    protected $username;
    
    /**
     * User email
     * @var string
     */
    protected $email;
    
    /**
     * User status
     * @var int
     */
    protected $status;
    
    /**
     * User profile
     * @var mixed
     */
    protected $profile;
    
    /**
     * User password (hashed)
     * @var string
     */
    protected $password;
            
    /**
     * Creatd date time
     * @var string
     */
    protected $created;
    
    /**
     * Modified date time
     * @var string
     */
    protected $modified;

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function setModified($modified) {
        $this->modified = $modified;
        return $this;
    }              
    
    public function getProfile() {
        return $this->profile;
    }

    public function setProfile($profile) {
        $this->profile = $profile;
        return $this;
    }
}
