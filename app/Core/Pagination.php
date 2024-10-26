<?php 
namespace Core;
//use Core\Searchkeyword;
class Pagination extends Routerpagination
{
	
	public function selectTable_limit($table, $start, $finish, $order=null)
	{
		try{
			$sql="SELECT * FROM $table $order LIMIT $start, $finish";
			$stmt=$this->getConnection()->prepare($sql);
			return $stmt;	
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	public function selectTable_limitWhere($table,$start, $finish, $where, $order=null)
	{
		try{
			$sql="SELECT * FROM $table WHERE $where=:$where $order LIMIT $start, $finish";
			$stmt=$this->getConnection()->prepare($sql);
			$stmt->bindParam(':'.$where,$getData);				
			return $stmt;
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	public function pagination($table, $number, $name=null, $where=null, $order=null)
	{
		
		$finish=$number;
		$page = isset($_GET[$name]) ? (int)filter_var($_GET[$name], FILTER_SANITIZE_NUMBER_INT) : 1;
		$start=($page>1) ? ($page * $finish) - $finish:0;
		$stmt=(!empty($where))?$this->selectTable_limitWhere($table,$start, $finish, $where, $order):$this->selectTable_limit($table, $start,$finish,$order);		
		$stmt->execute();
		return $stmt;
	}
	
	public function pg_view($table, $number, $name=null, $where=null)
	{
		$nopage = isset($_GET[$name]) ? (int)filter_var($_GET[$name], FILTER_SANITIZE_NUMBER_INT) : 1;
		$stmt=$this->selectTable($table, $where);
		$stmt->execute();
		$total=$stmt->rowCount();
		$jumpage=ceil($total/$number);
		$next_prev = self::pg_view_np($nopage, $jumpage);
				
		$pagi ="";

		$url = htmlspecialchars($_SERVER['REQUEST_URI']);
		$parsed_url = parse_url($url);
		parse_str($parsed_url['query'] ?? '', $query_params);

		

		for($page=1; $page<=$jumpage; $page++)
		{
	
			$query_params['halaman'] = $page;
			$new_query_string = http_build_query($query_params);
			$new_url = $parsed_url['path'] . '?' . $new_query_string;

			if(($page>=$nopage-2)&&($page<=$nopage+2)||($page==1)||($page==$jumpage))
			{
				$tampilpage = $page;				
				$class=($page==$nopage)?'active':'page-item';
				$pagi .= '<li class="'.$class.'"><a class="page-link" href="'.$new_url.'">'.$page.'</a></li>';
			}
		}

		
		$all_page = [

			1=>$pagi,
			2=>$next_prev[1],
			3=>$next_prev[2]
		];

		return $all_page;
	}
	
	public function pg_view_np($nopage, $jumpage)
	{
		
		// Mendapatkan skema (http atau https)
		$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
		// Mendapatkan host
		$host = $_SERVER['HTTP_HOST'];
		// Mendapatkan path
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		// Menggabungkan semua bagian
		$base_url = $scheme . '://' . $host . $path;

		$prev = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
		$prev .= ($nopage>1)?'<li class="page-item"><a class="page-link" href="'.$base_url.'?halaman='.($nopage-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>':null;

		$next =($nopage>=$jumpage)?null:'<li class="page-item"><a class="page-link" href="'.$base_url.'?halaman='.($nopage+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		$next .='</ul></nav>';

		$stmt = 
		[
			1=>$prev,
			2=>$next
		];
		return $stmt;
		
	} 

}
