<?php

namespace App\Traits\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiResponses
{
    public function ok($data = null)
    {
        return $this->responseObjectRoot(200, $data);
    }

    public function created($data = null)
    {
        return $this->responseObjectRoot(201, $data);
    }

    public function accepted($data = null)
    {
        return $this->responseObjectRoot(202, $data);
    }

    public function noContent($data = null)
    {
        return $this->responseObjectRoot(204, $data);
    }

    public function badRequest($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(400, $description, $code);
    }

    public function unauthorized($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(401, $description, $code);
    }

    public function forbidden($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(403, $description, $code);
    }

    public function notFound($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(404, $description, $code);
    }

    public function methodNotAllowed($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(405, $description, $code);
    }

    public function conflict($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(409, $description, $code);
    }

    public function unprocessableEntity($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(422, $description, $code);
    }

    public function internalServerError($description = null, $code = null)
    {
        return $this->responseObjectErrorRoot(500, $description, $code);
    }

    public function errorResponse($code, $description = null)
    {
        $parseCode = explode('.', $code);

        $statusCode  = $parseCode[0];

        if ($description == null) {
            $description = $this->getDescriptionErrorResponse($code);
        }

        //$objErrorResponse = compact('code', 'description');

        if ($statusCode == 400) {
            return $this->badRequest($description, $code);
        } else if ($statusCode == 401) {
            return $this->unauthorized($description, $code);
        } else if ($statusCode == 403) {
            return $this->forbidden($description, $code);
        } else if ($statusCode == 404) {
            return $this->notFound($description, $code);
        } else if ($statusCode == 405) {
            return $this->methodNotAllowed($description, $code);
        } else if ($statusCode == 409) {
            return $this->conflict($description, $code);
        } else if ($statusCode == 422) {
            return $this->unprocessableEntity($description, $code);
        } else if ($statusCode == 500) {
            return $this->internalServerError($description, $code);
        }

        return $this->internalServerError($description, $code);
    }

    public function errorResponseStatusCode($statusCode, $description = null)
    {
        return $this->responseObjectErrorRoot($statusCode, $description);
    }

    public function responseObjectRoot($statusCode, $data = null)
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            //$data = $this->filterData($data);
            $data = $this->sortData($data);

            $data = $data->values()->all();
        }

        if (config('app.showQueryLog', false)) {
            $data = [
                'data' => $data,
                'queryLog' => [
                    'laravel' => DB::getQueryLog(),
                ] + (isset($_SESSION['DB']['queryLog']) ? $_SESSION['DB']['queryLog'] : [])
            ];
            // $data['queryLog'] = DB::getQueryLog();
        }

        return response()->json($data, $statusCode);
    }

    public function responseObjectErrorRoot($statusCode, $description = null, $code = null)
    {
        if ($code == null) {
            $code = $statusCode;
        }

        if ($description == null) {
            $description = $this->getDescriptionErrorResponse($code);
        }

        if (config('app.showQueryLog', false)) {

            $queryLog['laravel'] = DB::getQueryLog();
            $queryLog += isset($_SESSION['DB']['queryLog']) ? $_SESSION['DB']['queryLog'] : [];
            return response()->json(compact('code', 'description', 'queryLog'), $statusCode);
        }

        return response()->json(compact('code', 'description'), $statusCode);
    }

    public function httpException($code = null, $description = null)
    {
        $parseCode = explode('.', $code);

        $statusCode  = $parseCode[0];

        if ($description == null) {
            $description = $this->getDescriptionErrorResponse($code);
        }

        throw new HttpException($statusCode, $description);
    }

    public function getDescriptionErrorResponse($code)
    {
        switch ($code) {
            case 400.1:
                return 'Se debe especificar al menos un valor diferente para actualizar.';
                break;
            case 401:
                return 'Se requiere autenticación para obtener la respuesta solicitada.';
                break;
            case 401.1:
                return 'Token inválido.';
                break;
            case 401.2:
                return 'El token ha expirado.';
                break;
            case 401.3:
                return 'Autenticación no valida. Ya';
                break;
            case 403:
                return 'No poseé permisos para ejecutar esta acción.';
                break;
            case 403.01:
                return 'Constraseña incorrecta.';
                break;
            case 403.02:
                return 'Usuario inactivo.';
                break;
            case 403.03:
                return 'Usuario con sesión activa.';
                break;
            case 404:
                return 'Recurso no encontrado.';
                break;
            case 404.01:
                return 'Usuario encontrado.';
                break;
            case 404.02:
                return 'Usuario eliminado.';
                break;
            default:
                break;
        }

        return '';
    }

    public function sortData(\Illuminate\Support\Collection $collection)
    {
        if (request()->has('sortBy')) {
            $collection = $collection->sortBy->{request()->sortBy};
        } else  if (request()->has('sortByDesc')) {
            $collection = $collection->sortByDesc->{request()->sortByDesc};
        }

        return $collection;
    }

    protected function filterData(\Illuminate\Support\Collection $collection)
    {
        foreach (request()->query() as $query => $value) {
            if (isset($query, $value) && !in_array($query, ['sortBy', 'sortByDesc'])) {
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    /*
    public function continue($data)
    {
        return response()->json($this->responseObjectRoot($data), 100);
    }

    public function switchingProtocols($data)
    {
        return response()->json($this->responseObjectRoot($data), 101);
    }

    public function processing($data)
    {
        return response()->json($this->responseObjectRoot($data), 102);
    }

    public function nonAuthoritative($data)
    {
        return response()->json($this->responseObjectRoot($data), 203);
    }

    public function resetContent($data)
    {
        return response()->json($this->responseObjectRoot($data), 205);
    }

    public function partialContent($data)
    {
        return response()->json($this->responseObjectRoot($data), 206);
    }

    public function multiStatus($data)
    {
        return response()->json($this->responseObjectRoot($data), 207);
    }

    public function alreadyReported($data)
    {
        return response()->json($this->responseObjectRoot($data), 208);
    }

    public function imUsed($data)
    {
        return response()->json($this->responseObjectRoot($data), 226);
    }

    public function multipleChoices($data)
    {
        return response()->json($this->responseObjectRoot($data), 300);
    }

    public function movedPermanently($data)
    {
        return response()->json($this->responseObjectRoot($data), 301);
    }

    public function found($data)
    {
        return response()->json($this->responseObjectRoot($data), 302);
    }

    public function seeOther($data)
    {
        return response()->json($this->responseObjectRoot($data), 303);
    }

    public function notModified($data)
    {
        return response()->json($this->responseObjectRoot($data), 304);
    }

    public function useProxy($data)
    {
        return response()->json($this->responseObjectRoot($data), 305);
    }

    public function switchProxy($data)
    {
        return response()->json($this->responseObjectRoot($data), 306);
    }

    public function temporaryRedirect($data)
    {
        return response()->json($this->responseObjectRoot($data), 307);
    }

    public function permanentRedirect($data)
    {
        return response()->json($this->responseObjectRoot($data), 308);
    }

    public function paymentRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 402);
    }

    public function notAcceptable($data)
    {
        return response()->json($this->responseObjectRoot($data), 406);
    }

    public function proxyAuthenticationRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 407);
    }

    public function requestTimeout($data)
    {
        return response()->json($this->responseObjectRoot($data), 408);
    }

    public function gone($data)
    {
        return response()->json($this->responseObjectRoot($data), 410);
    }

    public function preconditionFailed($data)
    {
        return response()->json($this->responseObjectRoot($data), 412);
    }

    public function payloadTooLarge($data)
    {
        return response()->json($this->responseObjectRoot($data), 413);
    }

    public function requestEntityTooLarge($data)
    {
        return response()->json($this->responseObjectRoot($data), 413);
    }

    public function requestUriTooLong($data)
    {
        return response()->json($this->responseObjectRoot($data), 414);
    }

    public function uriTooLong($data)
    {
        return response()->json($this->responseObjectRoot($data), 414);
    }

    public function unsupportedMediaType($data)
    {
        return response()->json($this->responseObjectRoot($data), 415);
    }

    public function rangeNotSatisfiable($data)
    {
        return response()->json($this->responseObjectRoot($data), 416);
    }

    public function requestedRangeNotSatisfiable($data)
    {
        return response()->json($this->responseObjectRoot($data), 416);
    }

    public function expectationFailed($data)
    {
        return response()->json($this->responseObjectRoot($data), 417);
    }

    public function imATeapot($data)
    {
        return response()->json($this->responseObjectRoot($data), 418);
    }

    public function authenticationTimeout($data)
    {
        return response()->json($this->responseObjectRoot($data), 419);
    }

    public function misdirectedRequest($data)
    {
        return response()->json($this->responseObjectRoot($data), 421);
    }

    public function locked($data)
    {
        return response()->json($this->responseObjectRoot($data), 423);
    }

    public function failedDependency($data)
    {
        return response()->json($this->responseObjectRoot($data), 424);
    }

    public function upgradeRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 426);
    }

    public function preconditionRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 428);
    }

    public function tooManyRequests($data)
    {
        return response()->json($this->responseObjectRoot($data), 429);
    }

    public function requestHeaderFieldsTooLarge($data)
    {
        return response()->json($this->responseObjectRoot($data), 431);
    }

    public function unavailableForLegalReasons($data)
    {
        return response()->json($this->responseObjectRoot($data), 451);
    }

    public function notImplemented($data)
    {
        return response()->json($this->responseObjectRoot($data), 501);
    }

    public function badGateway($data)
    {
        return response()->json($this->responseObjectRoot($data), 502);
    }

    public function serviceUnavailable($data)
    {
        return response()->json($this->responseObjectRoot($data), 503);
    }

    public function gatewayTimeout($data)
    {
        return response()->json($this->responseObjectRoot($data), 504);
    }

    public function httpVersionNotsupported($data)
    {
        return response()->json($this->responseObjectRoot($data), 505);
    }

    public function variantAlsoNegotiates($data)
    {
        return response()->json($this->responseObjectRoot($data), 506);
    }

    public function insufficientStorage($data)
    {
        return response()->json($this->responseObjectRoot($data), 507);
    }

    public function loopDetected($data)
    {
        return response()->json($this->responseObjectRoot($data), 508);
    }

    public function lengthRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 411);
    }

    public function notExtended($data)
    {
        return response()->json($this->responseObjectRoot($data), 510);
    }

    public function networkAuthenticationRequired($data)
    {
        return response()->json($this->responseObjectRoot($data), 511);
    }
    */
}
