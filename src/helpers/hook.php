<?php

use Nahid\Hookr\Facades\Hook;


if (!function_exists('hook_action')) {
    function hook_action($name, $params = [])
    {
        \Nahid\Hookr\Hook::action($name, $params);
    }
}

if (!function_exists('hook_filter')) {
    function hook_filter($name, $data, $params = [])
    {
        return \Nahid\Hookr\Hook::filter($name, $data, $params);
    }
}
