<?php

namespace app\models;

use JsonSerializable;

class ItemModel implements JsonSerializable {

    private $id;
    private $title;
    private $artist;
    private $format;
    private $year;
    private $origYear;
    private $label;
    private $rating;
    private $comment;
    private $buyPrice;
    private $condition;
    private $sellPrice;
    private $externalIds;

    public function __construct($id, $title, $artist, $format, $year, $origYear,
                                $label, $rating, $comment, $buyPrice, $condition,
                                $sellPrice, $externalIds) {

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


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set the value of artist
     */
    public function setArtist($artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get the value of format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the value of format
     */
    public function setFormat($format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the value of year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set the value of year
     */
    public function setYear($year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get the value of origYear
     */
    public function getOrigYear()
    {
        return $this->origYear;
    }

    /**
     * Set the value of origYear
     */
    public function setOrigYear($origYear): self
    {
        $this->origYear = $origYear;

        return $this;
    }

    /**
     * Get the value of label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     */
    public function setLabel($label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of rating
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     */
    public function setRating($rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get the value of comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     */
    public function setComment($comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of buyPrice
     */
    public function getBuyPrice()
    {
        return $this->buyPrice;
    }

    /**
     * Set the value of buyPrice
     */
    public function setBuyPrice($buyPrice): self
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    /**
     * Get the value of condition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set the value of condition
     */
    public function setCondition($condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get the value of sellPrice
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set the value of sellPrice
     */
    public function setSellPrice($sellPrice): self
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get the value of externalIds
     */
    public function getExternalIds()
    {
        return $this->externalIds;
    }

    /**
     * Set the value of externalIds
     */
    public function setExternalIds($externalIds): self
    {
        $this->externalIds = $externalIds;

        return $this;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'format' => $this->format,
            'year' => $this->year,
            'origYear' => $this->origYear,
            'label' => $this->label,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'buyPrice' => $this->buyPrice,
            'condition' => $this->condition,
            'sellPrice' => $this->sellPrice,
            'externalIds' => $this->externalIds
        ];
    }
}