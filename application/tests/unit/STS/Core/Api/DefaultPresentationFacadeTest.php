<?php
use STS\TestUtilities\PresentationTestCase;
use STS\Core\Api\DefaultPresentationFacade;

class DefaultPresentationFacadeTest extends PresentationTestCase
{
    const ADMIN_USER_ID = 'auser';
    /**
     * @test
     */
    public function itShouldReturnAllPresentationsForAnAdminUser()
    {
        $this->markTestIncomplete();
        $facade = $this->getFacadeWithMockedDeps();
        $presentations = $facade->getPresentationsForUserId(self::ADMIN_USER_ID);
        $this->assertTrue(is_array($presentations));
        $this->assertCount(3, $presentations);
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentations[0]);
    }

    private function getFacadeWithMockedDeps()
    {
        $presentationRepository = Mockery::mock('STS\Core\Presentation\MongoPresentationRepository');
        $facade = new DefaultPresentationFacade($presentationRepository);

        return $facade;
    }
}
