<?php

class Alex_LikeIcon_Block_Like extends Mage_Core_Block_Template
{

    public function getProductId() {

        return $this->getProduct()->getId();
    }
}