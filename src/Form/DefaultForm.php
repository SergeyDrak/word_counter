<?php

namespace Drupal\word_counter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 */
class DefaultForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'word_counter.default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('word_counter.default');
    $form['enable_counting'] = [
      '#type' => 'radios',
      '#title' => $this->t('Enable or disable counting'),
      '#options' => ['enable' => $this->t('Enable'), 'turn' => $this->t('Disable')],
      '#default_value' => $config->get('word_counter.enable_counting'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $this->config('word_counter.default')
      ->set('word_counter.enable_counting', $form_state->getValue('enable_counting'))
      ->save();
    // Flush all persistent caches.
    // This is executed based on old/previously known information, which is
    // sufficient, since new extensions cannot have any primed caches yet.
    drupal_flush_all_caches();
  }
}
