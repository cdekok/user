<?php
namespace Cept\User\Auth;

class AuthFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {
    
    /**
     * Memory cache
     * @var \Cept\User\Auth\Auth
     */
    protected $cache;
    
    /**
     * Create user repo
     * 
     * @return \Cept\User\Model\UserRepo
     */
    public function createService() {        
        if (!$this->cache) {
            $repo = $this->getDI()->get('\Cept\User\Model\UserRepo');        
            $identity = $this->getDI()->get('\Cept\User\Auth\Identity');        
            $this->cache = new \Cept\User\Auth\Auth($repo, $identity);
        }        
        return $this->cache;
    }
}