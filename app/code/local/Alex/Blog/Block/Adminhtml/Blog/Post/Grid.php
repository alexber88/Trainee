<?php

class Alex_Blog_Block_Adminhtml_Blog_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {

        parent::__construct();
        $this->setId('alex_blog_grid');
        $this->setDefaultSort('post_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $posts = Mage::getModel('blog/post')->getCollection()
//            ->addExpressionFieldToSelect(
//                'fullname',
//                'CONCAT({{first_name}}, \' \', {{last_name}})',
//                array('first_name' => 'cev1.value', 'last_name' => 'cev2.value'))
        ;
        $posts->getSelect()
            ->join(['ce' => 'customer_entity'], 'customer_id = ce.entity_id', 'ce.email')
            ->joinLeft(['cev1' => 'customer_entity_varchar'], 'cev1.entity_id = ce.entity_id AND cev1.attribute_id = 5', 'cev1.value as first_name')
            ->joinLeft(['cev2' => 'customer_entity_varchar'], 'cev2.entity_id = ce.entity_id AND cev2.attribute_id = 7', 'cev2.value as last_name');

        $this->setCollection($posts);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('alex_blog');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);

        $this->addColumn('post_id', array(
            'header' => $helper->__('Post #'),
            'index'  => 'post_id'
        ));

        $this->addColumn('title', array(
            'header' => $helper->__('Title'),
            'type'   => 'text',
            'index'  => 'title'
        ));
        $this->addColumn('img', array(
            'header' => $helper->__('Image'),
            'type'   => 'text',
            'index'  => 'img'
        ));
        $this->addColumn('first_name', array(
            'header'       => $helper->__('Name'),
//            'index'        => 'first_name',
            //'filter_index' => 'CONCAT(first_name, \' \', last_name)'
            'renderer' => 'Alex_Blog_Block_Adminhtml_Renderer_Name',
            'filter_condition_callback' => array($this, '_nameFilter'),
        ));
        $this->addColumn('status', array(
            'header'       => $helper->__('Status'),
            'index'        => 'status',
            'renderer' => 'Alex_Blog_Block_Adminhtml_Renderer_Status',
            'type' => 'options',
            'options' => ['Disabled', 'Enabled'],
        ));
        $this->addColumn('date', array(
            'header'       => $helper->__('Created At'),
            'index'        => 'date',
            'type' => 'datetime'
        ));

        $this->addExportType('*/*/exportInchooCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportInchooExcel', $helper->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_ids');
        $this->getMassactionBlock()->setFormFieldName('post_id');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('alex_blog')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
            'confirm' => Mage::helper('alex_blog')->__('Are you sure?')
        ));

        $statuses = [['value' => 0, 'label' => 'Disabled'], ['value' => 1, 'label' => 'Enabled']];

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('catalog')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('catalog')->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _nameFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue())
        {
            return $collection;
        }

        $values = explode(' ', $value);

        for($i=0; $i<count($values); $i++)
        {
            $collection->getSelect()
                ->where( "cev1.value like ? OR cev2.value like ?", "%{$values[$i]}%");
        }

        return $collection;
    }
    
}