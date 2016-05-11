<?php
class CamelThemes_Slider_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('slider/set_time')
                ->_addBreadcrumb('Slider Manager','Slider Manager');
       return $this;
     }
      public function indexAction()
      {
         $this->_initAction();
         $this->renderLayout();
         
      }
      public function editAction()
      {
           $sliderId = $this->getRequest()->getParam('id');
           $sliderModel = Mage::getModel('slider/slider')->load($sliderId);
           if($sliderModel->getId() || $sliderId == 0)
           {
           	Mage::register('slider_data',$sliderModel);
           	$this->loadLayout();
             $this->_setActiveMenu('slider/set_time');
             $this->_addBreadcrumb('slider Manager', 'slider Manager');
             $this->_addBreadcrumb('Slider Description', 'Slider Description');
             $this->getLayout()->getBlock('head')
                  ->setCanLoadExtJs(true);
             $this->_addContent($this->getLayout()
                  ->createBlock('slider/adminhtml_slider_edit'))
                  ->_addLeft($this->getLayout()
                  ->createBlock('slider/adminhtml_slider_edit_tabs')
              );
             $this->renderLayout();
           }
           else
           {
           		Mage::getSingleton('adminhtml/session')
                       ->addError('Test does not exist');
                 $this->_redirect('*/*/');
           }
       }
       public function newAction()
       {
          $this->_forward('edit');
       }
       public function saveAction()
       {
       		if($data = $this->getRequest()->getPost())
       		{
       			try{

       				$image = Mage::getModel('slider/slider')
       					->load($this->getRequest()->getParam('id'));

       				if($_FILES['imagename']['name']!=''){
       					$uploader = new Varien_File_Uploader('imagename');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
       					$uploader->setAllowRenameFiles(false);
    						$uploader->setFilesDispersion(false);
    						$path = Mage::getBaseDir('media') . DS .'slider' .DS;
    						$filename=time().$_FILES['imagename']['name'];
    						$uploader->save($path, $filename );

    						unlink("$path/$image[imagename]");
       				}
              
       				$data = $this->getRequest()->getPost();

              
       				if($_FILES['imagename']['name'] != ''){
       					$data['imagename'] = $filename;
       				}else {
                   if (isset($data['imagename']['value'])){
                      $data['imagename'] = $data['imagename']['value'];
                   }
                }
              

       				$sliderModel = Mage::getModel('slider/slider');
       				$sliderModel->setData($data)->setId($this->getRequest()->getParam('id'));
       				$sliderModel->save();

       				if($this->getRequest()->getParam('id') <= 0)
       					$sliderModel->setCreatedTime(Mage::getSingleton('core/date')->gmtDate());

       				Mage::getSingleton('adminhtml/session')
                               ->addSuccess('successfully saved');
                 	Mage::getSingleton('adminhtml/session')
                                ->setsliderData(false);
                 	$this->_redirect('*/*/');
                	return;

       			}catch(Exception $e){
       				Mage::getSingleton('adminhtml/session')
                                  ->addError($e->getMessage());
                	Mage::getSingleton('adminhtml/session')
                 			->setsliderData($this->getRequest()->getPost()
                );
                $this->_redirect('*/*/edit',
                            array('id' => $this->getRequest()
                                                ->getParam('id')));
                return;
       			}
       		}
       		$this->_redirect('*/*/');
          

       }
          public function deleteAction()
          {
              if($this->getRequest()->getParam('id') > 0)
              {
                try
                {
                    $testModel = Mage::getModel('slider/slider');
                    $testModel->setId($this->getRequest()
                                        ->getParam('id'))
                              ->delete();
                    Mage::getSingleton('adminhtml/session')
                               ->addSuccess('successfully deleted');
                    $this->_redirect('*/*/');
                 }
                 catch (Exception $e)
                  {
                           Mage::getSingleton('adminhtml/session')
                                ->addError($e->getMessage());
                           $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                  }
             }
            $this->_redirect('*/*/');
       }


       public function massDeleteAction() {
        $slideIds = $this->getRequest()->getParam('slider');
        if(!is_array($slideIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($slideIds as $slideId) {
                    $slide = Mage::getModel('slider/slider')->load($slideId);
                    $slide->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($slideIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}
?>