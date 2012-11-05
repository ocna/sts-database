#presentation-new.feature
Feature: Presentation-New
    In order to add a new presentation
    As a user
    I want a form to enter new presentations
    
    Scenario: An admin can see all the schools in the system in the location dropdown
        Given I am logged in as the user "auser"
        When I go to "/presentation/index/new"
        Then the "location" dropdown should contain schools for all areas
        
    Scenario Outline: A non admin can see only the schools for thier areas
        Given I am logged in as the user "<user>"
        When I go to "/presentation/index/new"
        Then the "location" dropdown should contain schools areas assigned to "<user>"
        
        Examples:
            | user  |
            | cuser |
            | fuser |