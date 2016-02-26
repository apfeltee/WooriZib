<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function euckr($txt) {
	return iconv("UTF-8", "EUC-KR", $txt);
} 

function utf8($txt) {
	return iconv("EUC-KR","UTF-8", $txt);
} 