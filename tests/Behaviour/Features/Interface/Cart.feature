@cart @javascript
Feature: Cart interface Test

  @cart-add-1
  Scenario: Add 1 product to cart
    Given I am on home page
    When I add "Article A" to my cart
    And I see my cart
    Then the cart contain 1 "Article A" product
    And I have 1 article on cart
    And the total amount is 10.00 Euro

  @cart-add-example
  Scenario Outline: Add 1 product to cart with table test
    Given I have an empty cart
    And I am on home page
    When I add <quantity> "<product name>" to my cart
    And I see my cart
    Then the cart contain <quantity> "<product name>" product
    And I have <quantity> article on cart
    And the total amount is <total amount> Euro

    Examples:
      | product name | quantity | total amount |
      | Article A    | 1        | 10.00        |
      | Article A    | 2        | 20.00        |
      | Article B    | 10       | 150.00       |

