<?php

class Item {
    function __construct() {
        //echo "Has llegado al constructor de Items<br>";
    }

    function getAllItems() {
        echo "Has llegado al método getAllItems() del controlador Item\n";
    }
    
    function getItemById($id) {
        echo "Has llegado al método getItemById() del controlador Item\n";
        echo "El ID del item es: " . $id . "\n";
    }
    
    function getItemsByArtist($id) {
        echo "Has llegado al método getItemsByArtist() del controlador Item\n";
        echo "El ID del artista es: " . $id . "\n";
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
        echo "Los datos del ítem son : " . json_encode($data) . "\n";
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