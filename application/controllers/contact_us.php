<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Contact_us extends SP_Controller {
    public function __construct() {
        parent::__construct();
        #ini_set('post_max_size', '100M'); // not working T_T
        #ini_set('max_file_uploads', '100M'); // not working T_T
    }

    public function index() {


        if(isset($_POST['send'])){
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('who', 'Who', 'required');
            $this->form_validation->set_rules('msg', 'Message', 'required');

            $this->form_validation->set_message('required', 'required');
            $this->form_validation->set_message('valid_email', 'Invalid %s');

            $this->form_validation->set_error_delimiters('<em style="color: white;font-size: 12px;">', '</em>');

            if($this->form_validation->run() == TRUE){

                $msg     = <<<qaz
				<html>
					<body>
						<h1>Inquiry | Professional 101 spanish</h1>
						<ul>
							<li><b>Name: </b> {$_POST['name']}</li>
							<li><b>Email: </b> {$_POST['email']}</li>
							<li><b>Contact Number: </b> {$_POST['cont_num']}</li>
							<li><b>He/She is a: </b> {$_POST['who']}</li>
							<li><b>Message: </b> {$_POST['msg']}</li>
						</ul>
					</body>
				</html>
qaz;
                $emailTo = $this->My_model->select_where('admins', array( 'username' => 'admin' ));
                //wag mo na baguhin to henry mae, thanks.
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: <' . $emailTo->email . '>' . "\r\n";
                $headers .= "From: " . $_POST['email'] . "\r\n";
                $subject = 'Inquiry| Professional 101 spanish';
                $send    = mail($emailTo->email, $subject, $msg, $headers);
                if($send){
                    echo "<script>alert('Successfully Sent');window.location = 'home';</script>";
                }
            }
        }

        $data['page_title']   = 'Professional 101 spanish | Contact Us';
        $data['main_content'] = 'front/contact/contact_view';
        $this->load->view('front/includes/main_view', $data);
    }

    public function hey() {
        phpinfo();
        die;
    }


}