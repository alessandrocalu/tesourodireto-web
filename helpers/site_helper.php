<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'third_party/valida-cpf-cnpj.php');


if(!function_exists('validar_cpf_cnpj'))
{
    function validar_cpf_cnpj($value)
    {
        $cpf_cnpj = new ValidaCPFCNPJ($value);
        return $cpf_cnpj->valida();
    }
}

if(!function_exists('retorna_tipo_cpf_cnpj'))
{
    function retorna_tipo_cpf_cnpj($value)
    {
        $cpf_cnpj = new ValidaCPFCNPJ($value);
        return $cpf_cnpj->retornaTipo();
    }
}

if(!function_exists('formata_cnpj_cpf'))
{
    function formata_cnpj_cpf($value)
    {
        $cpf_cnpj = new ValidaCPFCNPJ($value);
        return $cpf_cnpj->formata();
    }
}

if(!function_exists('converteDataMysql'))
{
    function converteDataMysql($data) {
        if (strlen($data) > 10) {
            $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $data);
            $newDateString = $myDateTime->format('Y-m-d H:i:s');
            return $newDateString;
        } elseif (strlen($data) == 10) {
            $date = DateTime::createFromFormat('d/m/Y', $data);
            return $date->format('Y-m-d');
        } elseif (strlen($data) == 8) {
            $date = DateTime::createFromFormat('dmY', $data);
            return $date->format('Y-m-d');
        } else if (strlen($data) == 6) {
            $date = DateTime::createFromFormat('dmy', $data);
            return $date->format('Y-m-d');
        } else {
            return FALSE;
        }
    }
}

function convertDate($data, $newFormat) {
    $old_data = DateTime::createFromFormat('Y-m-d', $data);
    return $old_data->format($newFormat);
}

function getLocalDateTime() {
    $timezone = new DateTimeZone("America/Sao_Paulo");
    $date = new DateTime("now", $timezone);
    
    return $date;        
}

function getStatusRegistroCab($status) {
    switch($status) {
        case 0:
            return "Pendente";
        
        case 1:
            return "Em processamento";                    
            
        case 2:
            return "Processado com sucesso";            

        case 3:
            return "Processado com erro";
            
        case 6:
            return "Status não esperado";
            
        default:
            return "$status";
    }
}

function currencyToLocale($valor) {
  return number_format($valor, 2, ",", ".");
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = ''//chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .'';
            //.chr(125);// "}"
        return $uuid;
    }
}

function createThumbImage($upload_image, $thumbnail, $thumb_width, $thumb_height, $file_ext = 'jpg') {
    //$thumbnail = $thumb_path.$fileName;
    
    list($width,$height) = getimagesize($upload_image);
    $thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
    $white = imagecolorallocate($thumb_create, 255, 255, 255);
    imagefill($thumb_create, 0, 0, $white);
    
    switch($file_ext){
        case 'jpg':
            $source = imagecreatefromjpeg($upload_image);
            break;
        case 'jpeg':
            $source = imagecreatefromjpeg($upload_image);
            break;

        case 'png':
            $source = imagecreatefrompng($upload_image);
            break;
        case 'gif':
            $source = imagecreatefromgif($upload_image);
            break;
        default:
            $source = imagecreatefromjpeg($upload_image);
    }
    
    $maxSize = max($width, $height);
    $ratio = $maxSize / max($thumb_width, $thumb_height);

    imagecopyresampled($thumb_create, $source, ($thumb_width - $width/$ratio)/2, ($thumb_height - $height/$ratio)/2, 0, 0, $width/$ratio, $height/$ratio, $width, $height);
    switch($file_ext){
        case 'jpg' || 'jpeg':
            imagejpeg($thumb_create,$thumbnail,100);
            break;
        case 'png':
            imagepng($thumb_create,$thumbnail,100);
            break;

        case 'gif':
            imagegif($thumb_create,$thumbnail,100);
            break;
        default:
            imagejpeg($thumb_create,$thumbnail,100);
    }
    
    /*header("Content-Type: image/jpg");
    echo file_get_contents($thumbnail);*/
}