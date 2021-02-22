<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use Illuminate\Http\Request;

class FuncionesController extends Controller
{
    public function topsecret(Request $request)
    {
        $data = $request->all();

        $satelites = [];

        //parseo de la informacion en un index por nombre para acceder mas facil a la informacion
        foreach ($data['satellites'] as $satelite) {
            $satelites[$satelite['name']] = $satelite;
        }

        $position = Funcion::GetLocation($satelites['kenobi']['distance'], $satelites['skywalker']['distance'], $satelites['sato']['distance']);
        $message = Funcion::GetMessage($satelites['kenobi']['message'], $satelites['skywalker']['message'], $satelites['sato']['message']);

        // si no se puede resolver alguna de las funciones, devuelvo un 400 y termino la ejecucion
        if ($position === false || $message === false) {
            return response('No se decodifico la posicion/mensaje', 400);
        }

        $result = [
            'position' => Funcion::GetLocation($satelites['kenobi']['distance'], $satelites['skywalker']['distance'], $satelites['sato']['distance']),
            'message' => Funcion::GetMessage($satelites['kenobi']['message'], $satelites['skywalker']['message'], $satelites['sato']['message']),
        ];

        return response()->json(
            compact('result')
        );
    }

    public static function topsecret_split(Request $request, $name)
    {
        $data = $request->all();

        // guardamos la informacion en variable SESSION
        session()->put($name, $data);
        session()->save();

        //si ya estan los datos de los demas satelites, resuelvo, y sino, devuelvo el error
        if ($request->session()->has('kenobi') && $request->session()->has('skywalker') && $request->session()->has('sato')) {
            $result['position'] = Funcion::GetLocation($request->session()->get('kenobi')->distance, $request->session()->get('skywalker')->distance, $request->session()->get('sato')->distance);
            $result['message'] = Funcion::GetMessage($$request->session()->get('kenobi')->message, $request->session()->get('skywalker')->message, $request->session()->get('sato')->message);

            $request->session()->flush();

        } else {
            return response('No se decodifico la posicion/mensaje, faltan datos', 400);
        }

        return response()->json(
            compact('result')
        );
    }
}
