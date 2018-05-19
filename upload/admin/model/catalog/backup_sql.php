<?php
class ModelCatalogBackupSql extends Model {

	private $nameTabale = [];

	public function getsql()
	{
		$result = $this->db->query('SHOW TABLES' );

		foreach ($result->rows as $data)
		{
			foreach ($data as $tabel)
			{
				$this->nameTabale[] =  $tabel;
			}
		}



		return $this->getAllData($this->nameTabale);
	}

	public function getAllData($tables)
	{
		$return = '';
		foreach($tables as $table)
		{
			// Get content of each table
			$result = $this->db->query('SELECT * FROM '. $table) ;
			// Get number of fields (columns) of each table
			// Add table information
			$return .= "--\n" ;
			$return .= '-- Tabel structure for table `' . $table . '`' . "\n" ;
			$return .= "--\n" ;
			$return.= 'DROP TABLE  IF EXISTS `'.$table.'`;' . "\n" ;
			// Get the table-shema
			$shema = $this->db->query('SHOW CREATE TABLE '.$table) ;
			// Extract table shema
			$tableshema = $shema->rows ;
			// Append table-shema into code
			$return.= $tableshema[0]["Create Table"].";" . "\n\n" ;
			// Cycle through each table-row

				// Prepare code that will insert data into table


				// Extract data of each row
				foreach($result->rows as $data)
				{
					$return .= 'INSERT INTO `'.$table .'`  VALUES ( '  ;
						foreach ($data as $val) {

							$return .= '"'.$this->db->escape($val) . "\"," ;
						}
					// Let's remove the last comma
					$return = substr("$return", 0, -1) ;
					$return .= ");" ."\n" ;
				}


			$return .= "\n\n" ;
		}
		$return .= 'SET FOREIGN_KEY_CHECKS = 1 ; '  . "\n" ;
		$return .= 'COMMIT ; '  . "\n" ;
		$return .= 'SET AUTOCOMMIT = 1 ; ' . "\n"  ;
		return $return;
	}
}
