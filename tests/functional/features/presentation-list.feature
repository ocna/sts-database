#presentation-list.feature
Feature: Presentation-List
    In order to see all the presentations entered
    As a user
    I want to view a list of the presentations
    
    Scenario Outline: Any system user can go to view the presentations list
        Given I am logged in as the user "<user>"
        When I go to "/presentation/index"
        Then I should see "Presentations"

        Examples:
            | user  |
            | cuser |
            | fuser |
            | auser |

    Scenario Outline: A non admin user will see a message that the functionality is not available yet
        Given I am logged in as the user "<user>"
        When I go to "/presentation/index"
        Then I should see "This feature is in progress and will be available soon."

        Examples:
            | user  |
            | cuser |
            | fuser |