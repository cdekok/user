<?php
namespace Cept\User\Model;

class UserRepo {
    
    /**
     * User has been banned and is not allowed to log in
     */
    const STATUS_BANNED = -1;
    
    /**
     * User is registered but account is not confirmed
     */
    const STATUS_UNCONFIRMED = 0;
    
    /**
     * User is active and is allowed to login
     */
    const STATUS_ACTIVE = 1;
    
    /**
     * Database connection
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    
    /**
     * Tablename
     * @var string
     */
    protected $tableName = 'rbac_user';

    /**
     * Password minumum amount of characters
     * @var integer
     */
    protected $passwordMinChars = 6;
    
    /**
     * User repository
     * @param \Doctrine\Connection $db
     */
    public function __construct(\Doctrine\DBAL\Connection $db) {
        $this->db = $db;
    }
    
    /**
     * Get user by username or email
     * 
     * @param string $usernameOrEmail
     * @return \Cept\User\Model\User
     */
    public function getByUsernameOrEmail($usernameOrEmail, $status = null) {
        $select = $this->db->createQueryBuilder();
        $select->select('*')
            ->from($this->getIdentifier(), 'u')
            ->where('u.username = '.$select->createNamedParameter($usernameOrEmail))
            ->orWhere('u.email = '.$select->createNamedParameter($usernameOrEmail));
        if ($status !== null) {            
            $select->andWhere('u.status = '.$select->createNamedParameter($status, \PDO::PARAM_INT));
        }
        $stmt = $select->execute();
        $row =  $stmt->fetch();        
        if (!$row) {
            return;
        }
        $user = new User();
        $user->hydrate($row);
        return $user;
    }
    
    /**
     * Create new user
     * password will be hashed automatically
     * 
     * @param array $user
     * @return \Cept\User\Model\User
     * @throws Exception\InvalidArgumentException
     * @throws Exception\CreateException
     */
    public function create(array $user) {
        $required = [
            'username',
            'email',
            'password'
        ];
        foreach ($required as $field) {
            if (!array_key_exists($field, $user)) {
                throw new Exception\InvalidArgumentException('Missing required field: '.$field);
            }
        }        
        if (mb_strlen($user['password']) < $this->passwordMinChars) {
            throw new Exception\InvalidArgumentException('Password must contain atleast '.$this->passwordMinChars.' characters');
        }        
        
        $user['password'] = $this->passwordHash($user['password']);
        if (!isset($user['status'])) {
            $user['status'] = self::STATUS_UNCONFIRMED;
        }        
        $user['created'] = new \DateTime;        
        
        if (!$this->db->insert(
                $this->getIdentifier(), 
                $user,
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    'datetime',
                ]
            )) {
            throw new Exception\CreateException('Could not create user');
        }
        $model = new \Cept\User\Model\User();
        $model->hydrate($user);
        return $model;
    }    

    /**
     * Get minimum amount of characters for password
     * 
     * @return integer
     */
    public function getPasswordMinChars() {
        return $this->passwordMinChars;
    }

    /**
     * Hash the password to store in the database
     * 
     * @param string $password
     * @return string
     */
    public function passwordHash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Check if password matches the hash
     * 
     * @param string $password
     * @param string $hash
     */
    public function passwordVerify($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Set minimum amount of characters for password
     * 
     * @param integer $passwordMinChars
     * @return \Cept\User\Model\UserRepo
     */
    public function setPasswordMinChars($passwordMinChars) {
        $this->passwordMinChars = $passwordMinChars;
        return $this;
    }    
    
    /**
     * Get escaped table name
     * 
     * @return string
     */
    protected function getIdentifier() {
        return $this->db->quoteIdentifier($this->tableName);;
    }
}
