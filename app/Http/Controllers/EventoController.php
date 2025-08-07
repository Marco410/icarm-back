<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Interested;
use App\Models\Iglesia;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventoController extends  ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {

            $churchID = $request->churchID ?? 1;

            // Validar parámetros de entrada
            if ($request->has('isAdmin') && !in_array($request->isAdmin, ['admin', 'admin_list', 'user'])) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Valor inválido para el parámetro isAdmin'
                ]);
            }

            // Si es admin, devolver todos los eventos
            if ($request->isAdmin == "admin") {
                $eventos = Evento::orderBy('fecha_inicio', 'asc')
                    ->where('iglesia_id', $churchID)
                    ->with(["iglesia"])
                    ->get();

                return $this->ok([
                    'status' => 'Success', 
                    'data' => [
                        'eventos' => $eventos
                    ]
                ]);
            }

            // Si es admin_list, devolver eventos ordenados por fecha descendente
            if ($request->isAdmin == "admin_list") {
                $eventos = Evento::orderBy('fecha_inicio', 'desc')
                    ->where('iglesia_id', $churchID)
                    ->with(["iglesia"])
                    ->get();

                return $this->ok([
                    'status' => 'Success', 
                    'data' => [
                        'eventos' => $eventos
                    ]
                ]);
            }


            if ($request->isAdmin == "user") {
                $userId = $request->user_id;

                $eventos = Evento::where('id', '!=', 1)
                    ->where('iglesia_id', $churchID)
                    ->where('is_public', 1)
                    ->where('fecha_fin', '>', Carbon::now()->subDays(1)->format('Y-m-d H:i:s'))
                    ->orderBy('fecha_inicio', 'asc')
                    ->with(["iglesia"])
                    ->withCount('interested')
                    ->withCount(['interested as user_interested' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }])
                    ->get();
            }

            // Para usuarios normales, devolver eventos públicos
            // For nomal users, we need to return public events
            $userId = $request->user_id ?? null;
            $currentDate = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');

            $query = Evento::where('id', '!=', 1)
                ->where('is_public', 1)
                ->where('fecha_fin', '>', $currentDate)
                ->orderBy('fecha_inicio', 'asc')
                ->with(["iglesia"])
                ->withCount('interested');

            // Si hay un usuario autenticado, agregar información de interés
            if ($userId) {
                $query->withCount(['interested as user_interested' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }]);
            }

            $eventos = $query->get();

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'eventos' => $eventos
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos obtener los eventos, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function get(Request $request)
    {
        try {
            // Validar que el link esté presente
            if (!$request->has('link') || empty($request->link)) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'El parámetro link es requerido'
                ]);
            }

            $evento = Evento::where('link', $request->link)
                ->with(["iglesia"])
                ->withCount(['interested'])
                ->first();

            if (!$evento) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Evento no encontrado'
                ]);
            }

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'evento' => $evento
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos obtener el evento, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function getInterested(Request $request)
    {
        try {
            // Validar campos requeridos
            $required_fields = ['eventoID', 'userID'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || !$request->$field) {
                    return $this->badRequest([
                        'status' => 'Error',
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            // Verificar que el evento existe
            $evento = Evento::find($request->eventoID);
            if (!$evento) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Evento no encontrado'
                ]);
            }

            // Verificar que el usuario existe
            $user = \App\Models\User::find($request->userID);
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Usuario no encontrado'
                ]);
            }

            $isInterested = Interested::where('evento_id', $request->eventoID)
                ->where('user_id', $request->userID)
                ->exists();

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'interested' => $isInterested
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos verificar el interés del usuario, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    public function create_interested(Request $request)
    {
        try {
            // Validar campos requeridos
            $required_fields = ['eventoID', 'userID'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || !$request->$field) {
                    return $this->badRequest([
                        'status' => 'Error',
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            // Verificar que el evento existe
            $evento = Evento::find($request->eventoID);
            if (!$evento) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Evento no encontrado'
                ]);
            }

            // Verificar que el usuario existe
            $user = \App\Models\User::find($request->userID);
            if (!$user) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Usuario no encontrado'
                ]);
            }

            // Verificar si ya existe el interés
            $existingInterest = Interested::where('evento_id', $request->eventoID)
                ->where('user_id', $request->userID)
                ->first();

            if ($existingInterest) {
                // Si ya existe, eliminarlo (toggle off)
                $existingInterest->delete();
                $action = 'removed';
                $message = 'Interés removido exitosamente';
            } else {
                // Si no existe, crearlo (toggle on)
                $interested = Interested::create([
                    'evento_id' => $request->eventoID,
                    'user_id' => $request->userID,
                ]);
                $action = 'added';
                $message = 'Interés agregado exitosamente';
            }

            return $this->ok([
                'status' => 'Success', 
                'message' => $message,
                'data' => [
                    'action' => $action,
                    'interested' => $action === 'added'
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos procesar el interés del usuario, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            // Validar campos requeridos
            $required_fields = ['iglesia_id', 'nombre', 'fecha_inicio'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || empty($request->$field)) {
                    return $this->badRequest([
                        'status' => 'Error',
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            // Verificar que la iglesia existe
            $iglesia = Iglesia::find($request->iglesia_id);
            if (!$iglesia) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'La iglesia seleccionada no existe'
                ]);
            }

            // Validar fechas
            if ($request->fecha_fin && $request->fecha_inicio > $request->fecha_fin) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'La fecha de inicio no puede ser mayor que la fecha de fin'
                ]);
            }

            // Validar que la fecha de inicio no sea en el pasado
            if (Carbon::parse($request->fecha_inicio)->isPast()) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'La fecha de inicio no puede ser en el pasado'
                ]);
            }

            // Si es favorito, quitar favorito de otros eventos
            if ($request->is_favorite == 1) {
                Evento::where('id', '!=', 1)->update(['is_favorite' => 0]);
            }

            // Crear el evento
            $evento = Evento::create([
                'iglesia_id' => $request->iglesia_id,
                'nombre' => trim($request->nombre),
                'link' => Str::slug(trim($request->nombre . ' ' . $request->fecha_inicio), '-'),
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'descripcion' => $request->descripcion ?? '',
                'direccion' => $request->direccion ?? '',
                'is_favorite' => $request->is_favorite ?? 0,
                'can_register' => $request->can_register ?? 0,
                'is_public' => $request->is_public ?? 1,
            ]);

            // Procesar imágenes si se proporcionan
            if ($request->hasFile('img_vertical')) {
                $nameFoto = $this->storeFoto($request, $evento->id, 'img_vertical');
                if ($nameFoto) {
                    $evento->img_vertical = $nameFoto;
                }
            }

            if ($request->hasFile('img_horizontal')) {
                $nameFotoH = $this->storeFoto($request, $evento->id, 'img_horizontal');
                if ($nameFotoH) {
                    $evento->img_horizontal = $nameFotoH;
                }
            }

            $evento->save();

            return $this->ok([
                'status' => 'Success',
                'message' => 'Evento creado exitosamente',
                'data' => $evento->load('iglesia')
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos crear el evento, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validar campos requeridos
            $required_fields = ['evento_id', 'iglesia_id', 'nombre', 'fecha_inicio'];
            foreach ($required_fields as $field) {
                if (!$request->has($field) || empty($request->$field)) {
                    return $this->badRequest([
                        'status' => 'Error',
                        'message' => "El campo {$field} es requerido"
                    ]);
                }
            }

            // Verificar que el evento existe
            $evento = Evento::find($request->evento_id);
            if (!$evento) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Evento no encontrado'
                ]);
            }

            // Verificar que la iglesia existe
            $iglesia = Iglesia::find($request->iglesia_id);
            if (!$iglesia) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'La iglesia seleccionada no existe'
                ]);
            }

            // Validar fechas
            if ($request->fecha_fin && $request->fecha_inicio > $request->fecha_fin) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'La fecha de inicio no puede ser mayor que la fecha de fin'
                ]);
            }

            // Si es favorito, quitar favorito de otros eventos
            if ($request->is_favorite == 1) {
                Evento::where('id', '!=', 1)
                    ->where('id', '!=', $request->evento_id)
                    ->update(['is_favorite' => 0]);
            }

            // Actualizar el evento
            $evento->update([
                'iglesia_id' => $request->iglesia_id,
                'nombre' => trim($request->nombre),
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'descripcion' => $request->descripcion ?? '',
                'direccion' => $request->direccion ?? '',
                'is_favorite' => $request->is_favorite ?? 0,
                'can_register' => $request->can_register ?? 0,
                'is_public' => $request->is_public ?? 1,
            ]);

            // Procesar imágenes si se proporcionan
            if ($request->hasFile('img_vertical')) {
                $nameFoto = $this->storeFoto($request, $evento->id, 'img_vertical');
                if ($nameFoto) {
                    $evento->img_vertical = $nameFoto;
                    $evento->save();
                }
            }

            if ($request->hasFile('img_horizontal')) {
                $nameFotoH = $this->storeFoto($request, $evento->id, 'img_horizontal');
                if ($nameFotoH) {
                    $evento->img_horizontal = $nameFotoH;
                    $evento->save();
                }
            }

            return $this->ok([
                'status' => 'Success',
                'message' => 'Evento actualizado exitosamente',
                'data' => $evento->load('iglesia')
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos actualizar el evento, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showEvents(Request $request)
    {
        try {
            // Validar que iglesia_id esté presente
            if (!$request->has('iglesia_id') || !$request->iglesia_id) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'El parámetro iglesia_id es requerido'
                ]);
            }

            // Verificar que la iglesia existe
            $iglesia = Iglesia::find($request->iglesia_id);
            if (!$iglesia) {
                return $this->badRequest([
                    'status' => 'Error',
                    'message' => 'Iglesia no encontrada'
                ]);
            }

            $eventos = Evento::where('iglesia_id', $request->iglesia_id)
                ->orderBy('fecha_inicio', 'asc')
                ->get();

            return $this->ok([
                'status' => 'Success',
                'data' => [
                    'eventos' => $eventos
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos obtener los eventos de la iglesia, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function storeFoto($request,$id,$nameKey)
    {

        if ($request->hasFile($nameKey)) {
            $file = $request->file($nameKey);
            $nameWithExtension = $file->getClientOriginalName();
            $name = explode('.', $nameWithExtension)[0];
            $nameResult = $this->generateNameFile($name);

           $ruta = public_path() . '/eventos/' . $id;

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

           $path = $ruta ."/".$nameResult;

            Image::make($request->file($nameKey))->encode('jpg', 70)->save($path);

            return $nameResult;
        } else {
            return null;
        }
    }

    public function generateNameFile($value)
    {
        $link = html_entity_decode($value);
        $link = $this->deleteAccents($link);
        $link = strtolower($link); 
        $link = preg_replace("/[^ A-Za-z0-9_.-]/", '', $link);
        $link = str_replace(' ', '-', $link);

        return $link . '.jpg';
    }

    public function deleteAccents($cadena)
    {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
}



