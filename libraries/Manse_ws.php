<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: Leonardo
 * Date: 03/02/2016
 * Time: 13:15
 */

class manse_ws {

    var $_ci;

    public function __construct()
    {
        $this->_ci =& get_instance();

        // Load the library
        $this->_ci->load->library('Guzzle');
    }

    public function consultar($tipoDocumento, $documento) {

        $url_ws = "http://s2.manse.com.br/rest/portal-textil/consultar";

        // {"consultante":"string(razao ou fantasia)","id_consultante":"string(cnpj do consultante)","tipoid_consultado":"string(cnpj ou cpf)","id_consultado":"string(numero do id)"}

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', 'http://s2.manse.com.br/rest/portal-textil/consultar', [
            'json' => [
                'id_consultante' => '10433561000138',
                'consultante' => 'teste',
                'tipoid_consultado' => $tipoDocumento,
                'id_consultado' => $documento
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('portal-textil:U0VHO21L#!r3')
            ]
        ]);
        
        $res = $res->getBody();
        $res = json_decode((string)$res, true);
        
        return $res;
    }
    
    public function listarConsultantes($consultante, $id_consultante) {
        $url_ws = "http://s2.manse.com.br/rest/portal-textil/listarConsultantes";

        // {"consultante":"string(razao ou fantasia)","id_consultante":"string(cnpj do consultante)","tipoid_consultado":"string(cnpj ou cpf)","id_consultado":"string(numero do id)"}

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', $url_ws, [
            'json' => [
                'id_consultante' => $id_consultante
                //'consultante' => $consultante
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('portal-textil:U0VHO21L#!r3')
            ]
        ]);
        
        $res = $res->getBody();
        $res = json_decode((string)$res, true);
        
        return $res;
    }
    
    public function cadastrarConsultante($consultante, $id_consultante, $telefone, $contato) {
        
        $url_ws = "http://s2.manse.com.br/rest/portal-textil/cadastrarConsultante";

        // {"consultante":"string(razao ou fantasia)","id_consultante":"string(cnpj do consultante)","telefone":"string","contato":"string"}

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', $url_ws, [
            'json' => [
                'id_consultante' => $id_consultante,
                'consultante' => $consultante,
                'telefone' => $telefone,
                'contato' => $contato
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('portal-textil:U0VHO21L#!r3')
            ]
        ]);
        
        $res = $res->getBody();
        $res = json_decode((string)$res, true);
        
        return $res;
    }
    
    
    public function apagarConsultante($id_consultante) {
        
        $url_ws = "http://s2.manse.com.br/rest/portal-textil/apagarConsultante";

        // {"consultante":"string(razao ou fantasia)","id_consultante":"string(cnpj do consultante)","telefone":"string","contato":"string"}

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', $url_ws, [
            'json' => [
                'id_consultante' => $id_consultante
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('portal-textil:U0VHO21L#!r3')
            ]
        ]);
        
        $res = $res->getBody();
        $res = json_decode((string)$res, true);
        
        return $res;
    }    

}