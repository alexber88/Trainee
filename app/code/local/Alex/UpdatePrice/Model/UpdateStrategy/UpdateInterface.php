<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 08.08.16
 * Time: 11:22
 */

interface Alex_UpdatePrice_Model_UpdateStrategy_UpdateInterface
{
    public function calculateNewPrice($price, $value);
}