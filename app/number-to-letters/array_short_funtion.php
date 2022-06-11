<?php
    function phparraysort($Array, $SortBy=array(), $Sort = SORT_REGULAR) {
        if (is_array($Array) && count($Array) > 0 && !empty($SortBy)) {
                $Map = array();                     
                foreach ($Array as $Key => $Val) {
                    $Sort_key = '';                         
                                    foreach ($SortBy as $Key_key) {
                                            $Sort_key .= $Val[$Key_key];
                                    }                
                    $Map[$Key] = $Sort_key;
                }
                asort($Map, $Sort);
                $Sorted = array();
                foreach ($Map as $Key => $Val) {
                    $Sorted[] = $Array[$Key];
                }
                return array_reverse($Sorted);
        }
        return $Array;
    }

    /*$Array = phparraysort($Array, array('UnreadCount','EntryDate','ModifiedDate'));
    print_r($Array);*/
?>