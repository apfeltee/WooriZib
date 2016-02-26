<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 가격 표시 부분 다국어 부분 사용한다.
 */
function multi_view($data, $type, $num=3){
	
	$text = "";

	$lines = explode("---dungzi---",$data);
	foreach($lines as $key=>$line){
		if($line!="" && $key<$num){
			$col = explode("--type--",$line);

			$label = "";
			if($type=="email"){
				if($col[0]=="work") $label = "<span class=\"label label-sm label-danger\">"."업무용". "</span>";
				else if($col[0]=="personal") $label = "<span class=\"label label-sm label-success\">"."개인용". "</span>";
			} else if($type=="phone"){
				if($col[0]=="mobile") $label = "<span class=\"label label-sm label-danger\">"."휴대". "</span>";
				else if($col[0]=="home") $label = "<span class=\"label label-sm label-success\">"."자택". "</span>";
				else if($col[0]=="office") $label = "<span class=\"label label-sm label-danger\">"."회사". "</span>";
				else if($col[0]=="fax") $label = "<span class=\"label label-sm label-default\">"."팩스". "</span>";
				else if($col[0]=="etc") $label = "<span class=\"label label-sm label-default\">"."기타". "</span>";
			} else if($type=="address"){
				if($col[0]=="work") $label = "<span class=\"label label-sm label-danger\">"."직장". "</span>";
				else if($col[0]=="home") $label = "<span class=\"label label-sm label-success\">"."자택". "</span>";

			} else if($type=="homepage"){
				if($col[0]=="work") $label = "<span class=\"label label-sm label-danger\">"."회사". "</span>";
				else if($col[0]=="personal") $label = "<span class=\"label label-sm label-success\">"."개인". "</span>";
				else if($col[0]=="blog") $label = "<span class=\"label label-sm label-default\">"."블로그". "</span>";						
			} else if($type="gongsil"){
				$label = "<span class=\"label label-sm label-danger\" style=\"font-size:10px;\"><strong>".$col[0]. "</strong></span>";
			}

			if($label=="") $label = $col[0];
			if($col[1]!=""){
				if($type=="email"){
					$text .= $label ." ". $col[1] . "<br/>";
				} else if($type=="phone"){
					$text .= $label ." <a href=\"tel:". $col[1] . "\">" . $col[1] . "</a><br/>";
				} else if($type=="address"){
					$text .= $label ." ". $col[1] . "<br/>";
				} else if($type=="homepage"){			
					$text .= $label ." <a href=\"http://". preg_replace('#^https?://#', '', $col[1]) . "\" target=\"_blank\">" . preg_replace('#^https?://#', '', $col[1]) . "</a><br/>";	
				} else if($type=="gongsil"){
					$text .= $label ." <a href=\"tel:". $col[1] . "\">" . $col[1] . "</a><br/>";
				}
			}
		}
	}

	return $text;
}