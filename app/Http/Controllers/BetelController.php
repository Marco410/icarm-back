<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Betel;
use App\Services\NotificationService;
use Intervention\Image\Facades\Image;

class BetelController extends ApiController
{
    public $notificationService;

    public function getAll(Request $request){
        $beteles = Betel::with(['user','user2','user_anf','user_anf2'])->get();
        if($beteles){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'beteles' => $beteles
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde.'
            ]);
        }
    }


    public function create(Request $request){

        $nameFoto = $this->storeFoto($request,'img');
 
        $betel = Betel::create([
            'user_id' => $request->user_id,
            'user2_id' => $request->user2_id,
            'user_anf_id' => $request->user_anf_id,
            'user_anf2_id' => $request->user_anf2_id,
            'user_name' => $request->user_name,
            'user2_name' => $request->user2_name,
            'user_anf_name' => $request->user_anf_name,
            'user_anf2_name' => $request->user_anf2_name,
            'img' => $nameFoto,
            'map_url' => $request->map_url,
            'direccion' => $request->direccion,
            'contacto' => $request->contacto,
            'telefono' => $request->telefono,
        ]);

        if($betel){
            return $this->ok([
                'status' => 'Success', 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

    public function update(Request $request)
    {
        
        $errores = array();
        $flagValidation = true;
        $result = (object)[];

        if($flagValidation){
            
            $betel = Betel::where('id',$request->betel_id)->update([
                'user_id' => $request->user_id,
                'user2_id' => $request->user2_id,
                'user_anf_id' => $request->user_anf_id,
                'user_anf2_id' => $request->user_anf2_id,
                'user_name' => $request->user_name,
                'user2_name' => $request->user2_name,
                'user_anf_name' => $request->user_anf_name,
                'user_anf2_name' => $request->user_anf2_name,
                'map_url' => $request->map_url,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
            ]);

            return $this->ok([
                'status' => 'Success', 
                'data' => $betel
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

    public function delete(Request $request)
    {
        
        $errores = array();
        $flagValidation = true;
        $result = (object)[];

        if($flagValidation){
            $betel = Betel::where('id',$request->betel_id)->first();
            
            if($betel->img != null){

                $path = public_path() . '/beteles/'.$betel->img;
    
                unlink($path);
            }
            
            $betel = Betel::where('id',$betel->id)->delete();

            return $this->ok([
                'status' => 'Success', 
            ]);
        }
        else{
            $result = (object) [
                'type' => 'error',
                'message' => 'No se pudo eliminar el betel.',
                'data' => $errores
            ];
        }
        return $this->ok(json_decode(json_encode($result)));
    }



    public function storeFoto($request,$nameKey)
    {

        if ($request->hasFile($nameKey)) {
            $file = $request->file($nameKey);
            $nameWithExtension = $file->getClientOriginalName();
            $name = explode('.', $nameWithExtension)[0];
            $nameResult = $this->generateNameFile($name);

           $ruta = public_path() . '/beteles';

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

           $path = $ruta ."/".$nameResult;

            Image::make($request->file($nameKey))->encode('jpg', 50)->save($path);

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

        return $link . date('s') . '.jpg';
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
