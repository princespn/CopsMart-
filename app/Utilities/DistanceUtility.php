<?php
namespace App\Utilities;

class DistanceUtility {

    function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            // $unit = strtoupper($unit);
            return ($miles * 1.609344);

            //   if ($unit == "K") {
            //     return ($miles * 1.609344);
            //   } else if ($unit == "N") {
            //     return ($miles * 0.8684);
            //   } else {
            //     return $miles;
            //   }
        }
    }
}
