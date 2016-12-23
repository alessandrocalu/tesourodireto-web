<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . 'libraries/ImageManipulator.php';

class Usuarios extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('usuario','',true);
		$this->load->helper(array('form'));
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
			if(!$this->usuario->isAdmin($this->session->userdata('logged_in')['id'])) {
					$this->output->set_status_header('401', 'Nao autorizado');
					echo 'Não autorizado!!';
					return;
			}
            
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario['perfil'] == "admin");
			$data['main_content'] = 'usuarios/index';

            $pgSettings = array(
                'page' => $this->input->get('page') == '' ? 1 : $this->input->get('page'),
                'rowsPerPage' => 10
            );
			$dataResult = $this->usuario->get_filtered($pgSettings, $this->input->get('filtro'), $this->input->get('status'));

            $data['data_rows'] = $dataResult;

            $data['filtro'] = $this->input->get('filtro');
            $data['status'] = $this->input->get('status');

			$this->load->view('includes/template', $data);
            $this->load->view('usuarios/index', $data);
            $this->load->view('includes/footer', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}

	public function adicionar() {

		if($this->session->userdata('logged_in'))
		{
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario["perfil"] == "admin");
			$data['main_content'] = 'usuarios/adicionar';
			$this->load->view('includes/template_modal', $data);
		}
		else
		{
			//If no session, redirect to login page
			redirect('account', 'refresh');
		}
	}

	public function adicionar_post() {

		$this->load->library('form_validation');

		$id = $this->input->post('id');
        
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|xss_clean');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirmar_senha', 'Confirmar Senha', 'trim|required|xss_clean');

    	if($this->form_validation->run() == true) {

            if($this->usuario->login_existe($this->input->post('email'))) {

                $data['json'] = '{"message" : "Login de usuário já utilizado.", "success": 0}';
                $this->load->view('includes/json_view', $data);

                return;
            }

            $senha = $this->input->post('senha');
            $confirmar_senha = $this->input->post('confirmar_senha');
            if($senha != $confirmar_senha) {
                $data['json'] = '{"message" : "Senhas não conferem!!!", "success": 0}';
                $this->load->view('includes/json_view', $data);
                return;
            }

            $perfil = $this->input->post('perfil');
            if ($perfil != ""){
                $perfil = "cliente";
            }

            $usuario = array(
				'nome' => $this->input->post('nome'),
				'email' => $this->input->post('email'),
                'senha' => md5($this->input->post('senha')),
                'perfil' => $perfil
			);

            $dt_nasc = $this->input->post('dt_nasc');
            if ($dt_nasc != ""){
                $dt_nasc = substr($dt_nasc, 6, 4)."-".substr($dt_nasc, 3, 2)."-".substr($dt_nasc, 0, 2);
            }
            $usuario["dt_nasc"] = $dt_nasc; 
            

            if($this->usuario->insert($usuario)) {

                $data['json'] = '{"message": "Registro adicionado com sucesso!", "success": 1}';
                $this->load->view('includes/json_view', $data);

                return;
            }
		}

        $erro = "Erro ao adicionar registro!";
        $list_erros = $this->form_validation->error_array();
        if (isset($list_erros) && is_array($list_erros) && count($list_erros)){
            reset($list_erros);
            $erro = "";
            foreach ($list_erros as $strErro) {
                if ($erro != ""){
                    $erro .= "<br> ";    
                }
                $erro .= $strErro;
            }
        }
        $data['json'] = '{"message" : "'.$erro.'", "success": 0}';
        $this->load->view('includes/json_view', $data);

	}

	public function editar($id)
	{
        if($this->session->userdata('logged_in'))
        {
            $usuario = $this->usuario->get($this->session->userdata('logged_in')['id']);
            $data['admin'] = ($usuario["perfil"] == "admin");
            
            $data['details'] = $this->usuario->get($id);
            
            $data['main_content'] = 'usuarios/editar';
            $this->load->view('includes/template_modal', $data);
        }
        else
        {
            //If no session, redirect to login page
            redirect('account', 'refresh');
        }
	}

	public function editar_post() {

		$this->load->library('form_validation');

		$id = $this->input->post('id');
        
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|xss_clean');

		if($this->form_validation->run() == true)
		{
            $old = $this->usuario->get($id);

            if(strcmp($old['email'], $this->input->post('email')) != 0 && $this->usuario->login_existe($this->input->post('email'))) {
                $data['json'] = '{"message" : "E-mail de usuário já utilizado.", "success": 0}';
                $this->load->view('includes/json_view', $data);

                return;
            }

            $usuario = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email')                
			);
            
            $senha = $this->input->post('senha');
            $confirmar_senha = $this->input->post('confirmar_senha');
            if(strlen($senha) > 0) {
                if($senha != $confirmar_senha) {
                    $data['json'] = '{"message" : "Senhas não conferem!!!", "success": 0}';
                    $this->load->view('includes/json_view', $data);
                    return;
                }
                $usuario['senha'] = md5($this->input->post('senha'));                
            }

            $dt_nasc = $this->input->post('dt_nasc');
            if ($dt_nasc != ""){
                $dt_nasc = substr($dt_nasc, 6, 4)."-".substr($dt_nasc, 3, 2)."-".substr($dt_nasc, 0, 2);
            }
            $usuario["dt_nasc"] = $dt_nasc; 

            $perfil = $this->input->post('perfil');
            if ($perfil != ""){
                $perfil = "cliente";
            }
            
            $this->usuario->update($id, $usuario);

            $data['json'] = '{"message": "Registro alterado com sucesso!", "success": 1}';
            $this->load->view('includes/json_view', $data);

            return;
		}

        $data['json'] = '{"message" : "Erro ao alterar registro!", "success": 0}';
        $this->load->view('includes/json_view', $data);

	}

    public function modal_desativar($id) {

        if($this->session->userdata('logged_in'))
        {
            $data['main_content'] = 'usuarios/modal_desativar';
            $data['details'] = $this->usuario->get($id);
            $this->load->view('includes/template_modal', $data);
        }
        else
        {
            //If no session, redirect to login page
            redirect('account', 'refresh');
        }

    }

    public function desativar_post(){
        if ($this->usuario->desativar($this->input->post('id'))) {
            $data['json'] = '{"message": "Usuário desativado com sucesso!", "success": 1}';
            $this->load->view('includes/json_view', $data);
        } else {
            $data['json'] = '{"message": "Erro ao desativar usuário!!!", "success": 0}';
            $this->load->view('includes/json_view', $data);
        }
    }

    public function modal_ativar($id) {

        if($this->session->userdata('logged_in'))
        {
            $data['main_content'] = 'usuarios/modal_ativar';
            $data['details'] = $this->usuario->get($id);
            $this->load->view('includes/template_modal', $data);
        }
        else
        {
            //If no session, redirect to login page
            redirect('account', 'refresh');
        }

    }

    public function ativar_post(){
        if ($this->usuario->ativar($this->input->post('id'))) {
            $data['json'] = '{"message": "Usuário ativado com sucesso!", "success": 1}';
            $this->load->view('includes/json_view', $data);
        } else {
            $data['json'] = '{"message": "Erro ao ativar usuário!!!", "success": 0}';
            $this->load->view('includes/json_view', $data);
        }
    }
}
