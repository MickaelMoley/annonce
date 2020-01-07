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
    public $limitPerPage = 10;

    /*
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $make;

    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    public $bodyStyle;

    /**
     * @var string
     */
    public $fuelType;

    /**
     * @var string
     */
    public $transmission;

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
    public $minMileage;

    /**
     * @var null|integer
     */
    public $maxMileage;

    /**
     * @var string
     */
    public $dealer_id;


    /**
     * @var string
     */
    public $sort;

}