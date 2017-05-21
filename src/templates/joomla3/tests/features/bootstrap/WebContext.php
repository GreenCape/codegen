<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class WebContext implements Context
{
	/**
	 * Initializes context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the
	 * context constructor through behat.yml.
	 */
	public function __construct()
	{
	}

	/**
	 * @Given I am logged in as :arg1
	 */
	public function iAmLoggedInAs($arg1)
	{
		throw new PendingException();
	}

	/**
	 * @When I install the extension :arg1
	 */
	public function iInstallTheExtension($arg1)
	{
		throw new PendingException();
	}

	/**
	 * @Then I should see :arg1 in the :arg2 menu
	 */
	public function iShouldSeeInTheMenu($arg1, $arg2)
	{
		throw new PendingException();
	}
}
