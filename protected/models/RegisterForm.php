<?php

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user registration form data. It is used by the 'register' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
        public $username;
        public $password;
        public $repeat_password;
        public $email;
        public $name;

        private $_identity;

        /**
         * Declares the validation rules.
         * The rules state that username, password & email are required,
         * and username & email needs to be unique.
         */
        public function rules()
        {
                return array(
                        // username and password are required
                        array('username, password, repeat_password, email, name', 'required'),
                        // make sure username and email are unique
                        array('username, email', 'unique'),
                        // confermation password validation 
                        array('password, repeat_password', 'length', 'min'=>6, 'max'=>40),
                        array('repeat_password', 'compare', 'compareAttribute'=>'password'),
                       
                        //email validaiton
                       array('email','email'), 
                );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
                return array(
                        'username'=>'Username',
                        'password'=>'Password',
                        'repeat_password'=>'confirm password',
                        'email'=>'Email',
                        'name'=>'Name',
                );
        }
}