Feature:

  Scenario: Test scenario 1 - success
    When I do something
    #! firstSuccess
    Then something is done
    And response should be JSON:
        """
        {
            "test": true
        }
        """

  Scenario: Test scenario 2 - success
    When I do something
    #! secondSuccess
    Then something is done
        """
        {
            "test": false
        }
        """
