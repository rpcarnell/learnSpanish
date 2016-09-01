<?php
class Braintree_model extends CI_Model
{
    var $token = '';
    public function __construct()
    {
         $this->load->library('Braintree_lib');//also loads the files in third_party/Braintree.php
         $this->token = $this->braintree_lib->create_client_token();
    }
    public function createPaymentMethod($customer_ID)
    {
        $result = Braintree_Customer::create([
        'firstName' => 'Mike',
        'lastName' => 'Jones',
        'company' => 'Jones Co.',
        'email' => 'mike.jones@example.com',
        'phone' => '281.330.8004',
        'fax' => '419.555.1235',
        'website' => 'http://example.com']);
        $result->success;
        $result->customer->id;
        $clientToken = Braintree_ClientToken::generate(["customerId" => $result->customer->id]);
         echo $clientToken; exit;
       $result2 = Braintree_PaymentMethod::create([
    'customerId' => $result->customer->id,
    'paymentMethodNonce' => $_POST['cc-number']// nonceFromTheClient
]);
       
        $result = Braintree_PaymentMethod::create([
                  'customerId' => $result->customer->id,
                  'paymentMethodNonce' => $_POST['cc-number']//nonceFromTheClient,
               ]);
        
    }
    public function sale() 
    {
        $creditCard = array('number' => $_POST['cc-number'],'expirationMonth' => $_POST['cc-exp-month'], 'expirationYear' => $_POST['cc-exp-year']);
        $token = $this->braintree_lib->create_client_token();
       //  $this->load->model('Customers_model');
      //  //$this->Customers_model->sale();
        //$result = Braintree_Transaction::sale(array('amount' =>  $_POST['amount'], 'creditCard' => $creditCard )); 
        $result = Braintree_Transaction::sale([
              'amount' => $_POST['amount'],
              'orderId' => 'order id',
              /*'merchantAccountId' => 'a_merchant_account_id', IF NONE IS USED, DEFAULT MERCHANT ACCOUNT GOES*/
              'paymentMethodNonce' => nonceFromTheClient,
              'customer' => [
                'firstName' => 'Drew',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'phone' => '312-555-1234',
                'fax' => '312-555-1235',
                'website' => 'http://www.example.com',
                'email' => 'drew@example.com'
              ],
              'billing' => [
                'firstName' => 'Paul',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'streetAddress' => '1 E Main St',
                'extendedAddress' => 'Suite 403',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryCodeAlpha2' => 'US'
              ],
              'shipping' => [
                'firstName' => 'Jen',
                'lastName' => 'Smith',
                'company' => 'Braintree',
                'streetAddress' => '1 E 1st St',
                'extendedAddress' => 'Suite 403',
                'locality' => 'Bartlett',
                'region' => 'IL',
                'postalCode' => '60103',
                'countryCodeAlpha2' => 'US'
              ],
              'options' => [
                'submitForSettlement' => true
              ]
            ]);
         
    }
}
?>
