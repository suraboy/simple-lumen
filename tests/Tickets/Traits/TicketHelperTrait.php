<?php

namespace Tests\Tickets\Traits;
use App\Models\Ticket;

/**
 * Class TicketHelperTrait.
 *
 * Tests helper for agreement.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
trait TicketHelperTrait
{
    protected function getTicketData()
    {
        return factory(Ticket::class)->make()->toArray();
    }
}
