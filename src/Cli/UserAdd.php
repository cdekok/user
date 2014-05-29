<?php
namespace Cept\User\Cli;

class UserAdd extends \Symfony\Component\Console\Command\Command {
    
    public function configure() {
        $this->setName('user:add')
                ->setDescription('Add a new user');
    }
    
    public function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
        /** @var $dialog \Symfony\Component\Console\Helper\DialogHelper **/        
        $dialog = $this->getHelperSet()->get('dialog');        
        $username = $dialog->ask($output, 'Type username');
        $email = $dialog->ask($output, 'Type email');
        $password = $dialog->ask($output, 'Type password');
        $output->writeln('Add user:'.$username);
    }
}