<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{

    public static function GetLocation($distancia_kenobi, $distancia_skywalker, $distancia_sato)
    {
        $r1 = $distancia_kenobi;
        $r2 = $distancia_skywalker;
        $r3 = $distancia_sato;

        $n1 = (270000 - $r1 ** 2 + $r2 ** 2) / 160;
        $n2 = (3 / 800) * (30000 - $r1 ** 2 + $r3 ** 2);

        $y = $n1 - $n2;

        $x = sqrt(($r1 ** 2 - ($y + 200) ** 2));
        $x1 = $x - 500;
        $x2 = (-1 * $x) - 500;

        $f2x1 = ($x1 - 100) ** 2 + ($y + 100) ** 2;
        $f2x1 = round($f2x1, 1);
        $f3x1 = ($x1 - 500) ** 2 + ($y - 100) ** 2;
        $f3x1 = round($f3x1, 1);

        $f2x2 = ($x2 - 100) ** 2 + ($y + 100) ** 2;
        $f2x2 = round($f2x2, 1);
        $f3x2 = ($x2 - 500) ** 2 + ($y - 100) ** 2;
        $f3x2 = round($f3x2, 1);
        $d2 = round(($r2 ** 2), 1);
        $d3 = round(($r3 ** 2), 1);

        if ($f2x1 == $d2 && $f3x1 == $d3) {
            $x = round($x1, 1);
        } elseif ($f2x2 == $d2 && $f3x2 == $d3) {
            $x = round($x2, 1);
        } else {
            return false;
        }

        if (number_format($x, 0) == -0) $x = 0;
        if (number_format($y, 0) == -0) $y = 0;

        return [
            'x' => $x,
            'y' => $y,
        ];
    }
    public static function GetMessage($mensaje_kenobi, $mensaje_skywalker, $mensaje_sato)
    {
        $mensajes = [$mensaje_kenobi, $mensaje_skywalker, $mensaje_sato];

        $i = count($mensajes[0]);

        foreach ($mensajes as $key => $mensaje) {
            if ($i > count($mensaje)) {
                $i = count($mensaje);
            }
        }

        $mensaje_completo = [];
        foreach ($mensajes as $mensaje) {
            while (count($mensaje) > $i) {
                array_shift($mensaje);
            }
            foreach ($mensaje as $key => $palabra) {
                if ($palabra == '') {
                    continue;
                }

                $mensaje_completo[$key] = $palabra;
            }
        }

        ksort($mensaje_completo);

        return implode(' ', $mensaje_completo);
    }
}
