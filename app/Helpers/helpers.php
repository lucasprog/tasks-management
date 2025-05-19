<?php

if (!function_exists('is_array_of_arrays')) {
    function is_array_of_arrays(array $array): bool
    {
        return !empty($array) && array_keys($array) === range(0, count($array) - 1) && is_array($array[0]);
    }
}