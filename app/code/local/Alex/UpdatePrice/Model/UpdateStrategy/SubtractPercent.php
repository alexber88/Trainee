<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 08.08.16
 * Time: 11:23
 */

class Alex_UpdatePrice_Model_UpdateStrategy_SubtractPercent implements Alex_UpdatePrice_Model_UpdateStrategy_UpdateInterface
{
    public function calculateNewPrice($price, $value)
    {
        return $price - ($price * $value)/100;
    }
}