<?php

namespace model;


// TODO: Implement menu


class MenuItem extends BLLBase {

// Init variables
    private $href, $name;

    private static $constraints = [
        'name' => ['maxLength' => 30],
        'href' => ['maxLength' => 2000, 'regex' => '@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS']
    ];

// Constructor
    public function __construct($name, $href) {

        $this->SetName($name);
        $this->SetHref($href);
    }


// Getters and Setters

    # Name
    public function SetName($value) {

        // Check if name is valid
        if ($this->IsValidString("Name", $value, self::$constraints["name"])) {

            // Set name
            $this->name = trim($value);

            return true;
        }

        return false;
    }

    public function GetName() {
        return $this->name;
    }

    # href
    public function SetHref($value) {

        // Check if href is valid
        if ($this->IsValidString("href", $value, self::$constraints["href"])) {

            // Set href
            $this->href = trim($value);

            return true;
        }

        return false;
    }

    public function GetHref() {
        return $this->href;
    }

// Private methods

}