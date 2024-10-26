<?php 
namespace Core;

class Searchkeyword extends Constructor
{
	protected function searchKeyword($table, $column, $keyword, $start = '', $finish = '')
	{
	    try
	    {
	       
	        $start = intval($start);
	        $finish = intval($finish);	     
	        $sql = "SELECT * FROM $table WHERE $column LIKE :keyword";
	        if (!empty($finish)) {
	            $sql .= " LIMIT :start, :finish";
	        }
	        $stmt = $this->getConnection()->prepare($sql);
	        $keywordParam = "%$keyword%";
	        $stmt->bindParam(':keyword', $keywordParam, \PDO::PARAM_STR);
	        if (!empty($finish)) {
	            $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
	            $stmt->bindParam(':finish', $finish, \PDO::PARAM_INT);
	        }
	        $stmt->execute();
	        return $stmt;
	    }
	    catch(\PDOException $e)
	    {
	        
	        error_log($e->getMessage());
	        throw new \Exception("Terjadi kesalahan dalam query database.");
	    }
	}

	public function limitSearch($table, $column, $keyword, $number, $name)
	{
		$finish = $number;
		$page = isset($_GET[$name])?(int)($_GET[$name]):1;
		$start = ($page>1)?($page*$finish)-$finish:0;
		$stmt = 
		[
			1=>Searchkeyword::searchKeyword($table, $column, $keyword),
			2=>Searchkeyword::searchKeyword($table, $column, $keyword, $start, $finish)
		];
		return $stmt;
	}
	public function limitSearch__paging($number, $keyword='', $total='', $path_url='', $name='halaman')
	{
		$nopage = isset($_GET[$name])?(int)$_GET[$name]:1;
		$total_page = ceil($total / $number);
		$next_prev = self::pg_view_search($nopage, $total_page,$path_url, $keyword);
		$pagi='';


		
		for($page=1; $page<=$total_page; $page++)
		{
			if(($page>=$nopage-2)&&($page<=$nopage+2)||($page==1)||($page==$total_page))
			{
				$tampilpage = $page;				
				$class=($page==$nopage)?'active':'page-item';
				$pagi.='<li class="'.$class.'"><a class="page-link" href="'.$path_url.'?keyword='.$keyword.'&'.$name.'='.$page.'">'.$page.'</a></li>';
			}
		}

		$all_page = [

			1=>$pagi,
			2=>$next_prev[1],
			3=>$next_prev[2]
		];

		return $all_page;
		
	}
	
	public function pg_view_search($nopage, $totalpage,$path_url='',$keyword='keyword')
	{
		$prev = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
		$prev .= ($nopage>1)?'<li class="page-item"><a class="page-link" href="'.$path_url.'?keyword='.$keyword.'&halaman='.($nopage-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>':null;

		$next =($nopage>=$totalpage)?null:'<li class="page-item"><a class="page-link" href="'.$path_url.'?keyword='.$keyword.'&halaman='.($nopage+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		$next .='</ul></nav>';

		$stmt = 
		[
			1=>$prev,
			2=>$next
		];
		return $stmt;
		
	} 
}
