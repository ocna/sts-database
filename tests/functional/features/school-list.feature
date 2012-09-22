Feature: School-List
	In order to see all the schools in the system
	As an admin user
	I want to see a list of schools in the admin section
	
	Scenario: Login and view schools section
		Given I am logged in as user with role "admin"
		When I go to "/admin/school"
		Then I should see "Schools"
		
	Scenario Outline: Access denied for non admins
		Given I am logged in as user with role "<role>"
		When I go to "/admin/school"
		Then I should see "Access Denied"
		
		Examples:
			| role 			|
			| coordinator 	|
			| facilitator 	|