<?php

class UpdateDatabaseOptions {

    private $tableName;
    private $db;

    public function __construct($tableName) {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $this->db->$tableName;
        if ($this->tableName == '')
            $this->tableName = $this->db->prefix . $tableName;
    }

    private function getSelectValueString($selectValuesArray) {
        $selectValues = $selectValuesArray[0];
        if (count($selectValuesArray) > 1)
            $selectValues = implode(",", $selectValuesArray);
        return $selectValues;
    }

    private function getConditionString($conditionsArray) {
        $i = 0;
        foreach ($conditionsArray as $column => $columnValue) {
            if (is_string($columnValue))
                $conditions[$i++] = "$column='$columnValue'";
            else if (is_integer($columnValue))
                $conditions[$i++] = "$column=$columnValue";
        }
        $conditionString = $conditions[0];
        if (count($conditionsArray) > 1)
            $conditionString = implode(" and ", $conditions);
        return $conditionString;
    }

    protected function select($selectValues, $conditions) {
        if ($conditions != '')
            $sql = $this->db->get_results("SELECT $selectValues FROM $this->tableName WHERE $conditions", ARRAY_A);
        else
            $sql = $this->db->get_results("SELECT $selectValues FROM $this->tableName", ARRAY_A);
        return $sql;
    }

    public function selectValue($selectValuesArray, $conditionsArray) {
        $selectValues = $this->getSelectValueString($selectValuesArray);
        $conditions = '';
        if ($conditionsArray != '')
            $conditions = $this->getConditionString($conditionsArray);
        $optionValue = $this->select($selectValues, $conditions);
        return $optionValue;
    }

    protected function checkIfRowExistsInOptions($selectValuesArray, $conditionsArray) {
        $option = $this->selectValue($selectValuesArray, $conditionsArray);
        if (count($option) == 0)
            return false;
        else
            return true;
    }

    protected function updateDatabase($values, $conditions, $formatSpecifier, $conditionFormatSpecifier) {
        if ($this->checkIfRowExistsInOptions($values, $conditions) !== FALSE)
            $this->updateRow($values, $conditions, $formatSpecifier, $conditionFormatSpecifier);
        else
            $this->insertRow($values, $formatSpecifier);
    }

    public function insertRow($valueArray, $formatSpecifier) {
        if ($this->db->insert(
                        $this->tableName, $valueArray, $formatSpecifier
                ) !== FALSE)
            return TRUE;
        else
            return FALSE;
    }

    public function updateRow($values, $conditions, $formatSpecifier, $conditionFormatSpecifier) {
        if ($this->db->update(
                        $this->tableName, $values, $conditions, $formatSpecifier, $conditionFormatSpecifier
                ) !== FALSE)
            return TRUE;
        else
            return FALSE;
    }

    public function deleteRow($conditions, $conditionFormatSpecifier) {
        if ($this->db->delete(
                        $this->tableName, $conditions, $conditionFormatSpecifier
                ) !== FALSE)
            return TRUE;
        else
            return FALSE;
    }

}
