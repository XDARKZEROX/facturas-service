<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Facturas extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('factura_model');
        $this->load->model('perfil_model');
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

        $facturas_generadas = $this->generarFacturas($facturas);
        $data['perfil'] = $this->perfil_model->obtenerPerfil();

        if(!empty($facturas_generadas)){
            foreach($facturas_generadas as $factura){
                $data['factura'] = $factura;
                $html = $this->load->view('factura/factura', $data, true);
                $filename = 'clicdominio FAC'.$factura['numero_factura'].'.pdf';
                $output = $this->pdfgenerator->generate($html, $filename, false, 'A4', 'portrait');
                file_put_contents($filename, $output);
                $this->email->set_newline("\r\n");
                $this->email->from("no-reply@clicdominio.com");
                $this->email->to($factura['email_cliente']);
                $this->email->subject('[clicdominio] Emision de factura automatica FAC'.$factura['numero_factura']);
                $this->email->message('Su factura del mes ya se encuentra generada.');
                $this->email->attach($filename);
                if ($this->email->send()) {
                    unlink($filename);
                    $this->email->clear(true);
                } else {
                    show_error($this->email->print_debugger());
                }
                
            }
        }

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
