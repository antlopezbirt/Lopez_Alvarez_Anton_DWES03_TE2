<?php

namespace app\controllers;

use app\utils\JsonFileHandler;
use app\models\ItemModel;
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
        // echo "Hola desde el método getAll de ItemController.";

        $items = $this->dataHandler->readAllItems();
        // var_dump($items);
        if(isset($items)) {
            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($items, JSON_PRETTY_PRINT);
            return true;
        } else {
            echo "No hay items";
            return false;
        }

    }

    public function getById($id) {
        echo "Hola desde el método getById de ItemController.<br>";
        echo "ID solicitado: " . $id;
    }

    public function create($datosJson) {
        echo "Hola desde el método create de ItemController.";
    }

    public function update($datosJson) {
        echo "Hola desde el método update de ItemController.";
    }

    public function delete($datosJson) {
        echo "Hola desde el método delete de ItemController.";
    }

}