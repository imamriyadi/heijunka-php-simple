<?php
$parkir1 = array (
    'a'=>12,
    'b'=>4,
    'c'=>6,
    'd'=>11,
    'e'=>14
);


$total_qty =  total_qty($parkir1);
echo json_encode(pembagin_rute($parkir1,$total_qty));

function pembagin_rute($parkir1,$qty){
    $jumlah = '';
    $hasil = array();
    $group_arr = '';
    foreach($parkir1 as $pk2 => $pv2){
        $jumlah[$pk2] = ($pv2 / $qty);
    }
    
    for($i=0;$i<$qty;$i++){
       foreach($jumlah as $k => $v){
        if(count(array_keys($hasil, $k)) > 0){
                 $group_arr[$i][$k] = ($v * ($i+1) - count(array_keys($hasil, $k)));
            }else{
                $group_arr[$i][$k] = ($v * ($i+1));
            }
            arsort($group_arr[$i]);
        }
        array_push($hasil,nilai_hasil($group_arr[$i]));
    }
    return $group_arr;

}

function nilai_hasil($a){
    $nilai =  max($a);
    $area_antri = array_search($nilai,$a);
    return $area_antri;

}


function total_qty($arr){
    $total = '';
    foreach($arr as $r => $j){
        $total = $total + $j;
    }
    return $total;
}


?>