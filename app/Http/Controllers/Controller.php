<?php

namespace App\Http\Controllers;

use App\Contracts\ResponseTrait;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNoContent()
    {
        return $this->responseDeleteSuccess();
    }

}
