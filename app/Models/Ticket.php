<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\HasTranslations;

/**
 * Class Ticket
 * @package App\Models
 */
class Ticket extends Model
{

    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'tickets';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    public $translatable = [
        'name'
    ];


    /**
     * @var array
     */
    protected $fillable = array(
        'name',
        'description',
        'tel',
        'status'
    );

    /**
     * @var array
     */
    protected $hidden = array();

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
