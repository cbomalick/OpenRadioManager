<?
//Miscellaneous functions

//Checks if subarray exists within multiarray
function in_multiarray($elem, $array)
        {
            while (current($array) !== false) {
                if (current($array) == $elem) {
                    return true;
                } elseif (is_array(current($array))) {
                    if (in_multiarray($elem, current($array))) {
                        return true;
                    }
                }
                next($array);
            }
            return false;
        }

?>