<?php
namespace Cept\User\Model;

class UserRepo {
    
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
     * Get user by id
     * 
     * @param string $id
     * @return \Cept\User\Model\Cept\User\Model\User
     */
    public function getById($id) {
        $select = $this->db->createQueryBuilder();
        $select->from('user', 'u')->where('u.id = '.$select->createNamedParameter($id));
        $stmt = $this->db->query($select);
        $row =  $stmt->fetch();
        if (!$row) {
            return;
        }
        $user = new Cept\User\Model\User();
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
        
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['created'] = new \DateTime;        
        
        if (!$this->db->insert(
                $this->getIdentifier(), 
                $user,
                [
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
