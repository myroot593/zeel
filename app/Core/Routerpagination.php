<?php 
namespace Core;
//use Core\Searchkeyword;
class Routerpagination extends Searchkeyword
{
	
	
	public function pagination_router($table, $number, $router=null, $where=null, $order=null)
	{
		
		$finish=$number;
		$page=(isset($router))? (int)$router:1;
		$start=($page>1) ? ($page * $finish) - $finish:0;
		$stmt=(!empty($where))?$this->selectTable_limitWhere($table,$start, $finish, $where, $order):$this->selectTable_limit($table, $start,$finish,$order);		
		$stmt->execute();
		return $stmt;
	}
	
	public function pg_view_router($table, $number, $router, $path_url, $name='', $where=null)
	{
	    // Mengambil nilai router dan mengatur default
	   $ro = isset($router) && is_numeric($router) ? (int)$router : 1;

	    // Mendapatkan skema dan host
	    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
	    $host = $_SERVER['HTTP_HOST'];

	    // Menyusun base URL
	    $base_url = $scheme . '://' . $host . $path_url . $name; 

	    // Ambil data dari tabel
	    $nopage = $ro; 
	    $stmt = $this->selectTable($table, $where);
	    $stmt->execute();
	    $total = $stmt->rowCount();
	    $jumpage = ceil($total / $number);
	    $next_prev = self::pg_view_np_router($nopage, $jumpage, $base_url);

	    $pagi = "";
	    
	    for ($page = 1; $page <= $jumpage; $page++) {
	        if (($page >= $nopage - 2 && $page <= $nopage + 2) || $page == 1 || $page == $jumpage) {
	            // Menetapkan class active pada halaman yang sesuai
	            $class = ($page == $nopage) ? 'active' : 'page-item';
	            $pagi .= '<li class="' . $class . '"><a class="page-link" href="' . $base_url . $page . '">' . $page . '</a></li>';
	        }
	    }

	    $all_page = [

			1=>$pagi,
			2=>$next_prev[1],
			3=>$next_prev[2]
		];

		return $all_page;
	}

	
	public function pg_view_np_router($nopage, $jumpage, $base_url)
	{		
		
		$prev = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
		$prev .= ($nopage>1)?'<li class="page-item"><a class="page-link" href="'.$base_url.($nopage-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>':null;
		$next =($nopage>=$jumpage)?null:'<li class="page-item"><a class="page-link" href="'.$base_url.($nopage+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		$next .='</ul></nav>';
		$stmt = 
		[
			1=>$prev,
			2=>$next
		];
		return $stmt;	
	}



}
