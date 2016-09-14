<?php
class Billing_library
{
    private $params = array();
    public function __construct($params) { $this->params = $params; }
    public function formatRecurPurchase($unp, $Customers_model, $Tutors_model)
    {
         $recurring = $Customers_model->getRecurringProfile($unp->profile_id);
         $tutor = $Tutors_model->getTutor($unp->tutor_id);
         if (! $tutor) { $tutor = new stdClass(); }
         if (!isset($tutor->name)) { $tutor->name = ('(Tutor Not Decided Yet)'); }
         return "<div class='recurflexpay'><p>Your recurring subscription with ".$tutor->name." became due on ".date('M-d-Y', $unp->date_created).". The amount due is $".$unp->{'price_paid'}.".</p></div>";
    }
    public function formatFlexPurchase($unp, $id, $Customers_model, $Tutors_model)
    {
         $tutor = $Tutors_model->getTutor($unp->tutor_id);
         $flex = $Customers_model->getCalendarEntry($id, $unp->tutor_id, 0);
         if (!$flex) { $flex = new stdClass(); }
         if (!isset($flex->duration) || $flex->duration == 0) { $flex->duration = 0; }
         if ($flex->duration > 120)
         {
             $timeHours = round($flex->duration / 60, 2);
             $timeHours = $timeHours. " hours";
         } else { $timeHours = $flex->duration. " minutes"; }
         if (! $tutor) { $tutor = new stdClass(); }
         if (!isset($tutor->name)) { $tutor->name = ('(Tutor Not Decided Yet)'); }
         return "<div  class='recurflexpay'><p>You have $timeHours of Flex Time with ".$tutor->name." that has not been paid for. The amount due is $".$unp->{'price_paid'}.".</p></div>";
     }
     public function internalCheckout($customer_ID, $result, $error, $post, & $c, $unpaid = false)
     {
          if (! $unpaid) { $data['type'] = $post['item_id']; }
          $data['profile_id'] = $post['appo'];
          if (! $unpaid)
          {
              $data['error'] = isset($result->message) ? $result->message : '';
              if ($data['error'] == '' || $error != '') 
              {
                   $c->storeMessage("Your payment of $".$post['amount']. " has been approved. You can now create appointments with your tutor.");
                   $c->Customers_model->insertPurchase($customer_ID, $post, true, $result);
                   $c->Customers_model->updateTimePurchased($customer_ID, $post);
                   $c->Customers_model->paidFor($customer_ID, $post);
                   echo "<script>window.location.href='" . base_url() . 'calendar/appointments?type='.$data['type'].'&id='.$data['profile_id']."'</script>";
              }
              else { echo "<script>window.location.href='" . base_url() . 'billing/payment?error=yes&item_id='.$data['type'].'&id='.$data['profile_id']."'</script>"; }
          } 
          else
          {
              $c->storeMessage("Your payment of $".$post['amount']. " has been approved.");
              echo "<script>window.location.href='" . base_url() . "billing/history'</script>";
          }
          
      }
      public function braintree_payment($c, $customer_ID, $post, $amount)
      {
            $error = '';
            $sale_result = false;
            $c->load->library('Braintree_lib');
            $payment_method_nonce = $post['payment_method_nonce'];
            $result = Braintree_Customer::create([
                'firstName' => $post['firstName'],
                'lastName' => $post['lastName'],
                'company' => isset($post['company']) ? $post['company'] : '',
                'email' => $post['email'],
                'phone' => $post['phone'],
                 'paymentMethodNonce' => $payment_method_nonce
             ]);
             if ($result->success)
             { 
                 $sale_result = Braintree_Transaction::sale([
                  'amount' => $amount,
                     'paymentMethodNonce' => $payment_method_nonce,
                  //'paymentMethodToken' => $result->customer->creditCards[0]->token,
                  'options' => [
                    'submitForSettlement' => True
                  ]
                ]);
                if (! $sale_result->success)  { $error = "There has been an error in the payment system. ".$sale_result->message; }
                else 
                {
                    $c->load->model('Customers_model');
                    $c->Customers_model->payUnPaid($customer_ID, $result);
                }
             } else { $error = "There has been an error creating the customer"; }
             return array($sale_result, $result, $error);
      }
}
?>