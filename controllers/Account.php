<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	private $fb;

	function __construct()
	{
		parent::__construct();
		$this->load->model('usuario','usuario');
		$this->load->model('Smtp', 'smtp');
		$this->fb = new Facebook\Facebook([
        	'app_id' => '530212320519947',
        	'app_secret' => '5ee85b0d41e4ead0ea1cf37906e5e288',
        	'default_graph_version' => 'v2.7',
        	'persistent_data_handler'=>'session'
    	]);
	}

	public function index()
	{
		$this->load->helper(array('form'));

		$helper = $this->fb->getRedirectLoginHelper();
    	$loginUrl = $helper->getLoginUrl(base_url().'index.php/account/loginfb');
		$data["loginUrl"] = $loginUrl; 
		$this->load->view('login_view', $data);
	}

	public function loginfb(){
		$helper = $this->fb->getRedirectLoginHelper();
 
    	try {
        	$accessToken = $helper->getAccessToken();
    	} catch(Facebook\Exceptions\FacebookResponseException $e) {
        	$erro = 'Erro da Graph API: ' . $e->getMessage();

    	} catch(Facebook\Exceptions\FacebookSDKException $e) {
        	$erro = 'Erro da Facebook SDK: ' . $e->getMessage();
    	}
    	 
    	if (isset($accessToken)) {
        	$this->session->set_userdata('facebook_access_token', (string) $accessToken);
        	try {
            	$response = $this->fb->get('/me?fields=id,name,email,picture', $accessToken);

    			$user = $response->getGraphUser();

   			    if((isset($user["email"]) || isset($user["id"])) && isset($user["name"])){
    				$email = isset($user["email"])?$user["email"]:$user["id"];
    				$nome = $user["name"];
    				$usuario = $this->usuario->login_existe($email);	

    				if (!$usuario){
	            		$usuario = array(
							'nome' => $nome,
							'email' => $email,
	                		'perfil' => "cliente"
						);

						if($this->usuario->insert($usuario)) {
							$usuario = $this->usuario->login_existe($email);
						}	
					}

					if ($usuario) {
						$this->register_session($usuario);
						redirect('home', 'refresh');
					}

					$erro = 'Erro em verificação de usuário!';
    			}
        	} catch(Facebook\Exceptions\FacebookResponseException $e) {
            	$erro = 'Erro da Graph API: ' . $e->getMessage();
        	} catch(Facebook\Exceptions\FacebookSDKException $e) {
            	$erro = 'Erro da Facebook SDK: ' . $e->getMessage();
        	}
    	} elseif ($helper->getError()) {
        	$erro = "Requisição negada para o usuário.";
    	}else{
        	$erro = "Erro desconhecido.";
    	}
 

    	//Field validation failed.  User redirected to login page
    	if(isset($erro) && $erro) {
    		$data["erro"] = $erro;
			redirect('account', 'refresh');
    	} 
	}	

	public function recoverpw() {
    	$this->load->view('recoverpw_view');
    }

    public function sendemail() {
    	$this->load->helper(array('form', 'url'));
		//This method will have the credentials validation
		$this->load->library('form_validation');

    	$this->form_validation->set_rules('login', 'Email', 'trim|required|xss_clean');
		if($this->form_validation->run() == FALSE)
		{
			$data['erro'] = 'Email não encontrato!';
			$this->load->view('login_view', $data);
		}	
		else
		{
			//Field validation succeeded.  Validate against database
			$login = $this->input->post('login');

		
			//query the database
			$usuario = $this->usuario->login_existe($login);

			if($usuario)
			{
				//Recupera ID de Usuário por email 
				$id = $usuario->id;
				$data_session = date("Y-m-d H:i:s", strtotime("24 hours", strtotime("now")));
				$chave_session = md5($data_session."chaveEmail".$login);
				$dados["data_session"] = $data_session;
				$dados["chave_session"] = $chave_session;
				if ($this->usuario->update($id, $dados)) 
				{
					$email = $login; 
					$assunto = "Carteira Tesouro Direto - Redefinição de Senha";
					$mensagem = "Olá <br><br> Para redefinir sua senha clique <a href='".base_url()."index.php/account/newpw?ch=".$chave_session."' >AQUI</a>";
					$this->smtp->enviar_email($email, $assunto, $mensagem);
					$data['mensagem'] = 'Foi enviado um email com instruções para redefinir senha!';
					$this->load->view('login_view', $data);
				} 
				else 
				{
                    echo "Erro ao gravar!";
                }	
			}	
			else
			{
				$data['erro'] = 'Email não encontrato!';
				$this->load->view('recoverpw_view', $data);
			}	
		}	
    }

    public function newpw() {
    	$this->load->helper(array('form', 'url'));
    	$chave = $this->input->get('ch');

    	if ($this->usuario->getByChave($chave)){
    		$data["chave"] = $chave;
    		$this->load->view('newpw_view', $data);
    	}
    	else
    	{
    		echo "Acesso não autorizado!";
    	}	
    }

    public function changepw() {
    	$this->load->helper(array('form', 'url'));
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('chave', 'Acesso', 'trim|required|xss_clean');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|xss_clean');

		$chave = $this->input->post('chave');
		$senha = $this->input->post('senha');
        $confirmar_senha = $this->input->post('confirmar_senha');

		
		if ($this->form_validation->run() == true) {
			if($senha != $confirmar_senha) {
                $data["erro"] = "Senhas não conferem!";
            }
	    	elseif ($usuario = $this->usuario->getByChave($chave)){
	    		$id = $usuario->id;
	    		$dados['senha'] = md5(trim($senha));
	    		if ($this->usuario->update($id, $dados)){
	    			$data["mensagem"] = "Senha alterada com sucesso!";
	    			$this->load->view('login_view', $data);
	    		}
	    		else
	    		{
	    			$data["erro"] = "Erro ao gravar dados!";	
	    		}	
	    	}
	    	else
	    	{
	    		$data["erro"] = "Acesso não autorizado!";
	    	}	
	    }
	    else
	    {
	    	$data["erro"] = "Preencha os campos corretamente!";
	    }	

	    $data["chave"] = $chave;
	    $this->load->view('newpw_view', $data);
    }

	public function login() {
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('login', 'login', 'trim|required|xss_clean');
		$this->form_validation->set_rules('senha', 'senha', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.  User redirected to login page
			$data["erro"] = "Email e/ou senha inválidos!";
			$this->load->view('login_view', $data);
		}
		else
		{
			//Go to private area
			redirect('home', 'refresh');
		}
	}

	function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('account', 'refresh');
	}

	function check_database($senha)
	{
		//Field validation succeeded.  Validate against database
		$login = $this->input->post('login');

		//query the database
		$result = $this->usuario->login($login, $senha);

		if($result)
		{
			$this->register_session($result);
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}


	function register_session($usuario){
		$sess_array = array();
		$sess_array = array(
			'id' => $usuario->id,
			'login' => $usuario->email,
			'perfil' => $usuario->perfil,
			'nome' => $usuario->nome,
			'esconder_menu' => false
		);
		$this->session->set_userdata('logged_in', $sess_array);
	}

}
