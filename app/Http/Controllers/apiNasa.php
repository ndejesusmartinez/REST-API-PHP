<?php

namespace App\Http\Controllers;
use App\Models\astronomyPictureOfTheDay;
use Illuminate\Http\Request;

class apiNasa extends Controller
{
    public function astronomyPictureOfTheDay(){
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.nasa.gov/planetary/apod?api_key=TQ8SQJfznFZZRqtiz2shEd7TvPe1dNeel6rnKos0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_close($ch);
            $data = curl_exec($ch);
            $result = (array)json_decode($data);
            //dd($result);
            $salida = [
                //'copyright'=>$result['copyright'],
                'fechaConsulta' =>$result['date'] ?? '',
                'urlFoto'=>$result['hdurl'] ?? '',
                'nombre'=>$result['title'] ?? '',
                'informacion'=>$result['explanation']
            ];
            astronomyPictureOfTheDay::updateOrCreate([
                'nombre'=>$salida['nombre'],
                'urlFoto'=>$salida['urlFoto'],
                'fechaConsulta'=>$salida['fechaConsulta'],
                'informacion'=>$salida['informacion']
            ]);
            return $salida;
        } catch (\Throwable $th) {
            //throw $th;
            dd("Error".$th);
        }
    }
}
