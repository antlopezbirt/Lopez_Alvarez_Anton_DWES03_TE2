<?php

namespace app\utils;

class ApiResponse {

    private $response = [
        'status' => '',
        'code' => '',
        'description' => '',
        'data' => '',
    ];

    public function __construct(string $status, int $code = 0, 
                                string $description = '', $data = []) {
        $this->response['status'] = $status;
        $this->response['code'] = $code;
        $this->response['description'] = $description;
        $this->response['data'] = $data;
    }

    // Getter de Status
    public function getStatus() {
        return $this->response['status'];
    }

    // Getter de Code
    public function getCode() {
        return $this->response['code'];
    }

    // Getter de Description
    public function getDescription() {
        return $this->response['description'];
    }

    // Getter de Data
    public function getData() {
        return $this->response['data'];
    }

    // Getter de la respuesta completa
    public function getResponse() {
        return $this->response;
    }

    // Setter de Status
    public function setStatus($status): void {
        $this->response['status'] = $status;
    }

    // Setter de Status
    public function setCode($code): void {
        $this->response['code'] = $code;
    }

    // Setter de Status
    public function setDescription($description): void {
        $this->response['description'] = $description;
    }

    // Setter de Status
    public function setData($data): void {
        $this->response['data'] = $data;
    }

    // Setter de la respuesta completa
    public function setResponse($response): void {
        $this->response = $response;
    }


    public function toJson() {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

}