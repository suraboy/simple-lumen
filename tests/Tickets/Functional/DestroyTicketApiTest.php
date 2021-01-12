<?php

use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\Tickets\Traits\TicketHelperTrait;
use App\Models\Ticket;

/**
 * Class DestroyBrandApiTest.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
class DestroyTicketApiTest extends TestCase
{
    use WithoutMiddleware;
    use TicketHelperTrait;

    /**
     *
     */
    public function testDestroyTicketApiSuccess()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $this->delete('v1/tickets/' . $ticket->id);

        $this->assertEquals(204, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testDestroyTicketApiNotFound()
    {
        $ticketID = 99;
        $this->delete('v1/tickets/' . $ticketID);
        $this->assertEquals(404, $this->response->getStatusCode());
    }
}
