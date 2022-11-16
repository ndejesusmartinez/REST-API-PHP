<?php

namespace App\Http\Controllers;
use App\Models\marvel;
use App\Models\marvel_event;
use Illuminate\Http\Request;

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
}
