<?php

// Clase que maneja las operaciones de lectura y escritura del fichero JSON
class JSONFileutil {
    private $jsonFile;
    private $jsonString;
    private $jsonDataArray;
    private bool $ficheroValido;

    public function __construct($ruta) {
        $this->jsonFile = $ruta;
        $this->jsonString = @file_get_contents($this->jsonFile);
        $this->jsonDataArray = json_decode($this->jsonString, true);

        // Si hay algún problema con el fichero, lanza excepciones que llegarán en una respuesta por la API
        if ($this->jsonString === false) throw new Exception('ERROR: El fichero no existe');
        if ($this->jsonDataArray === null) throw new Exception('ERROR: Fichero JSON corrupto');

    }

    public function getJsonFile() {
        return $this->jsonFile;
    }

    public function getJsonString() {
        return $this->jsonString;
    }

    public function getJsonDataArray() {
        return $this->jsonDataArray;
    }

    public function esValido() {
        return $this->ficheroValido;
    }

    // Intenta guardar los datos actualizados en el fichero
    public function guardarDatos($jsonData) {
        
        try {
            $fp = fopen($this->jsonFile, 'w');
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