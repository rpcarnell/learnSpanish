<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Signup_login extends SP_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Sign Up / Log In';
        $data['main_content'] = 'front/signup/sign_login_main';
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Membership', '/signup_login');
         $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $this->load->view('front/includes/main_view', $data);
    }
    public function login()
    {
        $data['err_log'] = '';
       
         //$this->session->sess_destroy();
        if(isset($_POST['login'])){
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('pwd', 'Password', 'trim|required');

            if($this->form_validation->run() == TRUE){
                $det = $this->My_model->select_where('customers', array( 'email_list' => $_POST['email'] ));
                
                if(count($det) == 1){
                    $pwd = md5($_POST['pwd'] . $det->date_reg);
                    if($pwd == $det->password){
                        if($det->inactive == 1){
                            $this->session->set_userdata('cur_reg',  $det->Customer_ID);
                            redirect(base_url('signup_login/membership_requirements'));
                        } else {  
                            $add = array( 'customerid' => $det->Customer_ID, 'email' => $det->email_list, 'login_customer' => TRUE );
                            $this->session->set_userdata($add);
                            redirect(base_url('profile'));
                        }
                    } else
                        $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
                } else
                    $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
            } else
                $data['err_log'] = '<p style="text-align: center; color: #670000;font-size: 12px;">Invalid Email/Password.</p>';
        }
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Membership', '/signup_login');
         $this->breadcrumbs->push('Login', '/signup_login/login');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
         
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Log In';
        $data['main_content'] = 'front/signup/sign_login_view';
         
        $this->load->view('front/includes/main_view', $data);
    }
    public function editProfile()
    {
         $data['customer'] = $this->My_model->select_where('customers', array( 'Customer_ID' => $this->session->userdata('customerid') ));
         $customerid = $this->session->userdata('customerid');
         $profile_id = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
         if ($profile_id == 0) return;
         $this->load->model('Customers_model');
         $err = '';
         $password_error = '';
         $emailerror = '';
         $success = '';
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | My Profile';
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
         $this->load->library('breadcrumbs');
         
         $row = $this->Customers_model->getUser($profile_id);
         if ($customerid != $row->Customer_ID) { return; }
         $this->breadcrumbs->push('Profile', '/profile');
         $this->breadcrumbs->push('Edit Profile', '/signup_login/editProfile');
         $this->breadcrumbs->unshift('Home', base_url());
         $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
         $data['emailerror'] = $emailerror;
         $data['err'] = $err;
         $data['success'] = $success;
         $data['password_error'] = $password_error;
         $data['row'] = $row;
         $data['main_content'] = 'front/signup/profile_edit';
         $this->load->view('front/includes/main_view', $data);
    }
    public function signup() {
        $data['err_log'] = '';
        

        $data['err'] = 0;
        if(isset($_POST['reg'])){
            $this->form_validation->set_rules('fname', 'First Name', 'trim|callback_init_value');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|callback_init_value');
            $this->form_validation->set_rules('email2', 'Email Address', 'trim|callback_init_value|valid_email|is_unique[customers.email_list]');
            $this->form_validation->set_rules('pwd', 'Password', 'trim|required|matches[conf_pwd]|min_length[5]');
            
            $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');
            $this->form_validation->set_rules('notes_cust_viewable', 'Notes', 'trim|required');

            $this->form_validation->set_message('valid_email', $this->input->post('email2') . ' is not a valid email');
            $this->form_validation->set_message('is_unique', $this->input->post('email2') . ' is already registered');
            $this->form_validation->set_message('required', '%s is required.');
            $this->form_validation->set_message('matches', '%s did not match.');
            $this->form_validation->set_message('min_length', '%s must be at least 5 characters.');

            $this->form_validation->set_error_delimiters('<em>', '</em><br/>');

            if($this->form_validation->run() == TRUE){
                $reg = date('Y-m-d h:i:s');
               // $id  = date('mydhis') . '-D' . $this->My_model->count_table('dancer');
                $add = array(
                   
                    'name'     => sanitize_input($_POST['fname'])." ".sanitize_input($_POST['lname']),
                    'email_list'     => sanitize_input($_POST['email2']),
                    'password'       => md5($_POST['pwd'] . $reg),
                    'date_reg'  => $reg,
                    'skype'  => "'".sanitize_input($_POST['skype'])."'",
                    'phone'  => sanitize_input($_POST['phone']),
                    'how_did_you_find_us' => sanitize_input($_POST['how_did_you_find_us']),
                    'time_zone' => sanitize_input($_POST['time_zone']),
                    'notes_cust_viewable' => sanitize_input($_POST['notes_cust_viewable'])
                );

                /***/
                $msg = <<<qaz
<html>
    <body>
    <h1>Professional 101 spanish Website Membership Confirmation</h1>
    <h4>NAME:          		{$_POST['fname']} {$_POST['lname']}</h4>
    <h4>EMAIL:       		{$_POST['email2']}</h4>
    <h2>THANK YOU FOR SIGNING UP!</h2>
    <p>Thank you for your interest in Professional101spanish.com</p>
    <p>Please visit the following link to confirm your membership: </p>
    </body>
</html>
qaz;
                /***/
                $config = $this->config->item('smtpsettings');
//                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $emailTo = $this->My_model->select_where('admins', array( 'username' => 'admin' ));

                $this->email->from($emailTo->email);
                $this->email->to($_POST['email2']);
                $this->email->subject('Professional 101 spanish | Email');
                $this->email->message($msg);

                $this->email->send();
                $this->My_model->inserting('customers', $add);

               // $this->My_model->inserting('forg_pass', array( 'dancer_id' => $id ));
               // $this->My_model->inserting('profile', array( 'dancer_id' => $id ));
                 $id = $this->db->insert_id();
                 
                $this->session->set_userdata('cur_reg', $id);

                redirect(base_url('signup_login/membership_requirements'));
            } else
                $data['err'] = 1;
        }
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Membership', '/signup_login');
         $this->breadcrumbs->push('Sign-Up', '/signup_login/signup');
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Sign Up / Log In';
        $data['main_content'] = 'front/signup/signup_view';
        $this->load->view('front/includes/main_view', $data);
    }
    
    public function signup_success() {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Sign Up Success';
        $data['main_content'] = 'front/signup/signup_success_view';
        $this->load->view('front/includes/main_view', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('signup_login'));
    }

    public function membership_requirements() {
        //$this->session->sess_destroy();
        if($this->session->userdata('cur_reg') == '')
            redirect(base_url('signup_login'));
        $emailTo              = $this->My_model->select_where('admins', array( 'username' => 'admin' ));
        $data['adminEmail']        = $emailTo->email;

        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Sign Up / Log In';
        $data['main_content'] = 'front/signup/mem_req_view';
        $this->load->view('front/includes/main_view', $data);
    }
/*
    public function add_req() {
        $ret = array();

        $this->form_validation->set_rules('loc', 'Location', 'required');
        $this->form_validation->set_rules('expertise', 'Expertise', 'required');
        $this->form_validation->set_rules('desc', 'Description', 'required');
        $this->form_validation->set_rules('agree', 'Agreeing to honor the pledge', 'required');

        $this->form_validation->set_message('required', '%s is required.');
        $this->form_validation->set_error_delimiters('<em>', '</em><br/>');

        $x      = 0;
        $c_name = 0;
        while($x < count($_POST['name'])){
            if($_POST['name'][$x] != '')
                $c_name++;
            $x++;
        }

        if($this->form_validation->run() == TRUE && $c_name > 2){
            $dancer_id = $this->session->userdata('cur_reg');

            $add = array(
                'dancer_id'   => $dancer_id,
                'location'    => sanitize_input($_POST['loc']),
                'expertise'   => sanitize_input(implode(',', $_POST['expertise'])),
                'desc'        => sanitize_input($_POST['desc']),
                'video_links' => sanitize_input($_POST['links'])
            );

            $this->My_model->inserting('mem_req', $add);
            
            
            
            $ref_name = $_POST['name'];
            $ref_info = $_POST['info'];

            $x = 0;
            while($x < count($ref_name) || $x < count($ref_info)){
                if($ref_name[$x] != '' || $ref_info[$x] != ''){
                    $this->My_model->inserting('reference', array(
                        'dancer_id' => $dancer_id,
                        'name'      => sanitize_input($ref_name[$x]),
                        'info'      => sanitize_input($ref_info[$x])
                    ));
                }
                $x++;
            }

            $this->My_model->inserting('for_regi', array( 'dancer_id' => $dancer_id, 'verfication' => random_char(20) ));
            
            $upd_prox_1 = array('type'        => $_POST['type']);  
            $this->My_model->update_where('dancer',  $upd_prox_1 , array( 'dancer_id' => $dancer_id ));
            $ret = array(
                'error' => 0
            );

            $this->session->sess_destroy();
        } else {
            $ret = array(
                'error'    => 1,
                'loc'      => form_error('loc'),
                'exp'      => form_error('exp'),
                'desc'     => form_error('desc'),
                'agree'    => form_error('agree'),
                'num_name' => ($c_name > 2 ? '' : '<em>You must have at least 3 references.</em><br/>')
            );
        }

        echo json_encode($ret);
    }
*/
    public function forg_pass() {
        $det = $this->My_model->select_where('customers', array( 'email_list' => $_POST['email'] ));
         
        if(count($det) == 1){
            $newPW = random_char(5);
            $pwd_hash = md5($newPW . $det->date_reg);
            $this->db->query("UPDATE customers SET password = '".$pwd_hash."' WHERE email_list = '".$_POST['email']."'");
            $msg = <<<qaz
			<html>
				<body>
					<h1>Forgot Password | Professional 101 spanish</h1>
					<p><b>Your new password:</b> {$newPW}</p>
				</body>
			</html>
qaz;

//            $config['mailtype'] = 'html';
            $config = $this->config->item('smtpsettings');
            $this->email->initialize($config);
            $emailTo = $this->My_model->select_where('admins', array( 'username' => 'admin' ));

            $this->email->from($emailTo->email, 'Professional 101 spanish - Admin');
            $this->email->to($_POST['email']);
            $this->email->subject('Forgot Password| Professional 101 spanish');
            $this->email->message($msg);


            if($this->email->send()){

            } else {
                echo $this->email->print_debugger();
            }
            echo 'New password was sent to ' . $_POST['email'];
        } else
            echo 'Email: <b>' . $_POST['email'] . '</b> does not exist.';
    }

    /***********CALLBACK******************/

    public function init_value($str) {
        if(in_array($str, array( 'First Name', 'Last Name', 'E-mail Address' ))){
            $this->form_validation->set_message('init_value', '%s is required.');
            return FALSE;
        } else
            return TRUE;
    }
}


