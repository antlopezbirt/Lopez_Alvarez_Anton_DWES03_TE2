<?php

// Clase que genera y envía respuestas HTTP
class Response {
    private $status;
    private $code; // El código de la cabecera
    private $respCode; // El código que se muestra en la respuesta
    private $description; // La descripción de la operación
    private $response;
    private $statusCodes = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    public function __construct($code, $description, $response) {
        
        // Para los códigos 204, por cabecera se pasa un 200 para que llegue el body
        $this->code = $code === 204 ? 200 : $code;
        $this->respCode = $code;
        $this->description = $description;
        $this->response = $response;

        // El status según el código
        $this->status = $this->statusCodes[$code];

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