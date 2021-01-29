<?php

namespace Drupal\Tests\word_counter\Functional;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests for the Word counter module.
 * @group word_counter
 */
class WordCounterTest extends BrowserTestBase
{
  /**
   * Modules to install
   *
   * @var array
   */
  public static $modules = array('node', 'word_counter');

  // A simple user
  private $user;


  //fix schema
  protected $strictConfigSchema = FALSE;


  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * @throws EntityStorageException
   */
  public function setUp()
  {
    parent::setUp();
    $this->user = $this->DrupalCreateUser(array(
      'administer site configuration' => TRUE,
      'access content' => TRUE
    ));
  }

  /**
   * Tests that '/admin/config/content/word_count/settings' returns a 200 OK response.
   */
  public function testRouterURLIsAccessible()
  {
    $this->drupalLogin($this->user);
    $this->drupalGet('/admin/config/content/word_count/settings');
    $this->assertResponse(200);
  }

  /**
   * Test that the options we expect in the form are present.
   */
  public function testFormFieldOptionsExist()
  {
    $this->drupalLogin($this->user);
    $this->drupalGet('/admin/config/content/word_count/settings');
    $this->assertResponse(200);

    // check that our radios field displays on the form.
    $this->assertFieldByName('enable_counting');

  }

  /**
   * Test form submit
   */
  public function testFormSubmit()
  {
    // Login.
    $this->drupalLogin($this->user);

    // Access config page.
    $this->drupalGet('/admin/config/content/word_count/settings');
    //$this->assertSession()->statusCodeEquals(200);
    $this->assertResponse(200);

    //Test the form elements exist and have defaults.
    $config = $this->config('word_counter.default');

    $this->assertSession()->fieldValueEquals(
      'enable_counting',
      $config->get('word_counter.enable_counting')
    );
    // Test form submission.
    $this->drupalPostForm(NULL, array(
      'enable_counting' => 'enable',
    ), t('Save configuration'));
    $this->assertSession()->pageTextContains('The configuration options have been saved.');

  }
}
