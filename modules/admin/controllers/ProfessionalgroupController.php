<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;
use STS\Core\Api\ProfessionalGroupFacade;
use STS\Web\Security\AclFactory;
use STS\Domain\User;
use STS\Core\Api\ApiException;
use STS\Core\Api\LocationFacade;
use STS\Domain\ProfessionalGroup;
use STS\Core\Location\AreaDto;
use STS\Core\Location\RegionDto;

class Admin_ProfessionalgroupController extends SecureBaseController {
	/**
	 * @var ProfessionalGroupFacade
	 */
	protected $professionalGroupFacade;

	/**
	 * @var Zend_View
	 */
	public $view;

	/**
	 * @var Zend_Session
	 */
	public $session;

	/**
	 * @var LocationFacade
	 */
	protected $locationFacade;

	public function init()
	{
		parent::init();
		$core = Core::getDefaultInstance();
        $this->user = $this->getAuth()->getIdentity();
		$this->professionalGroupFacade = $core->load('ProfessionalGroupFacade');
		$this->locationFacade = $core->load('LocationFacade');
		$this->session = new \Zend_Session_Namespace('admin');
	}

	public function indexAction()
	{
        // make sure user can edit professional groups
        $this->setPermissions();

		// setup filters
		$form = $this->getFilterForm();
		$criteria = array();

		$this->session->criteria = $criteria;
		$params = $this->getRequest()->getParams();

		if (array_key_exists('reset', $params)) {
			return $this->_helper->redirector('index');
		}
		if (array_key_exists('update', $params)) {
			$form->setDefaults($params);
			$this->filterParams('region', $params, $criteria);
			$this->filterParams('type', $params, $criteria);
			$this->session->criteria = $criteria;
		}

		if (!empty($criteria)) {
			$this->view->objects = $this->professionalGroupFacade->getProfessionalGroupsMatching($criteria);
		} else {
			$this->view->objects = $this->professionalGroupFacade->getAllProfessionalGroups();
		}

		$this->view->form = $form;

		$add = array();
		if ($this->view->can_edit) {
			$add['add'] = 'Add New Professional Group';
			$add['addRoute'] = '/admin/professionalgroup/new';
		}
		$this->view->layout()->pageHeader = $this->view->partial(
			'partials/page-header.phtml',
			array('title' => 'Professional Groups') + $add
		);
	}

	public function excelAction()
	{
		$criteria = $this->session->criteria;
		$professional_groups = $this->professionalGroupFacade->getProfessionalGroupsMatching($criteria);

		$headers = array(
			'Name',
			'Region',
			'Area'
		);

		$data = array();

		/**
		 * @var ProfessionalGroup $professional_group
		 */
		foreach ($professional_groups as $professional_group) {
			$data[] = array(
				$professional_group->getName(),
				$professional_group->getRegionName(),
				$professional_group->getAreaName()
			);
		}

		$this->outputCSV('professional_groups', $data, $headers);
	}

	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id');
		$professional_group = $this->professionalGroupFacade->getProfessionalGroupById($id);
		$this->view->layout()->pageHeader = $this->view
			->partial('partials/page-header.phtml', array(
				'title' => $professional_group->getName()
			));
		$this->view->professional_group = $professional_group;
	}

	public function newAction()
	{
		$this->view->form = $this->getForm();
		/** @var Zend_Controller_Request_Http $request */
		$request = $this->getRequest();
		$form = $this->getForm();
		$form->setAction('/admin/professionalgroup/new');
		if ($request->isPost()) {
			$postData = $request->getPost();
			if ($form->isValid($postData)) {
				try {
					$this->saveProfessionalGroup($postData);
					$this
						->setFlashMessageAndRedirect("The new professional_group \"{$postData['name']}\" has been created!", 'success', array(
							'module' => 'admin', 'controller' => 'professionalgroup',
							'action' => 'index'
						));
				} catch (ApiException $e) {
					$this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
				}
			} else {
				$this
					->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
			}
		}
		$this->view->form = $form;
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$form = $this->getForm();
		$form->setAction('/admin/professionalgroup/edit?id='.$id);
		$professional_group = $this->professionalGroupFacade->getProfessionalGroupById($id);
		$this->view->layout()->pageHeader = $this->view
			->partial('partials/page-header.phtml', array(
				'title' => 'Edit: '.$professional_group->getName()
			));
		$form->populate(
			array(
				'name'=>$professional_group->getName(),
				'area'=>$professional_group->getArea()->getId()
			)
		);
		if ($this->getRequest()->isPost()) {
			$request = $this->getRequest();
			$postData = $request->getPost();
			if ($form->isValid($postData)) {
				try {
					$updatedProfessionalGroup = $this->professionalGroupFacade->updateProfessionalGroup($professional_group);
					$this
						->setFlashMessageAndRedirect("The professional group \"{$updatedProfessionalGroup->getName()}\" has been updated!", 'success', array(
							'module' => 'admin', 'controller' => 'professionalgroup', 'action' => 'view', 'params' => array('id'=>$updatedProfessionalGroup->getId())
						));
				} catch (ApiException $e) {
					$this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
				}
			} else {
				$this
					->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
			}
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$professional_group = $this->professionalGroupFacade->getProfessionalGroupById($id);
		if ($professional_group)
		{
			$this->professionalGroupFacade->deleteProfessionalGroup($id);
			$this
				->setFlashMessageAndRedirect("The professional group \"{$professional_group->getName()}\" has been deleted!", 'success', array(
					'module' => 'admin', 'controller' => 'professionalgroup', 'action' => 'index'));
		} else {
			$this
				->setFlashMessageAndRedirect("There was a problem deleting this group. Please try
				 again.", 'failure',
					array(
					'module' => 'admin', 'controller' => 'professionalgroup', 'action' => 'index'));
		}
	}

	/**
	 * @param array $postData
	 * @return ProfessionalGroup
	 */
	private function saveProfessionalGroup($postData)
	{
		$professional_group = new ProfessionalGroup();
		$area = $this->locationFacade->getAreaById($postData['area']);
		$professional_group->setName($postData['name'])->setArea($area);
		return $this->professionalGroupFacade->saveProfessionalGroup($professional_group);
	}

	/**
	 * @return Admin_ProfessionalGroup
	 */
	private function getForm()
	{
		$areasArray = array_merge(array(''), $this->getAreasArray());

		$form = new \Admin_ProfessionalGroup(array('areas' => $areasArray));
		return $form;
	}

	/**
	 * @return array
	 */
	private function getAreasArray()
	{
		$areaDtos = $this->locationFacade->getAllAreas();
		$areaArray = array();
		/** @var AreaDto $dto */
		foreach ($areaDtos as $dto) {
			$areaArray[$dto->getId()] = $dto->getName();
		}
		return $areaArray;
	}

	/**
	 * @return Admin_ProfessionalGroupFilter
	 */
	private function getFilterForm()
	{
		$regions =  $this->getRegionsArray();

		$form = new \Admin_ProfessionalGroupFilter(
			array(
				'regions' => $regions
			)
		);
		return $form;
	}

	/**
	 * @return array
	 */
	private function getRegionsArray()
	{
		$regionsArray = array('');
		/** @var RegionDto $region */
		foreach ($this->locationFacade->getAllRegions() as $region) {
			$regionsArray[$region->getName()] = $region->getName();
		}
		return $regionsArray;
	}

	/**
	 * @param $key
	 * @param array $params
	 * @param array $criteria
	 */
	private function filterParams($key, &$params, &$criteria)
	{
		if (array_key_exists($key, $params)) {
			$chaff = array_search('0', $params[$key]);
			if($chaff !== false){
				unset($params[$key][$chaff]);
			}
			$criteria[$key] = $params[$key];
		}
	}

    private function setPermissions()
    {
        /** @var STS\Core\User\UserDTO $user */
        $user = $this->getAuth()->getIdentity();

        // permissions
        $this->view->can_view = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_PROFESSIONAL_GROUP,
            'view');
        $this->view->can_edit = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_PROFESSIONAL_GROUP, 'edit');
        $this->view->can_delete = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_PROFESSIONAL_GROUP, 'delete');
    }
}