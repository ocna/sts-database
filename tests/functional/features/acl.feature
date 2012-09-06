Feature: Acl
	In order to navigate to the parts of the system I may access
	As an sts user
	I want to see a menu that displays only those items I have permission to access
	
	Scenario Outline: Log in and view menu
		Given I am logged out 
		And I am on homepage
        When I fill in "userName" with "<userName>"
        And I fill in "password" with "<password>"
        And I press "submit"
        Then I <admin> see "Administration"
        And I <warning> see "You have logged in with Administrator priveleges, be carful"
        
        Examples:
            | userName                      | password      | admin 		| warning 		|
            | fuser         				| hambone		| should not 	| should not	|
            | cuser         				| hambone		| should not 	| should not	|
            | auser         				| hambone		| should 	 	| should 		|
            
	Scenario Outline: Browse to admin page:
		Given I am logged out
		And I am on homepage
		When I fill in "userName" with "<userName>"
        And I fill in "password" with "<password>"
        And I press "submit"
        And I go to "/admin"
		Then I <admin> see "Welcome to the administration section of the STS system. Please use caution when manipulating data."
		And I <denied> see "Access Denied"
		
		Examples:
            | userName                      | password      | admin 		| denied 		|
            | fuser         				| hambone		| should not 	| should 		|
            | cuser         				| hambone		| should not 	| should 		|
            | auser         				| hambone		| should 	 	| should not	|