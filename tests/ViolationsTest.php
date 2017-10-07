<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ViolationsTest extends TestCase
{
    public function testViolations()
    {
        $this->get('/violations/next/kkkkkkkkkkk');
        $this->assertResponseStatus(Response::HTTP_OK);

        $json = json_decode($this->response->content());

        echo'<pre>';print_r($json);echo'</pre>';exit;
    }
}
