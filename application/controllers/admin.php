<?php
// copied bdi
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('admin_base.php');
class Admin extends Admin_base {
    public function __construct() {
        parent::__construct();
       
        $this->adminCredentials = $this->session->userdata('adminCredentials');
       
        $this->load->model('my_model');
        $id = $this->adminCredentials['admin_id'];
        //  if they are logged in....
        if (is_numeric($id) && $id > 0)
        {
             $this->load->model('Timezones_model');   // from models directory
            $time_zone = $this->Timezones_model->getUserZone($id, false, true);
            $the_time = $this->Timezones_model->adminTimeZone($id) ;  
           // echo "$time_zone and ".date('H:i:s', time() + (3600 * (float)$the_time));
            date_default_timezone_set($time_zone);
        }
    }

    public function editStudents()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');

        if(!$adminCredentials)
            redirect(base_url() . 'admin');

         $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Students',base_url()."admin/editstudents");
         $this->breadcrumbs->unshift('Home', base_url()."admin");;
        $breadcrumbs = $this->breadcrumbs->show();
         $qry = "select * from customers";
          if(isset($_POST['btnsubmit'])){
            $try = $_POST['keyword'];
            $key = sanitize_input(TRIM($try));

            if(empty($key)){
                redirect(base_url('admin/manage_tutors'));
            } else {
                $data['spanish'] = $this->db->query("select * from customers  WHERE name like '%$key%' OR email_list like '%$key%' OR skype like '%$key%'")->result();
                 
            }
        } else $data['spanish'] = $this->db->query($qry)->result();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/editstudents';
        $this->load->view('admin/includes/main_view', $data);
    }
      public function add_Student()
    {
         $this->edit_student(true);
    }
     public function edit_Student($add_student = false)
    {
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Students', '/admin/edit_student');
        $this->breadcrumbs->unshift('Edit Students',base_url()."admin/editstudents");
        $this->breadcrumbs->unshift('Home', base_url()."admin");;
        $breadcrumbs = $this->breadcrumbs->show();
        $customer_id = $this->uri->segment(3);
        if (true !== $add_student) { $row = $this->getCustomerInfo($customer_id); }
        $my_tutor_id = $this->session->userdata('tutorid');
        $data['emailerror'] = false;
        $data['err'] = false;
        $data['success'] = false;
        $data['password_error'] = false;
         //we can also add students here, so there are now two conditions:
         if ( (isset($_POST['student_add']) && 1 == $_POST['student_add'] && true === $add_student) || (isset($_POST['profile_edit']) && 1 == $_POST['profile_edit'] )) {
               $uniqueMail = (true === $add_student) ? "|is_unique[customers.email_list]" : '';
              $this->form_validation->set_rules('email', 'Email Address', 'required|trim|callback_init_value|valid_email'.$uniqueMail);
              $this->form_validation->set_rules('name', 'Name', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('phone', 'Phone', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');
              $this->form_validation->set_message('valid_email', $this->input->post('email') . ' is not a valid email');
              $this->form_validation->set_message('is_unique', $this->input->post('email') . ' is already registered');
              $this->form_validation->set_message('required', '%s is required.');
              $this->form_validation->set_message('matches', '%s did not match.');
              $this->form_validation->set_message('min_length', '%s must be at least 5 characters.');
              if (true === $add_student)
              {  $this->form_validation->set_rules('pwd', 'Password', 'trim|required|matches[conf_pwd]|min_length[5]');  }    
              if ($this->form_validation->run() == TRUE) { list($data, $row) = $this->processCustomerData($add_student, $_POST); }
              else $data['err'] = "There has been an error";
         }
         $data['breadcrumbs'] = $breadcrumbs;
         $globals = $this->Fn_model->getGlobals();
         $data['page_title'] = $globals->{'store-name'}.' | Edit Profile';
         $data['breadcrumbs'] = $breadcrumbs;
         $data['my_tutor_id'] = $my_tutor_id;
         if (true !== $add_student)
         {   
             $data['customer_id'] = $customer_id;
             $data['row'] = $row;
         }
         $data['add_student'] = $add_student;
         $data['main_content'] = 'admin/stud_edit';
         $this->load->view('front/includes/main_view', $data);
    }
    
    function view_Student()
    {
        $customer_id = $this->uri->segment(3);
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('View Student',base_url()."admin/view_student");
        $this->breadcrumbs->unshift('Edit Students',base_url()."admin/editstudents");
        $this->breadcrumbs->unshift('Home', base_url()."admin");;
        $breadcrumbs = $this->breadcrumbs->show();
        $data['customer'] = $this->My_model->select_where('customers', array( 'Customer_ID' => $customer_id ));
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | My Profile';
        $this->load->model('Customers_model');
        $unpaid = $this->Customers_model->getUnPaid($customer_id);
        $teachers = $this->Customers_model->getUserTutors($customer_id);
        $teachersNum = count($teachers);
        unset($teachers);
        $outs = '';
        if (is_array($unpaid)) { $sum = 0;
            foreach ($unpaid as $unp) { $sum += $unp->price_paid; }
            $outs .= "<p><a href='".base_url()."billing/balance/".$customer_id."'>Pay Outstanding Balance of $".$sum."</a></p>";
        }
        $data['outs'] = $outs;
         $data['breadcrumbs'] = $breadcrumbs;
        $data['teachersNum'] = $teachersNum;
        $data['main_content'] = 'front/profile/profile_view_2';
        $this->load->view('front/includes/main_view', $data);
    }
   
    public function index() {

        $my_sess = $this->session->all_userdata();
 

        if(isset($my_sess['in'])){
            if($my_sess['in'] == 1)
                redirect(base_url() . 'admin/pages');
        }
 
        $data['jquerys'] = "";
        $data['error']   = "";
        $uname           = $this->input->post('uname');
        $pword           = $this->input->post('pword');
       // echo "pword is $pword ".md5($pword); exit;
        $count           = $this->My_model->count_where('admins', array( 'username' => $uname, 'password' => md5($pword)));


        if(isset($_POST['send'])){
            if($count == 0){
                $data['jquerys'] = "
					$('#main-wrapper2').animate({'margin-top': '-=50px'}, 'fast');
					$('#main-wrapper2').animate({'margin-top': '+=50px'}, 'fast');
					$('#main-wrapper2').animate({'margin-top': '-=50px'}, 'fast');
					$('#main-wrapper2').animate({'margin-top': '+=50px'}, 'fast');
					";
                $data['error']   = '<p style="text-align:center; color:red; font-size:10px;"><br />Invalid Username/Password</p>';
            } else {
          
                $query = $this->My_model->select_where('admins', array( 'username' => $uname, 'password' => md5($pword) ));
                $aw    = $query->admin_id;
                 

                $newdata = array(
                    'username'   => $uname,
                    'admin_id'   => $aw,
                    'logged_in'  => TRUE,
                    'admin_type' => $query->admin_type
                );
                $this->session->set_userdata('adminCredentials', $newdata);
                $this->session->set_userdata('in', true);
                $this->session->set_userdata('admin_id', $aw);
                redirect(base_url() . 'admin/pages');
            }

        }
       
        $this->load->view('admin/login', $data);
    }
    public function editValues()
    {
        $data = '';
         $this->load->library('breadcrumbs');
       // $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Edit Global Store Values', '/admin/editvalues');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
       
        $error = false;
        $result = '';
        if ($_POST)
        { $result = $this->Fn_model->updateGlobals();}
        if ($result !== 1) { $error = $result; }
          $globals = $this->Fn_model->getGlobals();
        $data['globals'] = $globals;
        $data['error'] = $error;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/editvalues';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function AjaxStudentTutors()
    {
        $this->load->model('Customers_model');
        $stud_id = $_POST['student_id'];
        $tutors = $this->Customers_model->getUserTutors($stud_id, true);
        if (count($tutors > 0)) $user = $this->Customers_model->getUser($stud_id);
        else $user = '';
        $data['tutors'] = $tutors;
        $data['user'] = $user;
        $this->load->view('admin/ajaxtutors', $data);
    }
    public function ajaxTutors()
    {
        $searchVar = true;
         if (!isset($_GET)) $searchVar = false;
        if (!isset($_GET['stustring']) || $_GET['stustring'] == '')  $_GET['stustring'] = '';
        //if (!isset($_GET['rect']) || !is_numeric($_GET['rect']) )  $searchVar = false;
        if ($searchVar)
        {
            $this->load->model('Tutors_model');
            
            $tutors = $this->Tutors_model->adminTutors($_GET['stustring']);
            if ($tutors && is_array($tutors))
            {
                  echo "<table border='0' cellpadding='5' cellspacing='5'><tr><th>Student</th><th>E-mail</th></tr>";//<th>Phone</th></tr>";
                  foreach ($tutors as $tutor)
                  {
                     echo "<tr><td>".$tutor->name."</td><td>$tutor->email_list</a></td></tr>";//<td>".$student->phone."</td></tr>";
                  }
                  echo "</table>";
            } else echo 0;
        } else { echo 0; }
        exit;
    }
    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url() . "admin");
    }
    public function invoices()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials) redirect(base_url() . 'admin');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Invoices and Payments', '/admin/invoices');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show(); 
        $qry = "select * from tutors ORDER by name ASC";
        $data['tutors'] = $this->db->query($qry)->result();
        $qry = "select * from customers ORDER by name ASC";
        $this->load->model('admin_model');
        $firstDate =  $this->admin_model->firstDate();
        $lastDate =  $this->admin_model->lastDate();
        $countStudents =  $this->admin_model->countStudents();
        $sumInvoices = $this->admin_model->sumInvoices();
        $sumPaid = $this->admin_model->sumPaid();
        $countRecur = $this->admin_model->countRecur();
        $countFlex = $this->admin_model->countFlex();
        $quantUnpaid = $this->admin_model->countUnpaid();
        $data['quantUnpaid'] = $quantUnpaid;
        $data['countRecur'] = $countRecur;
        $data['countFlex'] = $countFlex;
        $data['sumInvoices'] = $sumInvoices;
        $data['sumPaid'] = $sumPaid;
        $data['firstDate'] = $firstDate;
        $data['lastDate'] = $lastDate;
        $data['countStudents'] = $countStudents;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['customers'] = $this->db->query($qry)->result();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/manage_invoices';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function editInvoice()
    {
         $adminCredentials = $this->session->userdata('adminCredentials');
         if(!$adminCredentials) redirect(base_url() . 'admin');
         $id  = $this->uri->segment(3);
         if (!is_numeric($id)) return;
         $msg = false;
         $this->load->library('admin_library', array());
         if ($_POST)
         {   
              $editor = $this->admin_library->decideInvoiceEditor($_POST);
              if ($editor === true) { $msg = "Invoice has been successfully edited"; }
         }
         $queryStr = "SELECT * FROM purchases WHERE purchase_id = $id LIMIT 1";
         if($this->db->query($queryStr)->num_rows() > 0)
         { foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } } 
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->unshift('Edit Invoice', base_url().'admin/editinvoice');
         $this->breadcrumbs->unshift('Invoices and Payments', base_url().'admin/invoices');
         $this->breadcrumbs->unshift('Home', base_url()."admin");
         $breadcrumbs = $this->breadcrumbs->show(); 
         $data['msg'] = $msg;
         $data['breadcrumbs'] = $breadcrumbs;
         $data['data'] = $data[0];
         $this->load->model('admin_model');
         $data = $this->admin_model->getInvoiceData($data);
         $data['main_content'] = 'admin/edit_invoice';
         $this->load->view('admin/includes/main_view', $data);
    }
    public function createInvoice()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');

        if(!$adminCredentials)
            redirect(base_url() . 'admin');
         if ($_POST)
         {
              $this->load->model('admin_model');
              if (isset($_POST['recurringform']) && 1 == $_POST['recurringform']) { $editor = $this->admin_model->recurringEnter($_POST); }
              elseif (isset($_POST['flexform']) && 1 == $_POST['flexform']) { $editor = $this->admin_model->flexEnter($_POST); }
              elseif (isset($_POST['otherform']) && 1 == $_POST['otherform']) { $editor = $this->admin_model->OtherEnter($_POST); }
              //flexform
         }
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->unshift('Create Invoice', base_url().'admin/createinvoice');
        $this->breadcrumbs->unshift('Invoices and Payments', base_url().'admin/invoices');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show(); 
        // $qry = "select * from tutors";

        //$data['tutors'] = $this->db->query($qry)->result();
         $qry = "select * from customers order by name ASC";

        $data['customers'] = $this->db->query($qry)->result();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/create_invoice';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function manage_tutors() {
        $adminCredentials = $this->session->userdata('adminCredentials');

        if(!$adminCredentials)
            redirect(base_url() . 'admin');
 
         $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Tutors', '/admin/editvalues');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show(); 
         $qry = "select * from tutors";

        

        if(isset($_POST['btnsubmit'])){
            $try = $_POST['keyword'];
            $key = sanitize_input(TRIM($try));

            if(empty($key)){
                redirect(base_url('admin/manage_tutors'));
            } else {
                $data['spanish'] = $this->db->query("select * from tutors  WHERE name like '%$key%' OR email_list like '%$key%' OR skype_name like '%$key%' OR bio like '%$key%'")->result();
                 
            }
        } else $data['spanish'] = $this->db->query($qry)->result();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/manage_tutor_view';
        $this->load->view('admin/includes/main_view', $data);
    }
     public function pages() {

        $adminCredentials = $this->session->userdata('adminCredentials');
 
        if(!$adminCredentials)
            redirect(base_url() . 'admin');    // if they are not logged in force them to log in


        $id        = $this->uri->segment(3);  // 3rd item = pages
        $contents  = $this->input->post('contents');   // for future, not used yet 
        $titles    = $this->input->post('titles');     // not used
        $to_update = array
        ( 'page_title'   => $titles,
          'page_content' => $contents );

        if(isset($_POST['update'])){

            $this->My_model->update_where('pages', $to_update, array( 'page_id' => $id ));
        }
        $data['page_data'] = $this->My_model->select_where('pages', array( 'page_id' => $this->uri->segment(3) ));
       /* $data['page_list'] = $this->My_model->select_table('pages');
        /*$data['first']     = $this->db->query("SELECT * FROM pages ORDER BY page_id DESC LIMIT 0, 1")->row();*/
        // echo $this->db->last_query();die;

        $data['main_content'] = 'admin/pages_view';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function editCalendar()
    {
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $this->breadcrumbs->push('Edit Calendar', '/admin/add_tutor');
        
       
        $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
        //$data['main_content'] = 'admin/add_user_view';
        $data['main_content'] = 'admin/calendar';
        $data['type'] = 1;
        $data['customer_ID'] = 1;
       
        $this->load->view('admin/includes/main_view', $data);
    }

    /**************************************/
    public function add_tutor()
    {
	$adminCredentials = $this->session->userdata('adminCredentials');
	if ($adminCredentials == '') exit;	
        $data['success'] = '<p>&nbsp;</p>';	
        $names = $this->input->post('name');
        $pword = $this->input->post('password');
        $cpword = $this->input->post('cpassword');
        $email = $this->input->post('emails');
        $user_type = $this->input->post('user_type');
        $phone = $this->input->post('phone');
         $description = $this->input->post('description');
	  $to_insert = array(
                'name'=>$names,
                'password'=>md5($pword),
                'email_list'=>$email,
                /*'user_type' => $user_type,*/
                'inactive' => 0,
              'deleted' => 0,
              'bio' => $description,
              'phone' => $phone,
                  'skype_name' => $this->input->post('skype_name'),
              'time_zone' => $this->input->post('time_zone'),
              'notes_tutor_viewable' => $this->input->post('notes_tutor_viewable'),
              'notes_hidden_from_tutor' => $this->input->post('notes_hidden_from_tutor')
        );
		
        $this->form_validation->set_error_delimiters('<p class="fl" style="color:red; padding-top:5px; padding-left:5px;">', '</p>');
        $this->form_validation->set_rules('name', 'Name', 'required|is_unique[admins');
         $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');
        $this->form_validation->set_rules('cpassword', 'Confirm password', 'required');
        $this->form_validation->set_rules('emails', 'Email', 'required|valid_email|');
		
		
        if(isset($_POST['add'])){
            if ($this->form_validation->run() == FALSE){
                    $data['success'] = "<p>ERROR &nbsp;</p>";
            }
            else{
                $this->My_model->inserting('tutors',$to_insert);
                $thenewid = $this->db->insert_id();
                $config['upload_path'] = './uploads/tutors';
                $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|pdf';
                $config['max_size']	= '1000';
                $this->upload->initialize($config); 
                if($this->upload->do_upload('rolephoto'))//yes, someone chose to upload a file
                {
                   $uploaded = $this->upload->data('rolephoto');
                   $subject = $uploaded['file_type'];
                               $width = $uploaded['image_width'];
                                            $height = $uploaded['image_height'];
                    if ($width > 400)
                    {
                            $height = $height * (400 / $width);
                            $width = 400;
                    }
                    elseif ($height > 400)
                    {
                            $width = $width * (400 / $height);
                            $height = 400;
                    }
                    $this->load->library('image_moo');
                    $imgfile      = $uploaded['file_name'];
                    $this->image_moo
                    ->load('./uploads/tutors/' . $imgfile)
                    ->resize_crop($width, $height)
                    ->save_pa($prepend = "", $append = "", $overwrite = true);
                    $thenewid;
                    $where = array("tutor_ID" => $thenewid);
                    $to_update = array('photo' => $imgfile);
                    $this->My_model->update_where('tutors', $to_update, $where);
                }
                $data['success'] = "<p style='color:green !important;'>Successfully Add User</p>";
                 redirect(base_url() . 'admin/manage_tutors');
            }
        }
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Tutors', '/admin/manage_tutors');
        $this->breadcrumbs->push('Add Tutor', '/admin/add_tutor');
        
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
        //$data['main_content'] = 'admin/add_user_view';
        $data['main_content'] = 'admin/view_tutor_edit';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function edit_tutor()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials) redirect(base_url() . 'admin');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Tutors', '/admin/manage_tutors');
        $this->breadcrumbs->push('Edit Tutors', '/admin/editvalues');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
        $data['success'] = '<p>&nbsp;</p>';	
       
        if(isset($_POST['edittutor'])){
             $this->load->model('Tutors_model');
             $data = $this->Tutors_model->editTutorData();
        }
        $id  = $this->uri->segment(3);
        $qry = "select * from tutors WHERE tutor_ID = $id";
        $data['breadcrumbs'] = $breadcrumbs;
        $data['spanish'] = $this->db->query($qry)->row();
        $data['main_content'] = 'admin/view_tutor_edit';
        $this->load->view('admin/includes/main_view', $data);
    }
    public function getStudMails()
    {
        $this->load->library('breadcrumbs');
       // $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Student Emails', '/admin/getstudmails');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
        
        $this->load->model('Tutors_model');
        $this->load->model('Customers_model');
        
        
        $students = $this->Tutors_model->adminStudents();
        $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) ) ? $_GET['order'] : 0; 
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['students'] = $students;
        //$data['bills'] = $purchs;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/view_students';
        $this->load->view('front/includes/main_view', $data);
    }
    public function gettutmails()
    {
         $this->load->library('breadcrumbs');
       // $this->breadcrumbs->push('Tutor Page', '/tutors/profile');
        $this->breadcrumbs->push('Tutor Emails', '/admin/gettutmails');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
        
        $this->load->model('Tutors_model');
        $this->load->model('Customers_model');
        
        
        $tutors = $this->Tutors_model->adminTutors();
        $data['Customers_model'] = $this->Customers_model;
        $data['tutor_model'] = $this->Tutors_model;
        
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) ) ? $_GET['order'] : 0; 
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Tutor Profile';
        $data['tutors'] = $tutors;
        //$data['bills'] = $purchs;
        $data['breadcrumbs'] = $breadcrumbs;
        $data['main_content'] = 'admin/view_tutors';
        $this->load->view('front/includes/main_view', $data);
    }
    public function view_tutor() {
        $adminCredentials = $this->session->userdata('adminCredentials');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('View Tutor', base_url().'admin/view_tutor');
        $this->breadcrumbs->unshift('Edit Tutors', base_url().'admin/manage_tutors');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
        if(!$adminCredentials)
            redirect(base_url() . 'admin');

        $id  = $this->uri->segment(3);
        $qry = "
			select * from tutors WHERE tutor_ID = $id";


        $data['spanish'] = $this->db->query($qry)->row();
         $data['breadcrumbs'] = $breadcrumbs;

         
        $data['main_content'] = 'admin/view_tutor_view';
        $this->load->view('admin/includes/main_view', $data);
    }

 

    public function delete_tutor($aw = NULL) {
        $adminCredentials = $this->session->userdata('adminCredentials');

        if(!$adminCredentials)
            redirect(base_url() . 'admin');
        $this->My_model->delete_where('tutors', array( 'tutor_ID' => $aw ));
        echo "<script>alert('Successfully Deleted');window.location.href='" . base_url() . 'admin/manage_tutors' . "'</script>";
    }

    public function delete_student($aw = NULL) {
        $adminCredentials = $this->session->userdata('adminCredentials');

        if(!$adminCredentials)
            redirect(base_url() . 'admin');
        $this->My_model->delete_where('customers', array( 'Customer_ID' => $aw ));
        echo "<script>alert('Successfully Deleted');window.location.href='" . base_url() . 'admin/editstudents' . "'</script>";
    }
    public function admin_setting() {
        $adminCredentials = $this->session->userdata('adminCredentials');
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Edit Admin Account', '/admin/admin_setting');
        $this->breadcrumbs->unshift('Home', base_url()."admin");
        $breadcrumbs = $this->breadcrumbs->show();
        if(!$adminCredentials) { redirect(base_url() . 'admin'); }
        $data['title'] = 'Admin | Settings';
 
        $data['success'] = "<p> &nbsp;</p>";
        
        $qry = "SELECT * FROM admins";
        if(isset($_POST['btnsubmit'])){ 
            $try = $_POST['keyword'];
            $key = sanitize_input(TRIM($try));
            $qry = "select * from admins WHERE username like '%$key%' OR email like '%$key%' OR skype like '%$key%'";
            $data['spanish'] = $this->db->query($qry)->result();
        } else $data['spanish'] = $this->db->query($qry)->result();
        $data['admin'] = $this->db->query($qry)->result();
        $data['main_content'] = 'admin/admins_list';
        $data['breadcrumbs'] = $breadcrumbs;
        $this->load->view('admin/includes/main_view', $data);
    }
    public function add_Admin()
    {
        $this->editAdmin(true);
    }
    public function delete_Admin()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials) { redirect(base_url() . 'admin'); }
        $admin_type = $adminCredentials['admin_type'];
        if ($admin_type < 2) { $this->DelAdminMsg('You lack the power to delete other administrators'); }
        $admin_id = $this->uri->segment(3);
        $this->load->model('admin_model');
        $result = $this->admin_model->getAdminById($admin_id);
        if ($result === false) { $this->DelAdminMsg('Invalid Data'); }
        elseif ((int)$adminCredentials['admin_id'] == (int)$result->admin_id)
        { $this->DelAdminMsg('You cannot delete yourself'); }
        elseif (strtolower($result->username) == 'admin')
        { $this->DelAdminMsg('You cannot delete the main administrator'); }
        else {
            $this->admin_model->deleteAdmin($admin_id);
            $this->DelAdminMsg('Successfully Deleted'); 
        }
    }
    public function view_Admin()
    {
         $admin_id = $this->uri->segment(3);
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->push('View Administrator',base_url()."admin/view_admin");
         $this->breadcrumbs->unshift('Edit Admin Accounts',base_url()."admin/admin_setting");
         $this->breadcrumbs->unshift('Home', base_url()."admin");;
         $breadcrumbs = $this->breadcrumbs->show();
         $data['spanish'] = $this->My_model->select_where('admins', array( 'admin_id' => $admin_id ));
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Administrator Profile';
         
      
         $data['breadcrumbs'] = $breadcrumbs;
        
        $data['main_content'] = 'admin/view_admin';
        $this->load->view('front/includes/main_view', $data);
    }
    public function editYourAccount()
    {
        $adminCredentials = $this->session->userdata('adminCredentials');
       
        $this->editAdmin(false, $adminCredentials['admin_id']);
    }
    public function editAdmin($add_admin = false, $admin_id = '')
    {
         $adminCredentials = $this->session->userdata('adminCredentials');
         if (!is_numeric($admin_id) || $admin_id == 0) { $admin_id = $this->uri->segment(3); }
         $this->load->library('breadcrumbs');
         if (true === $add_admin) { $this->breadcrumbs->push('Add  Admin Account', base_url().'admin/admin_setting'); }
         else { $this->breadcrumbs->push('Edit Admin Account', base_url().'admin/admin_setting'); }
         $this->breadcrumbs->unshift('Edit Admin Accounts', base_url().'admin/admin_setting');
         $this->breadcrumbs->unshift('Home', base_url()."admin");
         $breadcrumbs = $this->breadcrumbs->show();
         $success = '';
         if ( ( isset($_POST['admin_add']) && 1 == $_POST['admin_add'] ) || ( isset($_POST['admin_edit']) && 1 == $_POST['admin_edit'] )) 
         {
              $uniqueMail = (true === $add_admin) ? "|is_unique[admins.email]" : '';
              $this->form_validation->set_rules('email', 'Email Address', 'required|trim|callback_init_value|valid_email'.$uniqueMail);
              $this->form_validation->set_rules('uname', 'Username', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('phone', 'Phone', 'required|trim|callback_init_value');
              $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');
              $this->form_validation->set_message('valid_email', $this->input->post('email') . ' is not a valid email');
              $this->form_validation->set_message('is_unique', $this->input->post('email') . ' is already registered');
              $this->form_validation->set_message('required', '%s is required.');
              $this->form_validation->set_message('matches', '%s did not match.');
              $this->form_validation->set_message('min_length', '%s must be at least 5 characters.');
             
              if (true === $add_admin) { $this->form_validation->set_rules('pwd', 'Password', 'trim|required|matches[conf_pwd]|min_length[5]'); }    
              if(TRUE == $this->form_validation->run())
              { list($second_error, $success, $password_error) = $this->_editAdmin_2($add_admin, $adminCredentials, $_POST); }
              else { $err = "There has been an error"; }
         }
         $data['add_admin'] = $add_admin;
         $data['password_error'] = isset($password_error) ? $password_error : '';
         $data['spanish'] = $this->My_model->select_where('admins', array( 'admin_id' => $admin_id ));
         $data['adminCredentials'] = $adminCredentials;
         $data['main_content'] = 'admin/admin_setting_view';
         $data['breadcrumbs'] = $breadcrumbs;
         $data['success'] = $success;
         $this->load->view('admin/includes/main_view', $data);
    }
    private function _editAdmin_2($add_admin, $adminCredentials, $post)
    {
         if (true !== $add_admin) { $where = array("admin_id" => $post['admin_id']); }
         $second_error = false;
         $password_error = false;
         $to_update = array('admin_type' => $post['admin_type'], 'skype' => $post['skype'],'time_zone' => $post['time_zone'], 'username' => $post['uname'], 'email' => $post['email'], 'phone' => $post['phone']);
         if (isset($post['pwd']) && trim($post['pwd']) != '')
         {  
             if ($post['pwd'] != $post['conf_pwd'])
             {
                  $second_error = true;
                  $password_error = "Passwords are not the same";
             }
             else { $to_update['password'] = md5($post['pwd']); }
         }
         if (true === $add_admin) {   $success = $this->_addNewAdmin($adminCredentials, $to_update, $second_error); }
         else { list($second_error, $success) = $this->_editExistingAdmin($adminCredentials, $to_update, $where, $second_error); }
         return array($second_error, $success, $password_error);
    }
    /**********General Seeting*************/
    public function general_setting() {
        //Put this code for restriction of login

        $adminCredentials = $this->session->userdata('adminCredentials');
        if(!$adminCredentials)
            redirect(base_url() . 'admin');


        $site_title = $this->input->post('site_title');
        $tag_line   = $this->input->post('tag_line');
        $meta_tags  = $this->input->post('meta_tags');
        $email      = $this->input->post('email');

        $to_update = array(
            'site_title'    => $site_title,
            'tag_line'      => $tag_line,
            'meta'          => $meta_tags,
            'email_address' => $email
        );

        $history_insert = array(
            'title'       => 'Update General Setting',
            'body'        => 'Update General Setting',
            'date_record' => date("Y-m-d H:m:s")
        );


        if(isset($_POST['update'])){
            $this->My_model->update_all_c('general_setting', $to_update, array( 'id' => 1 ));
            //$this->My_model->inserting('history',$history_insert);
            $data['success'] = "<p style='color:green;'>Settings Saved</p>";
        }


        $data['query'] = $this->My_model->select_where('general_setting', array( 'id' => 1 ));

        $data['main_content'] = 'admin/general_setting_view';
        $this->load->view('admin/includes/main_view', $data);


    }

    public function forgotpass() {


        if($this->input->post('send')){
            $email = $this->input->post('email');

            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            if($this->form_validation->run() == FALSE){
            } else {

                $length       = 10;
                $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for($i = 0; $i < $length; $i++){
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }

                $password = $randomString;
                $query    = $this->db->query("SELECT * FROM members WHERE email = '$email' and admin_id = 1");

                if($query->num_rows() > 0){

                    $to_update = array(
                        'password' => $password
                    );

                    $where = array(
                        'email' => $email
                    );

                    $this->db->where($where)->update('members', $to_update);
                    $message = 'This is your new password ' . $password . " Please do change your password in admin settings after you logged in.";

                    $config['mailtype'] = 'html';

                    $this->email->initialize($config);

                    $this->email->from($_POST['email']);
                    $this->email->to($_POST['email']);
                    $this->email->subject('Professional 101 spanish | Forgot Password');
                    $this->email->message($message);

                    $this->email->send();
                    echo "<script>alert('Successfully Sent');window.close();</script>";

                    // echo '<script language="javascript">alert("Wait for confirmation"); </script>';
                }
            }
        }
        $this->load->view('admin/forgot_view');

    }

    

    public function editPrices()
    {
         $adminCredentials = $this->session->userdata('adminCredentials');
         if(!$adminCredentials) redirect(base_url() . 'admin');
         $data['main_content'] = 'admin/general_prices';
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->push('Edit Prices and Offerings',base_url()."admin/editprices");
         $this->breadcrumbs->unshift('Home', base_url()."admin");;
         $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
         $this->load->model('admin_model');
         $prices = $this->admin_model->getPrices();
         $data['prices'] = $prices;
         $this->load->view('admin/includes/main_view', $data);
    }
    public function  changPricjax()
    {
          $id = $_POST['id'];
          $type = sanitize_input($_POST['type']);
          $price = $_POST['price'];
          $quantity = $_POST['quantity'];
          if (!is_numeric($id) || !is_numeric($quantity) || !is_numeric($price)) { echo "ERROR"; exit; } 
          $to_insert = array('type' => $type, 'price' => $price, 'quantity' => $quantity);
          $where = array("id" => $id);
          $this->My_model->update_where('prices',$to_insert, $where);
          echo "SUCCESS";
          exit;
    }
                             
}

