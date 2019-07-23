<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Facturas extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('factura_model');
    }

    public function index_get() {
        $facturas = $this->factura_model->obtenerFacturasDelDia();
        for($i =0;$i<count($facturas);$i++){
            $detalle_producto = $this->factura_model->obtenerDetalleFacturaPorId($facturas[$i]['id_factura']);
            $facturas[$i]['detalle'] = $detalle_producto;
        }

        //$this->generarFacturas($facturas);
        //$this->response($facturas);
        $this->load->view('factura/factura.html');
    }

    public function index_post() {

        $facturas = $this->factura_model->obtenerFacturasDelDia();
        for($i =0;$i<count($facturas);$i++){
            $detalle_producto = $this->factura_model->obtenerDetalleFacturaPorId($facturas[$i]['id_factura']);
            $facturas[$i]['detalle'] = $detalle_producto;
        }

        $facturas_generadas = $this->generarFacturas($facturas);
        $this->response($facturas_generadas);
    }

    private function generarFacturas($facturas){

        for($i =0;$i<count($facturas);$i++){
            $numero_factura = $this->factura_model->insertarFactura($facturas[$i]);
            $facturas[$i]['numero_factura'] = $numero_factura;
            foreach($facturas[$i]['detalle'] as $detalle){
                $this->factura_model->insertarDetalleFactura($detalle, $numero_factura);
            }
        }

        return $facturas;
    }
}
