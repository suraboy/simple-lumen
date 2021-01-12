<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\IndexTicketsRequest;
use App\Http\Requests\Tickets\ShowTicketsRequest;
use App\Http\Requests\Tickets\StoreTicketsRequest;
use App\Http\Requests\Tickets\UpdateTicketsRequest;
use App\Http\Requests\Tickets\DeleteTicketsRequest;
use App\Repositories\Tickets\TicketsRepository;
use App\Transformers\TicketsTransformer;
use App\Criterias\DateRangeCriteria;

/**
 * Class TicketController
 * @package App\Http\Controllers\V1
 */
class TicketController extends Controller
{
    /**
     * @var TicketsRepository
     */
    private $ticketsRepository;
    /**
     * @var TicketsTransformer
     */
    private $ticketsTransformer;

    /**
     * TicketController constructor.
     * @param TicketsRepository $ticketsRepository
     * @param TicketsTransformer $ticketsTransformer
     */
    public function __construct(TicketsRepository $ticketsRepository, TicketsTransformer $ticketsTransformer)
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->ticketsTransformer = $ticketsTransformer;
        parent::__construct();
    }


    /**
     * @param IndexTicketsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexTicketsRequest $request)
    {
        if ($request->created_start_at && $request->created_end_at) {
            #create start date & create end date
            $this->ticketsRepository->pushCriteria(new DateRangeCriteria($request->created_start_at, $request->created_end_at));
        }

        $model = $this->ticketsRepository->paginate();

        return $this->respondWithCollection($model, $this->ticketsTransformer);
    }

    /**
     * @param ShowTicketsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowTicketsRequest $request)
    {
        $model = $this->ticketsRepository->find($request->id);
        return $this->respondWithItem($model, $this->ticketsTransformer);
    }

    /**
     * @param StoreTicketsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTicketsRequest $request)
    {
        $model = $this->ticketsRepository->create($request->all());
        return $this->setStatusCode(201)->respondWithItem($model, $this->ticketsTransformer);
    }

    /**
     * @param UpdateTicketsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTicketsRequest $request)
    {
        $model = $this->ticketsRepository->update($request->all(), $request->id);
        return $this->respondWithItem($model, $this->ticketsTransformer);
    }

    /**
     * @param DeleteTicketsRequest $request
     * @return mixed
     */
    public function destroy(DeleteTicketsRequest $request)
    {
        $this->ticketsRepository->delete($request->id);
        return $this->respondNoContent();
    }
}
