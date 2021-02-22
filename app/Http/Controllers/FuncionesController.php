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

        foreach ($data['satellites'] as $satelite) {
            $satelites[$satelite['name']] = $satelite;
        }

        $position = Funcion::GetLocation($satelites['kenobi']['distance'], $satelites['skywalker']['distance'], $satelites['sato']['distance']);
        $message = Funcion::GetMessage($satelites['kenobi']['message'], $satelites['skywalker']['message'], $satelites['sato']['message']);

        if ($position === false || $message === false) {
            // return response('No se decodifico la posicion/mensaje', 400);
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

        session()->put($name, $data);
        session()->save();

        if ($request->session()->has('kenobi') && $request->session()->has('skywalker') && $request->session()->has('sato')) {
            http_response_code(200);
            $result['position'] = Funcion::GetLocation($request->session()->get('kenobi')->distance, $request->session()->get('skywalker')->distance, $request->session()->get('sato')->distance);
            $result['message'] = Funcion::GetMessage($$request->session()->get('kenobi')->message, $request->session()->get('skywalker')->message, $request->session()->get('sato')->message);

            $request->session()->flush();

        } else {
            return response('No se decodifico la posicion/mensaje', 400);
        }

        return response()->json(
            compact('result')
        );
    }
}
