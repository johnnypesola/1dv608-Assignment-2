<?php

namespace model;


class UserClientService {

// Init variables
    private $userAgent = '';
    private $ip = '';

// Constructor
    public function __construct() {
        $this->SetIp();
        $this->SetUserAgent();
    }

// Setters and Getters
    public function SetIp() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

// Public Methods
    public function GetIp() {
        return $this->ip;
    }

    public function SetUserAgent() {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

    public function GetUserAgent() {
        return $this->userAgent;
    }

    public function GetHash(){
        return \model\AuthService::Hash($this->ip . $this->userAgent);
    }
} 