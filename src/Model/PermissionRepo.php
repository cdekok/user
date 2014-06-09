<?php
namespace Cept\User\Model;

class PermissionRepo {
    
    /**
     * Database connection
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    
    /**
     * Tablename
     * @var string
     */
    protected $tableName = 'permission';
    
    /**
     * Permission repository
     * @param \Doctrine\Connection $db
     */
    public function __construct(\Doctrine\DBAL\Connection $db) {
        $this->db = $db;
    }
    
    /**
     * Create new permission
     * 
     * @param \Cept\User\Model\Permission $permission
     * @return \Cept\User\Model\User
     * @throws Exception\InvalidArgumentException
     * @throws Exception\CreateException
     */
    public function create(Permission $permission) {
        $required = [
            'title',
        ];
        $permissionData = $permission->extract();
        foreach ($required as $field) {
            if (!array_key_exists($field, $permissionData)) {
                throw new Exception\InvalidArgumentException('Missing required field: '.$field);
            }
        }
        $permissionData['created'] = new \DateTime;
        if (!$this->db->insert(
                $this->getIdentifier(), 
                $permission,
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    'datetime',
                ]
            )) {
            throw new Exception\CreateException('Could not create permission');
        }
        return $permission;
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
     * Return an array of all resources and their permission
     * <code>
     * [
     *  'User' => ['create', 'delete'],
     *  'Blog' => ['create']
     * ]
     * </code>
     * 
     * @return array
     */
    public function getAllResourcesPermissions() {
        $permissions = $this->fetchAll();
        $return = [];
        foreach ($permissions as $permission) {
            $return[$permission->getResource()][] = $permission->getTitle();
        }
        return $return;
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
     * Get result
     * 
     * @param \Doctrine\DBAL\Query\QueryBuilder $qb
     * @return \Phapp\Db\Result\ResultSet
     */
    protected function getResult(\Doctrine\DBAL\Query\QueryBuilder $qb) {
        $hydrator = new Permission();
        return new \Phapp\Db\Result\ResultSet($qb, $hydrator);
    }
}
