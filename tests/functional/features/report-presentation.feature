Feature: Presentations Report
    In order to learn about the results of presentaitons
    As an admin
    I need a report on presentation data
    
    Scenario: Will see menu item for reports
        Given I am logged in as user with role "admin"
        Then I should see "Reports"
        And I should see "Presentation Summary Report"

    Scenario: Navigating to the presentation report page
        Given I am logged in as user with role "admin"
        When I follow "Presentation Summary Report"
        Then the url should match "/admin/report/presentation"
        And I should see "Presentation Summary Report"

    Scenario: Navigating to the presentation report page
        Given I am logged in as user with role "admin"
        When I go to "/admin/report/presentation"
        Then I should see "Presentation Summary Report"

    Scenario Outline: Access denied for non admins
        Given I am logged in as user with role "<role>"
        When I go to "/admin/report/presentation"
        Then I should see "Access Denied"
        
        Examples:
            | role          |
            | coordinator   |
            | facilitator   |
