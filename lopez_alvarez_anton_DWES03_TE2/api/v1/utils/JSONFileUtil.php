<?php

// Clase que maneja las operaciones de lectura y escritura del fichero JSON
class JSONFileUtil {
    private string $jsonFilePath;
    private $jsonString;
    private $jsonDataArray;

    public function __construct($ruta) {
        $this->jsonFilePath = $ruta;
        $this->jsonString = @file_get_contents($this->jsonFilePath);
        $this->jsonDataArray = json_decode($this->jsonString, true);

        // Si hay algún problema con el fichero, lanza excepciones que llegarán en una respuesta por la API
        if ($this->jsonString === false) throw new Exception('ERROR: El fichero no existe');
        if ($this->jsonDataArray === null) throw new Exception('ERROR: Fichero JSON corrupto');

    }

    public function getJsonDataArray() {
        return $this->jsonDataArray;
    }

    // Intenta guardar los datos actualizados en el fichero
    public function guardarDatos($jsonData) {
        
        try {
            $fp = fopen($this->jsonFilePath, 'w');
            $resultado = fwrite($fp, json_encode($jsonData, JSON_PRETTY_PRINT));

            if ($resultado) return true;

        // No se ha podido escribir en el fichero
        } catch (Exception $e) {
            return false;

        // En cualquier caso, cierra el puntero.
        } finally {
            if ($fp) fclose($fp);
        }
    }
}