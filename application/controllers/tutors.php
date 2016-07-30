<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Tutors extends SP_Controller {
    public function __construct() {
        parent::__construct();
        $id = $this->session->userdata('tutorid');
        if (is_numeric($id) && $id > 0)
        {
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($id, true);
            $the_time = $this->Timezones_model->tutorTimeZone($id) ;  
           //echo date('H:i:s', time() + (3600 * (float)$the_time));
            date_default_timezone_set($time_zone);//***** we set the timezone first
        }
    }
    public function index()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutors';
        $this->load->model('Tutors_model');
        list($tutors, $pages) = $this->Tutors_model->getTutors();
        $purchase = false;
        $this->load->library('session');
        if (!$_GET || !isset($_GET['purchase'])) { redirect(base_url('billing/purchase')); }
        else 
        {   switch ((int)$_GET['purchase']) 
            {
                case 1:
                    $this->session->set_userdata('purchase', 1);
                    break;
                case 2: 
                    $this->session->set_userdata('purchase', 2);
                    break;
                case 3: 
                    $this->session->set_userdata('purchase', 3);
                    break;
                default:
                    $this->session->set_userdata('purchase', 1);
            }
        }
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutors', '/tutors?purchase='.$_GET['purchase']);
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['pages'] = $pages;        
        $data['tutors'] = $tutors;
        $data['main_content'] = 'front/tutors/tutors_list';
        $this->load->view('front/includes/main_view', $data);
    }
     public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('tutors/login'));
    }            
    public function login()
    {
        $this->load->library('breadcrumbs');
         $data['err_log'] = '';
         $this->Fn_model->ch_login2();
        //$this->session->sess_destroy();
        if(isset($_POST['login'])){
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('pwd', 'Password', 'trim|required');

            if($this->form_validation->run() == TRUE){
                $det = $this->My_model->select_where('tutors', array( 'email_list' => $_POST['email'] ));
                
                if(count($det) == 1){
                    $pwd = md5($_POST['pwd'] );
                    if($pwd == $det->password){
                        if($det->inactive == 1){
                            $this->session->set_userdata('cur_reg',  $det->tutor_ID);
                            redirect(base_url());
                        } else {
                            $add = array( 'tutorid' => $det->tutor_ID, 'tutoremail' => $det->email_list, 'login_tutor' => TRUE );
                            $this->session->set_userdata($add);
                            redirect(base_url('tutors/profile'));
                        }
                    } else
                        $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
                } else
                    $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
            } else
                $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
        }
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutors Login';
        $this->breadcrumbs->push('Tutors', '/tutors');
       //  $this->breadcrumbs->push('Login', '/tutors/login');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'front/tutors/sign_login_view';
        $this->load->view('front/includes/main_view', $data);
    }
    public function profile()
    {
         $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutors', '/tutors');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $this->load->model('Tutors_model');
        $id = $this->session->userdata('tutorid');
        $tutor = $this->Tutors_model->getTutor($id);
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['tutor'] = $tutor;
        $data['my_tutor'] = $id;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'front/tutors/profile_view';
        $this->load->view('front/includes/main_view', $data);
    }
    public function Schedule()
    {
         $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $tutor_id = $this->session->userdata('tutorid');
        $this->load->model('Customers_model');
        $tutor_id = $this->uri->segment(3);
        if (!is_numeric($tutor_id)) { echo "ERROR - there is no tutor ID. Exiting"; exit; }
        $type = $this->session->userdata('purchase');
         
       /* $id = isset($_GET['id']) ? $_GET['id']  : '';
        if (!is_numeric($type)) { echo "Unavailable type"; return; } 
        if (!is_numeric($id)) { echo "Unavailable ID"; return; } 
        if ($type == 2) { $row = $this->Customers_model->getRecurring($id); }
        elseif ($type == 1) { $row = $this->Customers_model->getCustInventory($id); }
        else return;*/
        $row = '';
        $data['row'] = $row;
        $data['type'] = $type;
        $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Schedule', '/tutors/schedule/'.$tutor_id);
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
	$error = '';
       // echo date_default_timezone_get() ;
        $data['error'] = $error;
        $this->load->model('Tutors_model');
        //$data['customer_ID'] = $customer_ID;
        $data['tutor_id'] = $tutor_id;
        $data['tutor'] = $this->Tutors_model;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Schedule';
        $data['main_content'] = 'front/calendar/cal_tutor_2';
        $this->load->view('front/includes/main_view',$data);
    }
    public function AjaxstudEdit()
    {
        if (!isset($_POST['student_id']) || !is_numeric($_POST['student_id'])) return;
        $this->load->model('Customers_model');
        $student = $this->Customers_model->getUser($_POST['student_id']);
        echo ($student) ? json_encode($student) : 0;
    }
    public function Ajaxstuedints()
    {
        $searchVar = true;
        if (!isset($_GET)) $searchVar = false;
        //if (!isset($_GET['stustring']) || $_GET['stustring'] == '')  $searchVar = false;
        if (!isset($_GET['order']) || !is_numeric($_GET['order']) )  $searchVar = false;
        if (!isset($_GET['rect']) || !is_numeric($_GET['rect']) )  $searchVar = false;
        if (!isset($_GET['tutorid']) || !is_numeric($_GET['tutorid']) )  $searchVar = false;        
        if ($searchVar)
        {
            $this->load->model('Tutors_model');
            $students = $this->Tutors_model->tutorStudents($_GET['tutorid'], $_GET['stustring']);
            if ($students && is_array($students))
            {
                  echo "<table border='0' cellpadding='5' cellspacing='5'><tr><th>Student</th><th>E-mail</th></tr>";//<th>Phone</th></tr>";
                  foreach ($students as $student)
                  {
                     echo "<tr><td><a  href='javascript:void(0)' onClick='studentSelected(".$student->Customer_ID.", ".$_GET['tutorid'].")'>".$student->name."</a></td><td>$student->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
                  }
                  echo "</table>";
            } else echo 0;
        } else echo 0;
        exit;
    }
    public function AjaxStudents()
    {
       
        $searchVar = true;
        if (!isset($_GET)) $searchVar = false;
        //if (!isset($_GET['stustring']) || $_GET['stustring'] == '')  $searchVar = false;
        if (!isset($_GET['order']) || !is_numeric($_GET['order']) )  $searchVar = false;
        if (!isset($_GET['rect']) || !is_numeric($_GET['rect']) )  $searchVar = false;
        if (!isset($_GET['tutorid']) || !is_numeric($_GET['tutorid']) )  $searchVar = false;        
        if ($searchVar)
        {
            $this->load->model('Tutors_model');
            $students = $this->Tutors_model->tutorStudents($_GET['tutorid'], $_GET['stustring']);
            
             if (isset($students) && is_array($students)) {  
    //echo "<table border='0' cellpadding='5' cellspacing='5'><tr><th>Student</th><th>E-mail</th></tr>";//<th>Phone</th></tr>";
      $emailsList = array();
  foreach ($students as $student)
  {
       
       $emailsList[] = $student->name." &lt".$student->email_list."&gt";//</a></td></tr>";//<td>".$student->phone."</td></tr>";
       //echo "<tr><td>".$student->name."</td><td>$student->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
  }
      echo implode(', ', $emailsList);
//  echo "</table>";
               } else echo 0;
        } else echo 0;
        exit;
    }
    public function studentDates()
    {
         $id = $tutor_id = $this->session->userdata('tutorid');
         if (isset($_POST['noTutorID'])) $tutor_id = '';
         elseif (!is_numeric($tutor_id)) {  $tutor_id = $_POST['tutor_id']; }
         if (!isset($_POST['noStudentID']))
         {   $student_id = $_POST['student_id'];
             if (!is_numeric($student_id)) exit;
         } else $student_id = '';
         $this->load->model('Customers_model');
         $dateDat = array();
         $DatesJSON = (isset($_POST['DatesJSON'])) ? $_POST['DatesJSON'] : '';
         if ($DatesJSON != '')
         {
            $DatesJSON = json_decode($DatesJSON);        
            $dateDat['DatesJSON_1'] =  date('m/d/Y', $DatesJSON[0]);
            $dateDat['DatesJSON_2'] =  date('m/d/Y', $DatesJSON[1]);
         }
         else
         {
            $DatesJSON[0] = 0;//$this->Customers_model->firstDate($student_id, $tutor_id );
            $DatesJSON[1] = time();//$this->Customers_model->LateDate($student_id, $tutor_id );
            $dateDat['DatesJSON_1']  = date('m/d/Y', $DatesJSON[0]);
            $dateDat['DatesJSON_2'] = date('m/d/Y', $DatesJSON[1]);
            
         }
         
         $countStudents = 1;
         if (is_numeric($tutor_id) && !is_numeric($student_id))
         {
             $this->load->model('Tutors_model');
             $countStudents = $this->Tutors_model->countStudents($tutor_id );
         }
         $dateDat['countStudents'] = $countStudents;
         $DatesJSON = json_encode($DatesJSON);
         $dateDat['sumInvoices'] = $this->Customers_model->sumInvoices($student_id, $tutor_id, $DatesJSON);
         $dateDat['sumPaid'] = $this->Customers_model->sumPaid($student_id, $tutor_id, $DatesJSON);        
         $dateDat['countRecur'] = $this->Customers_model->countRecur($student_id, $tutor_id, $DatesJSON);
         $dateDat['countFlex'] = $this->Customers_model->countFlex($student_id, $tutor_id, $DatesJSON);
         $dateDat['quantUnpaid'] = $this->Customers_model->countUnpaid($student_id, $tutor_id, $DatesJSON);
         echo json_encode($dateDat);
        exit;
    }
    public function studentEmails()
    {
         $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Student Emails', '/tutors/studentemails');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        
        $this->load->model('Tutors_model');
        $this->load->model('Customers_model');
        $id = $this->session->userdata('tutorid');
        
        $students = $this->Tutors_model->tutorStudents($id);
        $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) ) ? $_GET['order'] : 0; 
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['students'] = $students;
        //$data['bills'] = $purchs;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'front/tutors/teacher_students';
        $this->load->view('front/includes/main_view', $data);
    }
    public function invoicelist()
    {
         $this->Fn_model->ch_tutorlogin();
         $this->load->library('breadcrumbs');
         //$this->breadcrumbs->push('Invoices', '/tutors/invoices');
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->unshift('Tutor Page', base_url().'tutors/profile');
         $this->breadcrumbs->push('Invoices', '/tutors/profile');
         $this->breadcrumbs->unshift('Home', base_url());
         $breadcrumbs = $this->breadcrumbs->show(); 
         $id = $this->session->userdata('tutorid');
         $qry = "select * from tutors WHERE tutor_ID = $id ORDER by name ASC";
         $data['tutors'] = $this->db->query($qry)->result();
         $qry = "select * from customers ORDER by name ASC";
         $this->load->model('Tutors_model');
         $globals = $this->Fn_model->getGlobals();
         $firstDate = $this->Tutors_model->firstDate();
         $lastDate = $this->Tutors_model->LateDate();
         $countStudents = $this->Tutors_model->countStudents($id);
         $sumInvoices = $this->Tutors_model->sumInvoices($id);
         $sumPaid = $this->Tutors_model->sumPaid($id);
         $countRecur = $this->Tutors_model->countRecur($id);
         $countFlex = $this->Tutors_model->countFlex($id);
         
         $quantUnpaid = $this->Tutors_model->countUnpaid($id);
         $data['quantUnpaid'] = $quantUnpaid;
         $data['countFlex'] = $countFlex;
         $data['countRecur'] = $countRecur;
         $data['sumPaid'] = $sumPaid;
         $data['sumInvoices'] = $sumInvoices;
         $data['countStudents'] = $countStudents;
         $data['lastDate'] = $lastDate;
         $data['firstDate'] = $firstDate;
         $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
         $data['customers'] = $this->Tutors_model->tutorStudents($id);
         $data['breadcrumbs'] = $breadcrumbs;
         $data['main_content'] = 'front/tutors/manage_invoices';
         $this->load->view('front/includes/main_view', $data);
    }
    public function invoices()
    {
        return;
        $this->Fn_model->ch_tutorlogin();
        // $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Invoices', '/tutors/invoices');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $this->load->model('Tutors_model');
        $id = $this->session->userdata('tutorid');
        $tutor = $this->Tutors_model->getTutor($id);

        $countStudents = $this->Tutors_model->countStudents($id);
        $sumInvoices = $this->Tutors_model->sumInvoices($id);
        $sumPaid = $this->Tutors_model->sumPaid($id);
        $firstDate = $this->Tutors_model->firstDate();
        $lastDate = $this->Tutors_model->LateDate();
        $quantUnpaid = $this->Tutors_model->countUnpaid($id);
        $countFlex = $this->Tutors_model->countFlex($id);
        $countRecur = $this->Tutors_model->countRecur($id);
        $purchs = $this->Tutors_model->getPurchases($id);
        $this->load->model('Customers_model');
        $data['sumInvoices'] = $sumInvoices;
        $data['countRecur'] = $countRecur;
        $data['countFlex'] = $countFlex;
        $data['quantUnpaid'] = $quantUnpaid;
        $data['sumPaid'] = $sumPaid;
        $data['countStudents'] = $countStudents;
        $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        $data['firstDate'] = $firstDate;
        $data['lastDate'] = $lastDate;
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['tutor'] = $tutor;
        $data['bills'] = $purchs;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'front/tutors/billhistr';
        $this->load->view('front/includes/main_view', $data);
    }
     public function createInvoice()
    {
        $this->Fn_model->ch_tutorlogin();
         //if(!$adminCredentials) redirect(base_url() . 'admin');
          $id = $this->session->userdata('tutorid');       
         if (!is_numeric($id)) return;
         if ($_POST)
         {
              $this->load->model('admin_model');
              if (isset($_POST['recurringform']) && 1 == $_POST['recurringform']) { $editor = $this->admin_model->recurringEnter($_POST); }
              elseif (isset($_POST['flexform']) && 1 == $_POST['flexform']) { $editor = $this->admin_model->flexEnter($_POST); }
              elseif (isset($_POST['otherform']) && 1 == $_POST['otherform']) { $editor = $this->admin_model->OtherEnter($_POST); }
              echo "<script>window.location.href='" .base_url()."tutors/invoicelist'</script>";
         }
         $this->load->library('breadcrumbs');
       $this->breadcrumbs->push('Invoices', '/tutors/invoicelist');
         $this->breadcrumbs->push('Create Invoice', base_url().'admin/createinvoice');
        
        $this->breadcrumbs->unshift('Home', base_url());
         
         $breadcrumbs = $this->breadcrumbs->show(); 
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Create Invoice'; 
        $this->load->model('Tutors_model');
        $tutor = $this->Tutors_model->getTutor($id);
        if (!isset($tutor->tutor_ID) || !is_numeric($tutor->tutor_ID)) return;
        
        $students = $this->Tutors_model->tutorStudents($id);
        $data['customers'] = $students;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['tutor'] = $tutor;
        $data['main_content'] = 'front/tutors/create_invoice';
        $this->load->view('front/includes/main_view', $data);
    }
    public function editInvoice()
    {
         $this->Fn_model->ch_tutorlogin();
         //if(!$adminCredentials) redirect(base_url() . 'admin');
         $id  = $this->uri->segment(3);
         if (!is_numeric($id)) return;
         $msg = false;
         if ($_POST)
         {   
              $this->load->model('admin_model');
              if (isset($_POST['e_ecurringform']) && 1 == $_POST['e_ecurringform']) { $editor = $this->admin_model->recurringEdit($_POST); }
              elseif (isset($_POST['e_flexform']) && 1 == $_POST['e_flexform']) { $editor = $this->admin_model->flexEdit($_POST); }
              elseif (isset($_POST['e_otherform']) && 1 == $_POST['e_otherform']) { $editor = $this->admin_model->OtherEdit($_POST); }
              if ($editor === true)
              { $msg = "Invoice has been successfully edited"; }
         }
         $queryStr = "SELECT * FROM purchases WHERE purchase_id = $id LIMIT 1";
          
          if($this->db->query($queryStr)->num_rows() > 0)
          {
             foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
          } 
           $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Edit Invoice';       
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->unshift('Edit Invoice', base_url().'tutors/editinvoice');
        $this->breadcrumbs->unshift('Invoices', base_url().'tutors/invoicelist');
        $this->breadcrumbs->unshift('Home', base_url()."");
        $breadcrumbs = $this->breadcrumbs->show(); 
        $data['msg'] = $msg;
         $data['breadcrumbs'] = $breadcrumbs;
          $data['data'] = $data[0];
          
          if (2 == $data['data']->item_id)
          {
              $this->load->model('Customers_model');
              $recurring = $this->Customers_model->getRecurringProfile($data['data']->profile_id);
             $data['recurring'] = $recurring;
          }
          elseif (1 == $data['data']->item_id)
          {
              $this->load->model('Customers_model');
              
              $flexData = $this->Customers_model->getCustInventory($data['data']->profile_id);
              $data['flexData'] = $flexData;
          }
        $data['main_content'] = 'front/tutors/edit_invoice';
        $this->load->view('front/includes/main_view', $data);
    }
    private function _studentEditing()
    {
        
              $this->form_validation->set_rules('email', 'Email Address', 'required|trim|callback_init_value|valid_email');
               $this->form_validation->set_rules('name', 'Name', 'required|trim|callback_init_value');
               $this->form_validation->set_rules('phone', 'Phone', 'required|trim|callback_init_value');
               $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');
             $this->form_validation->set_message('valid_email', $this->input->post('email') . ' is not a valid email');
            $this->form_validation->set_message('is_unique', $this->input->post('email') . ' is already registered');
            $this->form_validation->set_message('required', '%s is required.');
            $this->form_validation->set_message('matches', '%s did not match.');
            $this->form_validation->set_message('min_length', '%s must be at least 5 characters.');
              if($this->form_validation->run() == TRUE)
              {
                  if (isset($_POST['customer_id']) && is_numeric($_POST['customer_id']) && $_POST['customer_id'] > 0) 
                  { $where = array("Customer_ID" => $_POST['customer_id']); $newUser = false; }
                  else $newUser = true;
                  $second_error = false;
                  $to_update = array('skype' => $_POST['skype'],'time_zone' => $_POST['time_zone'], 'name' => $_POST['name'], 'email_list' => $_POST['email'], 'phone' => $_POST['phone']);
                  if ($_POST['pwd'] && trim($_POST['pwd']) != '')
                  {
                      if ($_POST['pwd'] != $_POST['conf_pwd'])
                      {
                          $second_error = true;
                          $password_error = "Passwords are not the same";
                      }
                      else
                      {
                          $reg = date('Y-m-d h:i:s');
                          $to_update['date_reg']  = $reg;
                          $to_update['password'] = md5($_POST['pwd'] . $reg);
                      }
                  }
                      
                     /* if ( $this->Customers_model->emailEditError($_POST['email'], $profile_id) )
                      {
                          $second_error = true;
                          $emailerror = "E-mail is already registered";
                      }*/
                  if ($second_error === false) 
                  { 
                if ($newUser === true)
                    { $this->My_model->inserting('customers', $to_update); return 2; }
                    else  { $this->My_model->update_where('customers', $to_update, $where); return 1; }
                        
                  }
                  else return 0;
              }
              else return 0;
         
    }
    
    public function editStudents()
    {
        $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Edit Students', '/tutors/editstudents');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        
        $this->load->model('Tutors_model');
        $this->load->model('Customers_model');
        $id = $this->session->userdata('tutorid');
        $result = 1;
        if (isset($_POST['profile_edit']) && $_POST['profile_edit'] == 1)
        { $result = $this->_studentEditing(); }
        $students = $this->Tutors_model->tutorStudents($id);
        $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        $data['tutor_id'] = $id;
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) ) ? $_GET['order'] : 0; 
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['students'] = $students;
        $data['result'] = $result;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'front/tutors/students_edit';
        $this->load->view('front/includes/main_view', $data);
    }
     public function editStudent()
    {
        $this->Fn_model->ch_tutorlogin();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Edit Students', '/tutors/editstudents');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $customer_id = $this->uri->segment(3);
        if (!is_numeric($customer_id)) echo "ERROR - customer_id is not numeric";
         $this->load->model('Customers_model');
         $row = $this->Customers_model->getUser($customer_id);
        $my_tutor_id = $this->session->userdata('tutorid');
        
         if (isset($_POST['profile_edit']) && $_POST['profile_edit'] == 1){
              $this->form_validation->set_rules('email', 'Email Address', 'required|trim|callback_init_value|valid_email');
              $this->form_validation->set_rules('name', 'Name', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('phone', 'Phone', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');
              $this->form_validation->set_message('valid_email', $this->input->post('email') . ' is not a valid email');
              $this->form_validation->set_message('is_unique', $this->input->post('email') . ' is already registered');
              $this->form_validation->set_message('required', '%s is required.');
              $this->form_validation->set_message('matches', '%s did not match.');
              $this->form_validation->set_message('min_length', '%s must be at least 5 characters.');
              if($this->form_validation->run() == TRUE)
              {
                  $where = array("Customer_ID" => $_POST['customer_id']);
                  $second_error = false;
                  $to_update = array('skype' => $_POST['skype'],'time_zone' => $_POST['time_zone'], 'name' => $_POST['name'], 'email_list' => $_POST['email'], 'phone' => $_POST['phone']);
                  if ($_POST['pwd'] && trim($_POST['pwd']) != '')
                  {
                      if ($_POST['pwd'] != $_POST['conf_pwd'])
                      {
                          $second_error = true;
                          $password_error = "Passwords are not the same";
                      }
                      else
                      {
                          $reg = date('Y-m-d h:i:s');
                          $to_update['date_reg']  = $reg;
                          $to_update['password'] = md5($_POST['pwd'] . $reg);
                      }
                  }
                      
                      if ( $this->Customers_model->emailEditError($_POST['email'], $profile_id) )
                      {
                          $second_error = true;
                          $emailerror = "E-mail is already registered";
                      }    
                  if ($second_error === false) { $this->My_model->update_where('customers', $to_update, $where); $success = "Profile Successfully Edited"; }
              }
              else $err = "There has been an error";
         }
        
             $data['breadcrumbs'] = $breadcrumbs;
         $data['emailerror'] = $emailerror;
         $data['err'] = $err;
         $data['success'] = $success;
         $data['password_error'] = $password_error;
        
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Edit Profile';
        $data['breadcrumbs'] = $breadcrumbs;
        $data['my_tutor_id'] = $my_tutor_id;
        $data['customer_id'] = $customer_id;
        $data['row'] = $row;
        $data['main_content'] = 'front/tutors/stud_edit';
        $this->load->view('front/includes/main_view', $data);
    }
    public function editMyAccount()
    {
         $this->Fn_model->ch_tutorlogin();
         $this->load->library('breadcrumbs');
         $my_tutor_id = $this->session->userdata('tutorid');
         $this->load->model('Customers_model');
         
         $tutor_id = $this->uri->segment(3);
         if ($my_tutor_id != $tutor_id) return;
         if(isset($_POST['edittutor']))
         {
             $this->load->model('Tutors_model');
             $data = $this->Tutors_model->editTutorData();
         }
         $data['success'] = '';
         $globals = $this->Fn_model->getGlobals();
         $data['page_title'] = $globals->{'store-name'}.' | Edit My Account';
         $qry = "select * from tutors WHERE tutor_ID = $tutor_id";
         $data['spanish'] = $this->db->query($qry)->row();
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->push('Tutor Profile', '/tutors/profile');
         $this->breadcrumbs->push('Edit My Account', '/tutors/editMyaccount/'.$tutor_id);
         $this->breadcrumbs->unshift('Home', base_url());
         $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
         $data['success'] = $data['success'];
         $data['main_content'] = 'front/tutors/view_tutor_edit';
         $this->load->view('front/includes/main_view', $data);
    }
    public function tutorStudents()
    {
         $this->Fn_model->ch_tutorlogin();
        $id = $this->session->userdata('tutorid');
        $this->load->model('Tutors_model');
        $students = $this->Tutors_model->tutorStudents($id);
        if ($students && is_array($students))
        {
            foreach ($students as $stud)
            {
                echo "<p><a href='javascript:void(0)' onClick='chooseStudent($stud->Customer_ID)'>".ucwords($stud->name)."</a></p>";
            }
        }
    }
}
