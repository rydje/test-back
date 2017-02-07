<?php

class User extends AbstractEntity
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    protected static $_tokenList = [
        'first_name',
    ];

    protected static $_tokenBase = 'user';

    public function __construct($id, $firstname, $lastname, $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function renderFirstName()
    {
        return ucfirst(mb_strtolower($this->firstname));
    }

}
