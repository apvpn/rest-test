<?php

class an_db {

    var $_sql = '';

    /** @var Internal variable to hold the connector resource */
    var $_resource = '';

    /** @var Internal variable to hold the query result */
    var $_result = '';

    /** @var Internal variable to hold the query result */
    var $_insertId = '';

    //$host = '';
    /**
     * Database object constructor
     * @param string Database host
     * @param string Database user name
     * @param string Database user password
     * @param string Database name
     * @param string Common prefix for all tables
     * @param boolean If true and there is an error, go offline
     */
    function __construct($db_host, $db_username, $db_password, $db_name) {
        $this->_resource = mysqli_connect($db_host, $db_username, $db_password, $db_name);

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    /**
     * Execute the query
     * @return mixed A database resource if successful, FALSE if not.
     */
    function query($sql) {
        $result = mysqli_query($this->_resource, $sql) or die('Error while executing query: ' . mysqli_error($this->_resource));
        return $result;
    }

    /**
     * Execute the query for insert
     * @return auto increment id
     */
    function insert($table, $dbFields, $debug = false) {

        $field = array();
        $value = array();

        foreach ($dbFields as $k => $v) {
            $v = addslashes(stripslashes($v));

            $field[] = $k;
            $value[] = $v;
        }

        $f = implode('`,`', $field);
        $val = implode("','", $value);

        $insertSql = "INSERT INTO `$table` (`$f`) VALUES ('$val')";
        if ($debug)
            die($insertSql);

        $result = $this->query($insertSql);

        $this->_insertId = mysqli_insert_id();
        if (!mysqli_error($this->_resource)) {
            return $this->_insertId;
        } else {
            echo mysqli_error($this->_resource);
        }
    }

    //added by ashish patel (apt)
    function insertMultiple($table = null, $dbFields = array(), $dbValues = array(), $debug = false) {

        $value = array();

        foreach ($dbValues as $k1 => $v1) {
            $val = implode("','", $v1);
            $value[] = "('$val')";
        }
        $val = implode(",", $value);

        $f = implode('`,`', $dbFields);

        $insertMultipleSql = "INSERT INTO `$table` (`$f`) VALUES $val ";

        if ($debug)
            die($insertMultipleSql);

        $result = $this->query($insertMultipleSql);

        $this->_insertId = mysqli_insert_id();
        if (!mysqli_error($this->_resource)) {
            return $this->_insertId;
        } else {
            echo mysqli_error($this->_resource);
        }
    }

    /**
     * Execute the query for update
     * @return true for success
     */
    function update($table, $dbFields, $strWhere, $debug = false) {
        $updateSql = "UPDATE $table SET ";
        $i = 0;
        foreach ($dbFields as $k => $v) {

            //$v = addslashes(stripslashes($v));

            if ($i == 0) {
                $updateSql .= " $k = '$v' ";
            } else {
                $updateSql .= ", $k = '$v' ";
            }
            $i++;
        }

        $updateSql .= " WHERE $strWhere";

        if ($debug)
            echo $updateSql;

        $result = $this->query($updateSql);

        return $result;
    }

    /**
     * Execute the query for sekect
     * @return array contains result
     */
    function select($debug = false, $vars = "*", $table = null, $where = "", $orderBy = "", $groupBy = "", $resultType = MYSQLI_ASSOC) {

        if ($vars != "*") {
            if (is_array($vars)) {
                $vars = implode(",", $vars);
            }
        }

        $selectSql = "SELECT " . $vars . " FROM " . $table . " WHERE 1 = 1 AND " . $where . " " . $groupBy . " " . $orderBy;

        if ($debug) {
            echo $selectSql;
            exit;
        }

        $resource = $this->query($selectSql);
//        $resource = mysqli_query($this->_resource, $selectSql) or die('Error while executing query: ' . mysqli_error($this->_resource));

        $result = array();

        while ($row = mysqli_fetch_array($resource, $resultType)) {
            $result[] = $row;
        }
        return $result;
    }

    function get_results($selectSql, $data_type = null, $resultType = MYSQLI_ASSOC) {
        $resource = $this->query($selectSql);
        $result = mysqli_fetch_all($resource, $resultType);
//        $result = array();
        /* while ($row = mysqli_fetch_array($resource, $resultType)) {
          $result[] = $row;
          } */
//pr($result);
        if ($data_type == 'json') {
            return json_encode($result);
        } else {
            return $result;
        }
    }

    function get_row($selectSql, $data_type = null, $resultType = MYSQLI_ASSOC) {

        $resource = $this->query($selectSql);
//        $resource = mysqli_query($this->_resource, $selectSql) or die('Error while executing query: ' . mysqli_error($this->_resource));

        $result = array();
        while ($row = mysqli_fetch_array($resource, $resultType)) {
            $result = $row;
        }
        if ($data_type == 'json') {
            return json_encode($result);
        } else {
            return $result;
        }
    }

    /**
     * Execute the query for delete
     * @return true
     */
    function delete($table, $where) {
        $deleteSql = "DELETE FROM $table WHERE $where ";
        $resource = mysqli_query($this->_resource, $deleteSql) or die('Error while executing query: ' . mysqli_error($this->_resource));
        $this->query($deleteSql);
//        $resource = mysqli_query($this->_resource, $sql) or die('Error while executing query: ' . mysqli_error($this->_resource));

        return $resource;
    }

    function getNextId($table) {
        $sql = "SHOW TABLE STATUS LIKE '" . $table . "'";
        $resource = $this->query($sql);
//        $resource = mysqli_query($this->_resource, $sql) or die('Error while executing query: ' . mysqli_error($this->_resource));

        $row = mysqli_fetch_array($resource);
        $nextId = $row['Auto_increment'];
        return $nextId;
    }

    function getName($id, $idValue, $table, $name) {
        $sqlSelect = "SELECT `" . $name . "`
                         FROM `" . $table . "`
                         WHERE `" . $id . "` = '" . $idValue . "'";
        //    echo $sqlSelect;

        $resource = $this->query($sqlSelect);
        $nameValue = "";
        while ($row = mysqli_fetch_array($resource)) {
            $nameValue = $row[$name];
        }

        if ($nameValue)
            return $nameValue;
        else
            return '';
    }

    //added by ashish patel (apt)
    function checkFieldValueExist($field_name = null, $field_value = null, $table_name = null, $condition = "", $debug = FALSE) {
        if ($field_name != null && $table_name != null) {
            $sqlSelect = "SELECT *
                         FROM `" . $table_name . "`
                         WHERE `" . $field_name . "` = '" . $field_value . "'  $condition ";
            if ($debug)
                die($sqlSelect);

            return $this->get_row($sqlSelect, "");
        } else {
            die("please pass valid field name of table and table name.");
        }
    }

    //added by ashish patel (apt)
    function getRandomRows($fields_str = "*", $table_name = null, $where = null, $num_of_random_records = 10, $debug = FALSE) {

        $random_rows_sql = "SELECT $fields_str FROM $table_name $where ORDER BY RAND() LIMIT $num_of_random_records";
        if ($debug)
            die($random_rows_sql);

        return $this->get_results($random_rows_sql);
    }

    //added by ashish patel (apt)
    function get_comma_seperated_field_value($field_name = null, $table_name = null, $where = null, $debug = false) {
        //prepare comma seperated string of hashtags
        $sql = "SELECT GROUP_CONCAT($field_name) AS `comma_seperated`  FROM " . $table_name . " $where ";
        if ($debug)
            die($sql);

        $rs = $this->get_row($sql);
        return $rs["comma_seperated"];
    }

    function update_calulated_value_with_field_of_table($calculation_value = null, $operation = "substract", $field_name = null, $table_name = null, $condition = null, $debug = false) {
        if (!is_null($calculation_value) && is_numeric($calculation_value)) :

            switch (strtolower($operation)):
                case "add":
                    $update_post_query = "UPDATE " . $table_name . " SET
					$field_name = $field_name + $calculation_value  $condition ";
                    break;
                case "substract":
                    $update_post_query = "UPDATE " . $table_name . " SET
					$field_name = $field_name - $calculation_value $condition ";
                    break;
                case "multiply":
                    $update_post_query = "UPDATE " . $table_name . " SET
					$field_name = $field_name * $calculation_value $condition ";
                    break;
                default:
                    break;
            endswitch;

            if ($debug)
                die($update_post_query);

            return $this->query($update_post_query);
        endif;

        return false;
    }

    /**
     * Called for taking last insert id
     * @return last inserted id
     */
    function getInsertId() {
        echo $this->_insertId;
    }

    /**
     * Execute the query for num of row count
     * @return number of rows for result
     */
    function numRows($sql) {
//        $_result = $this->query($sql);
        $_result = mysqli_query($this->_resource, $sql) or die('Error while executing query: ' . mysqli_error($this->_resource));

        $results = mysqli_num_rows($_result);
        mysqli_free_result($_result);
        return $results;
    }

    /**
     * Clode db connection
     */
    function dbClose() {
        mysqli_close($this->_resource);
    }

    /**
     * fetch the mysql result resource
     * @return fetched array
     */
    function fetchArray($rs) {
        return @mysqli_fetch_array($rs);
    }

    function str_to_time($date) {
        $date = date_create_from_format('m-d-Y', $date);
        $date = date_format($date, 'Y-m-d');
        $d3 = strtotime($date);
        return $d3;
    }

}

?>