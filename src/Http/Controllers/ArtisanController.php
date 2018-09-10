<?php

namespace CyberDuck\NovaArtisan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function list()
    {
        $commands = collect(Artisan::all())->map(function ($item, $key) {
            return $item->getDescription();
        });
        return response()->json($commands);
    }

    public function command($name)
    {
        $commands = Artisan::all();
        $command = $commands[$name];

        $options = collect($command->getDefinition()->getOptions())
            ->map(function ($option) {
                return [
                    'name' => '--'.$option->getName(),
                    'boolean' => !$option->acceptValue(),
                    'required' => $option->isValueRequired(),
                    'array' => $option->isArray(),

                ];
            });

        $arguments = collect($command->getDefinition()->getArguments())
            ->map(function ($option) {
                return [
                    'name' => $option->getName(),
                    'required' => $option->isRequired(),
                    'array' => $option->isArray(),

                ];
            });

        return response()->json([
            'name' => $name,
            'description' => $command->getDescription(),
            'options' => $options,
            'arguments' => $arguments,
        ]);
    }

    public function run($name, Request $request)
    {
        $arguments = $request->get('arguments', []);
        $options = $request->get('options', []);
        $cmd = 'php artisan '.$name;

        $params = ['--no-interaction' => true];
        $params = array_merge($params, $options);
        $params = array_merge($params, $arguments);

        Artisan::call($name, $params);

        return Artisan::output();
    }
}
