<?php

namespace AppBundle\Component;

//some utils for daily use

class Utility
{
  public static function crc32($val){
    $checksum = crc32($val);
    if($checksum < 0) $checksum += 4294967296;
    return $checksum;
  }  
  
}
