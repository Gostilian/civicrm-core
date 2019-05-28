<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2019                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 * Trait Custom Data trait.
 *
 * Trait for setting up custom data in tests.
 */
trait CRMTraits_Custom_CustomDataTrait {

  /**
   * Create a custom group with fields of multiple types.
   *
   * @param array $groupParams
   */
  public function createCustomGroupWithFieldsOfAllTypes($groupParams = []) {
    $this->createCustomGroup($groupParams);
    $this->ids['CustomField'] = $this->createCustomFieldsOfAllTypes();
  }

  /**
   * Create a custom group.
   *
   * @param array $params
   *
   * @return int
   */
  public function createCustomGroup($params = []) {
    $params = array_merge([
      'title' => 'Custom Group',
      'extends' => [$this->entity],
      'weight' => 5,
      'style' => 'Inline',
      'max_multiple' => 0,
    ], $params);
    $this->ids['CustomGroup'][$params['title']] = $this->callAPISuccess('CustomGroup', 'create', $params)['id'];
    return $this->ids['CustomGroup'][$params['title']];
  }

  /**
   * @return array
   */
  public function createCustomFieldsOfAllTypes() {
    $customGroupID = $this->ids['CustomGroup']['Custom Group'];
    $ids = [];
    $params = [
      'custom_group_id' => $customGroupID,
      'label' => 'Enter text here',
      'html_type' => 'Text',
      'data_type' => 'String',
      'default_value' => 'xyz',
      'weight' => 1,
      'is_required' => 1,
    ];

    $customField = $this->callAPISuccess('CustomField', 'create', $params);
    $ids['text'] = $customField['id'];

    $optionValue[] = [
      'label' => 'Red',
      'value' => 'R',
      'weight' => 1,
      'is_active' => 1,
    ];
    $optionValue[] = [
      'label' => 'Yellow',
      'value' => 'Y',
      'weight' => 2,
      'is_active' => 1,
    ];
    $optionValue[] = [
      'label' => 'Green',
      'value' => 'G',
      'weight' => 3,
      'is_active' => 1,
    ];

    $params = [
      'label' => 'Pick Color',
      'html_type' => 'Select',
      'data_type' => 'String',
      'weight' => 2,
      'is_required' => 1,
      'is_searchable' => 0,
      'is_active' => 1,
      'option_values' => $optionValue,
      'custom_group_id' => $customGroupID,
    ];

    $customField = $this->callAPISuccess('custom_field', 'create', $params);
    $ids['select_string'] = $customField['id'];

    $params = [
      'custom_group_id' => $customGroupID,
      'name' => 'test_date',
      'label' => 'test_date',
      'html_type' => 'Select Date',
      'data_type' => 'Date',
      'default_value' => '20090711',
      'weight' => 3,
      'time_format' => 1,
    ];

    $customField = $this->callAPISuccess('custom_field', 'create', $params);

    $ids['select_date'] = $customField['id'];
    $params = [
      'custom_group_id' => $customGroupID,
      'name' => 'test_link',
      'label' => 'test_link',
      'html_type' => 'Link',
      'data_type' => 'Link',
      'default_value' => 'http://civicrm.org',
      'weight' => 4,
      'is_required' => 1,
      'is_searchable' => 0,
      'is_active' => 1,
    ];

    $customField = $this->callAPISuccess('custom_field', 'create', $params);

    $ids['link'] = $customField['id'];
    $fileField = $this->customFieldCreate([
      'custom_group_id' => $customGroupID,
      'data_type' => 'File',
      'html_type' => 'File',
      'default_value' => '',
    ]);

    $ids['file'] = $fileField['id'];
    $ids['country'] = $this->customFieldCreate([
      'custom_group_id' => $customGroupID,
      'data_type' => 'Int',
      'html_type' => 'Select Country',
      'default_value' => '',
      'label' => 'Country',
      'option_type' => 0,
    ])['id'];

    return $ids;
  }

  /**
   * Get the custom field name for the relevant key.
   *
   * e.g returns 'custom_5' where 5 is the id of the field using the key.
   *
   * Generally keys map to data types.
   *
   * @param string $key
   *
   * @return string
   */
  protected function getCustomFieldName($key) {
    $linkField = 'custom_' . $this->ids['CustomField'][$key];
    return $linkField;
  }

}
