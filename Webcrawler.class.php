<?php
 
class Webcrawler
{
	protected $ch;
	protected $timeout;
	protected $userAgent ;
	protected $search_str ;
	protected $url;

	function __construct()
	{
		$this->ch=curl_init();
		$this->timeout = 10;
		$this->userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) chrome/34.0.1847.131 Safari/537.36';
		$this->url="";
	}

	protected function Search($search_str)
	{
		$this->$search_str=$search_str;
		
	}


	protected function Close()
	{
		curl_close($this->ch);		
	}

}

class WebcrawlerSearchYahoo extends Webcrawler
{
	function Search($search_str)
	{
		if(!$search_str) return false;
		parent::Search($search_str);
		
		
		$ch = $this->ch;
		$url= $this->url.$search_str;
		curl_setopt($ch, CURLOPT_URL,$url);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
 		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');  
 		curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		$html = curl_exec($ch);
		$html = iconv("Big5", "UTF-8", $html);
		return $html;
	}

	function __destruct()
	{
		parent::Close();
	}	
}

class WebcrawlerProcessYahoo extends Webcrawler
{

	function Process($Dat)
	{		
		foreach ($Dat as $key => $value) {
			$$key=$value;
		}

		if(!$OriginalHtml) return false;
		$ret['search_str']=$search_str;

		preg_match_all('/<td width=160 align=right><font color=#3333FF class=tt>([^<>]+)<\/font><\/td>/',$OriginalHtml,$target);
		$ret['Datatime'] = $target[1][0];

	 
		preg_match_all('/'.$search_str.'">([^<>]+)<\/a><br>/',$OriginalHtml,$target);
		$ret['Company']=$target[1][0];

		preg_match_all('/<th align=center width="55">([^<>]+)<\/th>/',$OriginalHtml,$target);
		foreach ($target[1] as $key => $value) {
			$ret['Title'][]=$value;
		}
		
		preg_match_all('/<td align="center" bgcolor="#FFFfff" nowrap><b>([^<>]+)<\/b><\/td>/',$OriginalHtml,$target);
		preg_match_all('/<b>([^<>]+)<\/b>/',$target[0][0],$target);				
		$tmp['Value'][1]=$target[0][0];

		preg_match_all('/<font color=(#.*)>([^<>]+)/',$OriginalHtml,$target);
		$tmp['Value'][3]=$target[0][1].'</font>';


		preg_match_all('/<td align="center" bgcolor="#FFFfff" nowrap>([^<>]+)<\/td>/',$OriginalHtml,$target);

		$num=0;

		foreach ($target[1] as $key => $value) {
			if($key == 1 or $key ==3){
				$ret['Value'][$num]=$tmp['Value'][$key];
				$num=$num+1;
				$ret['Value'][$num]=$value;

			}else{
				$ret['Value'][$num]=$value ;
				
			}
			$num++;
		}


		$this->SessionSave($ret);

		return $ret;
	}

	function SessionSave($ret){
		$_SESSION["List"][$ret["search_str"]]["Company"]=$ret['Company'];		
		
	}

	function __destruct()
	{
		
	}		

}

$Webcrawler = new Webcrawler();
$WebcrawlerSearch = new WebcrawlerSearchYahoo();
$WebcrawlerProcess = new WebcrawlerProcessYahoo();

?>