<?php

/**
 * added in composer file
 * #composer dump-autoload
 */

function findInList($list ,string $parameterName, string $parameterValue)
{
    foreach ($list as $item)
    {
        if($item->$parameterName == $parameterValue)
            return $item;
    }

    return NULL;
}


function findInListMultiple($list ,$params = [])
{
    $conditions = array_key_exists('conditions' , $params) ? $params['conditions'] : [];
    foreach ($list as $item)
    {
        $numberOfSuccessConditions = 0;
        foreach ($conditions as $condition)
        {
            $pName = array_key_exists('pName' , $condition) ? $condition['pName'] : -1;
            $pValue = array_key_exists('pValue' , $condition) ? $condition['pValue'] : -1;

            if($pName == -1 || $pValue == -1)
                continue;


            if($item->$pName == $pValue)
                $numberOfSuccessConditions++;
        }

        if($numberOfSuccessConditions == count($conditions))
            return $item;
    }

    return NULL;
}
