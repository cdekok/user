<?php
namespace Cept\User\Model;

class UserRepo {
    
    /**
     * Database connection
     * @var \Doctrine\DBAL\Driver\Connection
     */
    protected $db;
    
    /**
     * User repository
     * @param \Doctrine\Connection $db
     */
    public function __construct(\Doctrine\DBAL\Driver\Connection $db) {
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
}
