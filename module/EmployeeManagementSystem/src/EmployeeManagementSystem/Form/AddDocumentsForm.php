<?php
namespace EmployeeManagementSystem\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class AddDocumentsForm extends Form
{
	public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
        parent::__construct('Profile');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
         
        $this->add(array(
        			'name' => 'profilename',
        			'attributes' => array(
        				'type'  => 'text',
        			),
        			'options' => array(
        				'label' => 'Profile Name',
        			),
        ));
        
        	 
        $this->add(array(
        	'name' => 'fileupload',
        	'attributes' => array(
        			'type'  => 'file',
        	),
        	'options' => array(
        			'label' => 'File Upload',
        	),
        ));
        	 
        	 
        $this->add(array(
        		'name' => 'submit',
         		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Upload Now'
        		),
        ));
    
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Employee Picture.')
             ->setAttribute('id', 'image-file')
             ->setAttribute('multiple', true);   // That's it
        $this->add($file);
        
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('image-file');
        $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
        //    ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpg,image/gif'))
            ->attachByName('fileimagesize', array('maxWidth' => 1000, 'maxHeight' => 1000));
        //$fileInput->addValidator('Extension', false, array('jpg', 'png'));
        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}