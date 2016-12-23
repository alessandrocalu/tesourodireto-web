<?php

class Smtp extends CI_Model {

    public function __construct() {
    }

    public function getConfig() {
        return array(
            'protocol' => 'smtp',
            'smtp_host' => 'mx1.hostinger.com.br',
            'smtp_port' => 2525,
            'smtp_user' => 'contato', //@tesourodiretoweb.com.br', // change it to yours
            'smtp_pass' => 'td_web0099', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => true
              
  //          'protocol' => 'sendmail',
 //           'mailpath' => '/usr/sbin/sendmail',
 //           'charset' => 'iso-8859-1',
//            'wordwrap' => TRUE
        );
    }
    
    public function enviar_email($email, $assunto, $mensagem) {       
        $config = $this->getConfig();

        
        $this->load->library('email', $config);
        
        $this->email->from('contato@tesourodiretoweb.com.br', '@Cotações');
        $this->email->to($email);  

        $this->email->subject(utf8_decode($assunto));
        $this->email->message(utf8_decode($mensagem));	

        $this->email->send();  
  }

}