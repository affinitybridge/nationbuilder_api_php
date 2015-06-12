<?php

namespace Affinitybridge\NationBuilder;

class Validator {
  // scalar values
  const INT = 'int';
  const STRING = 'string';
  const BOOLEAN = 'boolean';

  // special strings
  const ISO_DATE = 'iso_date';
  const ISO_TIMESTAMP = 'iso_timestamp';
  const URL = 'url';
  const EMAIL = 'email';
  const PHONE = 'phone';
  const DISTRICT = 'district';

  // complex values
  const ABBR_PERSON = 'abbr_person';
  const ADDRESS = 'address';
  const ARRAY_OF_STRINGS = 'array_of_strings';

  static public function getInvalidFields(array $data, array $fieldDefinitions) {
    $invalidFields = [];
    foreach ($fieldDefinitions as $fieldName => $fieldDefinition) {
      list($fieldType, $fieldDescription) = $fieldDefinition;
      if (isset($data[$fieldName]) && ! static::isSingleFieldValid($data[$fieldName], $fieldType)) {
        $invalidFields[$fieldName] = 'Invalid data for the "' . $fieldName . '" field. Expected type is "' . $fieldType . '". Field description is: ' . $fieldDescription;
      }
    }
    return $invalidFields;
  }

  static public function normalize(array $data, array $fieldDefinitions) {
    $normalized = [];
    foreach ($fieldDefinitions as $jsonPointer => $fieldDefinition) {
      list($fieldType, $fieldDescription) = $fieldDefinition;
      $value = static::getByJsonPointer($data, $jsonPointer);
      if (!is_null($value) && static::isSingleFieldValid($value, $fieldType)) {
        $normalized[$jsonPointer] = static::normalizeSingleField($value, $fieldType);
      }
    }
    return $normalized;
  }

  static protected function getByJsonPointer(array $data, $jsonPointer) {
    if (isset($data[$jsonPointer])) {
      return $data[$jsonPointer];
    }

    $jsonPointer = trim($jsonPointer, '/');
    if (isset($data[$jsonPointer])) {
      return $data[$jsonPointer];
    }

    $jsonPointerParts = explode('/', trim($jsonPointer, '/'));
    $referenced = & $data;
    foreach ($jsonPointerParts as $jsonPointerPart) {
      if (isset($referenced[$jsonPointerPart])) {
        $referenced = & $referenced[$jsonPointerPart];
      }
      else {
        return NULL;
      }
    }
    return $referenced;
  }

  static public function isSingleFieldValid($data, $fieldType) {
    switch ($fieldType) {
      case static::INT:
        return $data == (int) $data;
      case static::STRING:
        return is_string($data);
      case static::BOOLEAN:
        return $data == (boolean) $data;
      case static::ISO_DATE:
        return 1 === preg_match('{^\d{4}-\d{2}-\d{2}$}', $data);
      case static::ISO_TIMESTAMP:
        return 1 === preg_match('{^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}$}', $data);
      case static::URL:
        return valid_url($data, TRUE); // TODO remove dependency o Drupal.
      case static::EMAIL:
        return valid_email_address($data, TRUE); // TODO remove dependency o Drupal.
      case static::PHONE:
        $only_digits = preg_replace('{\D}', '', $data);
        return 5 < strlen($only_digits);
      case static::DISTRICT:
        return is_string($data); // TODO alias to STRING.
      case static::ABBR_PERSON:
        return is_array($data) && isset($data['first_name'], $data['last_name']); // TODO more thorough validation.
      case static::ADDRESS:
        return is_array($data); // TODO more thorough validation.
      case static::ARRAY_OF_STRINGS:
        return is_array($data) && array_reduce($data, function ($carry, $item) {return $carry && is_string($item);}, TRUE);
      default:
        return FALSE;
    }
  }

  static public function normalizeSingleField($data, $fieldType) {
    switch ($fieldType) {
      case static::INT:
        return (int) $data;
      case static::STRING:
        return (string) $data;
      case static::BOOLEAN:
        return (boolean) $data;

      case static::ISO_DATE:
      case static::ISO_TIMESTAMP:
      case static::URL:
      case static::EMAIL:
      case static::PHONE:
      case static::DISTRICT:
        return (string) $data;

      case static::ABBR_PERSON: // TODO more thorough validation.
      case static::ADDRESS: // TODO more thorough validation.
      case static::ARRAY_OF_STRINGS:
        return (array) $data;

      default:
        return $data;
    }
  }

  /**
   * @see https://tools.ietf.org/html/rfc6901
   * TODO Handle the "-" syntax.
   */
  static public function inlineJsonPointer(array $data) {
    $inlined = [];
    foreach ($data as $jsonPointer => $value) {
      $jsonPointerParts = explode('/', trim($jsonPointer, '/'));
      $referenced = & $inlined;
      foreach ($jsonPointerParts as $jsonPointerPart) {
        if (!isset($referenced[$jsonPointerPart])) {
          $referenced[$jsonPointerPart] = [];
        }
        $referenced = & $referenced[$jsonPointerPart];
      }
      $referenced = $value;
    }
    return $inlined;
  }
}
