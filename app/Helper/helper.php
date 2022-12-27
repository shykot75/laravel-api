<?php

if( ! function_exists('api_response') ){
    function api_response($type=null, $message=null, $data=null, $status=null){
        return response()->json([
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ]);
    }
}
