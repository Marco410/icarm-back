<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChurchService;
use App\Models\Interested;
use App\Models\Iglesia;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ChurchServiceController extends  ApiController
{

    public function index(Request $request)
    {

        try {

            $churchID = $request->churchID ?? 1;

            $church_services = ChurchService::where('church_id', $churchID)
                ->get();

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'church_services' => $church_services
                ]
            ]);

        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos obtener los servicios de la iglesia, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'day' => 'required|string|max:50',
                'time' => 'required|string|max:10',
                'church_id' => 'required|integer|exists:iglesias,id',
            ]);

            $churchService = ChurchService::create($validated);

            return $this->ok([
                'status' => 'Success',
                'data' => [
                    'church_service' => $churchService
                ]
            ]);
        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos crear el servicio de la iglesia, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'day' => 'sometimes|required|string|max:50',
                'time' => 'sometimes|required|string|max:10',
                'church_id' => 'sometimes|required|integer|exists:iglesias,id',
            ]);

            $churchService = ChurchService::findOrFail($id);

            $churchService->update($validated);

            return $this->ok([
                'status' => 'Success',
                'data' => [
                    'church_service' => $churchService
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound([
                'status' => 'Error',
                'message' => 'Servicio de iglesia no encontrado',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos actualizar el servicio de la iglesia, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $churchService = ChurchService::findOrFail($id);
            $churchService->delete();
    
            return $this->ok([
                'status' => 'Success',
                'message' => 'Servicio de iglesia eliminado correctamente',
                'data' => [
                    'church_service' => $churchService
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound([
                'status' => 'Error',
                'message' => 'Servicio de iglesia no encontrado',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        } catch (\Exception $e) {
            return $this->badRequest([
                'status' => 'Error',
                'message' => 'No pudimos eliminar el servicio de la iglesia, intente más tarde',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }


}



