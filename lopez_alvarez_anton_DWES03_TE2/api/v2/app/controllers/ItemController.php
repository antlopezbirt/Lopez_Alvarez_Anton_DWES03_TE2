<?php

namespace app\controllers;

use app\utils\JsonFileHandler;
use app\models\ItemModel;
use app\utils\ApiResponse;
use config;

class ItemController {

    private $dataHandler;

    public function __construct() {
        $this->dataHandler = new JsonFileHandler(DATA_FILE);
    }

    public function index() {
        echo "Hola desde el índice de ItemController.";
    }

    public function getAll() {

        $items = $this->dataHandler->readAllItems();

        if(isset($items)) {
            $response = new ApiResponse('OK', 200, 'Todos los ítems', $items);
            return $this->sendJsonResponse($response);
        } else {
            $response = new ApiResponse('ERROR ', 500, 'No hay ítems', null);
            return $this->sendJsonResponse($response);
        }

    }

    public function getById($id) {
        echo "Hola desde el método getById de ItemController.<br>";
        echo "ID solicitado: " . $id;

        $items = $this->dataHandler->readAllItems();

        var_dump($items);
    }

    public function create($datosJson) {
        // echo "Hola desde el método create de ItemController.";

        // Primero coge todos los items

        $items = $this->dataHandler->readAllItems();

        // Crear nuevo ID
        $newId = $this->generateNewId($items);

        // Instanciar un ItemModel con los datos para guardarlo
        $item = new ItemModel($newId, $datosJson['title'], $datosJson['artist'], $datosJson['format'], 
                                $datosJson['year'], $datosJson['origYear'], $datosJson['label'],
                                $datosJson['rating'], $datosJson['comment'], $datosJson['buyPrice'],
                                $datosJson['condition'], $datosJson['sellPrice'],
                                $datosJson['externalIds']);

        if ($this->dataHandler->writeItem($item)) {
            $response = new ApiResponse('Created', 201, 'Item guardado', $item);
            return $this->sendJsonResponse($response);
        } else {
            $response = new ApiResponse('ERROR', 500, 'No se pudo guardar', $item);
            return $this->sendJsonResponse($response);
        }
    }

    public function update($datosJson) {
        echo "Hola desde el método update de ItemController.";
    }

    public function delete($datosJson) {
        echo "Hola desde el método delete de ItemController.";
    }

    private function sendJsonResponse(ApiResponse $response) {
        header('Content-Type: application/json');
        http_response_code($response->getCode());
        echo $response->toJson();
    }

    private function generateNewId($items) {
        $newId = 0;

        foreach($items as $item) {
            $newId = max($item->getId(), $newId);
        }

        return $newId + 1;
    }

}