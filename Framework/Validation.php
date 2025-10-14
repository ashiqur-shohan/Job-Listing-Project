<?php

namespace Framework;

class Validation{
    /**
     * 
     * @param string $vlaue
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function string($value, $min = 1, $max=INF){
        if(is_string($value)){
            $value = trim($value);
            $length = strlen($value);

            // return true if length is between min and max
            return $length >= $min && $length <= $max;
        }

        // not a string
        return false;
    }

    /**
     * Validate email format
     * 
     * @param string $email
     * @return mixed
     */
    public static function email($value){
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Match two values
     * 
     * @param string $value1
     * @param string $value2
     * @return bool
     */
    public static function match($value1, $value2){
        $value1 = trim($value1);
        $value2 = trim($value2);

        return $value1 === $value2;
    }
}