<?php
class CamelThemes_Slider_Block_Adminhtml_slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
   {
       parent::__construct();
       $this->setId('contactGrid');
       $this->setDefaultSort('id_CamelThemes_Slider');
       $this->setDefaultDir('DESC');
       $this->setSaveParametersInSession(true);
   }
   protected function _prepareCollection()
   {
      $collection = Mage::getModel('slider/slider')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
    }
   protected function _prepareColumns()
   {
       $this->addColumn('id_camelThemes_slider',
             array(
                    'header' => 'Id',
                    'align' =>'right',
                    'width' => '10px',
                    'index' => 'id_camelThemes_slider',
               ));
       $this->addColumn('title',
               array(
                    'header' => 'Title',
                    'align' =>'left',
                    'width' => '150px',
                    'index' => 'title',
              ));
       $this->addColumn('url', array(
                    'header' => 'Url',
                    'width'     => '150px',
                    'align' =>'left',
                    'index' => 'url',
             ));
       
        $this->addColumn('imagename', array(
          'header'    => 'image',
          'align'     =>'left',
          
          'index'     => 'imagename',
          'frame_callback' => array($this, 'callback_image'),

          'filter'    => false,
          'width'     => '100px',
          'sortable'  => false,
        ));
        
    
      $this->addColumn('status', array(
            'header'    => 'Status',
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'status',
            
        ));

        $this->addColumn('action',
                array(
                'header'    => 'Edit',
                'width'   => '60',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                    'caption' => 'Edit',
                    'url'     => array(
                        'base'=>'*/*/edit'
                    ),
                    
                    'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
            ));

       







         return parent::_prepareColumns();
    }
    public function getRowUrl($row)
    {
         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function callback_image($value)
    {
        $width = 150;
        
        return "<img src='".Mage::getBaseUrl('media')."slider/".$value."' width=".$width." />";
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id_camelThemes_slider');
        $this->getMassactionBlock()->setFormFieldName('slider');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('customer')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('customer')->__('Are you sure?')
        ));

  }
}