Feature: Presentation Form
	In order to enter information about a presentation
	As a system user
	I need to imput the information in a form
	
	Background:
		Given the following areas exist:
			| _id 	| name 			| 
			| 1		| NY-NYC		|
			| 2		| TX-Dallas		|
			| 3		| NY-Rochester	|
			| 4		| NY-Buffalo	|
			
		And the following schools exist:
			| _id	| name					| area_id	|
			| 1		| Albany Medical School | 1			|
			| 2		| Rochester School		| 3			|
			| 3		| Buffalo School		| 4			|
			| 4		| Texas School			| 2			|
			
		And the following members exist:
			| _id	| fname		| lname	| fullname		|
			| 1		| Member	| User	| Member User	|
			
		And the following users exist:
			| _id	| email					| pw		| member_id |
			| muser | member.user@email.com | abc123	| 1			|
		
		And I am logged in with username "muser" and password "abc123"
		
	Scenario: Only show schools in the same area as the user
		Given the member with id "1" is related to:
			| type 		| area_id 	|
			| presenter | 1			|
			
		When I go to "/presentation/index/new"
		
		Then the "location" dropdown "should" contain:
			| value	| label		|
			| 1		| Albany Medical School	|
			
		Then the "location" dropdown "should not" contain:
			| value	| label				|
			| 2		| Rochester School	|
			| 3		| Buffalo School	|
			| 4		| Texas School		|
		
	Scenario: Show schools where the user has a different role in different areas
		Given the member with id "1" is related to:
			| type 			| area_id 	|
			| presenter		| 1			|
			| facilitator 	| 3			|
			| coordinator 	| 4			|
		
		When I go to "/presentation/index/new"
		
		Then the "location" dropdown "should not" contain:
			| value	| label			|
			| 4		| Texas School	|
			
		Then the "location" dropdown "should" contain:
			| value	| label					|
			| 2		| Rochester School		|
			| 3		| Buffalo School		|
			| 1		| Albany Medical School	|
