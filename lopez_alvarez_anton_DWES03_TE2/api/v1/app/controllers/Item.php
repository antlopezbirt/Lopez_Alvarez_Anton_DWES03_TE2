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
        foreach ($this->jsonData as $item) {
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

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $item) {

            // Si encuentra el Item, instancia un modelo con sus datos y lo devuelve
            if ($item['id'] === $id) {
                $item = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
            
                // Responde con el Item serializado
                $res = ['status' => 'OK', 'code' => '200', 'response' => $item];
                echo json_encode($res);

                return true;
            }
        }

        // Si llega aquí es que no ha encontrado el item, devuelve un 404
        http_response_code(404);
        $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Item no encontrado'];
        echo json_encode($res);
    }
    
    // Devuelve todos los Items cuyo artista sea el recibido, o un 404 si no se encuentra ninguno
    function getItemsByArtist($artist) {

        // Sustituye guiones por espacios en caso de que los haya
        $artist = str_replace('-', ' ', $artist);

        // Declara el array donde se recogeran los modelos de Items
        $arrayItems = array();

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $item) {
            // Acumula en el array los Items de ese artista
            if ($item['artist'] === $artist) {
                $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
            }
        }

        // Si ha encontrado alguno, responde con los items serializados (jsonSerialize)
        if (count($arrayItems) > 0) {
            $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
            echo json_encode($res);

        // En caso contrario un 404
        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Artista no encontrado'];
            echo json_encode($res);
        }
    }
    
    function getItemsByFormat($format) {

        // Declara el array donde se recogeran los modelos de Items
        $arrayItems = array();

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $item) {

            // Acumula en el array los Items de ese artista
            if ($item['format'] === $format) {
                $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
            }
        }

        // Si ha encontrado alguno, responde con los items serializados (jsonSerialize)
        if (count($arrayItems) > 0) {
            $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
            echo json_encode($res);

        // En caso contrario un 404
        } else {
            http_response_code(404);
            $res = ['status' => 'Not Found', 'code' => '404', 'response' => 'Formato no encontrado'];
            echo json_encode($res);
        }
    }
    
    function sortItemsByKey($key, $order) {

        // Primero comprueba si la clave recibida existe en el JSON
        if (array_key_exists($key, $this->jsonData[0])) {

            $order = $order == 'asc' ? SORT_ASC : SORT_DESC;

            // Ordena los items según los parámetros recibidos
            array_multisort(array_column($this->jsonData, $key), $order, $this->jsonData);

            // Declara el array donde se recogeran los modelos de Items
            $arrayItems = array();

            // Recorre el objeto JSON y acumula los datos en un array de modelos Item
            foreach ($this->jsonData as $item) {
                $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                    $item['artist'], $item['format'], $item['year'], 
                    $item['origYear'], $item['label'], $item['rating'],
                    $item['comment'], $item['buyPrice'], $item['condition'], 
                    $item['sellPrice'], $item['externalIds']);
            }

            // Responde con todos los items serializados (mediante jsonSerialize)
            http_response_code(200);
            $res = ['status' => 'OK', 'code' => '200', 'response' => $arrayItems];
            echo json_encode($res);

        } else {
            // Devuelve un 400, la clave para ordenar no existe
            http_response_code(400);
            $res = ['status' => 'OK', 'code' => '400', 'response' => 'La clave para ordenar no existe.'];
            echo json_encode($res);
        }
    }
    

    // Consultas POST (Create, Put, Delete)

    function createItem($data) {

        try {
            // Si se recibe más de una entrada llega como array multidimensional y no se acepta
            if (!array_key_exists(0, $data)) {

                // Genera un nuevo ID a partir del mas alto encontrado en el registro
                $nuevoId = 0;
                foreach ($this->jsonData as $item) $nuevoId = max(intval($item['id']), $nuevoId);
                $nuevoId++;
    
                try {
                    // Intenta generar el nuevo Item a partir de los datos y el nuevo ID
                    $nuevoItem = new ItemModel($nuevoId, $data['title'], 
                        $data['artist'], $data['format'], $data['year'], 
                        $data['origYear'], $data['label'], $data['rating'],
                        $data['comment'], $data['buyPrice'], $data['condition'], 
                        $data['sellPrice'], $data['externalIds']);
    
                    // Añade el Item creado al array jsonData
                    array_push($this->jsonData, $nuevoItem);
    
                    // Intenta guardar los datos actualizados en el fichero
                    try {
                        $fp = fopen(PATH_JSON, 'w');
                        $resultado = fwrite($fp, json_encode($this->jsonData, JSON_PRETTY_PRINT));
    
                        if ($resultado) {
                            http_response_code(201);
                            $res = ['status' => 'Created', 'code' => '201', 'response' => $nuevoItem];
                            echo json_encode($res);
                        }
    
                    } catch (Exception $e) {
    
                        http_response_code(500);
                        $res = ['status' => 'Internal Server Error', 'code' => '500', 'response' => 'No se pudo guardar la operación: ' . $e->getMessage()];
                        echo json_encode($res);
    
                    } finally {
                        if ($fp) fclose($fp);
                    }
    
                } catch (Exception $e) {
                    http_response_code(400);
                    $res = ['status' => 'Internal Server Error', 'code' => '400', 'Error de formato al crear el Item: ' . $e->getMessage()];
                    echo json_encode($res);
                }
    
    
            // Ha llegado más de una entrada (o ninguna)
            } else {
                http_response_code(400);
                $res = ['status' => 'Bad Request', 'code' => '400', 'response' => 'Solo se admite un Item. (Recibidos: ' . count($data) . ')'];
                echo json_encode($res);
            }

        // Los datos recibidos no tienen el formato correcto
        } catch (Error $e) {
            http_response_code(400);
            $res = ['status' => 'Bad Request', 'code' => '400', 'response' => 'Formato inválido'];
            echo json_encode($res);
        }
    }
    
    function updateItem($id, $data) {

        $encontrado = false;

        // Busca el item e intenta actualizar los datos
        foreach ($this->jsonData as $item) {
            if (intval($item['id']) === $id) {
                $encontrado = true;
                $claveInexistente = false;

                // Si lo encuentra, trata de actualizarlo
                foreach($data as $clave => $valor) {
                    // Si existe la clave, actualiza el valor en jsonData
                    if (array_key_exists($clave, $item))
                        $item[$clave] = $valor;
                    // En caso contrario, registra el error y sale del bucle
                    else {
                        $claveInexistente = true;
                        break;
                    }
                }
                
                break;
            }
        }

        // Si lo ha encontrad
        if ($encontrado !== false) {

            if (!$claveInexistente) {
                
                // Escribe los datos actualizados en el fichero
                try {
                    $fp = fopen(PATH_JSON, 'w');
                    $resultado = fwrite($fp, json_encode($this->jsonData, JSON_PRETTY_PRINT));

                    if ($resultado) {
                        http_response_code(204);
                        $res = ['status' => 'No Content', 'code' => '204', 'response' => 'Contenido actualizado'];
                        echo json_encode($res);
                    }

                } catch (Exception $e) {

                    http_response_code(500);
                    $res = ['status' => 'Internal Server Error', 'code' => '500', 'response' => 'No se pudo completar la operación: ' . $e->getMessage()];
                    echo json_encode($res);

                } finally {
                    if ($fp) fclose($fp);
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