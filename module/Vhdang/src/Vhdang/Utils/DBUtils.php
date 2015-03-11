<?php

namespace Vhdang\Utils;

class DBUtils {
	
    /**
     * convert date format to Y-m-d format for updating database
     * @param string $date
     * @param string $fromFormat
     */
    public static function getDBDateFormat($date,$fromFormat = 'd-m-Y'){
        if ($date == null){
            return null;
        }
        
         $newDate = new \DateTime();
   	   $newDate = $newDate->createFromFormat($fromFormat, $date);
       	
       	if ($newDate){
       		return  $newDate->format('Y-m-d');
       	}else{
       		throw new \Exception('Invalid date format. DBUtils Error.');
       	}
        
    }
}