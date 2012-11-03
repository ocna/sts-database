Feature: Login
    In order to access the system
    As an sts user
    I need to provide my credentials to login
    
    Scenario Outline: Invalid login credentials
        Given I am on homepage
        When I fill in "userName" with "<userName>"
        And I fill in "password" with "<password>"
        And I press "submit"
        Then I should see "Your username or password is invalid, please check them and try again."
        
        Examples:
            | userName                      | password      |
            | muser         				| badpass		|
    
    Scenario: Generic valid user login
        Given I am on "/session/login"
        When I fill in "userName" with "muser"
        And I fill in "password" with "hambone"
        And I press "submit"
        Then I should see "Welcome to the home page of the STS database system."	
        And I should see "What would you like to do today?"
        And I should see "Logout"
        And I should see "Member User"
            
   
