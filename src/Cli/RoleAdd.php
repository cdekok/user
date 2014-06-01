<?php
namespace Cept\User\Cli;

class RoleAdd extends \Phapp\Cli\Command {
    
    public function configure() {
        $this->setName('role:add')
                ->setDescription('Add a new role');
    }
    
    /**
     * Add new role
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {        
        $repo = $this->getRoleRepo();
        
        /* @var $dialog \Symfony\Component\Console\Helper\DialogHelper */
        $dialog = $this->getHelperSet()->get('dialog');        
                
        $role = [];
        $role['title'] = $dialog->ask($output, 'Role: ');
        $role['description'] = $dialog->ask($output, 'Description: ');        
        $userModel = $repo->create($role);        
        $output->writeln('Added role');        
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