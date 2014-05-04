<?php
namespace Cept\User\Model;

class User {
    
    /**
     * User id
     * @var string
     */
    protected $id;
    
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
     * User password (hashed)
     * @var string
     */
    protected $password;
    
    /**
     * Slug based on username
     * @var string
     */
    protected $slug;
    
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
    
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
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

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function setModified($modified) {
        $this->modified = $modified;
        return $this;
    }
    
    /**
     * Hydrate user object
     * 
     * @param array $data
     * @return \Cept\User\Model\User
     */
    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $def = 'set'.ucfirst($key);
            $this->{$def}($value);
        }
        return $this;
    }            
}
