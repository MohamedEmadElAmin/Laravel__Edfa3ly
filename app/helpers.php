<?php

/**
 * added in composer file
 * #composer dump-autoload
 */

function _findInList($list ,string $parameterName, string $parameterValue)
{
    foreach ($list as $item)
    {
        if($item->$parameterName == $parameterValue)
            return $item;
    }

    return NULL;
}
function _findInListMultiple($list ,$conditions)
{
    foreach ($list as $item)
    {
        $numberOfSuccessConditions = 0;
        foreach ($conditions as $index => $value)
        {
            if($item->$index == $value)
                $numberOfSuccessConditions++;
        }

        if($numberOfSuccessConditions == count($conditions))
            return $item;
    }

    return NULL;
}
function _generateHash($uniqueId): string
{
    $hash = preg_replace_callback('/[xy]/', function ($matches)
    {
        return dechex('x' == $matches[0] ? mt_rand(0, 15) : (mt_rand(0, 15) & 0x3 | 0x8));
    }
        , 'xxxx' . $uniqueId . 'xxxx');

    return $hash . _generateRandomString(2);
}
function _generateRandomString($length = 10): string
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
