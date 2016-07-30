<?php

class Admin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
        public function getAdminById($id)
        {
            if (!is_numeric($id)) return false;
            $row = $this->My_model->select_where('admins', array('admin_id' => $id));
            return ($row) ? $row : false;
        }
        public function deleteAdmin($id)
        {
            if (!is_numeric($id)) return false;
            $row = $this->My_model->delete_where('admins', array('admin_id' => $id));
            return ($row) ? $row : false;
        }
        public function recurringEdit($post)
        {
            $theDate = isset($post['startdate']) ? $post['startdate'] : '';
            if ($theDate != '')
            {
                $date = explode('/',$post['startdate']);
                $date = $date[2]."-".$date[0]."-".$date[1];
                $date = $date ." 00:00:00";
            }
            else $date = '';
            $to_insert = array(
              'minutes-per-week' => sanitize_input((int)$post['hoursperweek'] * 60),
              'tutor-ID'     => sanitize_input($post['tutor_id']),
               'customer-ID' => sanitize_input($post['customer_id']),
               'lenghts-months' => sanitize_input($post['months']),
               'StartDate'        => sanitize_input($date),
            );
             
            $this->My_model->update_where('Customers-RecurringProfiles', $to_insert, array('recurring-Profile-ID' => sanitize_input($post['profile_id']) ));
            unset($to_insert);
             $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 2;
             $to_insert['profile_id'] = $post['profile_id'];
             $to_insert['customer_id'] = $post['customer_id'];
             $this->My_model->update_where('purchases', $to_insert, array('purchase_id' => sanitize_input($post['purchase_id'])) );
             return true;
        }
        public function recurringEnter($post)
        {
            $theDate = isset($post['startdate']) ? $post['startdate'] : '';
            if ($theDate != '')
            {
                $date = explode('/',$post['startdate']);
                $date = $date[2]."-".$date[0]."-".$date[1];
                $date = $date ." 00:00:00";
            }
            else $date = '';
            $to_insert = array(
              'minutes-per-week' => sanitize_input((int)$post['hoursperweek'] * 60),
              'tutor-ID'     => sanitize_input($post['tutor_id']),
               'customer-ID' => sanitize_input($post['customer_id']),
               'lenghts-months' => sanitize_input($post['months']),
               'StartDate'        => sanitize_input($date),
            );
            $this->My_model->inserting('Customers-RecurringProfiles', $to_insert);
            unset($to_insert);
            $profile_id = $this->db->insert_id();
            //Array ( [hoursperweek] => 34 [price] => 345 [startdate] => 11/30/2015 [months] => 465 [emailinvoice] => on [recurringform] => 1 [tutor_id] => 4 [customer_id] => 3 )
             $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 2;
             $to_insert['profile_id'] = $profile_id;
             $to_insert['customer_id'] = $post['customer_id'];
             //$to_insert['hoursperweek'] = round( (  $post['minutesperweek'] / 60 ), 2);
             if (isset($post['emailinvoice']) && $post['emailinvoice'] == 'on')
             {
                 $this->load->model('Customers_model');
                 $customer = $this->Customers_model->getUser($to_insert['customer_id']);
                 $this->load->model('Tutors_model');
                 $tutor = $this->Tutors_model->getTutor($to_insert['tutor_id']);
                 $message = "<p>Hello ".ucwords($customer->name).",</p><p></p><p>Thank you for choosing us. We appreciate your business. We would like to notify you that a new invoice has been created for services.<p>";
                 $message .= "<p>You have an oustanding balance of $".$to_insert['price_paid']." for ".((int)$post['hoursperweek'] * 60)." per week of recurring time with Tutor ".$tutor->name.".</p><p>Thanks for doing business with us. For more information visit your profile.</p>";
                 $storeMail = 'corky@oklearnspanish.com';
                 if ($storeMail == '') echo "<div style='color: #a00'>Warning: store Email unavailable<div>";
                 else {
                      $config['mailtype'] = 'html';
                      $this->email->initialize($config);
                      $this->email->from($storeMail);
                      $this->email->to($customer->email_list);  
                      $this->email->subject('Outstanding Balance');
                      $this->email->message($message);	
                      $this->email->send(); 
                 }
             }
             $this->My_model->inserting('purchases', $to_insert);
        }
        public function getPrices()
        {
             $queryStr = "SELECT * FROM prices ORDER BY type, quantity";
             if($this->db->query($queryStr)->num_rows() > 0)
             {
                foreach($this->db->query($queryStr)->result() as $row) { $data[] = $row; } 
             }
             return (isset($data)) ? $data : false;
        }
        public function flexEdit($post)
        {
            //Array ( [notes] => bible [hours] => 4654 [price] => 456 [flexform] => 1 [tutor_id] => 6 [customer_id] => 3 )
            
             $to_insert = array(
                        'FlexTime' => 60 *  sanitize_input($post['hours']),
                        'tutor_ID'     => sanitize_input($post['tutor_id']),
                         'Customer_ID' => sanitize_input($post['customer_id'])
                    );
             
           
              $this->My_model->update_where('customersInventory', $to_insert, array('Inventory_ID' => sanitize_input($post['inventory_id']) ));
              
            unset($to_insert);
            $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 1;
             $to_insert['profile_id'] = sanitize_input($post['inventory_id']);
             $to_insert['customer_id'] = $post['customer_id'];
             $this->My_model->update_where('purchases', $to_insert, array('purchase_id' => sanitize_input($post['purchase_id'])) );
             return true;
        }
        public function flexEnter($post)
        {
            //Array ( [notes] => bible [hours] => 4654 [price] => 456 [flexform] => 1 [tutor_id] => 6 [customer_id] => 3 )
            
             $to_insert = array(
                        'FlexTime' => 60 * sanitize_input($post['hours']),
                 'OrigTime' => 60 * sanitize_input($post['hours']),
                        'tutor_ID'     => sanitize_input($post['tutor_id']),
                        'MakeUpTime'        => '',
                        'Customer_ID' => sanitize_input($post['customer_id'])
                    );
              $this->My_model->inserting('customersInventory', $to_insert);
             $profile_id = $this->db->insert_id();
            unset($to_insert);
            $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 1;
             $to_insert['profile_id'] = $profile_id;
             $to_insert['customer_id'] = $post['customer_id'];
             if (isset($post['emailinvoice']) && $post['emailinvoice'] == 'on')
             {
                 $this->load->model('Customers_model');
                 $customer = $this->Customers_model->getUser($to_insert['customer_id']);
                 $this->load->model('Tutors_model');
                 $tutor = $this->Tutors_model->getTutor($to_insert['tutor_id']);
                 $message = "<p>Hello ".ucwords($customer->name).",</p><p></p><p>Thank you for choosing us. We appreciate your business. We would like to notify you that a new invoice has been created for services.<p>";
                 $message .= "<p>You have an oustanding balance of $".$to_insert['price_paid']." for ".$post['hours']." hours of Flex Time with ".$tutor->name.".</p><p>Thanks for doing business with us. For more information visit your profile.</p>";
                 $storeMail = 'corky@oklearnspanish.com';
                 if ($storeMail == '') echo "<div style='color: #a00'>Warning: store Email unavailable<div>";
                 else {
                      $config['mailtype'] = 'html';
                      $this->email->initialize($config);
                      $this->email->from($storeMail);
                      $this->email->to($customer->email_list);  
                      $this->email->subject('Outstanding Balance');
                      $this->email->message($message);	
                      $this->email->send(); 
                 }
             }
             $this->My_model->inserting('purchases', $to_insert);
        }
        public function otherEnter($post)
        {
             //Array ( [price] => 564 [emailinvoice] => on [recurring] => on [otherform] => 1 [tutor_id] => 6 [customer_id] => 3 )
             $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 3;
             $to_insert['customer_id'] = $post['customer_id'];
             if (isset($post['emailinvoice']) && $post['emailinvoice'] == 'on')
             {
                 $this->load->model('Customers_model');
                 $customer = $this->Customers_model->getUser($to_insert['customer_id']);
                 $this->load->model('Tutors_model');
                 $tutor = $this->Tutors_model->getTutor($to_insert['tutor_id']);
                 $message = "<p>Hello ".ucwords($customer->name).",</p><p></p><p>Thank you for choosing us. We appreciate your business. We would like to notify you that a new invoice has been created for services.<p>";
                 $message .= "<p>You have an oustanding balance of $".$to_insert['price_paid']." for a custom purchase with Tutor ".$tutor->name.".</p><p>Thanks for doing business with us. For more information visit your profile.</p>";
                 $storeMail = 'corky@oklearnspanish.com';
                 if ($storeMail == '') echo "<div style='color: #a00'>Warning: store Email unavailable<div>";
                 else {
                      $config['mailtype'] = 'html';
                      $this->email->initialize($config);
                      $this->email->from($storeMail);
                      $this->email->to($customer->email_list);  
                      $this->email->subject('Outstanding Balance');
                      $this->email->message($message);	
                      $this->email->send(); 
                 }
             }
             $this->My_model->inserting('purchases', $to_insert);
        }
        public function OtherEdit($post)
        {
            
            $to_insert['price_paid'] = (int)$post['price'];
             $to_insert['date_created'] = time();
             $to_insert['tutor_id'] = (isset($post['tutor_id']) && is_numeric($post['tutor_id']) ) ? $post['tutor_id'] : 0;
             $to_insert['date_paid'] = 0;
             $to_insert['notes'] = sanitize_input($post['notes']);
             $to_insert['viewed'] = 0;
             $to_insert['item_id'] = 3;
             $to_insert['customer_id'] = $post['customer_id'];
             $this->My_model->update_where('purchases', $to_insert, array('purchase_id' => sanitize_input($post['purchase_id'])));
             return true;
        }
        public function firstDate()
        {
           $query = "SELECT * FROM purchases ORDER BY date_created ASC LIMIT 1";
           $data = $this->db->query($query)->result();
           return $data[0]->date_created;
        }
        public function lastDate()
        {
            $query = "SELECT * FROM purchases ORDER BY date_created DESC LIMIT 1";
            $data = $this->db->query($query)->result();
            return $data[0]->date_created;
        }
        public function countStudents()
        {
            $queryStr = "SELECT count(*) as stud FROM customers";
            $data = $this->db->query($queryStr)->result();
           return $data[0]->stud;
        }
         public function sumInvoices()//this is for tutors. Different than the one in customers_model.php
         {
             $queryStr = "SELECT * FROM purchases WHERE date_paid = 0";
             $data = 0;
             if($this->db->query($queryStr)->num_rows() > 0)
             {
                foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } 
             }
             return $data;
         }
         public function sumPaid()//this is for tutors. Different than the one in customers_model.php
         {
              $queryStr = "SELECT * FROM purchases WHERE date_paid != 0";
              $data = 0;
              if($this->db->query($queryStr)->num_rows() > 0)
              {
                 foreach($this->db->query($queryStr)->result() as $row) { $data += (float)$row->price_paid; } 
              }
              return $data;
         }
         public function countRecur()
         { return $this->countItem( 2); }
         public function countFlex()
         { return $this->countItem(1); }
         private function countItem($item_id)//this is for tutors. Different than the one in customers_model.php
         {
              $queryStr = "SELECT count(*) as quant FROM purchases WHERE item_id = $item_id";
              $data = $this->db->query($queryStr)->result();
              return $data[0]->quant;
         }
         public function countUnpaid()//this is for tutors. Different than the one in customers_model.php
         {
              $queryStr = "SELECT count(*) as quant FROM purchases WHERE date_paid = 0";
              $data = $this->db->query($queryStr)->result();
              return $data[0]->quant;
         }
         public function getInvoiceData($data)
         {
             $this->load->model('Customers_model');
             if (2 == $data['data']->item_id)
             {
                 $recurring = $this->Customers_model->getRecurringProfile($data['data']->profile_id);
                 $data['recurring'] = $recurring;
             }
             elseif (1 == $data['data']->item_id)
             {
                 $flexData = $this->Customers_model->getCustInventory($data['data']->profile_id);
                 $data['flexData'] = $flexData;
             }
             return $data;
         }
            
}