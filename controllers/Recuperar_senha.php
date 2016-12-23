<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recuperar_senha extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('tb_usuario');
		$this->load->helper(array('form', 'site_helper'));
	}

	public function index() {
        
        $email = $this->input->get('email');
        $chave = $this->input->get('chave');
        
        if(isset($email) && isset($chave)) {
            
            $data = array(
                'email' => $email,
                'chave' => $chave
            );
            
            if($chave != $this->tb_usuario->getHashRecuperacaoSenha($email)) {
                $data['chave_invalida'] = true;
            }
            
            $data['main_content'] = 'recuperar_senha/index';
            $this->load->view('recuperar_senha/template', $data);
            
        } else {
            redirect('', 'refresh');
        }
        
    }
    
    public function alterar() {
        $email = $this->input->post('email');
        $chave = $this->input->post('chave');
        
        $senha = $this->input->post('senha');
        $confirmar_senha = $this->input->post('confirmar_senha');
        
        if(isset($email) && isset($chave)) {
            
            $data = array(
                'email' => $email,
                'chave' => $chave
            );            
            
            if($chave != $this->tb_usuario->getHashRecuperacaoSenha($email)) {
                $data['chave_invalida'] = true;
                
                $data['main_content'] = 'recuperar_senha/index';
                $this->load->view('recuperar_senha/template', $data);                
                
                return;
            }
            
            if($senha != $confirmar_senha) {
                $data['mensagem_erro'] = 'Senhas não conferem';
                
                $data['main_content'] = 'recuperar_senha/index';
                $this->load->view('recuperar_senha/template', $data);
                
                return;
            }
            
            $this->tb_usuario->alterar_senha($email, $senha);
            
            $data['main_content'] = 'recuperar_senha/sucesso';
            $this->load->view('recuperar_senha/template', $data);
            
        }
    }
    
}