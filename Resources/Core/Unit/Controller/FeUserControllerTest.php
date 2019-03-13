<?php
namespace CAG\CagTests\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Ruta <email@thomasruta.de>
 * @author Jochen Rieger <j.rieger@connecta.ag>
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

class FeUserControllerTest extends UnitTestCase
{
    /**
     * @var \CAG\CagTests\Controller\FeUserController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\CAG\CagTests\Controller\FeUserController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }


    /**
     * @test
     */
    public function loginActionTestFeUserLogin()
    {

    }
}
