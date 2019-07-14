<?php

namespace Drupal\leo_weather\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SearchForm extends FormBase {

    /**
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId() {
        return 'leo_weather_search_form';
    }

    /**
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state, $city = NULL) {


        $form['city'] = [
            '#type' => 'textfield',
            '#title' => $this->t('City Name'),
            '#default_value' => $city,
            '#description' => $this->t('Please enter the name of the city you like to know the current weather.'),
            '#required' => TRUE,
        ];


        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Search'),
        ];

        return $form;

    }


    /**
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        // Redirect to controller
        $form_state->setRedirect('leo_weather.weather', array(city => $form_state->getValue('city')));

    }
}