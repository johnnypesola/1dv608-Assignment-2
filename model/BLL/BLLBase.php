<?php

namespace model;


class BLLBase {

    protected function IsValidString($stringName, $stringContent, $constraints = []) {

        // Default values
        if(!isset($constraints['minLength'])) {
            $constraints['minLength'] = 1;
        }
        if(!isset($constraints['maxLength'])) {
            $constraints['maxLength'] = 100;
        }
        if(!isset($constraints['regex'])) {
            $constraints['regex'] = '/[^a-z_\-0-9]/i';
        }
        if(!isset($constraints['throwException'])) {
            $constraints['throwException'] = false;
        }

        // Default messages
        if(!isset($constraints['emptyMsg'])) {
            $constraints['emptyMsg'] = "$stringName is missing";
        }
        if(!isset($constraints['minLengthMsg'])) {
            $constraints['minLengthMsg'] = "$stringName has too few characters, at least " . $constraints['minLength'] . " characters.";
        }
        if(!isset($constraints['maxLengthMsg'])) {
            $constraints['maxLengthMsg'] = "$stringName is too long. Max length is " . $constraints['maxLength'] . " characters.";
        }
        if(!isset($constraints['regexMsg'])) {
            $constraints['regexMsg'] = "$stringName contains invalid characters.";
        }

        // Check if $stringContent is empty
        if($constraints['minLength'] == 1 && trim(strlen($stringContent)) == 0) {

            // Throw exception if specified
            if($constraints['throwException']) {
                throw new \Exception($constraints['emptyMsg']);
            }

            ValidationService::AddValidationError($constraints['emptyMsg']);
        }

        // Check if $stringContent is too short
        if($constraints['minLength'] > 1 && trim(strlen($stringContent)) < $constraints['minLength']) {

            // Throw exception if specified
            if($constraints['throwException']) {
                throw new \Exception($constraints['minLengthMsg']);
            }

            ValidationService::AddValidationError($constraints['minLengthMsg']);
        }

        // Check if $stringContent is too long
        if(strlen($stringContent) > $constraints['maxLength']) {

            // Throw exception if specified
            if($constraints['throwException']) {
                throw new \Exception($constraints['maxLengthMsg']);
            }

            ValidationService::AddValidationError($constraints['maxLength']);
        }

        // Check if $stringContent is valid
        if(preg_match($constraints['regex'], $stringContent)) {

            // Throw exception if specified
            if($constraints['throwException']) {
                throw new \Exception($constraints['regexMsg']);
            }

            ValidationService::AddValidationError($constraints['regexMsg']);
        }

        return true;
    }
} 