<?php
class CamelThemes_Slider_Block_Adminhtml_slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
   protected function _prepareForm()
   {

      

       $form = new Varien_Data_Form();
       $this->setForm($form);
       $fieldset = $form->addFieldset('slider_form',
                                       array('legend'=>'Edit Image'));
        $fieldset->addField('title', 'text',
                       array(
                          'label' => 'Title',
                          'class' => 'required-entry',
                          'required' => true,
                           'name' => 'title',
                    ));

        $fieldset->addField('status', 'select',
                    
                    array(
                        'label' => 'Status',
                        'name' => 'status',
                        'values'=>array(

                           array(
                                'value'     => 'Enabled',
                                'label'     => 'Enabled',
                            ),
                          array(
                                'value'     => 'Disabled',
                                'label'     => 'Disabled',
                          ),))
                    
                 );

        
        $fieldset->addField('url', 'text',
                       array(
                          'label' => 'URL',
                          'class' => 'required-entry',
                          'required' => true,
                           'name' => 'url',
                    ));

        $fieldset->addField('imagename', 'file',
                    array(
                        'label' => 'Image',
                       // 'class' => 'required-entry',
                        'required' => false,
                        'name' => 'imagename',
                        
                 ));





      /*  
        $fieldset->addField('description', 'editor', array(
                        'name'      => 'description',
                        'label'     => 'Description',
                        'title'     => 'description',
                        'required' => true,
                        'style'     => 'height:15em',
                        'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                        'wysiwyg'   => true,
                        'required'  => false,
        ));
      */ 
          
 if ( Mage::registry('slider_data') )
 {
    $form->setValues(Mage::registry('slider_data')->getData());
  }
  return parent::_prepareForm();
 }

 public function callback_image($value)
    {
        $width = 150;
        
        return "<img src='".Mage::getBaseUrl('media')."slider/".$value."' width=".$width." />";
    }
}