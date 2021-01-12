<?php

namespace App\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Carbon\Carbon;
/**
 * Class ScopeOutletCriteria
 * @package Prettus\Repository\Criteria
 */
class DateRangeCriteria implements CriteriaInterface
{

    /**
     * @var string
     */
    private $start_date;
    private $end_date;

    /**
     * OutletCriteria constructor.
     *
     * @param $start_date
     * @param $end_date
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereBetween('created_at', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)]);
    }
}
