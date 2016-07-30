<?php
class Admin_library
{
    private $params = array();
    public function __construct($params) { $this->params = $params; }
    public function decideInvoiceEditor($post)//used by controller's editInvoice()
    {
        $this->load->model('admin_model');
        if (isset($post['e_ecurringform']) && 1 == $post['e_ecurringform']) { $editor = $this->admin_model->recurringEdit($post); }
        elseif (isset($post['e_flexform']) && 1 == $post['e_flexform']) { $editor = $this->admin_model->flexEdit($post); }
        elseif (isset($post['e_otherform']) && 1 == $post['e_otherform']) { $editor = $this->admin_model->OtherEdit($post); }
        return $editor;
    }
}
?>