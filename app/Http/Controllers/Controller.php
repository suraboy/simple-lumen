<?php

namespace App\Http\Controllers;

use App\Contracts\ResponseTrait;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use ResponseTrait;
    /**
     * Constructor
     *
     * @param Manager|null $fractal
     */

    public function __construct(Manager $fractal = null)
    {
        $fractal = $fractal === null ? new Manager() : $fractal;
        $this->setFractal($fractal);
        if (app('request')->has('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
    /**
     * @param $resource
     * @return mixed
     */
    public function respondWithSuccess($resource = [])
    {
        return $this->respond($resource);
    }
    /**
     * @param $message
     * @return mixed
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'errors' => [
                'status_code' => $this->getStatusCode(),
                'message' => $message
            ]
        ]);
    }
    /**
     * @return mixed
     * @param $message
     */
    public function respondUnauthorized($message = 'The requested resource failed authorization')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
    }
    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'The requested resource could not be found')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }
    /**
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = 'An internal server error has occurred')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }
    /**
     * @param string $message
     * @return mixed
     */
    public function respondUnprocessableEntity($message = 'The request cannot be processed with the given parameters')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * @param array $resource
     * @param $callback
     * @return mixed
     */
    public function respondCreated($resource, $callback)
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithItem($resource,$callback);
    }

    /**
     * @param $resource
     * @param $callback
     * @return mixed
     */
    public function respondUpdated($resource, $callback)
    {
        return $this->setStatusCode(Response::HTTP_OK)->respondWithItem($resource,$callback);
    }
    /**
     * @return mixed
     */
    public function respondNoContent()
    {
        return $this->responseDeleteSuccess();
    }
    /**
     * @param null $message
     * @return mixed
     */
    public function respondHttpConflict($message = null)
    {
        return $this->setStatusCode(Response::HTTP_CONFLICT)->respondWithError($message);
    }
}
