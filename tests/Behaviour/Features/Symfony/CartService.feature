@cart-service
Feature: Cart Service Test

  @cart-service-add-1
  Scenario: Add 1 product to cart
    Given I select the "art_1" product id
    When I add the selected product to cart
    Then the cart contain 1 "Article A" product
    And I have 1 article on cart
    And the total amount is 10.00 Euro

  @cart-service-add-3
  Scenario: Add 3 products (but 2 same) to cart
    Given I select the "art_1" product id
    And I select the "art_2" product id
    And I select the "art_2" product id
    When I add the selected product to cart
    Then the cart contain 1 "Article A" product
    And the cart contain 2 "Article B" product
    And I have 3 article on cart
    And the total amount is 40.00 Euro


  @cart-service-add-table
  Scenario: Test total of cart
    Given I select this product :
    | quantity | product id |
    | 2        | art_2      |
    | 1        | art_1      |
    | 4        | art_3      |
    When I add the selected product to cart
    Then the cart contain 1 "Article A" product
    And the cart contain 2 "Article B" product
    And the cart contain 4 "Article C" product
    And I have 7 article on cart
    And the total amount is 52.00 Euro
