<?php
use App\Models\Ticket;
use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\Tickets\Traits\TicketHelperTrait;

/**
 * Class UpdateTicketApiTest.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
class UpdateTicketApiTest extends TestCase
{
    use WithoutMiddleware;
    use TicketHelperTrait;

    /**
     *
     */
    public function testUpdateTicketApiSuccess()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $queryParams = [
            'tel' => '0972418912',
            'status' => 'rejected'
        ];

        $this->put('v1/tickets/'.$ticket->id,$queryParams);

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testUpdateTicketApiWithQueryParamUnprocessable()
    {
        $ticketData = $this->getTicketData();

        $ticket = factory(Ticket::class)->create($ticketData);

        $queryParams = [
            'tel' => '0972418912121',
            'status' => 'rejected1'
        ];

        $this->put('v1/tickets/'.$ticket->id,$queryParams);


        $this->assertEquals(422, $this->response->getStatusCode());
    }
}
