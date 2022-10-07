<?php 
    header('Content-type: application/json');

    $token = '9485b423-5bfe-4d2b-8b1d-0a878fb8aebd';
    
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    ini_set('display_errors',1); ini_set('display_startup_erros',1); error_reporting(E_ALL); 
    ini_set('memory_limit', '-1'); date_default_timezone_set('America/Sao_Paulo');

    header('Content-Type: text/html; charset=utf-8');

    if(!isset($_GET['token']) || ($token != $_GET['token'])){
      echo 'Acesso não autorizado';
      exit;
    }

    include_once('./classes/Database.class.php');
    include_once('./classes/Functions.class.php');

    $db = new Database();
    $funcao = new Functions();

    $crlv = $funcao->buildQuery('CRLV');
    $atpv = $funcao->buildQuery('ATPV');

    $db->query = "$crlv";
    $db->content = NULL;
    $rows_crlv = ($db->select());

    $db->query = "$atpv";
    $db->content = NULL;
    $rows_atpv = ($db->select());
    
    $data = array();

    $arrayCRLV = array();
    foreach((array)$rows_crlv[0] as $key => $value) {
        $arrayCRLV[$key] = $value;
        $data[$key] = $key;
    }
    
    $arrayATPV = array();
    foreach((array)$rows_atpv[0] as $key => $value) {
        $arrayATPV[$key] = $value;
        $data[$key] = $key;
    }

    foreach($data as $key => $value) {
        $data[$key] = array(
            "CRLV"=> (array)$arrayCRLV[$key][0],
            "ATPV"=> (array)$arrayATPV[$key][0],
        );
    }


  echo json_encode($data);
?>