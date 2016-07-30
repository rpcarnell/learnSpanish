<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Profile extends SP_Controller {

    var $vid_name, $cover_name;
    private $_audioImg;

    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
        $this->Fn_model->ch_login();
         $customer_ID = $this->session->userdata('customerid');
        if (is_numeric($customer_ID) && $customer_ID > 0)
        { 
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($customer_ID);
            //echo "timezone is ".$time_zone;
            date_default_timezone_set($time_zone);//***** we set the timezone first
        } 
    }
    public function index() {
        $customer_id = $this->session->userdata('customerid');
        if (!is_numeric($customer_id)) {
              redirect(base_url('signup_login'));
        }
        $data['customer'] = $this->My_model->select_where('customers', array( 'Customer_ID' => $customer_id ));
        //$current_date     = time();
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | My Profile';
         /*if(isset($_POST['submit'])){
            $this->form_validation->set_rules('youtube_link', 'Youtube Id', 'required');
            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()){

                $youtube_url = explode('=', $_POST['youtube_link']);
                $headers     = get_headers('https://www.youtube.com/watch?v=' . end($youtube_url));
                if(!strpos($headers[0], '200')){
                    $data['err'] = 'Invalid Video Id';
                } elseif(preg_match('/^http(s|)\/\/www\.youtube.com\/watch\?v=[A-Za-z0-9-_]$/', $_POST['youtube_link'])) {
                    
                    $data['err'] = 'Invalud Youtube video URL';
                } else {
                     $video_str  = (isset($_POST['youtube_title']) && $_POST['youtube_title'] != '') ? $_POST['youtube_title']: $customerid;//(string)$video_obj->entry->title;

                    $to_insert = array( 'customerid'    => $customerid,
                                        'dancer_video' => end($youtube_url),
                                        'video_title'  => $video_str

                    );

                    $this->My_model->inserting('video_dancer', $to_insert);
                    redirect(base_url() . 'profile');
                }
            }
        }*/
        $this->load->model('Customers_model');
        $unpaid = $this->Customers_model->getUnPaid($customer_id);
        $teachers = $this->Customers_model->getUserTutors($customer_id);
        $teachersNum = count($teachers);
        unset($teachers);    // finished counting, so dispose
        $outs = '';   // outstanding bills
        if (is_array($unpaid)) { $sum = 0;
            foreach ($unpaid as $unp) { $sum += $unp->price_paid; }
            $outs .= "<p><a href='".base_url()."billing/balance/".$customer_id."'>Pay Outstanding Balance of $".$sum."</a></p>";
        }
        $data['outs'] = $outs; // application/views/front/profile for source code
        $data['teachersNum'] = $teachersNum;
        $data['main_content'] = 'front/profile/profile_view';
        // add /includes to the path below to find the code
        $this->load->view('front/includes/main_view', $data);  // calls "view" in the view directory which is all the code for handling users.
    }


    public function managSubscr()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Manage Subscriptions';
        $data['main_content'] = 'front/profile/managSubscr';
        $this->load->model('Customers_model');
      
         $this->load->library('breadcrumbs');
          $this->breadcrumbs->push('Profile', '/profile');
         $this->breadcrumbs->push('Manage Subscriptions', '/profile/managSubscr');
         $this->breadcrumbs->unshift('Home', base_url());
           $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $rows = $this->Customers_model->getUserRecurrings($customer_ID);
        $data['recurring'] = $rows;
        $data['Customers_model'] = $this->Customers_model;
         $this->load->model('Tutors_model');
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->view('front/includes/main_view', $data);
    }


    public function emailtutor()
    {
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | E-Mail Tutors';
        $data['main_content'] = 'front/profile/emailtutor';
        $this->load->model('Customers_model');
         $this->load->library('breadcrumbs');
          $this->breadcrumbs->push('Profile', '/profile');
         $this->breadcrumbs->push('Manage Subscriptions', '/profile/emailtutor');
         $this->breadcrumbs->unshift('Home', base_url());
           $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $rows = $this->Customers_model->getUserTutors($customer_ID);
         $data['tutors'] = $rows;
        $data['Customers_model'] = $this->Customers_model;
         $this->load->model('Tutors_model');
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->view('front/includes/main_view', $data);
    }


    public function managSub()
    {
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Manage Subscriptions';
        $data['main_content'] = 'front/profile/managOneSubs';
        $this->load->model('Customers_model');
         $this->load->library('breadcrumbs');
          $this->breadcrumbs->push('Profile', '/profile');
         $this->breadcrumbs->push('Manage Subscriptions', '/profile/managSubscr');
          $this->breadcrumbs->push('Edit a Subscription', '/profile/managSub');
         $this->breadcrumbs->unshift('Home', base_url());
           $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $item_ID = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $msg = '';
        if ($_POST && isset($_POST['managesubscr']))
        {
             $theDate = isset($_POST['pause-start-date']) ? $_POST['pause-start-date'] : '';
             if ($theDate != '')
             {
                 $date_sd = explode('/',$_POST['pause-start-date']);
                 $date_sd = $date_sd[2]."-".$date_sd[0]."-".$date_sd[1];
                 $date_sd = $date_sd ." 00:00:00";
             }
             else $date_sd = '';
             $pauselengthdays = ('Indefinite' == $_POST['pause-length-days']) ? 7777 : $_POST['pause-length-days'];
             $lenghtsmonths = (0 == $_POST['lenghts-months']) ? 7777 : $_POST['lenghts-months'];
             $to_insert = array(
                        'pause-start-date' => sanitize_input($date_sd),
                        'lenghts-months'     => sanitize_input($lenghtsmonths),
                        'minutes-per-week'        => sanitize_input($_POST['minutes-per-week']),
                        'pause-length-days' => sanitize_input($pauselengthdays)
                    );
            $where = "recurring-Profile-ID = ".$_POST['item_ID']." AND `customer-ID` =".$_POST['customer_ID'];
            $this->My_model->update_where('Customers-RecurringProfiles',  $to_insert, $where);
            //echo "<script>window.location.href='".base_url()."profile/managSubscr'</script>";
            $msg = "Changes have been saved";
        }
        $row = $this->Customers_model->getRecurring($item_ID);
        $data['recu'] = $row; 
        $data['customer_ID'] =  $customer_ID;
        $data['item_ID'] =  $item_ID;
        $data['msg'] =  $msg;
        $data['pauseDays'] = $globals->{'max-pause-days'};
         $this->load->model('Tutors_model');
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->view('front/includes/main_view', $data);
    }

    public function delSubs()
    {
        $recurrentID = $_POST['recurrentID'];
        $customer_ID = $_POST['customer_ID'];
        $type = $_POST['type'];
        if (!is_numeric($type) || !is_numeric($customer_ID) || !is_numeric($recurrentID)) return;
        $this->load->model('Customers_model');
        $row = $this->Customers_model->getRecurring($recurrentID);
        $query = "DELETE FROM `Customers-RecurringProfiles` WHERE `recurring-Profile-ID` = $recurrentID AND `customer-ID` = $customer_ID LIMIT 1";
        $this->db->query($query);
        
        $query = "DELETE FROM `calendarEntries` WHERE `from_recurring` = $type AND `customer_ID` = $customer_ID AND `tutor_ID` =".$row->{'tutor-ID'}." LIMIT 1";
        $this->db->query($query);
    }
}
