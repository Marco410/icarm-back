<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Interested;
use App\Models\Iglesia;

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
    public function index()
    {
        
        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'eventos' => Evento::where('id', '!=', 1)->where('fecha_fin','>',Carbon::now()->subDays(2)->format ('Y-m-d h:i:s'))->orderBy('id','desc')->with(["iglesia"])->withCount('interested')->get()
            ]
        ]);
    }


    public function get(Request $request)
    {
        
        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'evento' => Evento::where('id', $request->eventoID)->with(["iglesia"])->withCount(['interested'])->get()
            ]
        ]);
    }

    public function getInterested(Request $request)
    {
        
        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'interested' => Interested::where('evento_id', $request->eventoID)->where('user_id', $request->userID)->count() != 0 ? true: false,
            ]
        ]);
    }


    public function create_interested(Request $request)
    {

        $interest = Interested::where('evento_id', $request->eventoID)->where('user_id', $request->userID)->count();


        if($interest == 0){
            
            $interested = Interested::create([
                'evento_id' => $request->eventoID,
                'user_id' => $request->userID,
            ]);
        }else{
            $interested = Interested::where('evento_id', $request->eventoID)->where('user_id', $request->userID)->delete();
        }

        if($interested){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'interested' => $interested
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
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
        
        $errores = array();
        $flagValidation = true;
        $result = (object)[];
   
     /*    $countEvent = DB::table('evento')
             ->select('id')
             ->where('iglesia_id',$request->iglesia_id)
             ->where('nombre',$request->nombre)
             ->where('fecha_inicio',$request->fecha_inicio)
             ->where('fecha_fin',$request->fecha_fin)->count();

        if($countEvent > 0)
        {
            $flagValidation = false;
            array_push($errores,'El evento ya esta registrado');
        } */

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

            if($request->is_favorite == 1) {
                $eventosUpdate = Evento::where('id','!=',1)->update([
                    'is_favorite' => 0
                ]);
            }
            
            $evento = Evento::create([
                'iglesia_id' => $request->iglesia_id,
                'nombre' => $request->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'is_favorite' => $request->is_favorite,
                'can_register' => $request->can_register,
            ]);

                $nameFoto = $this->storeFoto($request,$evento->id,'img_vertical');
                $evento->img_vertical = $nameFoto;

                $nameFotoH = $this->storeFoto($request,$evento->id,'img_horizontal');
                $evento->img_horizontal = $nameFotoH;
                $evento->save();

            return $this->ok([
                'status' => 'Success', 
                'data' => $evento
            ]);
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

    public function storeFoto($request,$id,$nameKey)
    {

        if ($request->hasFile($nameKey)) {
            $file = $request->file($nameKey);
            $nameWithExtension = $file->getClientOriginalName();
            $name = explode('.', $nameWithExtension)[0];
            $nameResult = $this->generateNameFile($name);

           /*  request()->file("imagen")->storeAs('public', 'marcas/' . $nameResult); */

           //$ruta = storage_path() .'/app/public/eventos/'.$request->id;

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



