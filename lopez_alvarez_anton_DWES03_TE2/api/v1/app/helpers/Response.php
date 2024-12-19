<?php

class Response {
    private $estado;
    private $codigo;
    private $respuesta;

    public function __construct($codigo, $respuesta) {
        $this->codigo = $codigo;
        $this->respuesta = $respuesta;

        // Asigna el mensaje de status según el código

        switch ($this->codigo) {
            case 200: {
                $this->estado = 'OK';
                break;
            }
                
            case 201: {
                $this->estado = 'Created';
                break;
            }

            case 204: {
                $this->estado = 'No Content';
                break;
            }

            case 400: {
                $this->estado = 'Bad Request';
                break;
            }

            case 404: {
                $this->estado = 'Not Found';
                break;
            }

            case 500: {
                $this->estado = 'Internal Server Error';
                break;
            }
        }
    }

    public function enviar() {

        // Pasa el código a la cabecera
        http_response_code($this->codigo);

        // Imprime el mensaje en el body de la respuesta
        $res = ['status' => $this->estado, 'code' => $this->codigo, 'response' => $this->respuesta];
        echo json_encode($res);
    }
}