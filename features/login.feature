Feature: Login
    In order to access the system
    As an sts user
    I need to provide my credentials to login
    
    Scenario Outline: Generic valid user login
        Given I am on "/login"
        When I fill in "userName" with "<userName>"
        And I fill in "password" with "<password>"
        And I press "submit"
        Then I should be on "/index/home"
        And I should see "Welcome"
        And I should see "<name>"
        And I should see "Logout"
        
        Examples:
            | userName                      | password  | name              |
            | muser					        | abc123    | Member User       |
            
    Scenario Outline: Invalid login credentials
        Given I am on "/index/login"
        When I fill in "userName" with "<userName>"
        And I fill in "password" with "<password>"
        And I press "submit"
        Then I should see "Your email or password is invalid, please check them and try again. If you are having trouble logging in click here to reset your password."
        
        Examples:
            | userName                      | password      |
            | muser         				| badpass		|
