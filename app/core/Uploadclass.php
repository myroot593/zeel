<?php
/**
Class upload untuk kebutuhan upload



**/
class Uploadclass
{
	public function createImage($data=null, $path=null)
	{
		$this->file_name=$_FILES[$data]['name'];
		$this->file_tmp=$_FILES[$data]['tmp_name'];
		$this->file_size=$_FILES[$data]['size'];
		$this->file_dir=$path;
		$this->file_extension=strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
		$this->file_item=rand(100,1000000).".".$this->file_extension;		
		$this->file_valid=array('jpg','jpeg','png');
		$this->file_base64=base64_encode(file_get_contents($this->file_tmp));
		$this->file_base64DB="data::image/".$this->file_extension.";base64,".$this->file_base64;
	}
	
}

?>