<?php

class Item {

    private string $jsonString;
    private array $jsonData;

    public function __construct() {
       $this->jsonString = file_get_contents(PATH_JSON);
       $this->jsonData = json_decode($this->jsonString, true); 
    }

    // Devuelve todos los Items de la coleccion musical
    function getAllItems() {
        // Declara el array donde se recogeran los modelos de Items
        $arrayItems = array();

        // Recorre el objeto JSON y acumula los datos en un array de modelos Item
        foreach ($this->jsonData as $clave => $item) {
            $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
        }

        // Responde con todos los items serializados (mediante jsonSerialize)
        $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
        echo json_encode($res);
    }
    
    // Devuelve el Item que se corresponda con el ID recibido, o un 404 si no existe
    function getItemById($id) {

        // Busca el ID en los datos, recibiendo la clave o un false
        $encontrado = array_search(strval($id), array_column($this->jsonData, 'id'));

        // Si encuentra el Item, instancia un modelo y lo devuelve
        if ($encontrado) {
            $item = new ItemModel($this->jsonData[$encontrado]['id'], $this->jsonData[$encontrado]['title'], 
                $this->jsonData[$encontrado]['artist'], $this->jsonData[$encontrado]['format'], $this->jsonData[$encontrado]['year'], 
                $this->jsonData[$encontrado]['origYear'], $this->jsonData[$encontrado]['label'], $this->jsonData[$encontrado]['rating'],
                $this->jsonData[$encontrado]['comment'], $this->jsonData[$encontrado]['buyPrice'], $this->jsonData[$encontrado]['condition'], 
                $this->jsonData[$encontrado]['sellPrice'], $this->jsonData[$encontrado]['externalIds']);
            
            // Responde con el Item serializado
            $res = ['status' => 'OK', 'code' => '200', 'response' => $item];
            echo json_encode($res);

        // Si no se encuentra, devuelve un codigo 404 y un mensaje explicativo
        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Item no encontrado.'];
            echo json_encode($res);
        }
    }
    
    // Devuelve todos los Items cuyo artista sea el recibido, o un 404 si no se encuentra ninguno
    function getItemsByArtist($artist) {
        echo "Has llegado al método getItemsByArtist() del controlador Item\n";
        echo "El artista buscado es: " . $artist . "\n";

        // Busca todas las ocurrencias de ese artista y guarda las claves
        $encontrados = array_keys(array_column($this->jsonData, 'artist'), strval($artist));
        var_dump(array_column($this->jsonData, 'artist'));
        var_dump($encontrados);
        // var_dump($this->jsonData);
        // if ($encontrados) {
        //     // Declara el array donde se recogeran los modelos de Items
        //     $arrayItems = array();

        //     foreach ($encontrados as $clave => $item) {
        //         $arrayItems[] = new ItemModel($this->jsonData[$clave]['id'], $this->jsonData[$clave]['title'], 
        //         $this->jsonData[$clave]['artist'], $this->jsonData[$clave]['format'], $this->jsonData[$clave]['year'], 
        //         $this->jsonData[$clave]['origYear'], $this->jsonData[$clave]['label'], $this->jsonData[$clave]['rating'],
        //         $this->jsonData[$clave]['comment'], $this->jsonData[$clave]['buyPrice'], $this->jsonData[$clave]['condition'], 
        //         $this->jsonData[$clave]['sellPrice'], $this->jsonData[$clave]['externalIds']);
        //     }

        //     // Responde con todos los items serializados (mediante jsonSerialize)
        //     $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
        //     echo json_encode($res);

        // } else {
        //     http_response_code(404);
        //     $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Artista no encontrado.'];
        //     echo json_encode($res);
        // }
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