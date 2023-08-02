<?php
    if (! function_exists('internalErrorResponse')) {
        function internalErrorResponse($var) {
            if(!$var) return response('Internal server error', 500);
        }
    }

    if (! function_exists('getSuccess')) {
        function getSuccess($data=null) {
            return response( $data ?? 'Success', 200);
        }
    }

    if (! function_exists('sortSubCollection')) {
        function sortArrayByKey($sortArray, $key)
        {
            $sortFunc = function($a, $b) use ($key){
                if ($a[$key] == $b[$key]) {
                    return 0;
                }
                return ($a[$key] < $b[$key]) ? -1 : 1;
            };
            usort($sortArray, $sortFunc);

            return $sortArray;
        }
    }