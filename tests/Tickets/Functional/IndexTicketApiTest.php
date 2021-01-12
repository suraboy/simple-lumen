<?php

use App\Models\Ticket;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\Tickets\Traits\TicketHelperTrait;

/**
 * Class IndexTicketApiTest.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
class IndexTicketApiTest extends TestCase
{
    use WithoutMiddleware;
    use TicketHelperTrait;

    /**
     *
     */
    public function testIndexTicketApiSuccess()
    {
        $ticketData = $this->getTicketData();

        factory(Ticket::class)->create($ticketData);

        $this->get('v1/tickets');

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testIndexTicketApiWithQueryParamSuccess()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $queryParams = [
            'status' => $ticketData['status'],
            'created_start_at' => date('Y-m-d H:i:s',strtotime($ticket->created_at)),
            'created_end_at' => date('Y-m-d H:i:s',strtotime($ticket->created_at))
        ];

        $this->call('GET', 'v1/tickets', $queryParams);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testIndexTicketApiWithQueryParamUnprocessable()
    {
        $ticketData = $this->getTicketData();

        factory(Ticket::class)->create($ticketData);

        $queryParams = [
            'tel' => '121321321323123',
            'status' => 'active'
        ];

        $this->call('POST', 'v1/tickets', $queryParams);

        $this->assertEquals(422, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testEndpointApiNotFound()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $this->call('GET', 'v1/ticketss' . $ticket->id);

        $this->assertEquals(404, $this->response->getStatusCode());

    }
}
