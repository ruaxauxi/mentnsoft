<?php

namespace Vhdang\Utils;

use Zend\Escaper\Escaper;

class String {
    
    public static function formatNumber($str){
        $str = str_replace(',', '', $str);
        return $str;
    }
	 
    public static function removeBBCode($str){
		//$reg = '/(\[url\](.)*\[\/url\])|(\[img\](.)*\[\/img\])/i';
		
		$str = preg_replace ('/(\[url\])|(\[\/url\])|(\[img\])|(\[\/img\])/i',"",$str);
		$str = preg_replace ('/(\[QUOTE=(.){1,50}\])/i',"",$str);
		$str = preg_replace ('/(\[\/QUOTE])/i',"",$str);		
		
		return $str;
		/* preg_match_all($reg, $str,$matches);
	
		$matches = $matches[0];
		foreach ($matches as $link){
			$newlink = preg_replace('/\[url\]|\[\/url\]|\[img\]|\[\/img\]/i',"",$link);
			$href = "<a href='". $newlink ."'>Link</a>";
			$str = str_replace($link, $href, $str);
		}
	
		return $str;
	 */
	
	}
	
    public static function getDomainName($str){
        $regex = '(((http?|https?)\:\/\/|www)([a-z0-9\-\.]*))'; // SCHEME
        preg_match($regex, $str,$matches);
       
        if (! $matches && !isset($matches[0])){
            return null;
        }          
        $url = $matches[0];
        $url = explode(".", $url);
        if (strtolower($url[0]) == 'www' && isset($url[1])){
        	return $url[1];
        }else{
        	return $url[1];
        }
    }
    
    
    public static function makelink($text) {
        $text = str_replace(' http://webwarper.net/ww/~av/', 'http://',$text);
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~:]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">Link</a>', $text);
       
    }
    
    
   /*  public static function makelink($input) {
    	$parse = explode(' ', $input);
    	$links = array();
    	foreach ($parse as $token) {
    		if (parse_url($token, PHP_URL_SCHEME)) {
    			$link = array(
    					'text'	=> $token,
    					'url'	=> '<a href="' . $token . '" target="_blank">Link</a>'
    			);
    			$links[] = $link;
    		}
    	}
    
    
    	foreach($links as $link){
    		$input = str_replace($link['text'],$link['url'],$input);
    	}
    
    	return $input;
    } */
    
  /*   public static function makeLonglink($input) {
    	$parse = explode(' ', $input);
    	$links = array();
    	foreach ($parse as $token) {
    		if (parse_url($token, PHP_URL_SCHEME)) {
    			$link = array(
    					'text'	=> $token,
    					'url'	=> '<a href="' . $token . '" target="_blank">' . $token . '</a>'
    			);
    			$links[] = $link;
    		}
    	}
    
    
    	foreach($links as $link){
    		$input = str_replace($link['text'],$link['url'],$input);
    	}
    
    	return $input;
    } */
    
    public static function escapeString($str){
        $escaper = new Escaper('utf-8');
        $str = $escaper->escapeHtml($str);
        $str = $escaper->escapeHtmlAttr($str);
        //$str = $escaper->escapeJs($str);
       // $str = $escaper->escapeCss($str);
        return $str;
    }
    
    public static function stripUnicode($str){
    	if(!$str) return false;
    	$unicode = array(
    			'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
    			'd'=>'đ',
    			'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
    			'i'=>'í|ì|ỉ|ĩ|ị',
    			'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
    			'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
    			'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
    	);
    	foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
    	return $str;
    }
    
    public static function seoURL($str){
        $str = String::stripUnicode($str);
        $str = str_replace(' ', '-', $str);
        $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
        $str = strtolower($str);
        return $str . ' ';        
    }
    
}