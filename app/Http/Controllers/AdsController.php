<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use Intervention\Image\Facades\Image;

class AdsController extends ApiController
{

    public function getAll(Request $request){
        $ads = Ads::get();
        if($ads){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'ads' => $ads
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

    public function create(Request $request){

        $ad = Ads::create([ 
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'module' => $request->module,
            'active' => 1
        ]);

        if($request->img){
            $nameFoto = $this->storeFoto($request,$ad->id);
            $ad->img = $nameFoto;
            $ad->save();
        }

        if($ad){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'ad' => $ad,
                ]
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
       
    }

    public function storeFoto($request,$id)
    {
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nameWithExtension = $file->getClientOriginalName();
            $name = explode('.', $nameWithExtension)[0];
            $nameResult = $this->generateNameFile($name);

            $ruta = public_path() . '/ads/' . $id;

            if (!file_exists($ruta)) {
                mkdir($ruta, 0775, true);
            }

            $path = $ruta ."/".$nameResult;
            Image::make($request->file('img'))->encode('jpg', 70)->save($path);

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
