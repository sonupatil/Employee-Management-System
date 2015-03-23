<?php
namespace EmployeeManagementSystem\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Admin implements InputFilterAwareInterface
{
	public $id;
	public $first_name;
	public $last_name;
	public $email_id;
	public $phone_no;
	public $status;
	protected $inputFilter;
	
	 public function exchangeArray($data)
	 {
	 	$this->id     		= 	(isset($data['id']))     		? $data['id']     		: null;
	 	$this->first_name 	= 	(isset($data['first_name']))	? $data['first_name'] 	: null;
     	$this->last_name  	= 	(isset($data['last_name']))  	? $data['last_name']  	: null;
     	$this->email_id		=	(isset($data['email_id']))		? $data['email_id']		: null;
     	$this->phone_no		=	(isset($data['phone_no']))		? $data['phone_no']		: null;
     	$this->status		=	(isset($data['status']))		? $data['status']		: null;
	 }

	 public function getArrayCopy()
	 {
	 	return get_object_vars($this);
	 }
     // Add content to these methods:
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

     public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'id',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'first_name',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'last_name',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));
             $inputFilter->add(array(
             		'name'     => 'email_id',
             		'required' => true,
             		'filters'  => array(
             				array('name' => 'StripTags'),
             				array('name' => 'StringTrim'),
             		),
             		'validators' => array(
             				array(
             						'name'    => 'StringLength',
             						'options' => array(
             								'encoding' => 'UTF-8',
             								'min'      => 1,
             								'max'      => 100,
             						),
             				),
             		),
             ));
             $inputFilter->add(array(
             		'name'     => 'phone_no',
             		'required' => true,
             		'filters'  => array(
             				array('name' => 'StripTags'),
             				array('name' => 'StringTrim'),
             		),
             		'validators' => array(
             				array(
             						'name'    => 'StringLength',
             						'options' => array(
             								'encoding' => 'UTF-8',
             								'min'      => 1,
             								'max'      => 10,
             						),
             				),
             		),
             ));
             
             $inputFilter->add(array(
             		'name'     => 'status',
             		'required' => true,
             		'filters'  => array(
             				array('name' => 'StripTags'),
             				array('name' => 'StringTrim'),
             		),
             		'validators' => array(
             				array(
             						'name'    => 'StringLength',
             						'options' => array(
             								'encoding' => 'UTF-8',
             								'min'      => 1,
             								'max'      => 1,
             						),
             				),
             		),
             ));
             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
 }