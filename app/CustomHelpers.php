<?php

if (!function_exists('payment_options')) {
    function payment_options($selected,$array_type=false)
    {
        $array = [
            '1' => 'Online',
            '2' => 'Phone Pay',
            '3' => 'Cash',
            '4' => 'Other',
        ];
        if($array_type){
            return $array;
        }else{
            return isset($array[$selected]) ? $array[$selected] : "";
        }
       
    }
}
