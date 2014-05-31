<?php
namespace Cept\User\Model;

class RoleRepoFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {
    
    /**
     * Create user repo
     * 
     * @return \Cept\User\Model\UserRepo
     */
    public function createService() {
        $db = $this->getDI()->get('db');
        return $repo = new \Cept\User\Model\RoleRepo($db);
    }
}