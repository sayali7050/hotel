<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Environment Helper
 * 
 * Helper functions for environment configuration management
 * 
 * @package Hotel Management System
 * @version 2.0
 */

if (!function_exists('load_environment')) {
    /**
     * Load environment variables from .env file
     */
    function load_environment() {
        if (file_exists(FCPATH . '.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable(FCPATH);
            $dotenv->load();
        }
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable with optional default
     */
    function env($key, $default = null) {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Convert boolean strings
        if (strtolower($value) === 'true') {
            return true;
        }
        
        if (strtolower($value) === 'false') {
            return false;
        }
        
        // Convert null string
        if (strtolower($value) === 'null') {
            return null;
        }
        
        return $value;
    }
}

if (!function_exists('is_production')) {
    /**
     * Check if running in production environment
     */
    function is_production() {
        return env('CI_ENV') === 'production';
    }
}

if (!function_exists('is_development')) {
    /**
     * Check if running in development environment
     */
    function is_development() {
        return env('CI_ENV') === 'development';
    }
}

if (!function_exists('app_url')) {
    /**
     * Get application base URL
     */
    function app_url($path = '') {
        $base = rtrim(env('BASE_URL', base_url()), '/');
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }
}