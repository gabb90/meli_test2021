<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use Illuminate\Http\Request;

class FuncionesController extends Controller
{

    /**
     * Método que parsea el input que se recibe
     * @return \Illuminate\Http\JsonResponse
     */
    public function topsecret(Request $request)
    {
        $data = $request->all();

        $satelites = [];

        //parseo de la informacion en un index por nombre para acceder mas facil a la informacion
        foreach ($data['satellites'] as $satelite) {
            $satelites[$satelite['name']] = $satelite;
        }

        return $this->respuesta($satelites['kenobi'], $satelites['skywalker'], $satelites['sato']);
    }

    /**
     * Método que parsea el input que se recibe
     * @return \Illuminate\Http\JsonResponse
     */
    public function topsecret_split(Request $request, $name)
    {
        $data = $request->all();

        // guardamos la informacion en variable SESSION
        session()->put($name, $data);
        session()->save();

        //si ya estan los datos de los demas satelites, resuelvo, y sino, devuelvo el error
        if ($request->session()->has('kenobi') && $request->session()->has('skywalker') && $request->session()->has('sato')) {

            $respuesta = $this->respuesta($request->session()->get('kenobi'), $request->session()->get('skywalker'), $request->session()->get('sato'));

            //borro las variables de sesion ya que se obtuvieron las 3
            $request->session()->flush();
            return $respuesta;

        } else {
            return response('No se decodifico la posicion/mensaje, faltan datos.', 400);
        }

    }

    /**
     * Método que procesa los datos y devuelve error si no pudo encontrar el punto, o el resultado del desciframiento
     * @param array $kenobi
     * @param array $skywalker
     * @param array $sato
     * @return \Illuminate\Http\JsonResponse
     */
    private static function respuesta(array $kenobi, array $skywalker, array $sato)
    {
        $position = Funcion::GetLocation($kenobi['distance'], $skywalker['distance'], $sato['distance']);
        $message = Funcion::GetMessage($kenobi['message'], $skywalker['message'], $sato['message']);

        $result = [
            'position' => $position,
            'message' => $message,
        ];

        // si no se puede resolver alguna de las funciones, devuelvo un 400 y termino la ejecucion
        if ($position === false || $message === false) {
            return response('No se ha podido determinar la posicion y/o el mensaje.', 400);
        }

        return response()->json(
            compact('result')
        );
    }
}
