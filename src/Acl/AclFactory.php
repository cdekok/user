<?php
namespace Cept\User\Acl;

class AclFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {
    
    /**
     * @var \Cept\User\Model\RoleRepo
     */
    protected $cache;
    
    /**
     * Create user repo
     * 
     * @return \Cept\User\Model\UserRepo
     */
    public function createService() {
        if (!$this->cache) {
            $adapter = new \Phalcon\Acl\Adapter\Memory();
            /** @var $userRepo \Cept\User\Model\RoleRepo **/
            $roles = $this->getDI()->get('\Cept\User\Model\RoleRepo');
            $this->cache = new Acl($adapter, $roles);
        }
        return $this->cache;
    }
}