<?php
namespace Cept\User\Validator;

class UsernameEmail extends \Phalcon\Validation\Validator implements \Phalcon\Validation\ValidatorInterface {
    
    /**
    * Executes the validation
    *
    * @param \Phalcon\Validator $validator
    * @param string $attribute
    * @return boolean
    */
    public function validate($validator, $attribute) {
        $value = $validator->getValue($attribute);
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return (bool)preg_match('^[a-z0-9-]+$', $value);
    }
}
    