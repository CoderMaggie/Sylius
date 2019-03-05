@managing_promotions
Feature: Filtering promotions
    In order to browse promotions conveniently
    As an Administrator
    I want to filter promotions on the list

    Background:
        Given the store operates on a single channel in "United States"
        And the store has promotion "Christmas sale" with coupon "SANTA2018"
        And there is also a promotion "Basic promotion"
        And I am logged in as an administrator

    @todo @ui
    Scenario: Filtering promotions by coupon code
        When I browse promotions
        And I filter them by "SANTA2018" coupon code
        Then I should see a single promotion in the list
        And the "Christmas sale" promotion should exist in the registry
