
   <div class="spanish">
	     
       
     <div class="spanish-content">
<?php
       if (isset($_GET['error']))
       {
           echo "<div style='clear: both;'></div><br /><div style='margin: 5px; color: #a00;'>There is an error. Please try again.</div>";
       }
       ?>
        <div class="profile-info-top fr"> 
<?php if (!$totalbilling) { $submitURL = base_url().'billing/appointments'; }
else { $submitURL = base_url().'billing/totalbilling'; }
?><div class='registerForm cardform' style='width: 100%; margin-left: 20%;'>
    <h2>You have chosen to buy <?php echo $timeType;?> with Tutor <?php echo $tutor->name;?>.</h2>
    <h2>Billing Information</h2>
 



<form id="checkout" method="post" action="<?php echo base_url();?>billing/checkout">
    <p><div>First Name: </div><input type="text" name="firstName" /></p>
    <p><div>Last Name: </div><input type="text" name="lastName" /></p>
    <p><div>Company: </div><input type="text" name="company" /></p>
<p><div>E-Mail: </div><input type="text" name="email" /></p>
<p><div>Pḧone: </div><input type="text" name="phone" /></p>
    <p><div>Street Address: </div><input type="text" name="streetAddress" /></p>
    <p><div>City: </div><input type="text" name="city" /></p>
    <p><div>Region: </div><input type="text" name="region" /></p>
     <p><div>Postal Code: </div><input type="text" name="postalCode" /></p>
     
                     
                     
                    
                   
     <br /> <hr />             
     <h2>Choose a Payment Method</h2>              
  <div id="payment-form"></div><br />
   <input type='hidden' name='appo' value='<?php echo $id; ?>' />
     <input type='hidden' name='item_id' value='<?php echo $item_id; ?>' />
  <input type="hidden" name="amount" value="<?php echo $amount;?>" />
  <input type="submit" value="Pay $<?php echo $amount;?>">
</form>

<script src="https://js.braintreegateway.com/js/braintree-2.24.1.min.js"></script>

<script>

  braintree.setup(
    // Replace this with a client token from your server
   '<?php echo $token; ?>', "dropin", {
      container: "payment-form"
    });
</script>


</div>
            
    </div>
    </div>
    </div>
<script>
function  SubmitNow()
{
    jQuery('#warning').html('');
    var amount = jQuery('#amount').val();
    var ccn = jQuery('#cc-number').val();
    var cvc = jQuery('#cc-cvc').val();
    var ccname = jQuery('#cc-name').val();
    if (ccname.trim() == '') { jQuery('#warning').html("Please enter your name"); return false; }
    if (isNaN(amount) || parseInt(amount) == 0) {  jQuery('#warning').html("Amount is not valid"); return false; }
    if (isNaN(ccn) || ccn.trim() == '') { jQuery('#cc-number').val(''); jQuery('#warning').html("Credit Card Number is not valid"); return false; }
    if (isNaN(cvc) || cvc.trim() == '') { jQuery('#cc-cvc').val(''); jQuery('#warning').html("CVC is not valid"); return false; }
    if (isNaN(amount) || amount < 1) { alert('Amount is invalid'); return; } 
    return true;
   
}
</script>