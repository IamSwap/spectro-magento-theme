<?php
class CamelThemes_Slider_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('slider/set_time')
                ->_addBreadcrumb('test Manager','test Manager');
       return $this;
     }
      public function indexAction()
      {
         $this->_initAction();
         $this->renderLayout();
         
      }
      public function editAction()
      {
           $testId = $this->getRequest()->getParam('id');
           $testModel = Mage::getModel('slider/slider')->load($testId);
           if ($testModel->getId() || $testId == 0)
           {
             Mage::register('test_data', $testModel);
             $this->loadLayout();
             $this->_setActiveMenu('slider/set_time');
             $this->_addBreadcrumb('test Manager', 'test Manager');
             $this->_addBreadcrumb('Test Description', 'Test Description');
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

        if(isset($_FILES['imagename']['name']) and (file_exists($_FILES['imagename']['tmp_name']))) {
            try {
              $uploader = new Varien_File_Uploader('imagename');
              $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
           
           
              $uploader->setAllowRenameFiles(false);
           
              // setAllowRenameFiles(true) -> move your file in a folder the magento way
              // setAllowRenameFiles(true) -> move your file directly in the $path folder
              $uploader->setFilesDispersion(false);
             
              $path = Mage::getBaseDir('media') . DS . 'slider' ;
                         
              $uploader->save($path, $_FILES['imagename']['name']);
           
              $filename = $_FILES['imagename']['name'];
            }catch(Exception $e) {
           
            }
        }






         if ($this->getRequest()->getPost())
         {
           try {

                if(isset($_FILES['imagename']['name']) and (file_exists($_FILES['imagename']['tmp_name']))) {
            try {
              $uploader = new Varien_File_Uploader('imagename');
              $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
           
           
              $uploader->setAllowRenameFiles(false);
           
              // setAllowRenameFiles(true) -> move your file in a folder the magento way
              // setAllowRenameFiles(true) -> move your file directly in the $path folder
              $uploader->setFilesDispersion(false);
             
              $path = Mage::getBaseDir('media') . DS ;
                         
              $uploader->save($path, $_FILES['imagename']['name']);
           
              $filename = $_FILES['imagename']['name'];
            }catch(Exception $e) {
           
            }
        }


                 $postData = $this->getRequest()->getPost();

                 $postData['imagename'] = $filename;
                 



                 
                 $testModel = Mage::getModel('slider/slider');
               if( $this->getRequest()->getParam('id') <= 0 )
                  $testModel->setCreatedTime(
                     Mage::getSingleton('core/date')
                            ->gmtDate()
                    );
                  $testModel
                    ->addData($postData)
                    ->setUpdateTime(
                             Mage::getSingleton('core/date')
                             ->gmtDate())
                    ->setId($this->getRequest()->getParam('id'))
                    ->save();
                 Mage::getSingleton('adminhtml/session')
                               ->addSuccess('successfully saved');
                 Mage::getSingleton('adminhtml/session')
                                ->settestData(false);
                 $this->_redirect('*/*/');
                return;
          } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')
                                  ->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')
                 ->settestData($this->getRequest()
                                    ->getPost()
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
}
?>