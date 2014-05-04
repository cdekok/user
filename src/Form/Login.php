<?php
namespace Cept\User\Form;

/**
 * User login form
 */
class Login extends \Phapp\Form\Form {
    
    public function initialize() {
        $username = new \Phalcon\Forms\Element\Text('username');
        $username->addValidators([
            new \Phalcon\Validation\Validator\PresenceOf(
                ['message' => 'Username is required']
            )
        ]);
        $this->add($username);
        
        $password = new \Phalcon\Forms\Element\Password('password');
        $password->addValidators([
            new \Phalcon\Validation\Validator\PresenceOf([
                'message' => 'Password is required'
            ]),
            new \Phalcon\Mvc\Model\Validator\StringLength([
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            ]),            
        ]);
        $this->add($password);
        
        $csrf = new \Phalcon\Forms\Element\Hidden('csrf');
        $csrf->addValidators([
            new \Phalcon\Validation\Validator\Identical([
                'value' => $this->security->getSessionToken(),
                'message'=> 'CSRF Validation failed'
            ])
        ]);
        $this->add($csrf);
        
        $submit = new \Phalcon\Forms\Element\Submit('submit', ['class' => 'btn']);
        $this->add($submit);
    }
}
