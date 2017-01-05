<?php

use Nahid\Hookr\Facades\Hook;

if (!function_exists('hook_action')) {
    function hook_action($name, $params = [])
    {
        return Hook::action($name, $params);
    }
}

if (!function_exists('hook_filter')) {
    function hook_filter($name, $data, $params = [])
    {
        return Hook::filter($name, $data, $params);
    }
}
