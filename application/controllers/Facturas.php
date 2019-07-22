<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Facturas extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('factura_model');
    }

    public function index_get() {

        //1
        $facturas = $this->factura_model->obtenerFacturasDelDia();
        for($i =0;$i<count($facturas);$i++){
            $detalle_producto = $this->factura_model->obtenerDetalleFacturaPorId($facturas[$i]['id_factura']);
            $facturas[$i]['detalle'] = $detalle_producto;
        }

        //$this->generarFacturas($facturas);
        $this->response($facturas);
    }

    public function index_post() {

        $facturas = $this->factura_model->obtenerFacturasDelDia();
        for($i =0;$i<count($facturas);$i++){
            $detalle_producto = $this->factura_model->obtenerDetalleFacturaPorId($facturas[$i]['id_factura']);
            $facturas[$i]['detalle'] = $detalle_producto;
        }

        //Recorrer cada factura y agregarle el detalle dentro
        //Here make an auth token validation 
        //testing the auth Token:
        //$this->response($this->input->get_request_header('Authorization'));
        $this->generarFacturas($facturas);
        $this->response("complete");
    }

    private function generarFacturas($facturas){
        foreach($facturas as $factura) {
            $numero_factura = $this->factura_model->insertarFactura($factura);
            foreach($factura['detalle'] as $detalle){
                $this->factura_model->insertarDetalleFactura($detalle, $numero_factura);
            }
        }
    }

}
