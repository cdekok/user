<?php
namespace Cept\User\Auth;

class IdentityFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {
    
    /**
     * @var \Cept\User\Auth\Identity
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
            $session = new \Phalcon\Session\Bag('identity');        
            $this->cache = new \Cept\User\Auth\Identity($repo, $session);
        }
        return $this->cache;
    }
}