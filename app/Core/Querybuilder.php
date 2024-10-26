<?php 
namespace Core;
//use Core\Constructor;

class Querybuilder extends Pagination
{
    
    protected $where;
    protected $order;

    public function where($condition) {
        $this->where = $condition;
        return $this;
    }

    public function orderBy($column) {
        $this->order = "ORDER BY $column";
        return $this;
    }

    public function get($table) {
        try
        {
            $sql = "SELECT * FROM $table";
            if ($this->where) {
                $sql .= " WHERE {$this->where}";
            }
            if ($this->order) {
                $sql .= " {$this->order}";
            }
            return self::prepareStatement($sql);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}
