<?php
namespace EmployeeManagementSystem\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 //use EmployeeManagementSystem\Model\Admin;
 use EmployeeManagementSystem\Model\Employee;
 use EmployeeManagementSystem\Model\EmployeeTable;
 use EmployeeManagementSystem\Form\AdminForm;
 use EmployeeManagementSystem\Form\AddDocumentsForm;
 class AdminController extends AbstractActionController
 {
 	protected $employeeTable;

     public function indexAction()
     {
     	return new ViewModel(array(
      			'employees' => $this->getEmployeeTable()->fetchAll(),
      	));
     }

     public function addAction()
     {
         $form = new AdminForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $employee = new Employee();
             $form->setInputFilter($employee->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $employee->exchangeArray($form->getData());
                 $this->getEmployeeTable()->saveEmployee($employee);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('admin');
             }
         }
         return array('form' => $form);
     }
     
     public function addDocumentsAction()
     {
	    $form = new AddDocumentsForm('add-documents');
	    $request = $this->getRequest();
	    if ($request->isPost()) {
	        // Make certain to merge the files info!
	        $post = array_merge_recursive(
	            $request->getPost()->toArray(),
	            $request->getFiles()->toArray()
	        );
	
	        $form->setData($post);
	        if ($form->isValid()) {
	            $data = $form->getData();    
	            return $this->redirect()->toRoute('addDocuments/success');
	            }
	        }

	    return array('form' => $form);
     }

 	 public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admin', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $employee = $this->getEmployeeTable()->getEmployee($id);
         }
         catch (\Exception $ex) {
         	print("error");
             return $this->redirect()->toRoute('admin', array(
                 'action' => 'index'
             ));
         }

         $form  = new AdminForm();
         $form->bind($employee);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($employee->getInputFilter());
             $form->setData($request->getPost());
			print_r($request->getPost());
             if ($form->isValid()) {
                 $this->getEmployeeTable()->saveEmployee($employee);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('admin');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }

 	 public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admin');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getEmployeeTable()->deleteEmployee($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('admin');
         }

         return array(
             'id'    => $id,
             'employee' => $this->getEmployeeTable()->getEmployee($id)
         );
     }
     public function multiDeleteAction()
     {
// 		$form = new AdminForm('multiDelete');
		
// 		$form->add(array(
// 		    'type' => 'Zend\Form\Element\Checkbox',
// 		    'name' => 'checkbox',
// 		    'options' => array(
// 		        'label' => 'Delete',
// 		        'use_hidden_element' => true,
// 		        'checked_value' => 1,
// 		        'unchecked_value' => 0
// 		    )
// 		));
// 		echo '<pre>', var_dump($form), '</pre>';
     	return new ViewModel(array(
      			'employees' => $this->getEmployeeTable()->fetchAll(),
      	));
     }
     public function getEmployeeTable()
     {
     	if (!$this->employeeTable) {
     		$sm = $this->getServiceLocator();
     		$this->employeeTable = $sm->get('EmployeeManagementSystem\Model\EmployeeTable');
     	}
     	return $this->employeeTable;
     }    
 }