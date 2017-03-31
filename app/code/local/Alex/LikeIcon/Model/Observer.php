<?php

class Alex_LikeIcon_Model_Observer
{

    public function coreBlockAbstractToHtmlBefore($observer) {
        $block = $observer->getEvent()->getBlock();
        $blockType = $block->getType();

        if ($blockType == 'catalog/product_list') {
            $childNameAfter = $block->getChild('name.after');
            $layout = Mage::app()->getLayout();
            $likeBlock = $layout->createBlock('alex_likeicon/like')->setTemplate('likeicon/likeicon.phtml');
            $childNameAfter->append($likeBlock);
        }
    }
}