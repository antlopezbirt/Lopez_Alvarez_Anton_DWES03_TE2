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
        $encontrado = array_search($id, array_column($this->jsonData, 'id'));

        // Si encuentra el Item, instancia un modelo y lo devuelve
        if ($encontrado !== false) {
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
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Item no encontrado'];
            echo json_encode($res);
        }
    }
    
    // Devuelve todos los Items cuyo artista sea el recibido, o un 404 si no se encuentra ninguno
    function getItemsByArtist($artist) {

        // Sustituye guiones por espacios en caso de que los haya
        $artist = str_replace('-', ' ', $artist);

        // Busca todas las ocurrencias de ese artista y guarda las claves
        $encontrados = array_keys(array_column($this->jsonData, 'artist'), $artist);

        if ($encontrados) {
            // Declara el array donde se recogeran los modelos de Items
            $arrayItems = array();

            foreach ($encontrados as $clave) {
                $arrayItems[] = new ItemModel($this->jsonData[$clave]['id'], $this->jsonData[$clave]['title'], 
                $this->jsonData[$clave]['artist'], $this->jsonData[$clave]['format'], $this->jsonData[$clave]['year'], 
                $this->jsonData[$clave]['origYear'], $this->jsonData[$clave]['label'], $this->jsonData[$clave]['rating'],
                $this->jsonData[$clave]['comment'], $this->jsonData[$clave]['buyPrice'], $this->jsonData[$clave]['condition'], 
                $this->jsonData[$clave]['sellPrice'], $this->jsonData[$clave]['externalIds']);
            }

            // Responde con todos los items serializados (mediante jsonSerialize)
            $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
            echo json_encode($res);

        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Artista no encontrado'];
            echo json_encode($res);
        }
    }
    
    function getItemsByFormat($format) {

        // Busca todas las ocurrencias de ese formato y guarda las claves
        $encontrados = array_keys(array_column($this->jsonData, 'format'), $format);

        if ($encontrados) {
            // Declara el array donde se recogeran los modelos de Items
            $arrayItems = array();

            foreach ($encontrados as $clave) {
                $arrayItems[] = new ItemModel($this->jsonData[$clave]['id'], $this->jsonData[$clave]['title'], 
                $this->jsonData[$clave]['artist'], $this->jsonData[$clave]['format'], $this->jsonData[$clave]['year'], 
                $this->jsonData[$clave]['origYear'], $this->jsonData[$clave]['label'], $this->jsonData[$clave]['rating'],
                $this->jsonData[$clave]['comment'], $this->jsonData[$clave]['buyPrice'], $this->jsonData[$clave]['condition'], 
                $this->jsonData[$clave]['sellPrice'], $this->jsonData[$clave]['externalIds']);
            }

            // Responde con todos los items serializados (mediante jsonSerialize)
            $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
            echo json_encode($res);

        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Formato no encontrado'];
            echo json_encode($res);
        }
    }
    
    function sortItemsByKey($key, $order) {

        $order = $order == 'asc' ? SORT_ASC : SORT_DESC;

        array_multisort(array_column($this->jsonData, $key), $order, $this->jsonData);

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
    

    // Consultas POST (Create, Put, Delete)

    function createItem($data) {
        // Si se recibe más de una entrada llega como array multidimensional y no se acepta
        if (!array_key_exists(0, $data)) {

            // Genera un nuevo ID a partir del mas alto encontrado en el registro
            $nuevoId = 0;
            foreach ($this->jsonData as $clave => $item) $nuevoId = max(intval($item['id']), $nuevoId);
            $nuevoId++;

            // Agrega el ID al registro recibido
            $data = array('id' => strval($nuevoId)) + $data;
            
            // Anexa el nuevo elemento al array de Items
            array_push($this->jsonData, $data);

            // Escribe los datos en el fichero
            $fp = fopen(PATH_JSON, 'w');
            $resultado = fwrite($fp, json_encode($this->jsonData, JSON_PRETTY_PRINT));
            fclose($fp);

            // Envia la respuesta
            if ($resultado) {
                http_response_code(201);
                $res = ['status' => 'Created', 'code' => '201', 'response' => $data];
                echo json_encode($res);
            } else {
                http_response_code(500);
                $res = ['status' => 'Internal Server Error', 'code' => '500', 'response' => $data];
                echo json_encode($res);
            }

        // Ha llegado más de una entrada o ninguna
        } else {
            http_response_code(400);
            $res = ['status' => 'Bad Request', 'code' => '400', 'response' => 'Cantidad de registros inválida (' . count($data) . ')'];
            echo json_encode($res);
        }
    }
    
    function updateItem($id, $data) {

        // Busca el ID en los datos, recibiendo la clave o un false
        $encontrado = array_search($id, array_column($this->jsonData, 'id'));

        // Si encuentra el Item, comienza a iterar los datos para actualizarlo
        if ($encontrado !== false) {

            $claveInexistente = false;
            
            foreach($data as $clave => $valor) {
                // Si existe la clave, actualiza el valor en jsonData
                if (array_key_exists($clave, $this->jsonData[$encontrado]))
                    $this->jsonData[$encontrado][$clave] = $valor;
                // En caso contrario, registra el error y sale del bucle
                else {
                    $claveInexistente = true;
                    break;
                }
            }

            if (!$claveInexistente) {
                // Escribe los datos actualizados en el fichero
                $fp = fopen(PATH_JSON, 'w');
                $resultado = fwrite($fp, json_encode($this->jsonData, JSON_PRETTY_PRINT));
                fclose($fp);

                if ($resultado) {
                    http_response_code(204);
                    $res = ['status' => 'No Content', 'code' => '204', 'response' => 'Contenido actualizado'];
                    echo json_encode($res);
                } else {
                    http_response_code(500);
                    $res = ['status' => 'Internal Server Error', 'code' => '500', 'response' => 'No se puedo completar la operación'];
                    echo json_encode($res);
                }
            // Alguna clave no existia
            } else {
                http_response_code(400);
                $res = ['status' => 'Bad Request', 'code' => '400', 'response' => 'JSON mal formateado'];
                echo json_encode($res);
            }

        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Item no encontrado (' . $id . ')'];
            echo json_encode($res);
        }
    }
    
    function deleteItem($id) {

        // Busca el ID en los datos, recibiendo la clave o un false
        $encontrado = array_search(strval($id), array_column($this->jsonData, 'id'));

        // Si encuentra el Item, lo elimina
        if ($encontrado) {
            // Anexa el nuevo elemento al array de Items
            unset($this->jsonData[$encontrado]);

            // Escribe los datos en el fichero
            $fp = fopen(PATH_JSON, 'w');
            $resultado = fwrite($fp, json_encode($this->jsonData, JSON_PRETTY_PRINT));
            fclose($fp);

            // Envia la respuesta
            if ($resultado) {
                http_response_code(204);
                $res = ['status' => 'No Content', 'code' => '204', 'response' => 'Contenido eliminado'];
                echo json_encode($res);
            } else {
                http_response_code(500);
                $res = ['status' => 'Internal Server Error', 'code' => '500', 'response' => 'No se puedo completar la operación'];
                echo json_encode($res);
            }
        // Si no encuentra el Item a eliminar, responde con un 404
        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Item no encontrado (' . $id . ')'];
            echo json_encode($res);
        }
    }
}