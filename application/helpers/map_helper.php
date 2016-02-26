<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function lngToX($lng) {
    return round(OFFSET + RADIUS_1 * $lng * pi() / 180);        
}

function latToY($lat) {
    return round(OFFSET - RADIUS_1 * 
                log((1 + sin($lat * pi() / 180)) / 
                (1 - sin($lat * pi() / 180))) / 2);
}

function pixelDistance($lat1, $lng1, $lat2, $lng2, $zoom) {
    $x1 = lngToX($lng1);
    $y1 = latToY($lat1);

    $x2 = lngToX($lng2);
    $y2 = latToY($lat2);
        
    return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
}


/**
 * 클러스터의 리스트를 반환한다.
 *
 */
function cluster_list($title, $x, $y, $markers, $distance, $zoom) {
    $clustered = array();

    while (count($markers)) {
		$i = 0;

        $marker  = array_pop($markers);
		$cluster = array();
		
		if($marker['id'] == $title){
			$i=1;
		}
		/* 남은 모든 마커와의 거리를 계산한다. */
        foreach ($markers as $key => $target) {


            $pixels = pixelDistance($marker['lat'], $marker['lng'],
                                    $target['lat'], $target['lng'],
                                    $zoom);
            /* 두 마커 사이가 가까우면 해당 마커를 삭제하고 클러스터에 넣는다. */
            /* target marker from array and add it to cluster.      */
            if ($distance > $pixels) {
				if($target['id'] == $title){
					$i=1;
				}
               /** printf("Distance between %s,%s and %s,%s is %d pixels.\n", 
                    $marker['lat'], $marker['lng'],
                    $target['lat'], $target['lng'],
                    $pixels);
				**/
                unset($markers[$key]);
                $cluster[] = $target;
            }
        }

		if($i==1){
			if (count($cluster) > 0) {
				$cluster[] = $marker;
				$clustered = $cluster;
			} else {
				$clustered[] = $marker;
			}
			$i=0;
		}
    }

    return $clustered;
}

/**
 * 클러스터 별 숫자를 반환한다.
 * map_cluster = 맵클러스터(원반경 묶음) 사용하기
 * icon_only = 가격정보를 없애고 마커로만 보기
 */
function cluster_count($markers, $distance, $zoom, $map_cluster=true, $icon_only=false) {
    $clustered = array();

    while (count($markers)) {
        $marker  = array_pop($markers);
        $cluster = array();

		/* 남은 모든 마커와의 거리를 계산한다. */
        foreach ($markers as $key => $target) {
            $pixels = pixelDistance($marker['lat'], $marker['lng'],
                                    $target['lat'], $target['lng'],
                                    $zoom);
            /* 두 마커 사이가 가까우면 해당 마커를 삭제하고 클러스터에 넣는다. */
            /* target marker from array and add it to cluster.      */
            if ($map_cluster && ( $distance > $pixels )) {
               /** printf("Distance between %s,%s and %s,%s is %d pixels.\n", 
                    $marker['lat'], $marker['lng'],
                    $target['lat'], $target['lng'],
                    $pixels);
				**/
                unset($markers[$key]);
                $cluster[] = $target;
            }
        }

        
        $class = "cluster_s";
        if(count($cluster)>10) $class = "cluster_m";
        if(count($cluster)>100) $class = "cluster_l";

		$param = Array(
			"count" => count($cluster)+1,
			"id" => $marker['id'],
			"lat" => $marker['lat'],
			"lng" => $marker['lng'],
            "latitude" => $marker['lat'],
            "longitude" => $marker['lng'],
            "class" => $class,
			"icon_only" => $icon_only
		);

		

		if(count($cluster)==0){
			$param["type"] = $marker['type'];
			$param["sell_price"] = $marker['sell_price'];
			$param["lease_price"] = $marker['lease_price'];
			$param["full_rent_price"] = $marker['full_rent_price'];
			$param["monthly_rent_deposit"] = $marker['monthly_rent_deposit'];
			$param["monthly_rent_price"] = $marker['monthly_rent_price'];
		}
		
		$clustered[] = $param;
    }
    return $clustered;
}

function cluster($markers, $distance, $zoom) {
    $clustered = array();
    /* Loop until all markers have been compared. */
    while (count($markers)) {
        $marker  = array_pop($markers);
        $cluster = array();
        /* Compare against all markers which are left. */
        foreach ($markers as $key => $target) {
            $pixels = pixelDistance($marker['lat'], $marker['lng'],
                                    $target['lat'], $target['lng'],
                                    $zoom);
            /* If two markers are closer than given distance remove */
            /* target marker from array and add it to cluster.      */
            if ($distance > $pixels) {
               /** printf("Distance between %s,%s and %s,%s is %d pixels.\n", 
                    $marker['lat'], $marker['lng'],
                    $target['lat'], $target['lng'],
                    $pixels);
				**/
                unset($markers[$key]);
                $cluster[] = $target;
            }
        }

        /* If a marker has been added to cluster, add also the one  */
        /* we were comparing to and remove the original from array. */
        if (count($cluster) > 0) {
            $cluster[] = $marker;
            $clustered[] = $cluster;
        } else {
            $clustered[] = $marker;
        }
    }
    return $clustered;
}