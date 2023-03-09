<?php

namespace Drupal\students;

class StudentsStorage {

  static function getAll() {
    $result = db_query('SELECT * FROM {students}')->fetchAllAssoc('id');
    return $result;
  }

  static function exists($id) {
    return (bool) $this->get($id);
  }

  static function get($id) {
    $result = db_query('SELECT * FROM {students} WHERE id = :id', array(':id' => $id))->fetchAllAssoc('id');
    if ($result) {
      return $result[$id];
    }
    else {
      return FALSE;
    }
  }

  static function add($name, $gender, $faculty_number) {
    db_insert('students')->fields(array(
      'name' => $name,
      'gender' => $gender,
      'faculty_number' => $faculty_number,
    ))->execute();
  }

  static function edit($id, $name, $gender, $faculty_number) {
    db_update('students')->fields(array(
      'name' => $name,
      'gender' => $gender,
      'faculty_number' => $faculty_number,
    ))
    ->condition('id', $id)
    ->execute();
  }
  
  static function delete($id) {
    db_delete('students')->condition('id', $id)->execute();
  }
}
