Feature: Landing Page
    In order to access the sts system
    As an sts user
    I need a landing page to login
    
    Scenario: Accessing the page landing page
        Given I am on homepage
        And I should see "Welcome to the Survivors Teaching Students online program management system"
        Then I should see "Login"
        And I should see "User Name"
        And I should see "Password"
        And I should see "Welcome to the Survivors Teaching Students"
