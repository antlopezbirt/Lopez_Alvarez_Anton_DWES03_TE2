<?php

// Clase que genera y envía respuestas HTTP
class Response {
    private $status;
    private $code; // El código de la cabecera
    private $respCode; // El código que se muestra en la respuesta
    private $description; // La descripción de la operación
    private $response;

    public function __construct($code, $description, $response) {

        // Para los códigos 204, por cabecera se pasa un 200 para que llegue el body
        if ($code != 204) {
            $this->code = $code;
        } else {
            $this->code = 200;
        }

        $this->respCode = $code;
        $this->description = $description;
        $this->response = $response;

        // El status según el código

        switch ($this->code) {
            case 200: {
                $this->status = 'OK';
                break;
            }
            case 201: {
                $this->status = 'Created';
                break;
            }
            case 204: {
                $this->status = 'No Content';
                break;
            }
            case 400: {
                $this->status = 'Bad Request';
                break;
            }
            case 404: {
                $this->status = 'Not Found';
                break;
            }
            case 500: {
                $this->status = 'Internal Server Error';
                break;
            }
        }
    }

    public function enviar() {

        // Pasa el código a la cabecera
        http_response_code($this->code);

        // La respuesta siempre es un JSON
        header('Content-Type: application/json');

        // Genera el cuerpo de la respuesta
        $res = ['status' => $this->status, 'code' => $this->respCode, 
                'description' => $this->description, 'response' => $this->response];

        // Imprime el cuerpo generado
        echo json_encode($res);
    }
}