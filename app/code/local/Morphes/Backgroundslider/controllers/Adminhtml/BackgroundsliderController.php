<?php
class Morphes_Backgroundslider_Adminhtml_BackgroundsliderController extends Mage_Adminhtml_Controller_action
{
	public function indexAction() {	
		$this->loadLayout();
		$this->_setActiveMenu('morphes/backgroundslider');
		$this->_addBreadcrumb($this->__('Слайдер'), $this->__('Фон')); 		
		$this->_addContent($this->getLayout()->createBlock('backgroundslider/adminhtml_backgroundslider'));		
		$this->renderLayout();
	}

	public function editAction() {		
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('backgroundslider/backgroundslider')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}			

			Mage::register('background_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('morphes/backgroundslider');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('backgroundslider/adminhtml_backgroundslider_edit'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('background')->__('Не существует!'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {		
		$this->editAction();
		// $this->_title($this->__('Add new quote'));
  //       $this->loadLayout();
  //       $this->_setActiveMenu('catalog/background/background');
  //       // $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Add new quote'), Mage::helper('freaks_quotes')->__('Add new quote'));
  //       $this->_addBreadcrumb($this->__('Слайдер'), $this->__('Фон')); 
  //       $this->renderLayout();
	}

	public function saveAction() {
	    $id     = $this->getRequest()->getParam('id');
	    $model  = Mage::getModel('backgroundslider/backgroundslider');
			if ($data = $this->getRequest()->getPost()) {
				if(isset($_FILES['image_value']['name']) and (file_exists($_FILES['image_value']['tmp_name']))) {
				try {
						$uploader = new Varien_File_Uploader('image_value');
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
						$uploader->setAllowRenameFiles(false);
						// setAllowRenameFiles(true) -> move your file in a folder the magento way
						// setAllowRenameFiles(true) -> move your file directly in the $path folder
						$uploader->setFilesDispersion(false);
						$path = Mage::getBaseDir('media') . DS ;
						$uploader->save($path, $_FILES['image_value']['name']);
						$mediaUrl = Mage::getBaseUrl('media');
						$data['image_value'] = $mediaUrl.$_FILES['image_value']['name'];
					}catch(Exception $e) {}
				}else {
					$get_data_for_image = $model->load($id);
					$img = $get_data_for_image->getImageValue();
					$data['image_value'] = $img;
				}				
			$model->setData($data)->setId($id);			
			    try{
				$model->save();				
				    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Изображение сохранено!'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('background')->__('Ошибка сохранения'));
        $this->_redirect('*/*/');
	}

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('background');
        if(!is_array($ids)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('background')->__('Пожалуйста, выберите'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('background/background')->load($id);
						  $path = Mage::getBaseDir('media') . DS ;
						  unlink($path.$model['image_value']);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('background/background')->load($this->getRequest()->getParam('id'));
				$path = Mage::getBaseDir('media') . DS ;
				//$path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
				unlink($path.$model['image_value']);
				$model->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background has been deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function getSellerImagesAction() {
		$pramas	= $this->getRequest()->getParams();
		if(Mage::getModel('background/background')->getCollection()->addFieldToFilter('background_id', $pramas['backgroundId'])->addFieldToSelect('image_value')->getData()) {
			$sellerProduct = Mage::getModel('background/background')->getCollection()->addFieldToFilter('background_id', $pramas['backgroundId'])->addFieldToSelect('image_value')->getData();
			$_background_image = $sellerProduct[0]['image_value'];
		}
		echo Mage::getBaseUrl('media').'catalog/product'.$_background_image;
	}
}
