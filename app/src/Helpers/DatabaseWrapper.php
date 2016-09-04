<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 26/12/15
 * Time: 21:53
 */

namespace App\Helpers;

use PDO;
use Exception;

class DatabaseWrapper {

    public $_conn;
    private $queryLog   = [];
    private $logQueries = true;

    public function __construct( $db )
    {

        $pdo                = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'] . ";charset=" . $db['charset'], $db['user'], $db['pass']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->_conn        = $pdo;

    }

    public function exec($query, $params)
    {

        $start = microtime(true);

        if (strpos($query,'SELECT') !== false) {
            $result     = $this->executeQuery($query, $params)->fetchAll(\PDO::FETCH_ASSOC);
        }
        else {
            $result     =  $this->executeQuery($query, $params)->rowCount();
        }
        $this->logQuery($query, $params, $this->getElapsedTime( $start ));

        return $result;

    }

    public function executeQuery($query, $params) {

        $statment   = $this->_conn->prepare($query);
        $statment->execute($params);

        return $statment;

    }

    /**
     * Return first row based on query
     *
     * @param string $query SELECT statment
     * @param array $params Parameters for Query
     * @return array Array with Results
     * @access protected
     */
    public function fetchRow($query, $params = array()) {

        $start      = microtime(true);

        $result     = $this->executeQuery($query,$params)->fetch(\PDO::FETCH_ASSOC);

        $this->logQuery($query, $params, $this->getElapsedTime( $start ));

        return $result;

    }

    /**
     * Return all rows for a query
     *
     * @param string $query SELECT statment
     * @param array $params Parameters
     * @return array Array with Results
     * @access protected
     */
    public function fetchAll($query, $params) {

        $start      = microtime(true);

        $result     = $this->executeQuery($query,$params)->fetchAll(\PDO::FETCH_ASSOC);

        $this->logQuery($query, $params, $this->getElapsedTime( $start ));

        return $result;

    }

    public function fetchColumn($query, $params, $column = 0) {

        $start      = microtime(true);

        $result     = $this->executeQuery($query,$params)->fetchColumn($column);

        $this->logQuery($query, $params, $this->getElapsedTime( $start ));

        return $result;

    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as an associative array.
     *
     * @param string $statement The SQL query.
     * @param array  $params    The query parameters.
     * @param array  $types     The query parameter types.
     *
     * @return array
     */
    public function fetchAssoc($statement, array $params = array())
    {
        $start      = microtime(true);

        $result     = $this->executeQuery($statement, $params)->fetch(\PDO::FETCH_ASSOC);

        $this->logQuery($statement, $params, $this->getElapsedTime( $start ));

        return $result;
    }

    /**
     * Prepares and executes an SQL query and returns the first row of the result
     * as a numerically indexed array.
     *
     * @param string $statement The SQL query to be executed.
     * @param array  $params    The prepared statement params.
     * @param array  $types     The query parameter types.
     *
     * @return array
     */
    public function fetchArray($statement, array $params = array())
    {
        $start      = microtime(true);

        $result     = $this->executeQuery($statement, $params)->fetch(\PDO::FETCH_NUM);

        $this->logQuery($statement, $params, $this->getElapsedTime( $start ));

        return $result;
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression  The expression of the table on which to delete.
     * @param array  $identifier The deletion criteria. An associative array containing column-value pairs.
     * @param array  $types      The types of identifiers.
     *
     * @return integer The number of affected rows.
     *
     * @throws InvalidArgumentException
     */
    public function delete($tableExpression, array $identifier, array $types = array())
    {
        if (empty($identifier)) {
            throw InvalidArgumentException::fromEmptyCriteria();
        }

        $criteria = array();

        foreach (array_keys($identifier) as $columnName) {
            $criteria[] = $columnName . ' = ?';
        }

        return $this->executeUpdate(
            'DELETE FROM ' . $tableExpression . ' WHERE ' . implode(' AND ', $criteria),
            array_values($identifier),
            is_string(key($types)) ? $this->extractTypeValues($identifier, $types) : $types
        );
    }

    public function insert($table, $data)
    {
        $fields = $values = array();
        foreach ($data as $key => $value) {

            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $fields[] = '`'.str_replace("`", "``", $key).'`';
            $values[] = $this->_conn->quote($value);
        }

        $query = "INSERT INTO `$table` (".implode(',', $fields).") VALUES (".implode(',', $values).")";

        $this->_conn->exec($query);

        return $this->_conn->lastInsertId();
    }

    public function update($table, $data, $conditions = [])
    {
        if (!$data) {
            return false;
        }

        $updates = $wheres = array();
        foreach ($data as $key => $value) {

            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $updates[] = '`'.str_replace("`", "``", $key).'` = '.$this->_conn->quote($value);
        }

        foreach ($conditions as $key => $value) {
            $wheres[] = '`'.str_replace("`", "``", $key).'` = '.$this->_conn->quote($value);
        }

        $table = '`'.str_replace("`", "``", $table).'`';

        $query = "UPDATE $table SET ".implode(', ', $updates);

        if (count($wheres) > 0) {
            $query .= " WHERE ".implode(' AND ',$wheres);
        }

        $this->_conn->exec($query);

        return true;
    }

    public function query($statement)
    {

        return $this->_conn->exec($statement);

    }

    /**
     * Fetches the SQLSTATE associated with the last database operation.
     *
     * @return integer The last error code.
     */
    public function errorCode()
    {
        return $this->_conn->errorCode();
    }

    /**
     * Fetches extended error information associated with the last database operation.
     *
     * @return array The last error information.
     */
    public function errorInfo()
    {
        $this->connect();

        return $this->_conn->errorInfo();
    }

    /**
     * Returns the ID of the last inserted row, or the last value from a sequence object,
     * depending on the underlying driver.
     *
     * Note: This method may not return a meaningful or consistent result across different drivers,
     * because the underlying database may not even support the notion of AUTO_INCREMENT/IDENTITY
     * columns or sequences.
     *
     * @param string|null $seqName Name of the sequence object from which the ID should be returned.
     *
     * @return string A string representation of the last inserted ID.
     */
    public function lastInsertId($seqName = null)
    {
        return $this->_conn->lastInsertId($seqName);
    }

    /**
     * Get the elapsed time since a given starting point.
     *
     * @param  int    $start
     * @return float
     */
    protected function getElapsedTime($start)
    {
        return round((microtime(true) - $start) * 1000, 2);
    }

    public function logQuery($query, $params, $time)
    {
        if ($this->logQueries) {
            $this->queryLog[] = compact('query', 'params', 'time');
        }
    }

    public function getQueryLog() {
        return $this->queryLog;
    }


}