<?php

class DestinationRepository implements Repository
{
    use SingletonTrait;

    private $country;
    private $conjunction;
    private $computerName;

    public function __construct()
    {
        $this->country = Faker\Factory::create()->country;
        $this->conjunction = 'en';
        $this->computerName = Faker\Factory::create()->slug();
    }

    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Destination(
            $id,
            $this->country,
            $this->conjunction,
            $this->computerName
        );
    }
}
