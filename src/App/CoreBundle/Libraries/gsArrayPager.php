<?php

namespace App\CoreBundle\Libraries;

use App\CoreBundle\Libraries\Paginator\sfPager;

class gsArrayPager extends sfPager {

    protected $resultsArray = null;
    protected $allResult = 0;

    private function getCountValue($count) {
        if ($count)
            foreach ($count as $key => $value) {            
                $this->allResult = $value;
            }
    }

    /**
     * gsArrayPager constructor.
     * @param null $class
     * @param int $maxPerPage
     * @param null $count
     */
    public function __construct($class = null, $maxPerPage = 10, $count = null) {
        parent::__construct($class, $maxPerPage);
        $this->getCountValue($count);
    }

    public function init() {

        if (!$this->allResult)
            $this->setNbResults(count($this->resultsArray));
        else
            $this->setNbResults($this->allResult);

        if (($this->getPage() == 0 || $this->getMaxPerPage() == 0))
            $this->setLastPage(0);
        else
            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
    }

    /**
     * @param $array
     */
    public function setResultArray($array) {
        $this->resultsArray = $array;
    }

    /**
     * @return null
     */
    public function getResultArray() {
        return $this->resultsArray;
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function retrieveObject($offset) {
        return $this->resultsArray[$offset];
    }

    /**
     * @return array
     */
    public function getResults() {
        return array_slice($this->resultsArray, ($this->getPage() - 1) * $this->getMaxPerPage(), $this->maxPerPage);
    }

}