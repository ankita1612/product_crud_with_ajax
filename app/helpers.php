<?php 
function pr($arr)
{
    echo "<pre>";
    print_R($arr);
    echo "</pre>";
}
function obj_to_array($object)
{    
    $arr_result=(!empty($object)?json_decode(json_encode($object,true),true):"");
    return $arr_result;

}
?>