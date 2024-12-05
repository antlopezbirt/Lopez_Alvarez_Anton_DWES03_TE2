<?php

class Item {
    function __construct() {
        //echo "Has llegado al constructor de Items<br>";
    }

    function getAllItems() {
        //echo "Has llegado al método getAllItems() del controlador Item\n";

        $rutaJson = '../data/items.json';
        $jsonString = file_get_contents($rutaJson);
        $jsonData = json_decode($jsonString, true);
        echo($jsonString);
    }
    
    function getItemById($id) {
        // echo "Has llegado al método getItemById() del controlador Item\n";
        // echo "El ID del item es: " . $id . "\n";

        $rutaJson = '../data/items.json';
        $jsonString = file_get_contents($rutaJson);
        $jsonData = json_decode($jsonString, true);
        $clave = array_search(strval($id), array_column($jsonData, 'id'));
        echo json_encode($jsonData[$clave]);

    }
    
    function getItemsByArtist($id) {
        echo "Has llegado al método getItemsByArtist() del controlador Item\n";
        echo "El ID del artista es: " . $id . "\n";

        $rutaJson = '../data/items.json';
        $jsonString = file_get_contents($rutaJson);
        $jsonData = json_decode($jsonString, true);
        if ($claves = array_keys(array_column($jsonData, 'artist'), strval($id))) {
            echo "[\n";
            foreach ($claves as $clave) echo json_encode($jsonData[$clave]) . ",\n";
            echo "]";
        }
        //var_dump($claves);
        
    }
    
    function getItemsByFormat($id) {
        echo "Has llegado al método getItemsByFormat() del controlador Item\n";
        echo "El ID del formato es: " . $id . "\n";
    }
    
    function sortItemsByKey($key, $order) {
        echo "Has llegado al método sortItemsByKey() del controlador Item\n";
        echo "La clave para ordenar es: " . $key . " y el orden es: " . $order . "\n";
    }
    

    // Consultas POST (Create, Put, Delete)


    function createItem($data) {
        http_response_code(201);
        echo "Has llegado al método createItem() del controlador Item\n";
        echo "Los datos del ítem son: " . json_encode($data) . "\n";
    }
    
    function updateItem($id, $data) {
        http_response_code(204);
        // echo "Has llegado al método updateItem() del controlador Item\n";
        // echo "El ID del item es: " . ($id) . "\n";
        // echo "Los datos del ítem son: " . json_encode($data) . "\n";
    }
    
    function deleteItem($id) {
        if (false) {

        } else {
            http_response_code(201);
            echo "Has llegado al método deleteItem() del controlador Item\n";
            echo "El ID del item es: " . $id . "\n";
        } 
    }
}