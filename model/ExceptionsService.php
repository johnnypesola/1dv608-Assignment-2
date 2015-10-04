<?php

namespace model;


class ExceptionsService {

// Init variables
    private static $exceptions = [];

// Constructor

// Getters and Setters

// Private Methods

// Public Methods
    static public function AddException(\Exception $exception) {
        self::$exceptions[] = $exception;
    }

    static public function GetLastExceptionMessage() {
        $error = end(self::$exceptions);

        return $error->getMessage();
    }

    static public function GetLastException() {
        return end(self::$exceptions);
    }

    static public function GetAllExceptions() {
        return self::$exceptions;
    }

    static public function GetAllExceptionMessages() {
        $messagesArray = [];

        foreach (self::$exceptions as $exception) {
            $messagesArray[] = $exception->getMessage();
        }

        return $messagesArray;
    }

    static public function HasExceptions() {
        return sizeof(self::$exceptions) > 0;
    }
} 