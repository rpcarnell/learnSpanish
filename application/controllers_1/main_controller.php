<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class SP_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_getMainSettings();
    }
    private function _getMainSettings()
    {
        $this->load->model('Cronparser_model');
        $this->Cronparser_model->getGlobals();
    }
    public function showMessage() { return $this->_msgData(); }
    public function storeMessage($msg) { $this->_msgData('set', $msg); }
    private function _msgData($get = 'get', $msg = '')
    {
        $this->load->library('session');
        if ($get == 'set')
        {
             if (trim($msg) == '') { echo "<b>ERROR - message cannot be empty</b>"; return false; }
             $msgdata = array("message" => $msg);
             $this->session->set_userdata($msgdata);
        }
        else 
        {
            $msg = $this->session->userdata('message');
            $this->session->unset_userdata('message');
            return $msg;
        }
    }
}
?>