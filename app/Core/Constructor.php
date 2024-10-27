<?php 
namespace Core;
use database\Database;

class Constructor extends Database
{
	public function __construct(){
        parent::__construct(); 
    }
    public function prepareStatement($query)
    {
        return $this->getConnection()->prepare($query);
    }
}
