<?php
namespace Cept\User\Model;

class RoleRepoFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {
    
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
            $db = $this->getDI()->get('db');
            $this->cache = new \Cept\User\Model\RoleRepo($db);
        }
        return $this->cache;
    }
}