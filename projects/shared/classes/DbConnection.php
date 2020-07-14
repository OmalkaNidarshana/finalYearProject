<?php
	class dbConnection {

		var $host;
		var $user;
		var $dataBase;
		var $password;
		var $link;
		var $showQuery = false;

		function dbConnection($host,$user,$password,$dataBase){

			$this->host = $host;
			$this->user = $user;
			$this->dataBase = $dataBase;
			$this->password = $password;

			$this->link = new mysqli($this->host, $this->user, $this->password,$this->dataBase);

			if ($this->link->connect_error) {
				die("Connection failed: " . $this->link->connect_error);
			}
		}

		function fetchingData($sql){

			if($this->showQuery){
				echo $sql.'<br>';
			}

			$result = $this->link->query($sql);
			
			if ( isset($result->num_rows) && $result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$data[] = $row;
					
				}
			} else {
				$data = array();
			}
			return$data;
		}

		function getRecordSetFromQuery($sql){
			$data = $this->fetchingData($sql);
			return $data;
		}

		function getRowDataFromQuery($sql){
			$dataArr = '';
			$data = $this->fetchingData($sql);
			if(is_array($data)){
				foreach($data as $key=>$val){
					$dataArr = $val;
				}
			}
			return $dataArr;
		}

		function getcolumnDataFromQuery($sql){
			$dataArr = array();
			$data = $this->fetchingData($sql);
			if(is_array($data)){
				foreach($data as $key=>$val){
					foreach($val as $k=>$v){
						$dataArr[] = $v;
					}
				}
			}
			return $dataArr;
		}

		function getObjectDataFromQuery($sql){
			$dataArr = '';
			$data = $this->fetchingData($sql);
			if(is_array($data)){
				foreach($data as $key=>$val){
					foreach($val as $k=>$v){
						$dataArr = $v;
					}
				}
			}
			return $dataArr;
		}

		function insertUpdate($sql){
			$this->link->query($sql);
		}
	}





?>