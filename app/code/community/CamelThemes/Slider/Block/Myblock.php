<?php
class CamelThemes_Slider_Block_Myblock 
extends Mage_Core_Block_Template
{
	public function helloBlock()
	{
		return '<br>Hello from block method';
	}

	public function sliderblock()
     {
        //on initialize la variable
        $slider = '';
        $slider .= '<div id="banner-fade">';
        $slider .= '<ul class="slider">'; 

        /* we are doing the query to select all elements of the pfay_test table (thanks to our model test/test and we sort them by id_pfay_test */
        $collection = Mage::getModel('slider/slider')->getCollection()->setOrder('id_camelthemes_slider','asc');

         /* then, we check the result of the query and with the function getData() */
        foreach($collection as $data)
        {
             if($data->getData('status') == 'Enabled'){

             $slider .= '<li><a href="'.$data->getData('url').'">';
             $slider .= '<img  src="'.Mage::getBaseUrl('media')."slider/" .$data->getData('imagename').'" width="100%">';
             $slider .= '</a></li>';

            }
         }

        $slider .= '</ul></div>'; 
         //i return a success message to the user thanks to the Session.
         Mage::getSingleton('adminhtml/session')->addSuccess('Cool !!');
         return $slider;
      }


}    