<?php
use STS\Core;

use STS\Web\Controller\SecureBaseController;

class PresentationController extends SecureBaseController {
    public function newAction() {
        
        
        
        $core = Core::getDefaultInstance();
        $facade = $core->load('SchoolFacade');
        
        $schools = $facade->getAllSchools();
        
        
        $form = new Twitter_Bootstrap_Form_Horizontal();
        $element = new Zend_Form_Element_Select('school', array('disableLoadDefaultDecorators' =>
                                      true, 'class'=>'span4'));
        $element->setDecorators(array(
    array('ViewHelper'),
));     $element->addMultiOption(null,'');
        foreach ($schools as $school){
            $element->addMultiOption($school->getId(),$school->getName());
        }
        
        $form->addElement($element);
        $this->view->element = $element;
    }
    
    
    public function testAction(){
        
    }
    
    public function memberAction(){
        
        $term = $this->_request->getParam('term');
        $core = Core::getDefaultInstance();
        $facade = $core->load('MemberFacade');
        
        $dtos = $facade->searchForMembersByName($term);
        
        $data = array();
        foreach ($dtos as $dto){
            $data[]= $dto->getFirstName() . ' '. $dto->getLastName();
        }
        
        
        $this->_helper->json($data);
    }
    
}
