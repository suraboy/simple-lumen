<?php

use Laravel\Lumen\Testing\WithoutMiddleware;
use Tests\Tickets\Traits\TicketHelperTrait;

/**
 * Class StoreTicketApiTest.
 *
 * @author Sirichai Janpan <sirichai.jan@ascendcorp.com>
 */
class StoreTicketApiTest extends TestCase
{
    use WithoutMiddleware;
    use TicketHelperTrait;

    /**
     *
     */
    public function testStoreTicketApiSuccess()
    {
        $ticketData = $this->getTicketData();

        $this->post('v1/tickets',$ticketData);

        $this->assertEquals(201, $this->response->getStatusCode());
    }

    /**
     *
     */
    public function testStoreTicketApiWithQueryParamUnprocessable()
    {
        $queryParams = [
            'status' => 'active',
            'name' => ""
        ];

        $this->post('v1/tickets', $queryParams);

        $this->assertEquals(422, $this->response->getStatusCode());
    }
}
