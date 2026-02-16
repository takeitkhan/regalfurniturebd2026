@php
    $getVar = \App\Models\ProductAttributeVariation::where('main_pid', $prouduct_id)->get();
    //dump($getVar);
    $result = [];
    $tempArr = [];
    foreach($getVar as $var) {
        //dump($var->variations);
        $jsonDecoded = json_decode($var->variations);
    //    dump((array) $jsonDecoded);
        foreach((array) $jsonDecoded as $key => $data){
            $tempArr[$key] []= $data;
        }
    }

    foreach($tempArr as $key => $d){
        $result [$key]= array_unique($d);
    }

    dump($result);
@endphp
