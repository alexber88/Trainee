<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 28.07.16
 * Time: 18:27
 */

class Alex_UpdatePrice_Model_Observer
{
    public function addMassAction($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && $block->getRequest()->getControllerName() == 'catalog_product')
        {
            $textInput = [
                'name' => 'value',
                'type' => 'text',
                'class' => 'required-entry',
                'label' => 'Value',
            ];

            $items = [
                'addition' => 'Add to price',
                'subtraction' => 'Subtract from price',
                'addPercent' => 'Add percent to price',
                'subtractPercent' => 'Subtract percent from price',
                'multiplication' => 'Multiplicate price',
            ];

            foreach($items as $name => $label)
            {
                $block->addItem($name, [
                    'label' => $label,
                    'url' => Mage::app()->getStore()->getUrl('adminhtml/price/massUpdatePrice/', ['method' => '_'.$name]),
                    'additional' => [
                        'visibility' => $textInput
                    ]
                ]);
            }
        }
    }
}