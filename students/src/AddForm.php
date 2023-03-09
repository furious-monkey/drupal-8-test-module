<?php
/**
 * @file
 * Contains \Drupal\students\AddForm.
 */

namespace Drupal\students;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;

class AddForm extends FormBase {
  protected $id;

  function getFormId() {
    return 'students_add';
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $this->id = \Drupal::request()->get('id');
    $students = StudentsStorage::get($this->id);

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => ($students) ? $students->name : '',
    );
    $form['gender'] = array(
        '#type' => 'radios',
        '#title' => $this
            ->t('Gender'),
        '#default_value' => 0,
        '#options' => array(
            0 => $this
                ->t('Male'),
            1 => $this
                ->t('Female'),
        ),
    );
    $form['faculty_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Faculty number'),
      '#default_value' => ($students) ? $students->faculty_number : '',
    );
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => ($students) ? t('Edit') : t('Add'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
		/*Form validation rules here...*/
  }


  function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
		$gender = $form_state->getValue('gender');
    $faculty_number = $form_state->getValue('faculty_number');
    if (!empty($this->id)) {
      StudentsStorage::edit($this->id, SafeMarkup::checkPlain($name), SafeMarkup::checkPlain($gender), SafeMarkup::checkPlain($faculty_number));
      \Drupal::logger('students')->notice('@type: deleted %title.',
          array(
              '@type' => $this->id,
              '%title' => $this->id,
          ));

      drupal_set_faculty_number(t('Student has been edited'));
    }
    else {
      StudentsStorage::add(SafeMarkup::checkPlain($name), SafeMarkup::checkPlain($gender), SafeMarkup::checkPlain($faculty_number));
      \Drupal::logger('students')->notice('@type: deleted %title.',
          array(
              '@type' => $this->id,
              '%title' => $this->id,
          ));

      drupal_set_faculty_number(t('Student has been submitted'));
    }
    $form_state->setRedirect('students_list');
    return;
  }
}
