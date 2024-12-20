<?php

// Modelo de la entidad Item

class ItemModel implements JsonSerializable {

    private int $id;
    private string $title;
    private string $artist;
    private string $format;
    private int $year;
    private int $origYear;
    private string $label;
    private int $rating;
    private string $comment;
    private float $buyPrice;
    private string $condition;
    private float $sellPrice;
    private array $externalIds;
    
    /**
     * Constructor de la clase ItemModel.
     *
     * @param int $id El ID del ítem.
     * @param string $title El título del ítem.
     * @param string $artist El artista del ítem.
     * @param string $format El formato del ítem (ej. "LP", "CD").
     * @param int $year El año de publicación del ítem.
     * @param int $origYear El año de publicación original del ítem.
     * @param string $label El sello discográfico.
     * @param int $rating La calificación del ítem (de 1 a 10).
     * @param string $comment Comentarios sobre el ítem.
     * @param float $buyPrice El precio de compra del ítem.
     * @param string $condition La condición del ítem (ej. "M", "NM").
     * @param float $sellPrice El precio de venta del ítem, por defecto 0.
     * @param array $externalIds Identificadores externos asociados al ítem, por defecto un array vacío.
     */
    public function __construct(int $id, string $title, string $artist, 
        string $format, int $year, int $origYear, string $label, int $rating,
        string $comment, float $buyPrice, string $condition, float $sellPrice = 0,
        array $externalIds = []) {

        $this->id = $id;
        $this->title = $title;
        $this->artist = $artist;
        $this->format = $format;
        $this->year = $year;
        $this->origYear = $origYear;
        $this->label = $label;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->buyPrice = $buyPrice;
        $this->condition = $condition;
        $this->sellPrice = $sellPrice;
        $this->externalIds = $externalIds;
    }

    // Getters y setters

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getArtist() {
        return $this->artist;
    }

    public function setArtist(string $artist) {
        $this->artist = $artist;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setFormat(string $format) {
        $this->format = $format;
    }

    public function getYear() {
        return $this->year;
    }

    public function setYear(int $year) {
        $this->year = $year;
    }

    public function getOrigYear() {
        return $this->origYear;
    }

    public function setOrigYear(int $origYear) {
        $this->origYear = $origYear;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel(string $label) {
        $this->label = $label;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating(int $rating) {
        $this->rating = $rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment(string $comment) {
        $this->comment = $comment;
    }

    public function getBuyPrice() {
        return $this->buyPrice;
    }

    public function setBuyPrice(float $buyPrice) {
        $this->buyPrice = $buyPrice;
    }

    public function getCondition() {
        return $this->condition;
    }

    public function setCondition(string $condition) {
        $this->condition = $condition;
    }

    public function getSellPrice() {
        return $this->sellPrice;
    }

    public function setSellPrice(float $sellPrice) {
        $this->sellPrice = $sellPrice;
    }

    public function getExternalIds() {
        return $this->externalIds;
    }

    public function setExternalIds(array $externalIds) {
        $this->externalIds = $externalIds;
    }

    public function jsonSerialize(): mixed {

        return ['id' => $this->id, 'title' => $this->title, 
                'artist' => $this->artist, 'format' => $this->format, 
                'year' => $this->year, 'origYear' => $this->origYear, 
                'label' => $this->label, 'rating' => $this->rating,
                'comment' => $this->comment, 'buyPrice' => $this->buyPrice, 
                'condition' => $this->condition, 'sellPrice' => $this->sellPrice, 
                'externalIds' => $this->externalIds];
    }
}