<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Iglesia;

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
    public function index()
    {
        
        return $this->ok(Evento::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createEvent(Request $request)
    {
        
        $errores = array();
        $flagValidation = true;
        $result = (object)[];
   
        $countEvent = DB::table('eventos')
             ->select('id')
             ->where('iglesia_id',$request->iglesia_id)
             ->where('nombre',$request->nombre)
             ->where('fecha_inicio',$request->fecha_inicio)
             ->where('fecha_fin',$request->fecha_fin)->count();

        if($countEvent > 0)
        {
            $flagValidation = false;
            array_push($errores,'El evento ya esta registrado');
        }

        $iglesiaExist = DB::table('iglesias')
            ->select('id')
            ->where('id',$request->iglesia_id)->count();

        if($iglesiaExist == 0) 
        {
            $flagValidation = false;
            array_push($errores,'La iglesia que esta intentanto ingresar no existe');
        }

        if($request->fecha_inicio > $request ->fecha_fin)
        {
            $flagValidation = false;
            array_push($errores,'La fecha de inicio es mayor que la final');
        }
        
        if($flagValidation){

            
            $evento = Evento::create([
                'iglesia_id' => $request->iglesia_id,
                'nombre' => $request->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'descripcion' => $request->descripcion
            ]);

            
                $nameFoto = $this->storeFoto($request,$evento->id);
                $evento->imagen = $nameFoto;
                $evento->save();
            
            
            $result = (object) [
                'type' => 'success',
                'message' => 'Evento registrado con exito',
                'data' => $evento
            ];
        }
        else{
            $result = (object) [
                'type' => 'error',
                'message' => 'Hay errores en el registro',
                'data' => $errores
            ];
        }
        return $this->ok(json_decode(json_encode($result)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showEvents(Request $request)
    {
        return $this->ok(Evento::where('iglesia_id', $request->iglesia_id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //FALTA VALIDAR
        
        $imgeliminar = "";
        $evento = Evento::find($request->id);
        $imgeliminar = $evento->imagen;
        $evento->iglesia_id = $request->iglesia_id;
        $evento->nombre = $request->nombre;
        $evento->fecha_inicio = $request->fecha_inicio;
        $evento->fecha_fin = $request->fecha_fin;
        $evento->descripcion = $request->descripcion;

        $path_file_delete = public_path() . '/eventos/' . $request->id . "/".$imgeliminar;
        unlink($path_file_delete);
        $nameFoto = $this->storeFoto($request,$request->id);
        $evento->imagen = $nameFoto;

        $evento->save();
        

        return $this->ok($evento);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeFoto($request,$id)
    {

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $name = $file->getClientOriginalName();
            $nameResult = $this->generateNameFile($name);

           /*  request()->file("imagen")->storeAs('public', 'marcas/' . $nameResult); */

           //$ruta = storage_path() .'/app/public/eventos/'.$request->id;

           $ruta = public_path() . '/eventos/' . $id;

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

           $path = $ruta ."/".$nameResult;

            Image::make($request->file('imagen'))
                ->resize(768,449)->save($path);

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

        return $link;
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



