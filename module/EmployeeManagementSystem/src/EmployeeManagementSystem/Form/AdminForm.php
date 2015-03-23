<?php
namespace EmployeeManagementSystem\Form;

use Zend\Form\Form;

class AdminForm extends Form
{

	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('employee');

		$this->add(array(
				'name' => 'id',
				'type' => 'Zend\Form\Element\Hidden',
		));
		$this->add(array(
				'name' => 'first_name',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
						'label' => 'First Name',
				),
		));
		$this->add(array(
				'name' => 'last_name',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
						'label' => 'Last Name',
				),
		));
		$this->add(array(
				'name' => 'email_id',
				'type' => 'Zend\Form\Element\Email',
				'options' => array(
						'label' => 'Email Id',
				),
		));	
		$this->add(array(
				'name' => 'phone_no',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
						'label' => 'Phone No',
				),
		));	
		$this->add(array(
				'name' => 'status',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
						'label' => 'Status',
				),
		));				
		$this->add(array(
				'name' => 'submit',
				'type' => 'Zend\Form\Element\Submit',
				'attributes' => array(
						'value' => 'Go',
						'id' => 'submitbutton',
				),
		));
	}
}