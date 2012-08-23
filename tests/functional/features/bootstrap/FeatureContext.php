<?php
require_once 'vendor/autoload.php';
use Behat\Behat\Context\ClosuredContextInterface, Behat\Behat\Context\TranslatedContextInterface, Behat\Behat\Context\BehatContext, Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode, Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    public function __construct(array $parameters)
    {
        var_dump($parameters);
        die;
        
    }
    /**
     * @Given /^the following areas exist:$/
     */
    public function theFollowingAreasExist(TableNode $table)
    {
        throw new PendingException();
    }
    /**
     * @Given /^the following schools exist:$/
     */
    public function theFollowingSchoolsExist(TableNode $table)
    {
        throw new PendingException();
    }
    /**
     * @Given /^the following members exist:$/
     */
    public function theFollowingMembersExist(TableNode $table)
    {
        throw new PendingException();
    }
    /**
     * @Given /^the following users exist:$/
     */
    public function theFollowingUsersExist(TableNode $table)
    {
        throw new PendingException();
    }
    /**
     * @Given /^I am logged in with username "([^"]*)" and password "([^"]*)"$/
     */
    public function iAmLoggedInWithUsernameAndPassword($arg1, $arg2)
    {
        throw new PendingException();
    }
    /**
     * @Given /^the member with id "([^"]*)" is related to:$/
     */
    public function theMemberWithIdIsRelatedTo($arg1, TableNode $table)
    {
        throw new PendingException();
    }
    /**
     * @Then /^the "([^"]*)" dropdown "([^"]*)" contain:$/
     */
    public function theDropdownContain($arg1, $arg2, TableNode $table)
    {
        throw new PendingException();
    }
}
