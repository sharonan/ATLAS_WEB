<?php
class BaseDAO
{
		protected $_model;
        /**
        * Connection to MySQL.
        *
        * @var string
        */
        var $link;

        /**
        * Holds the most recent connection.
        *
        * @var string
        */
        var $recent_link = null;

        /**
        * Holds the contents of the most recent SQL query.
        *
        * @var string
        */
        var $sql = '';

        /**
        * Holds the number of queries executed.
        *
        * @var integer
        */
        var $query_count = 0;

        /**
        * The text of the most recent database error message.
        *
        * @var string
        */
        var $error = '';

        /**
        * The error number of the most recent database error message.
        *
        * @var integer
        */
        var $errno = '';

        /**
        * Do we currently have a lock in place?
        *
        * @var boolean
        */
        var $is_locked = false;

        /**
        * Show errors? If set to true, the error message/sql is displayed.
        *
        * @var boolean
        */
        var $show_errors = false;

        /**
        * Constructor. Initializes a database connection and selects our database based on constants.
        *
        * @return boolean
        */
        function __construct()
        {
		   //RETURN FALSE to break DB requirement
			return false;
                $this->link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);

                if ($this->link)
                {
                        if (@mysql_select_db(DB_NAME, $this->link))
                        {
                                $this->recent_link =& $this->link;
                                return $this->link;
                        }
                }
                // If we couldn't connect or select the db...
                $this->raise_error("Could not select and/or connect to database: ".DB_NAME);
				
				$this->_model = get_class($this);
        }
		
		 /**
        * Connect with a different database than our constants define.
        *
        * @return boolean
        */
        function pickDatabase($db_host, $db_user,$db_pass, $db_name)
        {
                $this->link = @mysql_connect($db_host, $db_user, $db_pass);

                if ($this->link)
                {
                        if (@mysql_select_db($db_name, $this->link))
                        {
                                $this->recent_link =& $this->link;
                                return $this->link;
                        }
                }
                // If we couldn't connect or select the db...
                $this->raise_error("Could not select and/or connect to database: ".$db_name);
        }

        /**
        * Executes a sql query. If optional $only_first is set to true, it will
        * return the first row of the result as an array.
        *
        * @param  string  Query to run
        * @param  bool    Return only the first row, as an array?
        * @return mixed
        */
        function query($sql, $only_first = false)
        {
                $this->recent_link =& $this->link;
                $this->sql =& $sql;
                $result = @mysql_query($sql, $this->link);

                $this->query_count++;

                if ($only_first)
                {
                        $return = $this->fetch_array($result);
                        $this->free_result($result);
                        return $return;
                }
                return $result;
        }

        /**
        * Fetches a row from a query result and returns the values from that row as an array.
        *
        * @param  string  The query result we are dealing with.
        * @return array
        */
        function fetch_array($result)
        {
                return @mysql_fetch_assoc($result);
        }

        /**
        * Returns the number of rows in a result set.
        *
        * @param  string  The query result we are dealing with.
        * @return integer
        */
        function num_rows($result)
        {
                return @mysql_num_rows($result);
        }

        /**
        * Retuns the number of rows affected by the most recent query
        *
        * @return integer
        */
        function affected_rows()
        {
                return @mysql_affected_rows($this->recent_link);
        }

        /**
        * Returns the number of queries executed.
        *
        * @param  none
        * @return integer
        */
        function num_queries()
        {
                return $this->query_count;
        }

        /**
        * Lock database tables
        *
        * @param   array  Array of table => lock type
        * @return  void
        */
        function lock($tables)
        {
                if (is_array($tables) AND count($tables))
                {
                        $sql = '';

                        foreach ($tables AS $name => $type)
                        {
                                $sql .= (!empty($sql) ? ', ' : '') . "$name $type";
                        }

                        $this->query("LOCK TABLES $sql");
                        $this->is_locked = true;
                }
        }

        /**
        * Unlock tables
        */
        function unlock()
        {
                if ($this->is_locked)
                {
                        $this->query("UNLOCK TABLES");
                }
        }

        /**
        * Returns the ID of the most recently inserted item in an auto_increment field
        *
        * @return  integer
        */
        function insert_id()
        {
                return @mysql_insert_id($this->link);
        }

        /**
        * Escapes a value to make it safe for using in queries.
        *
        * @param  string  Value to be escaped
        * @param  bool
        * @return string
        */
        function prepare($value, $do_like = false)
        {
			$value = stripslashes($value);

			if ($do_like)
			{
				$value = str_replace(array('%', '_'), array('\%', '\_'), $value);
			}

			if (function_exists('mysql_real_escape_string'))
			{
				if (!is_numeric($value))
				{
					return trim("'". mysql_real_escape_string($value, $this->link) . "'");
				} else {
					return trim( mysql_real_escape_string($value, $this->link) );
				}
			} else {
				if (!is_numeric($value))
				{
					return trim("'". mysql_escape_string($value). "'");
				} else {
					return trim( mysql_escape_string($value) );
				}
         	}
		}
			
			

        /**
        * Frees memory associated with a query result.
        *
        * @param  string   The query result we are dealing with.
        * @return boolean
        */
        function free_result($result)
        {
                return @mysql_free_result($result);
        }

        /**
        * Turns database error reporting on
        */
        function show_errors()
        {
                $this->show_errors = true;
        }

        /**
        * Turns database error reporting off
        */
        function hide_errors()
        {
                $this->show_errors = false;
        }

        /**
        * Closes our connection to MySQL.
        *
        * @param  none
        * @return boolean
        */
        function close()
        {
                $this->sql = '';
                return @mysql_close($this->link);
        }

        /**
        * Returns the MySQL error message.
        *
        * @param  none
        * @return string
        */
        function error()
        {
                $this->error = (is_null($this->recent_link)) ? '' : mysql_error($this->recent_link);
                return $this->error;
        }

        /**
        * Returns the MySQL error number.
        *
        * @param  none
        * @return string
        */
        function errno()
        {
                $this->errno = (is_null($this->recent_link)) ? 0 : mysql_errno($this->recent_link);
                return $this->errno;
        }

        /**
        * Gets the url/path of where we are when a MySQL error occurs.
        *
        * @access private
        * @param  none
        * @return string
        */
        function _get_error_path()
        {
                if ($_SERVER['REQUEST_URI'])
                {
                        $errorpath = $_SERVER['REQUEST_URI'];
                }
                else
                {
                        if ($_SERVER['PATH_INFO'])
                        {
                                $errorpath = $_SERVER['PATH_INFO'];
                        }
                        else
                        {
                                $errorpath = $_SERVER['PHP_SELF'];
                        }

                        if ($_SERVER['QUERY_STRING'])
                        {
                                $errorpath .= '?' . $_SERVER['QUERY_STRING'];
                        }
                }

                if (($pos = strpos($errorpath, '?')) !== false)
                {
                        $errorpath = urldecode(substr($errorpath, 0, $pos)) . substr($errorpath, $pos);
                }
                else
                {
                        $errorpath = urldecode($errorpath);
                }
                return $_SERVER['HTTP_HOST'] . $errorpath;
        }

        /**
        * If there is a database error, the script will be stopped and an error message displayed.
        *
        * @param  string  The error message. If empty, one will be built with $this->sql.
        * @return string
        */
        function raise_error($error_message = '')
        {
                if ($this->recent_link)
                {
                        $this->error = $this->error($this->recent_link);
                        $this->errno = $this->errno($this->recent_link);
                }

                if ($error_message == '')
                {
                        $this->sql = "Error in SQL query:\n\n" . rtrim($this->sql) . ';';
                        $error_message =& $this->sql;
                }
                else
                {
                        $error_message = $error_message . ($this->sql != '' ? "\n\nSQL:" . rtrim($this->sql) . ';' : '');
                }

                $message = "<textarea rows=\"10\" cols=\"80\">MySQL Error:\n\n\n$error_message\n\nError: {$this->error}\nError #: {$this->errno}\nFilename: " . $this->_get_error_path() . "\n</textarea>";

                if (!$this->show_errors)
                {
                        $message = "<!--\n\n$message\n\n-->";
                }
                die("There seems to have been an error with our database, please try again later.<br /><br />\n$message");
        }
}