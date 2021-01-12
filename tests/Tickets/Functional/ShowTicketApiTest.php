<?php

use App\Models\Ticket;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\Tickets\Traits\TicketHelperTrait;

/**
 * Class ShowTicketApiTest.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
class ShowTicketApiTest extends TestCase
{
    use WithoutMiddleware;
    use TicketHelperTrait;

    /**
     *
     */
    public function testShowTicketApiSuccess()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $this->get('v1/tickets/'.$ticket->id);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     *
     */

    public function testShowTicketApiNotFound()
    {
        $this->get('v1/tickets/999');

        $this->assertEquals(404, $this->response->getStatusCode());
    }

}
