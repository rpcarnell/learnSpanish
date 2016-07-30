<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('main_controller.php');
class Billing extends SP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $customer_ID = $this->session->userdata('customerid');
        if (is_numeric($customer_ID) && $customer_ID > 0)
        { 
            $this->load->model('Timezones_model');
            $time_zone = $this->Timezones_model->getUserZone($customer_ID);
           // echo "timezone is ".$time_zone;
            date_default_timezone_set($time_zone);//***** we set the timezone first
        }    
    }
    public function index()
    {
        
    }
    function formatMoney($number, $cents = 2) { // cents: 0=never, 1=if needed, 2=always
        
         if (is_numeric($number)) { // a number
            if (!$number) { // zero
              $money = ($cents == 2 ? '0.00' : '0'); // output zero
            } else { // value
              if (floor($number) == $number) { // whole number
                $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
              } else { // cents
                $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
              } // integer or decimal
            } // value
             return $money;
          } // numeric
    } // formatMoney41

    public function history()
    {
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $this->load->model('Customers_model');
        $userMessage = $this->showMessage();
        $row = $this->Customers_model->getPurchases($customer_ID);
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Billing History';
        $data['customer_ID'] = $customer_ID;
        $data['bills'] = $row;
        $data['Customers_model'] = $this->Customers_model;
        $this->load->model('Tutors_model');
        $data['tutor_model'] = $this->Tutors_model;
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Billing History', '/billing/history');
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['userMessage'] = $userMessage;
        $data['order'] = (isset($_GET['order']) && is_numeric($_GET['order']) && $_GET['order'] < 2) ? $_GET['order'] : 0; 
        $data['main_content'] = 'front/billhistr';   // somehow this calls billhistr.php  - corky
        $this->load->view('front/includes/main_view', $data);
    }

    public function buyTime()
    {
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
         $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Billing History', '/billing/history');
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['customer_ID'] = $customer_ID;
        $data['main_content'] = 'front/billing/tutor_info';
        $this->load->view('front/includes/main_view', $data);
    }
    public function bill()
    {
         $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
         $customer_id = $this->session->userdata('customerid');
         if ($id != $customer_id) { return; }
         $globals = $this->Fn_model->getGlobals();
         $data['page_title'] = $globals->{'store-name'}.' | Billing';
         $this->load->model('Customers_model');
         $sum = 0;
         $unpaid = $this->Customers_model->getUnPaid($customer_id);
         foreach ($unpaid as $unp) { $sum += $unp->price_paid; }
         $data['main_content'] = 'front/payment_2';
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->push('Payment', '/home/payment');
         $this->breadcrumbs->unshift('Profile', base_url()."profile");
         $this->breadcrumbs->unshift('Home', base_url());
         $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
         $data['amount'] = $sum;
         $data['id'] = $id;
         $this->load->library('Braintree_lib');//also loads the files in third_party/Braintree.php
         $token = $this->braintree_lib->create_client_token();
         $data['token'] = $token;
         $data['totalbilling'] = true;
         $this->load->view('front/includes/main_view', $data);
    }
    public function balance()
    {
         $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
         $globals = $this->Fn_model->getGlobals();
         $data['page_title'] = $globals->{'store-name'}.' | Billing';
         $this->load->library('breadcrumbs');
         $this->breadcrumbs->push('Billing', '/billing/balance');
         $this->breadcrumbs->unshift('Profile', base_url()."profile");
         $this->breadcrumbs->unshift('Home', base_url());
         $breadcrumbs = $this->breadcrumbs->show();
         $data['breadcrumbs'] = $breadcrumbs;
         $data['main_content'] = 'front/billing/billing';
         $this->load->model('Customers_model');
         $unpaid = $this->Customers_model->getUnPaid($id);
         $this->load->model('Tutors_model');
         $outs = '';
         $purchases = '';
         if (is_array($unpaid)) 
         { 
            $sum = 0;
            $this->load->library('billing_library', array());
            foreach ($unpaid as $unp) 
            { 
                 $sum += $unp->price_paid; 
                 if ($unp->item_id == 2)
                 { $purchases .= $this->billing_library->formatRecurPurchase($unp, $this->Customers_model, $this->Tutors_model); }
                 elseif ($unp->item_id == 1)
                 { $purchases .= $this->billing_library->formatFlexPurchase($unp, $id, $this->Customers_model, $this->Tutors_model); }
            }
            $outs = "<a style='margin-bottom:30px; font-size: 20px; padding: 10px; text-decoration: none;' href='".base_url()."billing/bill/".$id."'>The total amount due is $".$sum."</a>";
        }
        $data['purchases'] = $purchases;
        $data['outs'] = $outs;
        $this->load->view('front/includes/main_view', $data);
    }
    public function buyFlexTime()
    {
        $data['main_content'] = 'front/billing/buyflextime';
        $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $tutor = $this->getTutor();
        $data['tutor'] = $tutor;
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Buy Flex Time';
        $error = false;
        $this->load->model('Customers_model');
        if (isset($_POST) && isset($_POST['buyingflex']) && $_POST['buyingflex'] == 1)
        {
            if (!is_numeric($_POST['enterhours'])) { $error = "Please enter how many hours you want to buy"; }
            if ($error === false)
            {
		$id = $this->Customers_model->Inventory();
                
                if (is_numeric($id) && $id > 0)
                { echo "<script>window.location='".base_url()."billing/payment?id=$id&item_id=1'</script>"; }
                else echo "<script>alert('There was an error submitting your data');</script>";
             }
        }
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutors', '/tutors?purchase=1');
        $this->breadcrumbs->push('Buy Flex Time', 'billing/buyFlexTime/'.$customer_ID);
        
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['error'] = $error;
        $data['customer_ID'] = $customer_ID;
        $this->load->view('front/includes/main_view', $data);
    }
    
    //this function handles price, at least for the Ajax part in billing/buyFlexTime/(int)
    public function getFlexPrice()
    {
        if (!isset($_POST) || !isset($_POST['quant']) || !is_numeric($_POST['quant'])) return;
        if (!isset($_POST['quantSel']) || !is_numeric($_POST['quantSel'])) return;
        $MinutesOrHours = $_POST['quantSel'];     // 0 = minutes, 1 = hours - for info only, not used here... corky
        $minutes = $_POST['quant'];  // js converts to minutes according to status of MinutesOrHours         
        $this->load->model('Customers_model');   // ?
        $PricePerMin =  abs( $this->Customers_model->calculate_price_per_Flex($minutes/60));  // /60 = convert to hours bcause calculate_price.... expects hours
        $temp = array(  $this->formatMoney (($PricePerMin * $minutes )), $PricePerMin );
        echo json_encode($temp );   
    }



     public function getRecurringPrice()
    {
        if (!isset($_POST) || !isset($_POST['quant']) || !is_numeric($_POST['quant'])) return;
        $quant = $_POST['quant'];
        $this->load->model('Customers_model');
        $permin =  round(( $quant), 2);
        $row = abs( $this->Customers_model->calculate_price_per_min($permin));
        echo $row; 
    }
    public function purchase()
    {
         $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Purchase';
          $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Purchase', '/billing/purchase');
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
         $data['main_content'] = 'front/billing/purchase';
        $this->load->view('front/includes/main_view', $data);
    }
    public function custom()
    {
        $data['main_content'] = 'front/billing/custom';
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Custom Purchase';
        $tutor = $this->getTutor();
        $data['tutor'] = $tutor;
        $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
         if (!is_numeric($id)) { echo "ERROR - invalid ID"; exit; }
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        if ($customer_ID != $id) { echo "ERROR - invalid ID"; exit; }
         $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutors', '/tutors?purchase=3');
        $this->breadcrumbs->push('Non-Booked Item', 'billing/custom/'.$customer_ID);
        
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $this->load->view('front/includes/main_view', $data);
    }
    public function recurring()
    {
        $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $error = false;
        if ($_POST && isset($_POST['minutesperweek']))
        {
           /* if (trim($_POST['date']) == '') { $error = 'Date is missing';  }
            else*/
            if (trim($_POST['minutesperweek']) == '') { $error = 'How Many Minutes per Week?';  }
            //if (trim($_POST['numberofmonths']) == '') { $error = 'Number of months is missing';  }
            $this->load->model('Customers_model');
            if ($error === false)
            {
		//$this->Customers_model->Inventory();
                $id = $this->Customers_model->RecurringProfiles();
                if (is_numeric($id) && $id > 0)
                { echo "<script>window.location='".base_url()."billing/payment?id=$id&item_id=2'</script>"; }
                else echo "<script>alert('There was an error submitting your data');</script>";
             } 
        }
        if (!is_numeric($id)) { echo "ERROR - invalid ID"; exit; }
        $globals = $this->Fn_model->getGlobals();
        $data['page_title'] = $globals->{'store-name'}.' | Recurring Purchase';
        $tutor = $this->getTutor();
        $this->load->library('breadcrumbs');
        $this->breadcrumbs->push('Tutors', '/tutors?purchase=2');
        $this->breadcrumbs->push('Buy Recurring Time', 'billing/recurring/'.$customer_ID);
        
        $this->breadcrumbs->unshift('Profile', base_url()."profile");
        $this->breadcrumbs->unshift('Home', base_url());
        $breadcrumbs = $this->breadcrumbs->show();
        $data['breadcrumbs'] = $breadcrumbs;
        $data['tutor'] = $tutor;
        $data['error'] = $error;
        $data['customer_ID'] = $customer_ID;
        $data['main_content'] = 'front/billing/tutor_info';
        $this->load->view('front/includes/main_view', $data);
    }
    private function getTutor()
    {
        $id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $this->load->model('Tutors_model');
        $tutor = $this->Tutors_model->getTutor($id);
        return $tutor;
    }
     public function balancecheck()
    {
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $amount = $this->input->post('amount');
        if (!is_numeric($amount) || (int)$amount == 0) { echo "ERROR - amount $amount is not valid"; return; }
        if (!is_numeric($customer_ID)) { echo "There's no customer ID"; return; }
        $this->load->library('Braintree_lib');
        $this->load->library('billing_library', array());
        list($sale_result, $result, $error) = $this->billing_library->braintree_payment($this, $customer_ID, $_POST, $amount);
        /*$payment_method_nonce = $_POST['payment_method_nonce'];
        $this->load->library('billing_library', array());
        $result = Braintree_Customer::create([
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'company' => isset($_POST['company']) ? $_POST['company'] : '',
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
             'paymentMethodNonce' => $payment_method_nonce
         ]);
         if ($result->success)
         { 
             $sale_result = Braintree_Transaction::sale([
              'amount' => $amount,
              'paymentMethodToken' => $result->customer->creditCards[0]->token,
              'options' => [
                'submitForSettlement' => True
              ]
            ]);
            if (! $sale_result->success)  { $error = "There has been an error in the payment system. ".$sale_result->message; }
            else 
            {
                $this->load->model('Customers_model');
                $this->Customers_model->payUnPaid($customer_ID);
            }
         }
         else { $error = "There has been an error creating the customer"; }*/
         $result2 = new stdClass();
         $result2->customerID = $result->customer->id; 
         $result2->merchantID = $result->customer->merchantId; 
         $result2->transactionID = $sale_result->transaction->id;
         $result2->message = isset($sale_result->message) ? $sale_result->message :  '';
         
         $this->load->model('Customers_model');
         $this->billing_library->internalCheckout($customer_ID, $result2, $error, $_POST, $this, true);
         return;
    }
    public function checkout()
    {
        $this->Fn_model->ch_login();
        $customer_ID = $this->session->userdata('customerid');
        $amount = $_POST['amount'];
        $error = '';
        if (!is_numeric($_POST['item_id']) || !is_numeric($_POST['appo']) ) { echo "Type or appo are not numeric. Contact administrator!"; return; } 
        if (!is_numeric($amount) || (int)$amount == 0) { echo "ERROR - amount $amount is not valid"; return; }
        if (!is_numeric($customer_ID)) { echo "There's no customer ID"; return; }
        $this->load->library('Braintree_lib');
        $payment_method_nonce = $_POST['payment_method_nonce'];
        $result = Braintree_Customer::create([
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'company' => isset($_POST['company']) ? $_POST['company'] : '',
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
             'paymentMethodNonce' => $payment_method_nonce
         ]);
      // print_r($result);
         if ($result->success)
         { 
             $sale_result = Braintree_Transaction::sale([
              'amount' => $amount,
              'paymentMethodToken' => $result->customer->creditCards[0]->token,
              'options' => [
                'submitForSettlement' => True
              ]
            ]);
            if (! $sale_result->success)  { $error = "There has been an error in the payment system. ".$sale_result->message; }
         }
         else { $error = "There has been an error creating the customer"; }
         $result2 = new stdClass();
         $result2->customerID = $result->customer->id; 
         $result2->merchantID = $result->customer->merchantId; 
         $result2->transactionID = $sale_result->transaction->id;
         $result2->message = isset($sale_result->message) ? $sale_result->message :  '';
         $this->load->library('billing_library', array());
         $this->load->model('Customers_model');
         $this->billing_library->internalCheckout($customer_ID, $result2, $error, $_POST, $this); 
         return;
    }
                
    public function payment()
    {
            $globals = $this->Fn_model->getGlobals();
            $data['page_title'] = $globals->{'store-name'}.' | Payment';
            $data['main_content'] = 'front/payment';
            $id = $_GET['id'];
 
            if (!is_numeric($id)) { echo "ERROR - Invalid Payment"; exit; }
            $item_id = $_GET['item_id'];
            if (!is_numeric($item_id)) { echo "ERROR - Invalid Item ID"; exit; }
            $this->load->model('Customers_model');
            $customer_ID = $this->session->userdata('customerid');
            if ($item_id == 2) 
            {
                $appo = $this->Customers_model->getRecurringProfile($id);
                $minutes_per_week = $appo->{'minutes-per-week'};
                $amount = $this->Customers_model->calculate_price_per_min($minutes_per_week);
                $price = $appo->{'minutes-per-week'} * $amount;
               // $price = ceil($price * 4.35);
                
                if ($customer_ID != $appo->{'customer-ID'})
                { echo "ERROR - Invalid Payment ID. This does not belong to you"; exit; }
            }
            elseif ($item_id == 1) 
            {
                $appo = $this->Customers_model->getCustInventory($id);
                $FlexTime = $appo->{'unpaidTime'};
                if ($customer_ID != $appo->customer_ID) {  echo "Unknown Customer ID - Exiting"; exit; }
                $amount = $this->Customers_model->calculate_price_per_Flex($FlexTime);   // /60  corky
                $price = $this->formatMoney (($FlexTime * $amount ));
                $appo->{'tutor-ID'} = $appo->tutor_ID;
                
            }
            $this->load->model('Tutors_model');
            $tutor = $this->Tutors_model->getTutor($appo->{'tutor-ID'});
                
            if (!is_numeric($amount) || $amount == 0) { echo "ERROR - amount is either numeric or does not exist"; return; } 
            $this->load->library('breadcrumbs');
            $this->breadcrumbs->push('Payment', '/home/payment');
            $this->breadcrumbs->unshift('Home', base_url());
            $breadcrumbs = $this->breadcrumbs->show();
            $data['breadcrumbs'] = $breadcrumbs;
            $data['id'] = $id;
            $data['amount'] = round($price, 2);
            switch ($item_id)
            {
                case 1:
                    $timeType = "Flex Time";
                    break;
                case 2:
                    $timeType = "Recurrent Time";
                    break;
            }
            $this->load->library('Braintree_lib');//also loads the files in third_party/Braintree.php
            $token = $this->braintree_lib->create_client_token();
            $data['tutor'] = $tutor;
            $data['token'] = $token;
            $data['timeType'] = $timeType;
            $data['item_id'] = $item_id;
            $data['totalbilling'] = false;
             $this->load->view('front/includes/main_view', $data);
	}
        public function totalbilling()
        {
            if (!$_POST) return;
            $amount = $_POST['amount'];
            if (!is_numeric($amount) || (int)$amount == 0) { echo "ERROR - amount $amount is not valid"; return; }
            $customer_ID = $this->session->userdata('customerid');
            if (!is_numeric($customer_ID)) { echo "There's no customer ID"; return; }
            $sum = 0;
                
            $this->load->model('Customers_model');
            $unpaid = $this->Customers_model->getUnPaid($customer_ID);
            foreach ($unpaid as $unp) { $sum += $unp->price_paid; }
            //$sum = $sum + 1;
            if ($sum != $amount) { echo "ERROR - quantity $amount is not valid"; return; } 
            $globals = $this->Fn_model->getGlobals();
            $data['page_title'] = $globals->{'store-name'}.' | Appointments';
            $this->load->library('Braintree_lib');
            $this->load->model('Customers_model');
            //let's get the credit card information:
            
           
            $creditCard = array('billingAddressId' => $customer_ID, 'number' => $_POST['cc-number'],'expirationMonth' => $_POST['cc-exp-month'], 'expirationYear' => $_POST['cc-exp-year']);
            $token = $this->braintree_lib->create_client_token(); 
            $result = Braintree_Transaction::sale(array('amount' =>  $amount, 'creditCard' => $creditCard ));
             $data['error'] = isset($result->message) ? $result->message : '';
            if ($data['error'] == '') { 
                $this->Customers_model->payUnPaid($customer_ID); 
                echo "<script>window.location.href='" . base_url() . "profile'</script>";
            }
            else echo "<script>window.location.href='" . base_url() . 'billing/bill/'.$customer_ID."?error=yes'</script>";
        }
	public function appointments()//deprecated???
        {
            $this->Fn_model->ch_login();
            $customer_ID = $this->session->userdata('customerid');
            if (!is_numeric($customer_ID)) { echo "There's no customer ID"; return; }
            $this->load->library('breadcrumbs');
            $this->breadcrumbs->push('Teacher Schedules', '/home/appointments');
            $this->breadcrumbs->unshift('Home', base_url());
            $breadcrumbs = $this->breadcrumbs->show();
            $data['breadcrumbs'] = $breadcrumbs;
            $globals = $this->Fn_model->getGlobals();
            $data['page_title'] = $globals->{'store-name'}.' | Appointments';
           // $this->load->library('Braintree_lib');//also loads the files in third_party/Braintree.php
            if (!$_POST) return;
            $this->load->model('Customers_model');
            $this->load->model('Braintree_model');
             $this->Braintree_model->createPaymentMethod($customer_ID);
            //$creditCard = array('number' => $_POST['cc-number'],'expirationMonth' => $_POST['cc-exp-month'], 'expirationYear' => $_POST['cc-exp-year']);
            //$token = $this->braintree_lib->create_client_token();
            $this->Braintree_model->sale();
            
            
            // $result = Braintree_Transaction::sale(array('amount' =>  $_POST['amount'], 'paymentMethodNonce' => nonceFromTheClient, 'options' => [ 'submitForSettlement' => True ] )); 
           
       /* $result = Braintree_Transaction::sale(array(
    'amount' => '56.00',
    'creditCard' => array(
        'number' => '4111111111111111',
        'expirationMonth' => '05',
        'expirationYear' => '16'
    ) */
    
//));
           $data['type'] = $_POST['item_id'];
            $data['profile_id'] = $_POST['appo'];
            $data['customer_ID'] = $customer_ID;
            $data['error'] = isset($result->message) ? $result->message : '';
            if ($data['error'] == '') 
            {
                $this->storeMessage("Your payment of $".$_POST['amount']. " has been approved. You can now create appointments with your tutor.");
                $this->Customers_model->insertPurchase($customer_ID, $_POST);
                $this->Customers_model->updateTimePurchased($customer_ID, $_POST);
                $this->Customers_model->paidFor($customer_ID, $_POST);
                echo "<script>window.location.href='" . base_url() . 'calendar/appointments?type='.$data['type'].'&id='.$data['profile_id']."'</script>";
            }
            else echo "<script>window.location.href='" . base_url() . 'billing/payment?error=yes&item_id='.$data['type'].'&id='.$data['profile_id']."'</script>";
                 
                
    }
}
