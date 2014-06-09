<?php
namespace Cept\User\Acl;

class Acl {
    
    /**
     * @var \Phalcon\Acl\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Cept\User\Model\RoleRepo
     */
    protected $roleRepo;

    /**
     * @var \Cept\User\Model\PermissionRepo
     */
    protected $permissionRepo;
    
    /**
     * Check to see if all roles / resources are loaded
     * @var boolean
     */
    private $loaded = false;

    /**
     * ACL Setup
     * 
     * @param \Phalcon\Acl\AdapterInterface $adapter
     * @param \Cept\User\Model\RoleRepo $roleRepo
     * @param \Cept\User\Model\PermissionRepo $permissionRepo
     */
    public function __construct(
            \Phalcon\Acl\AdapterInterface $adapter, 
            \Cept\User\Model\RoleRepo $roleRepo,
            \Cept\User\Model\PermissionRepo $permissionRepo
        ) {
        $this->adapter = $adapter;
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }
    
    /**
     * Check if a user is allowed certain permissions
     * 
     * @param \Cept\User\Model\User $user
     * @param string $resource
     * @param string $permission
     * @return boolean
     */
    public function isAllowed(\Cept\User\Model\User $user, $resource, $permission) {
        $roles = $this->roleRepo->userHasRoles($user);
        foreach ($roles as $role) {
            if ($this->adapter->isAllowed($role->getTitle(), $resource, $permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get acl adapter
     * 
     * @return \Phalcon\Acl\AdapterInterface
     */
    public function getAdapter() {
        if (!$this->loaded) {
            $this->loadResources();
        }
        return $this->adapter;
    }
    
    /**
     * Load roles / permision into the acl adapter
     * 
     * @todo Do some caching & make parent roles work
     */
    private function loadResources() {
        $roles = $this->roleRepo->fetchAll();
        foreach ($roles as $role) {
            /** @var $role \Cept\User\Model\Role **/            
            $this->adapter->addRole($role->getTitle());
            $resources = $this->permissionRepo->getAllResourcesPermissions();
            foreach ($resources as $resource => $permissions) {
                $aclPermission = new \Phalcon\Acl\Resource($resource);
                $this->adapter->addResource($resource, $permissions);
            }
        }
    }
}