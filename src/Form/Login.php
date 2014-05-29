<?php
namespace Cept\User\Form;

/**
 * User login form
 */
class Login extends \Phapp\Form\Form {
    
    /**
     * Add elements and validators to the form
     */
    public function initialize() {
        $username = new \Phalcon\Forms\Element\Text('username');
        $username->setLabel('username or email');
        $username->addValidators([
            new \Phalcon\Validation\Validator\PresenceOf(
                ['message' => 'username / email is required']
            ),
            new \Cept\User\Validator\UsernameEmail([
                'message' => 'Invalid email or username must be lowercase and can only contain alpha numeric characters and -'
            ]),
            new \Phalcon\Validation\Validator\StringLength([
                'min' => 3,
                'messageMinimum' => 'Username is too short, minimum 3 characters',
                'max' => 30,
                'messageMaximum' => 'Username is too long, maximum 30 characters',
            ]),
        ]);
        $this->add($username);
        
        $password = new \Phalcon\Forms\Element\Password('password');        
        $password->setLabel('password');
        $password->addValidators([
            new \Phalcon\Validation\Validator\PresenceOf([
                'message' => 'Password is required'
            ]),
            new \Phalcon\Validation\Validator\StringLength([
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters please use a passphrase'
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
