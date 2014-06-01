<?php
namespace Cept\User\Cli;

class UserAdd extends \Phapp\Cli\Command {
    
    public function configure() {
        $this->setName('user:add')
                ->setDescription('Add a new user');
    }
    
    /**
     * Allow to create new users in the database
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {        
        $repo = $this->getUserRepo();
        
        /* @var $dialog \Symfony\Component\Console\Helper\DialogHelper */
        $dialog = $this->getHelperSet()->get('dialog');        
                
        $user = [];
        $user['username'] = $dialog->ask($output, 'Username: ');
        $user['email'] = $dialog->ask($output, 'Email: ');
        $user['password'] = $dialog->ask($output, 'Password: ');
        $user['status'] = \Cept\User\Model\UserRepo::STATUS_ACTIVE;
        
        $userModel = $repo->create($user);        
        $output->writeln('Added user');
        
        $roles = $this->getRoleRepo()->listTitle();
        $chosenRole = $dialog->select($output, 'Add role to user:', $roles);
        $role = $this->getRoleRepo()->getByTitle($roles[$chosenRole]);
        if ($this->getRoleRepo()->addUserToRole($userModel, $role)) {
            $output->writeln('Added user '.$userModel->getUsername().' to role '.$role->getTitle());
        }
    }
    
    /**
     * Get user repo
     * 
     * @return \Cept\User\Model\UserRepo
     */
    public function getUserRepo() {
        return $this->getDI()->get('\Cept\User\Model\UserRepo');
    }
    
    /**
     * Get role repo
     * 
     * @return \Cept\User\Model\RoleRepo
     */
    public function getRoleRepo() {
        return $this->getDI()->get('\Cept\User\Model\RoleRepo');
    }
}