Feature: Landing Page
    In order to access the sts system
    As an sts user
    I need a landing page to login
    
    Scenario Outline: Accessing the page landing page
        Given I am on <page>
        Then I should see "Login"
        And I should see "Email"
        And I should see "Password"
        And I should see "Welcome to the Survivors Teaching Students"
        
        Examples:
            | page      |
            | "/"       |
            | "login"   |
