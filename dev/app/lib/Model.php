<?php

class Model extends BaseDAO {
	
	function getUser($user_id){
		$sql = sprintf('SELECT * FROM user WHERE user_id = %s',
			$this->prepare($user_id)
		);
		if($user = $this->query($sql, true)){
			return $user;
		} else {
			return false;	
		}
	}
	
	/*
	*	Note:  __construct is used in BaseDAO
	*	If you redefine one, it will not establish connection to DB.
	*/

	function __destruct() {
	}
	
	
	/*
	 *	Get options from a db table
	 */
	function options($tablename, $valueCol, $labelCol, $preset = ''){
		$sql = sprintf('SELECT %s, %s FROM %s ORDER BY %s ASC',
			$valueCol,
			$labelCol,
			$tablename,
			$valueCol
		);
		if($qry = $this->query($sql)){
			while($options = $this->fetch_array($qry)){
				$opts[$options[$valueCol]] = $options[$labelCol];
			}
			$return = "";
			foreach($opts as $key=>$val){
				$return .= "
				<option ".($preset==$key?'selected="selected"':'')." value='$key'>$val</option>";	
			}
			return $return;
		} else {
			return false;	
		}
	}
	
}