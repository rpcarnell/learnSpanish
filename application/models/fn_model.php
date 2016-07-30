<?php 
class Fn_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
        public function getGlobals()
        {
             $query = "SELECT * FROM GlobalValues";
             $row = $this->db->query($query)->result();
             return ($row) ? $row[0] : false;
        }
        public function updateGlobals()
        {
            if (!is_numeric($_POST['chron-job-seconds'])) return "Value of cron job must be numeric";
            if (!is_numeric($_POST['makeup-allowance'])) return "Value of Makeup Allowance must be numeric";
            if (!is_numeric($_POST['delay-before-change-notice'])) return "Value of Delay Before Change Notice must be numeric";
            if (trim($_POST['pictures-location-path']) == '')  return "Location Path cannot be empty";
            if (trim($_POST['store-name']) == '')  return "Store Name cannot be empty";
            if (!is_numeric($_POST['nearest-hours-to-change'])) return "Value of Closest Scheduling Allowed must be numeric";
            $timezone = (isset($_POST['time-zone'])) ? $_POST['time-zone'] : '';
            if (!is_numeric($_POST['future-booking-limit'])) return "Value Farthest in the Future Allowed must be numeric";
            $query = "UPDATE GlobalValues SET `time-zone` = '$timezone', `chron-job-seconds`='".($_POST['chron-job-seconds'] * 60)."', `makeup-allowance`='".$_POST['makeup-allowance']."', `delay-before-change-notice`='".$_POST['delay-before-change-notice']."', `pictures-location-path`='".$_POST['pictures-location-path']."', `store-name`='".$_POST['store-name']."', `future-booking-limit`='".$_POST['future-booking-limit']."', `nearest-hours-to-change`='".$_POST['nearest-hours-to-change']."'";
            $this->db->query($query); 
            return 1;
        }
	public function ch_login()
	{
		if(!$this->session->userdata('login_customer'))
		{ 
		     redirect(base_url('signup_login'));
		}
                else
                {
                    $customer_id = $this->session->userdata('customerid');
                     if (!is_numeric($customer_id))
                     {
                            $this->session->sess_destroy();
                     }
		      
                }
	}
        public function ch_login1()
	{
            
		if($this->session->userdata('login_customer'))
		{
                     $customer_id = $this->session->userdata('customerid');
                     if (!is_numeric($customer_id))
                     {
                            $this->session->sess_destroy();
                     }
		     else redirect(base_url('profile'));
		}
	}
        public function ch_login2()
	{
		if($this->session->userdata('login_tutor'))
		{
			redirect(base_url('tutors/profile'));
		}
	}
        public function ch_tutorlogin()
	{
		if(!$this->session->userdata('login_tutor'))
		{
			redirect(base_url('tutors/login'));
		}
	}
}
?>
