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
     * @var \Phalcon\Cache\BackendInterface
     */
    protected $cache;

    /**
     * Key stored in cached
     * @var string
     */
    protected $cacheKey = 'acl.cache';

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
            \Cept\User\Model\PermissionRepo $permissionRepo,
            \Phalcon\Cache\BackendInterface $cache = null
        ) {
        $this->adapter = $adapter;
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
        $this->cache = $cache;
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
            if ($this->getAdapter()->isAllowed($role->getTitle(), $resource, $permission)) {
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
        // retrieve acl list from cache
        if ($this->cache) {
            $cache = $this->cache->get($this->cacheKey);
            if ($cache) {
                return $cache;
            }
        }
        // load data in acl
        if (!$this->loaded) {
            $this->loaded = true;
            $this->loadResources();
        }
        // save acl in cache
        if ($this->cache) {
            $this->cache->save($this->cacheKey, $this->adapter);
        }
        return $this->adapter;
    }

    /**
     * Key used for storing the cache
     * @return string
     */
    public function getCacheKey() {
        return $this->cacheKey;
    }

    /**
     * Set the key used to store the acl in the cache
     *
     * @param string $cacheKey
     * @return \Cept\User\Acl\Acl
     */
    public function setCacheKey($cacheKey) {
        $this->cacheKey = $cacheKey;
        return $this;
    }

    /**
     * Destroy the cache
     *
     * @return boolean
     */
    public function destroyCache() {
        return $this->cache->delete($this->cacheKey);
    }

    /**
     * Load roles / permision into the acl adapter
     */
    private function loadResources() {
        $this->adapter->setDefaultAction(\Phalcon\Acl::DENY);
        $roles = $this->roleRepo->fetchAll();
        $roles->setCacheResult(true);
        foreach ($roles as $role) {
            /** @var $role \Cept\User\Model\Role **/
            $aclRole = new \Phalcon\Acl\Role($role->getTitle(), $role->getDescription());
            $childRole = $this->getChildRole($roles, $role);
            if ($childRole) {
                $childRole = $childRole->getTitle();
            }
            $this->adapter->addRole($aclRole, $childRole);
            $resources = $this->permissionRepo->getAllResourcesPermissions();
            foreach ($resources as $resource => $permissions) {
                $aclPermission = new \Phalcon\Acl\Resource($resource);
                $this->adapter->addResource($resource, $permissions);
            }
        }
        // Allow roles
        $rhp = $this->roleRepo->fetchAllRoleHasPermission();
        foreach ($rhp as $rp) {
            $this->adapter->allow(
                $rp['role_title'],
                $rp['permission_resource'],
                $rp['permission_title']
            );
        }
    }

    /**
     * Return child role
     *
     * @param \Phapp\Db\Result\ResultSet $roles
     * @param \Cept\User\Model\Role $role
     * @return \Cept\User\Model\Role
     */
    private function getChildRole(\Phapp\Db\Result\ResultSet $roles, \Cept\User\Model\Role $role) {
        foreach ($roles as $r) {
            if ($r->getParent() === $role->getTitle()) {
                return $r;
            }
        }
    }
}