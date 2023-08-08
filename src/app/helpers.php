<?php

use Illuminate\Support\Facades\DB;

    if (! function_exists('internalErrorResponse')) {
        function internalErrorResponse($var, $rollback=false) {
            if($rollback) DB::rollback();
            if(!$var) return response('Internal server error', 500);
        }
    }