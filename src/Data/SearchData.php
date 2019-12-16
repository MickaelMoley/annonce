<?php


namespace App\Data;


use phpDocumentor\Reflection\Types\Integer;

class SearchData
{

    /**
     * @var Integer
     */
    public $current_page = 1;


    /*
     * @var int
     */
    public $limitPerPage = 5;

    /*
     * @var string
     */
    public $q = '';

    /**
     * @var null|integer
     */
    public $minPrice;

    /**
     * @var null|integer
     */
    public $maxPrice;
    /**
     * @var null|integer
     */
    public $minYear;

    /**
     * @var null|integer
     */
    public $maxYear;

    /**
     * @var null|integer
     */
    public $minKilometer;

    /**
     * @var null|integer
     */
    public $maxKilometer;

}