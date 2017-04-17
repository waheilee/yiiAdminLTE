<?php
/**
 * Created by PhpStorm.
 * User: Lynron
 * Date: 2016/9/24
 * Time: 0:55
 */

function dd()
{
    $vars = func_get_args();
    if (!empty($vars)) {
        foreach ($vars as $var) {
            var_dump($var);
        }
    }
    die;
}

function array_flatten($array)
{
    $return = [];

    array_walk_recursive($array, function ($x) use (&$return) {
        $return[] = $x;
    });

    return $return;
}

function mb_subtext($text, $length)
{
    return $length < mb_strlen($text, 'utf8') ? mb_substr($text, 0, $length, 'utf8').'...' : $text;
}