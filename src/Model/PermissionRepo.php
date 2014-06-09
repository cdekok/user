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
}
