<?php

require ('../utils/JSONFileUtil.php');

class Item {

    private $ficheroJson;
    private $jsonData;

    function __construct() {
        $this->ficheroJson =  new JSONFileUtil(PATH_JSON);
        $this->jsonData = $this->ficheroJson->getJsonDataArray();
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
        $res = new Response(200, $arrayItems);
        $res->enviar();
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
                $res = new Response(200, $item);
                $res->enviar();

                return true;
            }
        }

        // Si llega aquí es que no ha encontrado el item, devuelve un 404
        $res = new Response(404, 'Item no encontrado');
        $res->enviar();
    }
    
    // Devuelve todos los ítems cuyo artista sea el recibido, o un 404 si no se encuentra ninguno
    function getItemsByArtist($artist) {

        // Sustituye guiones por espacios en caso de que los haya
        $artist = str_replace('-', ' ', $artist);

        // Declara el array donde se recogeran los modelos de Items
        $arrayItems = array();

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $item) {
            // Acumula en el array los Items de ese artista
            if (strtolower($item['artist']) === strtolower($artist)) {
                $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
            }
        }

        // Si ha encontrado alguno, responde con los items serializados (jsonSerialize)
        if (count($arrayItems) > 0) {
            $res = new Response(200, $arrayItems);
            $res->enviar();

        // En caso contrario un 404
        } else {
            $res = new Response(404, 'Artista no encontrado');
            $res->enviar();
        }
    }
    
    // Devuelve todos los items que tengan el formato recibido, o un 404 si no existe ese formato
    function getItemsByFormat($format) {

        // Declara el array donde se recogeran los modelos de Items
        $arrayItems = array();

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $item) {

            // Acumula en el array los Items de ese artista
            if (strtolower($item['format']) === strtolower($format)) {
                $arrayItems[] = new ItemModel($item['id'], $item['title'], 
                $item['artist'], $item['format'], $item['year'], 
                $item['origYear'], $item['label'], $item['rating'],
                $item['comment'], $item['buyPrice'], $item['condition'], 
                $item['sellPrice'], $item['externalIds']);
            }
        }

        // Si ha encontrado alguno, responde con los items serializados (jsonSerialize)
        if (count($arrayItems) > 0) {
            $res = new Response(200, $arrayItems);
            $res->enviar();

        // En caso contrario un 404
        } else {
            $res = new Response(404, 'Formato no encontrado');
            $res->enviar();
        }
    }
    
    // Ordena los items en base a una clave y un orden recibidos
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

            // Responde con todos los items serializados
            $res = new Response(200, $arrayItems);
            $res->enviar();

        // Devuelve un 400, la clave no existe
        } else {
            $res = new Response(400, 'La clave para ordenar no existe.');
            $res->enviar();
        }
    }
    

    // Consultas POST (Create, Put, Delete)

    // Crea un item nuevo a partir de los datos recibidos
    function createItem($data) {

        try {
            // Si se recibe más de una entrada llega como array multidimensional y no se acepta
            if (!array_key_exists(0, $data)) {

                // Genera un nuevo ID a partir del mas alto encontrado en el registro
                $nuevoId = 0;
                foreach ($this->jsonData as $item) $nuevoId = max(intval($item['id']), $nuevoId);
                $nuevoId++;
    
                try {
                    // Intenta instanciar el nuevo Item a partir de los datos y el nuevo ID
                    $nuevoItem = new ItemModel($nuevoId, $data['title'], 
                        $data['artist'], $data['format'], $data['year'], 
                        $data['origYear'], $data['label'], $data['rating'],
                        $data['comment'], $data['buyPrice'], $data['condition'], 
                        $data['sellPrice'], $data['externalIds']);
    
                    // Añade el Item creado al array jsonData
                    array_push($this->jsonData, $nuevoItem);

                    // Guarda los datos en el fichero
                    if ($this->ficheroJson->guardarDatos($this->jsonData)) {
                        $res = new Response(201, $nuevoItem);
                        $res->enviar();
                    } else {
                        $res = new Response(500, 'No se pudo guardar el ítem.');
                        $res->enviar();  
                    }

                // El ítem recibido no tienen buen formato internamente
                } catch (Exception $e) {
                    $res = new Response(400, 'Error de formato en los datos recibidos.');
                    $res->enviar();
                }

            // Ha llegado más de una entrada (o ninguna)
            } else {
                $res = new Response(400, 'Solo se puede crear un ítem. (Recibidos: ' . count($data) . ')');
                $res->enviar();
            }

        // Los datos recibidos no tienen formato válido
        } catch (Error $e) {
            $res = new Response(400, 'Formato no válido');
            $res->enviar();
        }
    }
    
    // Actualiza un ítem a partir de su ID y los datos recibidos
    function updateItem($id, $data) {

        // Busca el item e intenta actualizar los datos
        foreach ($this->jsonData as $key => $item) {

            if (intval($item['id']) === $id) {

                // Si lo encuentra, instancia un Item con sus datos
                $itemActualizar = new ItemModel($item['id'], $item['title'], 
                        $item['artist'], $item['format'], $item['year'], 
                        $item['origYear'], $item['label'], $item['rating'],
                        $item['comment'], $item['buyPrice'], $item['condition'], 
                        $item['sellPrice'], $item['externalIds']);
                
                // Actualiza el objeto Item
                foreach($data as $clave => $valor) {

                    // Si existe el atributo, lo actualiza
                    if (array_key_exists($clave, $item)) {

                        $setter = 'set' . ucwords($clave);

                        // Llama al método setter correspondiente
                        call_user_func([$itemActualizar, $setter], $valor); 
                    
                    // En caso contrario, responde con el fallo y sale del bucle
                    } else {
                        $res = new Response(400, 'JSON recibido con mal formato');
                        $res->enviar();

                        return false;
                    }
                }

                // Sustituye el item actualizado en jsonData
                $this->jsonData[$key] = $itemActualizar;

                // Escribe los datos actualizados en el fichero
                if ($this->ficheroJson->guardarDatos($this->jsonData)) {
                    $res = new Response(204, $itemActualizar);
                    $res->enviar();
                    return true;

                } else {
                    $res = new Response(500, 'No se pudo guardar la actualización.');
                    $res->enviar();
                    return false;
                }
            }
        }

        // Si llega aquí es que no ha encontrado el ítem. Devuelve un 404
        $res = new Response(404, 'Ítem no encontrado (' . $id . ')');
        $res->enviar();
    }
    
    // Elimina el ítem a partir de su ID
    function deleteItem($id) {

        // Recorre el JSON en busca del item
        foreach ($this->jsonData as $key => $item) {

            // Si encuentra el Item, lo elimina y guarda los cambios en el fichero
            if ($item['id'] === $id) {
                
                // Retira el item de jsonData
                unset($this->jsonData[$key]);

                // Escribe los datos actualizados en el fichero
                if ($this->ficheroJson->guardarDatos($this->jsonData)) {
                    // En caso de éxito devuelve un 204 (cabecera 200) y el ítem eliminado
                    $res = new Response(204, $item);
                    $res->enviar();

                    return true;
                } else {
                    $res = new Response(500, 'No se pudo guardar la actualización.');
                    $res->enviar();

                    return false;
                }
            }
        }

        // Si llega aquí es que no ha encontrado el item, devuelve un 404
        $res = new Response(404, 'Item no encontrado (' . $id . ')');
        $res->enviar();
    }
}