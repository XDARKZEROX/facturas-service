<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Facturas extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('factura_model');
        $this->load->library('pdfgenerator');
        $this->load->config('email');
        $this->load->library('email');
    }

    public function index_get() {
        $facturas = $this->factura_model->obtenerFacturasDelDia();
        for($i =0;$i<count($facturas);$i++){
            $detalle_producto = $this->factura_model->obtenerDetalleFacturaPorId($facturas[$i]['id_factura']);
            $facturas[$i]['detalle'] = $detalle_producto;
        }

        //$this->generarFacturas($facturas);
        //$this->response($facturas);
        $html = $this->load->view('factura/factura',[],true);
        $filename = 'reporte.pdf';
        $output = $this->pdfgenerator->generate($html, $filename, false, 'A4', 'portrait');
        file_put_contents($filename, $output);
        $this->email->set_newline("\r\n");
        $this->email->from("Alexander");
        $this->email->to("aguzman@northsouthstudios.com");
        $this->email->subject("factura");
        $this->email->message("test");
        $this->email->attach($filename);
        if ($this->email->send()) {
            echo 'Your Email has successfully been sent.';
            unlink($filename);
        } else {
            show_error($this->email->print_debugger());
        }
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

    private function realizarEnvioCorreo($data){

    }
}
