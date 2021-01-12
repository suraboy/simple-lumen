<?php
namespace App\Transformers;

use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

/**
 * Class TicketsTransformer
 * @package App\Transformers
 */
class TicketsTransformer extends TransformerAbstract
{
    /**
     * @param Ticket $model
     * @return array
     */
    public function transform(Ticket $model)
    {
        $format            = [
            'id'            => $model->id,
            'name'          => $model->name,
            'description'   => $model->description,
            'tel'           => $model->tel,
            'status'        => $model->status,
            'created_at'    => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at'    => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
        return $format;
    }
}
