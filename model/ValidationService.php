<?php

namespace model;


class ValidationService {

// Init variables
    private static $isValid = true;
    private static $validationErrorsArray = [];

// Constructor

// Getters and Setters

// Private Methods

// Public Methods

    public static function IsValid() {
        return self::$isValid;
    }

    public static function AddValidationError($errorMessage) {
        self::$isValid = false;

        self::$validationErrorsArray[] = $errorMessage;
    }

    public static function GetValidationErrors() {

        // It should not be valid
        assert(self::$isValid == false);

        return self::$validationErrorsArray;
    }
} 