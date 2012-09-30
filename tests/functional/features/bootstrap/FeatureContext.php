<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../../../application/'));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(dirname(__FILE__) . '/../../../../vendor/'));
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH,
    VENDOR_PATH,
    get_include_path()
)));
require_once 'vendor/autoload.php';
use Behat\Behat\Context\ClosuredContextInterface, Behat\Behat\Context\TranslatedContextInterface, Behat\Behat\Context\BehatContext, Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode, Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use STS\Core;
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
/**
 * Features context.
 */

class FeatureContext extends MinkContext {
    protected $core;
    public function __construct(array $parameters) {
        $this->core = Core::getDefaultInstance();
    }
    /**
     * @Given /^I am logged out$/
     */
    public function iAmLoggedOut() {
        $this->visit('/session/logout');
    }
    /**
     * @Given /^I am logged in as the user "([^"]*)"$/
     */
    public function iAmLoggedInAsTheUser($username) {
        $availableUsers = array(
            'auser' => 'hambone',
            'fuser' => 'hambone',
            'cuser' => 'hambone'
        );
        if (!array_key_exists($username, $availableUsers)) {
            throw \InvalidArgumentException("The username \"$username\" is not available.");
        }
        $this->visit('/session/logout');
        $this->iAmOnHomepage();
        $this->fillField('userName', $username);
        $this->fillField('password', $availableUsers[$username]);
        $this->pressButton('submit');
    }
    /**
     * @Given /^I am logged in as user with role "([^"]*)"$/
     */
    public function iAmLoggedInAsUserWithRole($role) {
        $availableRoles = array(
            'admin' => 'auser',
            'coordinator' => 'cuser',
            'facilitator' => 'fuser'
        );
        if (!array_key_exists($role, $availableRoles)) {
            throw \InvalidArgumentException("The role \"$role\" is not available.");
        }
        $this->iAmLoggedInAsTheUser($availableRoles[$role]);
    }
    /**
     * @Then /^the "([^"]*)" dropdown should contain schools for all areas$/
     */
    public function theDropdownShouldContainSchoolsForAllAreas($dropdown) {
        $this->theDropdownShouldContainSchoolsAreasAssignedTo($dropdown, 'auser');
    }
    /**
     * @Then /^the "([^"]*)" dropdown should contain schools areas assigned to "([^"]*)"$/
     */
    public function theDropdownShouldContainSchoolsAreasAssignedTo($dropdown, $username) {
        $schoolFacade = $this->core->load('SchoolFacade');
        if ($username == 'auser') {
            $schools = $schoolFacade->getSchoolsForSpecification(null);
        } else {
            $userFacade = $this->core->load('UserFacade');
            $user = $userFacade->findUserById($username);
            $memberFacade = $this->core->load('MemberFacade');
            $schoolSpec = $memberFacade->getMemberSchoolSpecForId($user->getAssociatedMemberId());
            $schools = $schoolFacade->getSchoolsForSpecification($schoolSpec);
        }
        $page = $this->getSession()->getPage();
        $aArray = explode(" ", $page->findField($dropdown)->getText());
        $expectedString = '';
        
        foreach ($schools as $school) {
            $expectedString.= $school->getName();
        }
        $eArray = explode(" ", $expectedString);
        
        foreach ($eArray as $key => $value) {
            assertEquals($value, $aArray[$key], "Issue with $key: e:$value a:{$aArray[$key]}");
        }
    }
}
