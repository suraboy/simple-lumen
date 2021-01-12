<?php

namespace App\Repositories\Tickets;

use App\Contracts\Repository;
use App\Criterias\RequestCriteria;
use App\Models\Ticket;
/**
 * Class AgreementRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TicketsRepositoryEloquent extends Repository implements TicketsRepository
{
    protected $fieldSearchable = [
        'name' => '=',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Ticket::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
