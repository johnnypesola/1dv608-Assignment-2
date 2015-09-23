<?php

# Show errors from server. Turn off on public servers.
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

# Prevent SESSION hijacking

    // Prevents javascript XSS attacks aimed to steal the session ID
    ini_set('session.cookie_httponly', 1);

    // Use strong hash
    ini_set('session.hash_function', 'whirlpool');

# Prevent SESSION fixation

    // Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);

    // Uses a secure connection (HTTPS) if possible
    //ini_set('session.cookie_secure', 1);