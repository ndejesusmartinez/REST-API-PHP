<?php

namespace App\Http\Controllers;
use DOMDocument;
use SimpleXMLElement;
use App\Models\marvel;
use App\Models\marvel_event;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;

class apiMArvel extends Controller
{
    //
    public function marvel(){
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://gateway.marvel.com:443/v1/public/characters?limit=100&ts=1&apikey=1b4a71597fe79145195683ef11f4635b&hash=55f6472b12db4ff84b98cf97e899c1a0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_close($ch);
            $data = curl_exec($ch);
            $result = (array)json_decode($data);
            //dd($result);
            $statusCode = $result['code'];
            if($statusCode === 200){
                $arrayHeroes = (array)$result['data'];
                //dd($arrayHeroes['results']);
                $salida = [];
                foreach($arrayHeroes['results'] as $keys){
                    $key = (array) $keys;
                    $urls = (array)$key['thumbnail'];
                    $salidas = [
                        'idHeroe'=>$key['id'],
                        'name'=>$key['name'],
                        'urlImage'=>$urls['path'].".".$urls['extension'],
                        'comics'=>$key['comics'],
                        'series'=>$key['series']
                    ];
                    array_push($salida,$salidas);
                }
                //dd($salida);
                foreach ($salida as $key) {
                    $insertarComics = (array)$key['comics'];
                    //dd($insertarComics);
                    marvel::updateOrCreate([
                        'idHeroe'=>$key['idHeroe'],
                        'name'=>$key['name'],
                        'urlImage'=>$key['urlImage'],
                        'comics'=>json_encode($insertarComics),
                        'series'=>json_encode($key['series'])
                    ]);
                }
                dd("OK");
            }else{
                if($statusCode === 409){
                    dd($result['status']);
                }else{
                dd(
                    "Error al realizar la peticion a la API de marvel, por favor valida nuevamente en unos minutos"
                );}
            }

        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function eventos(){
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://gateway.marvel.com:443/v1/public/events?limit=100&apikey=1b4a71597fe79145195683ef11f4635b&hash=55f6472b12db4ff84b98cf97e899c1a0&ts=1");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_close($ch);
            $data = curl_exec($ch);
            $result = (array)json_decode($data);
            $statusCode = $result['code'];
            if($statusCode === 200){
                $events = (array)$result['data'];
                $datos=[];
                foreach ($events['results'] as $key=>$value) {
                    $insertar = (array)$value;
                    $img = (array)$insertar['thumbnail'];
                    marvel_event::updateOrCreate([
                        "idEvento"=>$insertar['id'],
                        "title"=>$insertar['title'],
                        "description"=>$insertar['description'],
                        "start"=>$insertar['start'] ?? '',
                        "end"=>$insertar['end'] ?? '',
                        "img"=>$img['path'].".".$img['extension']
                    ]);
                }
                dd("OK");
            }
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function dian(){
        $fields = array('cufe' => 'dbb91abfc35f20dd71f49d6a0eb186d9d8c50d4b579ef8548659a07cb1a0c37c5ec9fe121aa2da629b39b7aef7138624'
        );
        $fields_string = json_encode($fields);
        $headers = array(
            'Content-Type: application/json',
            'Authorization:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwcm92ZWVkb3IiOjEwLCJpZCI6ODU2MjF9.sI1BpqLSMKhDgfmuGiKvOuqmoyv7d33v0vlVJVi34bs'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://alfaprod.dominadigital.com.co/api/WSGetStatusEvent/900047753-5");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = (array)json_decode($data);
        $res = $result['ApplicationResponse'];
        $xml   = simplexml_load_string($res);
        $codigoEvento = $xml->xpath('cac:DocumentResponse/cac:Response');
        $prueba = [];
        foreach ($codigoEvento as $key) {
            array_push($prueba,$key->xpath('cbc:ResponseCode')[0].":".$key->xpath('cbc:Description')[0]." Fecha: ".$key->xpath('cbc:EffectiveDate')[0]." Hora: ".$key->xpath('cbc:EffectiveTime')[0]);
        }
        return $prueba;
    }
}
