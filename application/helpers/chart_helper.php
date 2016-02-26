<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function index_stats($obj,$month=false,$gab=1) {
	$text = "";
	$text_gap = "&nbsp;";
	$stat = Array();
	if($month){
		for($i=30;$i>=0;$i--){
			$stat[date("Y m-d", strtotime(" -".$i."days"))]=0;
		}
		foreach($obj as $val){
			$stat[$val->h] = $val->cnt;
		}
		for($i=30;$i>=0;$i--){
			if($i % $gab != 0){
				$text .= "['".$text_gap."',".$stat[date("Y m-d", strtotime(" -".$i."days"))]."]";
				$text_gap = $text_gap."&nbsp;";
			} else {
				$text .= "['".date("m/d", strtotime(" -".$i."days"))."',".$stat[date("Y m-d", strtotime(" -".$i."days"))]."]";
			}
			if($i>0) $text .= ",";
		}	
	}
	else{
		foreach($obj as $val){
			$stat[intval($val->h)] = $val->cnt;
		}
		for($i=0;$i<24;$i++){
			if(!empty($stat[$i])){
				if($i % $gab != 0 && $i != 23){
					$text .= "['".$text_gap."',".$stat[$i]."]";
					$text_gap = $text_gap."&nbsp;";
				} else {
					$text .= "['".$i." 시',".$stat[$i]."]";
				}
			} else {
				if($i % $gab != 0 && $i != 23){
					$text .= "['".$text_gap."',0]";
					$text_gap = $text_gap."&nbsp;";					
				}
				else{
					$text .= "['".$i." 시',0]";
				}
			}
			if($i<23) $text .= ",";
		}
	}
	return $text;
}
