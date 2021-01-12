<?php

namespace App\Contracts;

use App\Serializers\CustomDataArraySerializer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait ResponseTrait
 * @package App\Contracts
 */
trait ResponseTrait
{
    /**
     * Status code of response.
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;
    /**
     * Fractal manager instance.
     *
     * @var Manager
     */
    protected $fractal;

    /**
     * Set fractal Manager instance.
     *
     * @param Manager $fractal
     */
    public function setFractal(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * Getter for statusCode.
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode.
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Send custom data response.
     *
     * @param $status
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustomResponse($status, $message)
    {
        return response()->json([
            'errors' => [
                'status_code' => $status,
                'message'     => $message,
            ],
        ], $status);
    }

    /**
     * Send this response when api user provide fields that doesn't exist in our application.
     *
     * @param $errors
     *
     * @return mixed
     */
    public function sendUnknownFieldResponse($errors)
    {
        return response()->json([
            'errors' => [
                'status_code'    => Response::HTTP_BAD_REQUEST,
                'unknown_fields' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when api user provide filter that doesn't exist in our application.
     *
     * @param $errors
     *
     * @return mixed
     */
    public function sendInvalidFilterResponse($errors)
    {
        return response()->json([
            'errors' => [
                'status_code'     => Response::HTTP_BAD_REQUEST,
                'invalid_filters' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when api user provide incorrect data type for the field.
     *
     * @param $errors
     *
     * @return mixed
     */
    public function sendInvalidFieldResponse($errors)
    {
        return response()->json([
            'errors' => [
                'status_code'    => Response::HTTP_BAD_REQUEST,
                'invalid_fields' => $errors,
            ],
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when a api user try access a resource that they don't belong.
     *
     * @return string
     */
    public function sendForbiddenResponse()
    {
        return response()->json([
            'errors' => [
                'status_code' => Response::HTTP_FORBIDDEN,
                'message'     => 'Forbidden',
            ],
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Send 404 not found response.
     *
     * @param string $message
     *
     * @return string
     */
    public function sendNotFoundResponse($message = '')
    {
        if ($message === '') {
            $message = 'The requested resource was not found';
        }

        return response()->json([
            'errors' => [
                'status_code' => Response::HTTP_NOT_FOUND,
                'message'     => $message,
            ],
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Send empty data response.
     *
     * @return string
     */
    public function sendEmptyDataResponse()
    {
        return response()->json(['data' => new \StdClass()]);
    }

    /**
     * Return collection response from the application.
     *
     * @param array|LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $collection
     * @param \Closure|TransformerAbstract                                        $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        //set empty data pagination
        if (empty($collection)) {
            $collection = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $resource   = new Collection($collection, $callback);
        }
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));

        $rootScope = $this->fractal->createData($resource);
        $this->fractal->setSerializer(new CustomDataArraySerializer());

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollectionWithOutPaginator($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal->createData($resource);
        $this->fractal->setSerializer(new CustomDataArraySerializer());

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Return single item response from the application.
     *
     * @param Model                        $item
     * @param \Closure|TransformerAbstract $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $this->fractal->setSerializer(new CustomDataArraySerializer());
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Return a json response from the application.
     *
     * @param array $array
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }

    /**
     * Return a json response from the application.
     *
     * @param array $array
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseDeleteSuccess()
    {
        return response(null, 204);
    }

    /**
     * @param $collection
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function customRespondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        //set empty data pagination
        if (empty($collection)) {
            $collection = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $resource   = new Collection($collection, $callback);
        }
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));

        $rootScope = $this->fractal->createData($resource);
        $this->fractal->setSerializer(new CustomDataArraySerializer());

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $item
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function customRespondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $this->fractal->setSerializer(new CustomDataArraySerializer());
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $statusCode
     * @param $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    protected function responseWithCustomMessage($statusCode, $message){
        return response([
            'status_code' => $statusCode,
            'message'     => $message,
        ], $statusCode);
    }

    /**
     * @param array $error
     * $error = [
     *      'name' => [
     *          'The name is require.'
     *      ]
     * ];
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithCustomValidationMessage($error = array()){
        $code = 422;
        $response = [
            'errors' => [
                'status_code' => $code,
                'message' => 'The given data was invalid.',
                'errors' => $error
            ]
        ];
        return response()->json($response, $code);
    }
}
