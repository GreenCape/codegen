<?template scope="application"?>
Feature: Installation
  In order to use the component
  As an administrator
  I need to be able to install the component

  Scenario: Installing component
    Given I am logged in as "administrator"
    When I install the extension "pkg_{{ project.name | file }}"
    Then I should see "{{ project.title }}" in the "Components" menu
