<?php

    class JSONView {

        //Devuelve un arreglo en formato Json y el mensaje asociado al codigo de estado
        public function response($data, $status = 200) {
            header("Content-Type: application/json");//el servidor le avisa al cliente externo que devolvemos json
            $statusText = $this->_requestStatus($status);//llama al metodo de abajo 
            header("HTTP/1.1 $status $statusText");//el cliente externo recibe el mensaje asociado al codigo de estado
            echo json_encode($data);
        }

        private function _requestStatus($code) {//metodo privado que devuelve el mensaje asociado al codigo de estado
            $status = array(
                200 => "Operación Exitosa",
                201 => "Recurso Creado",
                204 => "Sin Contenido",
                400 => "Solicitud Incorrecta",
                401 => "Acceso No Autorizado",
                404 => "Recurso No Encontrado",
                500 => "Error en el Servidor"
            );
            if(!isset($status[$code])) {
                $code = 500;
            }
            return $status[$code];
        }
    }