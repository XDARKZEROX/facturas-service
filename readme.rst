######################################
Servicio de Facturación Electronica
######################################

Servicio web de facturacion que obtiene el listado de facturas que pertenezcan
al dia actual y en caso de ser el mismo, enviara un correo creando una nueva factura.

*********
Version
*********
1.0.0


**************
Observaciones
**************

A partir de Mysql 8.0 el metodo de autenticación para mysqli mostrara un mensaje de error, esto se puede corregir ejecutando la siguiente sentencia sql para el usuario con el que se accede a la base de datos:

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mysql';

El sistema por el momento esta pensando en una relacion de emision de facturas de 1 a 1
Esto quiere decir que si se necesitase enviar mas de un correo de facturación perdiodicamente la solución es que se registren 2 clientes diferentes.

Otra observacion es que se necesita agregar una tabla de estados a la facturacion que pueda determinar si dicho cliente
debe seguir recibiendo correos de forma masiva

****************
Futuras mejoras
****************
Implementar un panel de facturacion que permita personalizar más el la logica de facturacion.
Por ejemplo el poder determinar si la factura a emitir sera 
- Mensual
- Anual
- Bimestral, Trimestral, etc.

CREATE TABLE IF NOT EXISTS `facturacion` (
   `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `estado` char(1) NOT NULL,
   `id_factura` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;