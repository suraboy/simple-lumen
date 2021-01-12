<?php
if (!function_exists('scopeExists')) {
    function scopeExists($class, $scopeName)
    {
        return method_exists($class, 'scope' . ucfirst($scopeName));
    }
}
