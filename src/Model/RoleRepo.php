<?php
namespace Cept\User\Model;

class RoleRepo {
    
    /**
     * Database connection
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    
    /**
     * Tablename
     * @var string
     */
    protected $tableName = 'role';
    
    /**
     * Permission link tablename
     * @var string
     */
    protected $linkPermissionTableName = 'role_has_permission';
    
    /**
     * Role link tablename
     * @var string
     */
    protected $linkRoleTableName = 'user_has_role';
    
    /**
     * User repository
     * @param \Doctrine\Connection $db
     */
    public function __construct(\Doctrine\DBAL\Connection $db) {
        $this->db = $db;
    }
    
    /**
     * Create role
     * 
     * @param array $role
     * @return \Cept\User\Model\Role
     * @throws Exception\InvalidArgumentException
     * @throws Exception\CreateException
     */
    public function create(array $role) {        
        if (!array_key_exists('title', $role)) {
            throw new Exception\InvalidArgumentException('Missing required field: '.$field);
        }
        if (!isset($role['description'])) {
            $role['description'] = null;        
        }        
        $role['created'] = new \DateTime;        
        
        if (!$this->db->insert(
                $this->getIdentifier(), 
                $role,
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    'datetime',
                ]
            )) {
            throw new Exception\CreateException('Could not create role');
        }
        $model = new Role();
        $model->hydrate($role);
        return $model;
    }

    /**
     * Get list of all role names
     * 
     * @return \Cept\User\Model\RoleResult
     */
    public function fetchAll() {
        $identifier = $this->getIdentifier();
        $select = $this->db->createQueryBuilder()->select('*')->from($identifier, 'r');
        return $this->getResult($select);
    }
    
    /**
     * Get role by title
     * 
     * @param string $title
     * @return \Cept\User\Model\Role
     */
    public function getByTitle($title) {
        $select = $this->db->createQueryBuilder();
        $select->select('*')
                ->from($this->getIdentifier(), 'r')
                ->where('r.title = '.$select->createNamedParameter($title));               
        $stmt = $this->db->executeQuery($select, $select->getParameters());
        $row =  $stmt->fetch();
        if (!$row) {
            return;
        }
        $role = new Role();
        $role->hydrate($row);
        return $role;
    }

    /**
     * Get list of all role names
     * 
     * @return array
     */
    public function listTitle() {
        $roles = $this->fetchAll();
        $return = [];
        foreach ($roles as $role) {
            $return[] = $role->getTitle();
        }
        return $return;
    }   
    
    /**
     * Add user to role
     * 
     * @param \Cept\User\Model\User $user
     * @param \Cept\User\Model\Role $role
     * @return boolean
     */
    public function addUserToRole(User $user, Role $role) {
        $table = $this->getUserRoleIdentifier();
        return $this->db->insert($table, [
            'user_username' => $user->getUsername(),
            'role_title' => $role->getTitle()
        ]);
    }

    /**
     * Retrieve list of users
     * 
     * @param \Cept\User\Model\User $user
     */
    public function userHasRoles(User $user) {
        $select = $this->db->createQueryBuilder();
        $select->select('r.*')
                ->from($this->getIdentifier(), 'r')
                ->innerJoin('r', 'user_has_role', 'uhr', 'r.title = uhr.role_title')
                ->where('uhr.user_username = '.$select->createNamedParameter($user->getUsername()));        
        return $this->getResult($select);
    }
    
    /**
     * Get roles permission
     * 
     * @return \Phapp\Db\Result\ResultSet
     */
    public function fetchAllRoleHasPermission() {
        $select = $this->db->createQueryBuilder();
        $select->select('*')
                ->from($this->linkPermissionTableName, 'rhp');
        return new \Phapp\Db\Result\ResultSet($select);
    }

    /**
     * Get result
     * 
     * @param \Doctrine\DBAL\Query\QueryBuilder $qb
     * @return \Cept\User\Model\RoleResult
     */
    protected function getResult(\Doctrine\DBAL\Query\QueryBuilder $qb) {
        $hydrator = new Role();
        return new RoleResult($qb, $hydrator);
    }

    /**
     * Get db identifier
     * 
     * @return string
     */
    protected function getIdentifier() {
        return $this->db->quoteIdentifier($this->tableName);
    }
    
    /**
     * Get user has role link table identifier
     * 
     * @return string
     */
    protected function getUserRoleIdentifier() {
        return $this->db->quoteIdentifier($this->linkRoleTableName);
    }
}