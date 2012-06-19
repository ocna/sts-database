Feature: Login
    In order to access the system
    As an sts user
    I need to provide my credentials to login
    
    Scenario Outline: Generic valid user login
        Given I am on "/login"
        When I fill in "email" with <email>
        And I fill in "password" with <password>
        And I press "loginButton"
        Then I should see "Welcome"
        And I should see "Name"
        And I should see "Logout"
        
        Examples:
            | email                         | password  | name              |
            | member.user@email.com         | abc123    | Member User       |
            | facilitator.user@email.com    | abc123    | Facilitator User  |
            | coordinator.user@email.com    | abc123    | Coordinator User  |
            ! admin.user@email.com          | abc123    | Admin User        |
            
    Scenario Outline: Invalid login credentials
        Given I am on "/login"
        When I fill in "email" with <email>
        And I fill in "password" with <password>
        And I press "loginButton"
        Then I should see "Your email or password is invalid, please check them and try again. If you are having trouble logging in click here to reset your password."
        
        Examples:
            | email                         | password      |
            | member.user@email.com         |               |
            |                               | abc123        |
            |                               |               |
            | coordinator.user@email.com    | badpass       |
            ! not email                     | abc123        |