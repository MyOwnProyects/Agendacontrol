<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;  // Asegúrate de importar la clase Request
use Phalcon\Http\Response; // Asegúrate de importar la clase Response
use App\Library\FuncionesGlobales;


class TipousuariosController extends BaseController
{
    protected $rutas;
    protected $url_api;

    public function initialize(){
        $config         = $this->getDI();
        $this->rutas    = $config->get('rutas');
        $config         = $config->get('config');
        $this->url_api  = $config['BASEAPI'];
    }

    public function IndexAction(){
        $aqui = 1;
        if ($this->request->isAjax()){
            $accion = $this->request->getPost('accion', 'string');
            $result = array();
            if($accion == 'get_rows'){
                $aqui   = 1;

                $arr_return = array(
                    "draw"          => $this->request->getPost('draw'),
                    "recordsTotal" => 0,
                    "recordsFiltered" => 10,
                    "data" => array()
                );
        
                // SE REALIZA LA BUSQUEDA DEL COUNT
                $route  = $this->url_api.$this->rutas['cttipo_usuarios']['show'];
                $result = FuncionesGlobales::RequestApi('GET',$route,$_POST);
        
                if (count($result) == 0){
                    return $arr_return;
                }
        
                $result = array(
                    "draw"              => $this->request->getPost('draw'),
                    "recordsTotal"      => count($result),
                    "recordsFiltered"   => 10,
                    "data"              => $result
                );
        
            }

            if ($accion == 'get_permisos'){
                // SE REALIZA LA BUSQUEDA DEL COUNT
                $_POST['fromcatalog']   = 1;
                $route  = $this->url_api.$this->rutas['ctpermisos']['show'];
                $result = FuncionesGlobales::RequestApi('GET',$route,$_POST);
            }

            $response = new Response();
            $response->setJsonContent($result);
            $response->setStatusCode(200, 'OK');
            return $response;
        }

        $this->view->create = FuncionesGlobales::HasAccess("Tipousuarios","create");
        $this->view->update = FuncionesGlobales::HasAccess("Tipousuarios","update");
        $this->view->delete = FuncionesGlobales::HasAccess("Tipousuarios","delete");
    }
    
}