<?php

use app\services\imap\Imap;
use app\services\LeadProfileBadges;
use app\services\leads\LeadsKanban;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\MailboxDoesNotExistException;

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class LeadController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
    }

    /* List all leads */


    public function table()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $this->app->get_table_data('leads');
    }

    public function kanban()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        $data['statuses']      = $this->leads_model->get_status();
        $data['base_currency'] = get_base_currency();
        $data['summary']       = get_leads_summary();

        echo $this->load->view('admin/leads/kan-ban', $data, true);
    }

    /* Add or update lead */
    public function leadgGenerate()
    {
        if ($this->input->post() && $this->input->post('token') == '7d68e2c38dfb38785e7a') 
        {
            $dataArr['status']              = 2; 
            $dataArr['source']              = 3; 
            $dataArr['assigned']            = 1; 
            $dataArr['name']                = $this->input->post('name');
            $dataArr['title']               = "Lead from bastionex Site";
            $dataArr['email']               = $this->input->post('email');
           // $dataArr['website']           = 
            $dataArr['phonenumber']         = $this->input->post('phone');
            $dataArr['lead_value']          = 0;
            $dataArr['company']             = $this->input->post('company');
            $dataArr['address']             = null;
            $dataArr['city']                = null;
            $dataArr['state']               = null;
            $dataArr['country']             = 0;
            $dataArr['zip']                 = null;
            $dataArr['default_language']    = 'english'; 
            $dataArr['description']         = $this->input->post('message'); 
            $dataArr['keywords']            = $this->input->post('keywords'); 
         
            $status     = $this->leads_model->addApiLead($dataArr);
            if($status)
            {
                echo json_encode([
                    'status'  => true,
                    'message'  => "Success",
                    'code'     => 1
                ]);
            }else{
                echo json_encode([
                    'success'  => false,
                    'message'  => "Falied",
                    'code'     => 0
                ]);
            }
        }else{
            echo json_encode([
                'success'  => false,
                'message'  => "Invalid Request",
                'code'     => 0
            ]);
        }


    }

}
