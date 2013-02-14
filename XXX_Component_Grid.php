<?php

class XXX_Component_Grid
{
	const CLASS_NAME = 'XXX_Component_Grid';
	
	public static $instanceCounter = 0;
	
	protected $ID = 'XXX_Component_Grid';
	
	protected $grid = array
	(
		'header' => array(),
		'body' => array(),
		'footer' => array()
	);
	
	protected $parsedGrid = array
	(
		'header' => array(),
		'body' => array(),
		'footer' => array(),
		'concatenatedRows' => array()
	);
	
	protected $concatenatedRows = array();
	
	protected $parsedCellRelations = array();
	
	protected $caption = '';
	
	protected $summary = '';
	
	protected $columns = array();
	
	protected $error;
		
	protected $valid = false;	
		
	public function __construct ()
	{
		$this->ID .= '_';
		$this->ID .= ++self::$instanceCounter;
	}
	
	protected function setRow (array $row, $type = 'body')
	{
		if (XXX_Type::isFilledArray($row) && XXX_Array::hasKey($this->grid, $type, false, false))
		{
			$this->grid[$type] = $rows;
		}
	}
	
	protected function setRows (array $rows, $type = 'body')
	{
		if (XXX_Type::isFilledArray($rows) && XXX_Array::hasKey($this->grid, $type, false, false))
		{
			$this->grid[$type] = $rows;
		}
	}
	
	protected function appendRow (array $row, $type = 'body')
	{
		if (XXX_Type::isFilledArray($row) && XXX_Array::hasKey($this->grid, $type, false, false))
		{
			$this->grid[$type][] = $row;
		}
	}
	
	protected function appendRows (array $rows, $type = 'body')
	{
		if (XXX_Type::isFilledArray($rows) && XXX_Array::hasKey($this->grid, $type, false, false))
		{
			foreach ($rows as $row)
			{
				if (XXX_Type::isFilledArray($row))
				{
					$this->grid[$type][] = $row;
				}
			}
		}
	}
	
	protected function resetRows ($type = 'body')
	{
		if (XXX_Array::hasKey($type, $this->grid, false, false))
		{
			$this->grid[$type] = array();
		}
	}
	
	public function setCaption ($caption)
	{
		$this->caption = $caption;
	}
	
	public function setSummary ($summary)
	{
		$this->summary = $summary;
	}
	
	public function setColumns (array $columns)
	{
		$this->columns = $columns;
	}
	
	////////////////////
	// Header
	////////////////////
	
	public function setHeaderRow (array $row)
	{
		$this->setRow($row, 'header');
	}
	
	public function setHeaderRows (array $rows)
	{
		$this->setRows($rows, 'header');
	}
	
	public function appendHeaderRow (array $row)
	{
		$this->appendRow($row, 'header');
	}
	
	public function appendHeaderRows (array $rows)
	{
		$this->appendRows($rows, 'header');
	}
	
	public function resetHeaderRows ()
	{
		$this->resetRows('header');
	}
	
	////////////////////
	// Body
	////////////////////
	
	public function setBodyRow (array $row)
	{
		$this->setRow($row, 'body');
	}
	
	public function setBodyRows (array $rows)
	{
		$this->setRows($rows, 'body');
	}
	
	public function appendBodyRow (array $row)
	{
		$this->appendRow($row, 'body');
	}
	
	public function appendBodyRows (array $rows)
	{
		$this->appendRows($rows, 'body');
	}
	
	public function resetBodyRows ()
	{
		$this->resetRows('body');
	}
	
	////////////////////
	// Footer
	////////////////////
	
	public function setFooterRow (array $row)
	{
		$this->setRow($row, 'footer');
	}
	
	public function setFooterRows (array $rows)
	{
		$this->setRows($rows, 'footer');
	}
	
	public function appendFooterRow (array $row)
	{
		$this->appendRow($row, 'footer');
	}
	
	public function appendFooterRows (array $rows)
	{
		$this->appendRows($rows, 'footer');
	}
	
	public function resetFooterRows ()
	{
		$this->FresetRows('footer');
	}
	
	////////////////////
	// Parse
	////////////////////
	
	protected function parseRows ($rows)
	{		
		$valid = true;
		
		$rowBasedMap = array();
		$columnBasedMap = array();
		$cellBasedMap = array();
		
		////////////////////
		// Count the cells and initiate rows in the row based map
		////////////////////
		
		$rowTotal = count($rows);
		$cellTotal = 0;
		
		for ($i = 0; $i < $rowTotal; ++$i)
		{
			$rowBasedMap[$i] = array();
			
			for ($j = 0, $jEnd = count($rows[$i]); $j < $jEnd; ++$j)
			{
				$cell = $rows[$i][$j];
				$rowSpan = 1;
				$columnSpan = 1;
				
				if (XXX_Type::isArray($cell))
				{
					if (XXX_Type::isVariableDefined($cell['rowSpan']))
					{
						$rowSpan = $cell['rowSpan'];
					}
					
					if (XXX_Type::isVariableDefined($cell['columnSpan']))
					{
						$columnSpan = $cell['columnSpan'];
					}
				}
				
				$cellTotal += $columnSpan * $rowSpan;
			}
		}
		
		////////////////////
		// Determine grid coordinates and populate the rowBasedMap and the cellBasedMap
		////////////////////
		
		$rowCounter = $cellCounter = 0;
		
		foreach ($rows as $cellRow => $row)
		{
			foreach ($row as $cellKey => $cell)
			{
				$rowSpan = 1;
				$columnSpan = 1;
				
				if (XXX_Type::isArray($cell))
				{
					if (XXX_Type::isVariableDefined($cell['rowSpan']))
					{
						$rowSpan = $cell['rowSpan'];
					}
					
					if (XXX_Type::isVariableDefined($cell['columnSpan']))
					{
						$columnSpan = $cell['columnSpan'];
					}
				}
				
				$cellBasedMap[$cellCounter] = array
				(
					'rows' => array(),
					'columns' => array()
				);
				
				// Determine grid coordinates
				
				$cellTop = $rowCounter;
				$cellLeft = -1;
				
				$possibleLeft = 0;
				
				$rowData = $rowBasedMap[$cellTop];
				
				do
				{
					if (XXX_Type::isVariableUndefined($rowData[$possibleLeft]))
					{
						$cellLeft = $possibleLeft;
					}
				}
				while ($cellLeft === -1 && $possibleLeft++ <= $cellTotal);
				
				if ($cellLeft === -1)
				{
					if ($valid)
					{
						$valid = false;
					}
				}
				
				for ($i = 0; $i < $columnSpan; ++$i)
				{
					for ($j = 0; $j < $rowSpan; ++$j)
					{
						$x = $cellLeft + $i;
						$y = $cellTop + $j;
						
						// Populate rowBasedMap
						$rowBasedMap[$y][$x] = $cellCounter;
						
						// Populate cellBasedMap
						if (!XXX_Array::hasValue($cellBasedMap[$cellCounter]['columns'], $x, false, false))
						{
							$cellBasedMap[$cellCounter]['columns'][] = $x;
						}
						if (!XXX_Array::hasValue($cellBasedMap[$cellCounter]['rows'], $y, false, false))
						{
							$cellBasedMap[$cellCounter]['rows'][] = $y;
						}
					}
				}
				
				++$cellCounter;
			}
			
			++$rowCounter;
		}
		
		////////////////////
		// Rearrange the keys properly and validate the cell count in the process
		////////////////////
		
		$validCellCount = count($rowBasedMap[0]);
		
		$rowBasedMapOriginal = $rowBasedMap;
		$rowBasedMap = array();
		
		for ($y = 0, $yEnd = count($rowBasedMapOriginal); $y < $yEnd; ++$y)
		{
			$rowBasedMap[$y] = array();
			
			if ($valid)
			{
				if (count($rowBasedMapOriginal[$y]) !== $validCellCount)
				{
					$valid = false;
				}
			}
			
			for ($x = 0, $xEnd = count($rowBasedMapOriginal[$y]); $x < $xEnd; ++$x)
			{
				if (XXX_Type::isVariableDefined($rowBasedMapOriginal[$y][$x]))
				{
					$rowBasedMap[$y][$x] = $rowBasedMapOriginal[$y][$x];
				}
				else
				{
					if ($valid)
					{
						$valid = false;
					}
				}
			}		
		}
		
		////////////////////
		// Populate columnBasedMap by the rowBasedMap
		////////////////////
		
		foreach ($rowBasedMap as $row)
		{
			foreach ($row as $column => $cell)
			{
				if (XXX_Type::isVariableUndefined($columnBasedMap[$column]) || !XXX_Type::isArray($columnBasedMap[$column]))
				{
					$columnBasedMap[$column] = array();
				}
				
				if (!XXX_Array::hasValue($columnBasedMap[$column], $cell, false, false))
				{
					$columnBasedMap[$column][] = $cell;
				}
			}
		}
		
		////////////////////
		// Result
		////////////////////
		
		$rowTotal = count($rowBasedMap);
		$columnTotal = count($columnBasedMap);
		$cellTotal = $rowTotal * $columnTotal;
		
		$result = array
		(
			'valid' => $valid,
			
			'rowTotal' => $rowTotal,
			'columnTotal' => $columnTotal,
			'cellTotal' => $cellTotal,
			
			'rowBasedMap' => $rowBasedMap,
			'columnBasedMap' => $columnBasedMap,
			'cellBasedMap' => $cellBasedMap
		);
		
		return $result;
	}
	
	protected function parseCellRelations ()
	{
		$cells = array();
		
		$rowResult = $this->parseRows($this->concatenatedRows);
		
		$this->parsedGrid['concatenatedRows'] = $rowResult;
		
		$columnTotal = $rowResult['columnTotal'];
		
		$headerFrom = 0;
		$headerTill = $this->parsedGrid['header']['rowTotal'] - 1;
		
		$footerFrom = $headerTill + 1;
		$footerTill = $footerFrom + $this->parsedGrid['footer']['rowTotal'] - 1;
		
		$bodyFrom = $footerTill + 1;
		$bodyTill = $bodyFrom + $this->parsedGrid['body']['rowTotal'] - 1;
		
		foreach ($rowResult['cellBasedMap'] as $cellKey => $cell)
		{
			$cellRelations = array();
			$cellRelations['self'] = $cellKey;
			$cellRelations['affected'] = array();
			$cellRelations['header'] = array();
			$cellRelations['footer'] = array();
			$cellRelations['body'] = array();
			$cellRelations['firstColumn'] = array();
			$cellRelations['lastColumn'] = array();
			
			$affectedRows = $cell['rows'];
			$affectedColumns = $cell['columns'];
			
			$cellTop = $affectedRows[0];
			$cellBottom = $affectedRows[count($affectedRows) - 1];
			$cellLeft = $affectedColumns[0];
			$cellRight = $affectedColumns[count($affectedColumns) - 1];
			
			if ($cellTop >= $headerFrom && $cellTop <= $headerTill)
			{
				$cellRelations['type'] = 'header';
			}
			else if ($cellTop >= $footerFrom && $cellTop <= $footerTill)
			{
				$cellRelations['type'] = 'footer';
			}
			else if ($cellTop >= $bodyFrom && $cellTop <= $bodyTill)
			{
				$cellRelations['type'] = 'body';
			}
			
			$affectedCells = array();
			$affectedCellCoordinates = array();
			
			////////////////////
			// Find all crossing cells
			////////////////////
						
			// Find all cells in the same row(s) as the current cell			
			foreach ($affectedRows as $affectedRow)
			{
				foreach ($rowResult['rowBasedMap'][$affectedRow] as $affectedCell)
				{
					$affectedCellTop = $rowResult['cellBasedMap'][$affectedCell]['rows'][0];
					$affectedCellBottom = $rowResult['cellBasedMap'][$affectedCell]['rows'][count($rowResult['cellBasedMap'][$affectedCell]['rows']) - 1];
					$affectedCellLeft = $rowResult['cellBasedMap'][$affectedCell]['columns'][0];
					$affectedCellRight = $rowResult['cellBasedMap'][$affectedCell]['columns'][count($rowResult['cellBasedMap'][$affectedCell]['columns']) - 1];
					
					// Exclude self
					if (!XXX_Array::hasValue($affectedCells, $affectedCell, false, false) && ($cellTop !== $affectedCellTop || $cellLeft !== $affectedCellLeft))
					{
						$affectedCells[] = $affectedCell;
						$affectedCellCoordinates[] = array($affectedCellTop, $affectedCellRight, $affectedCellBottom, $affectedCellLeft, $affectedCell);
					}
				}
			}
			
			// Find all cells in the same column(s) as the current cell			
			foreach ($affectedColumns as $affectedColumn)
			{
				foreach ($rowResult['columnBasedMap'][$affectedColumn] as $affectedCell)
				{
					$affectedCellTop = $rowResult['cellBasedMap'][$affectedCell]['rows'][0];
					$affectedCellBottom = $rowResult['cellBasedMap'][$affectedCell]['rows'][count($rowResult['cellBasedMap'][$affectedCell]['rows']) - 1];
					$affectedCellLeft = $rowResult['cellBasedMap'][$affectedCell]['columns'][0];
					$affectedCellRight = $rowResult['cellBasedMap'][$affectedCell]['columns'][count($rowResult['cellBasedMap'][$affectedCell]['columns']) - 1];
					
					// Exclude self
					if (!XXX_Array::hasValue($affectedCells, $affectedCell, false, false) && ($cellTop !== $affectedCellTop || $cellLeft !== $affectedCellLeft))
					{
						$affectedCells[] = $affectedCell;
						$affectedCellCoordinates[] = array($affectedCellTop, $affectedCellRight, $affectedCellBottom, $affectedCellLeft, $affectedCell);
					}
				}
			}
			
			////////////////////
			// Isolate specific cells
			////////////////////
			
			$cellRelations['body'] = $affectedCells;
			$cellRelations['affected'] = $affectedCells;
			
			$affectedRowsFrom = $affectedRows[0];
			$affectedRowsTill = $affectedRows[count($affectedRows) - 1];
			
			// Isolate first column field only if the current cell is not the first column
			foreach ($affectedCellCoordinates as $affectedCellKey => $affectedCell)
			{
				// The affected cell lies partially or fully within the affected rows
				if (XXX_Number::testRangeHit($affectedCell[0], $affectedCell[2], $cellTop, $cellBottom))
				{
					if ($affectedCell[3] === 0)
					{
						$cellRelations['firstColumn'][] = $affectedCell[4];
					}
					else if ($affectedCell[1] === $columnTotal - 1)
					{
						$cellRelations['lastColumn'][] = $affectedCell[4];
					}
				}
			}
						
			// Isolate header field(s) only if the current cell is not a header
			foreach ($affectedCellCoordinates as $affectedCellKey => $affectedCell)
			{
				if ($affectedCell[0] >= $headerFrom && $affectedCell[0] <= $headerTill)
				{
					$cellRelations['header'][] = $affectedCell[4];
					unset($cellRelations['body'][$affectedCellKey]);
				}
			}
			
			// Isolate footer field(s) only if the current cell is not a footer
			foreach ($affectedCellCoordinates as $affectedCellKey => $affectedCell)
			{
				if ($affectedCell[0] >= $footerFrom && $affectedCell[0] <= $footerTill)
				{
					$cellRelations['footer'][] = $affectedCell[4];
					unset($cellRelations['body'][$affectedCellKey]);
				}
			}
			
			sort($cellRelations['body']);
			sort($cellRelations['affected']);
			
			$cells[] = $cellRelations;
		}
		
		$this->parsedCellRelations = $cells;
	}
	
	protected function validate ()
	{
		$headerResult = $this->parsedGrid['header'];
		$footerResult = $this->parsedGrid['footer'];
		$bodyResult = $this->parsedGrid['body'];
		
		$valid = true;
		$columnTotal = 0;
				
		// If there is body data
		if (count($this->grid['body']))
		{
			// If there are headers and footers
			if (count($this->grid['header']) && count($this->grid['footer']))
			{				
				// Header
				if ($headerResult['valid'])
				{
					$columnTotal = $headerResult['columnTotal'];
				}
				else
				{					
					$valid = false;
					$this->error = array
					(
						'description' => '[Header] doesn\'t form a perfect grid (Possibly because of unmatching use of rowSpan & columnSpan)',
						'invoker' => array(self::CLASS_NAME, 'validate')
					);
				}
				
				if ($valid)
				{					
					// Footer
					if ($footerResult['valid'])
					{
						if ($columnTotal > 0)
						{
							if ($columnTotal !== $footerResult['columnTotal'])
							{
								$valid = false;
								$this->error = array
								(
									'description' => 'The number of columns in [Header (' . $columnTotal . ')] and [Footer (' . $footerResult['columnTotal'] . ')] differ',
									'invoker' => array(self::CLASS_NAME, 'validate')
								);
							}
						}
						else
						{
							$columnTotal = $footerResult['columnTotal'];
						}
					}
					else
					{
						$valid = false;
						$this->error = array
						(
							'description' => '[Footer] doesn\'t form a perfect grid (Possibly because of unmatching use of rowSpan & columnSpan)',
							'invoker' => array(self::CLASS_NAME, 'validate')
						);
					}
				}
			}
			else
			{
				if (count($this->grid['header']) === 0 && count($this->grid['footer']))
				{
					$valid = false;
					$this->error = array
					(
						'description' => '[Header] not defined, header & footer can only be used together',
						'invoker' => array(self::CLASS_NAME, 'validate')
					);
				}
				else if (count($this->grid['header']) && count($this->grid['footer']) === 0)
				{
					$valid = false;
					$this->error = array
					(
						'description' => '[Footer] not defined, header & footer can only be used together',
						'invoker' => array(self::CLASS_NAME, 'validate')
					);
				}
			}
			
			if ($valid)
			{
				// Body				
				if ($bodyResult['valid'])
				{
					if ($columnTotal > 0)
					{
						if ($columnTotal !== $bodyResult['columnTotal'])
						{
							$valid = false;
							$this->error = array
							(
								'description' => 'The number of columns in [Header (' . $columnTotal  . ')] / [Footer (' . $footerResult['columnTotal'] . ')]  and [Body (' . $bodyResult['columnTotal'] . ')] differ',
								'invoker' => array(self::CLASS_NAME, 'validate')
							);
						}
					}
				}
				else
				{
					$valid = false;
					$this->error = array
					(
						'description' => '[Body] doesn\'t form a perfect grid (Possibly because of unmatching use of rowSpan & columnSpan)',
						'invoker' => array(self::CLASS_NAME, 'validate')
					);
				}
			}
		}
		else
		{
			$valid = false;
			$this->error = array
			(
				'description' => '[Body] contains no rows at all',
				'invoker' => array(self::CLASS_NAME, 'validate')
			);
		}
		
		$this->valid = $result = $valid;
		
		return $result;
	}
	
	public function parse ()
	{
		$result = false;
		
		// Header (Can only exist when there is a footer)
		$headerResult = $this->parseRows($this->grid['header']);
		$this->parsedGrid['header'] = $headerResult;
		
		// Footer (Can only exist when there is a header)
		$footerResult = $this->parseRows($this->grid['footer']);
		$this->parsedGrid['footer'] = $footerResult;
		
		// Body
		$bodyResult = $this->parseRows($this->grid['body']);
		$this->parsedGrid['body'] = $bodyResult;
		
		$this->validate();
		
		if ($this->valid)
		{
			$concatenatedRows = array();
			
			foreach ($this->grid['header'] as $row)
			{
				$concatenatedRows[] = $row;
			}
			foreach ($this->grid['footer'] as $row)
			{
				$concatenatedRows[] = $row;
			}
			foreach ($this->grid['body'] as $row)
			{
				$concatenatedRows[] = $row;
			}
			
			$this->concatenatedRows = $concatenatedRows;
			
			$this->parseCellRelations();
			
			$result = true;
		}
		
		return $result;
	}
	
	public function script ()
	{
		$result = '';
		
		$globalScopeVariableInitialization = 'var ' . $this->ID . ';';
		
		$result .= $this->ID . ' = new XXX.Grid(\'' . $this->ID . '\', \'table\');';
		
		$result .= XXX_String::$lineSeparator;
		
		$result .= $this->ID . '.process();';
		
		$result .= XXX_String::$lineSeparator;
		
		return array('variableInitialization' => $globalScopeVariableInitialization, 'script' => $result);
	}
	
	public function compose ()
	{
		$result = '';
		
		if (XXX_Array::getFirstLevelItemTotal($this->grid['header']))
		{
			$composedHeader = XXX_HTML::composeRows($this->grid['header'], 'header');
		}
		
		if (XXX_Array::getFirstLevelItemTotal($this->grid['footer']))
		{
			$composedFooter = XXX_HTML::composeRows($this->grid['footer'], 'header');
		}
		
		if (XXX_Array::getFirstLevelItemTotal($this->grid['body']))
		{
			$composedBody = XXX_HTML::composeRows($this->grid['body'], 'data');
		}
		
		$result .= XXX_HTML::composeTable($this->ID, $this->caption, $this->summary, $composedBody, $composedHeader, $composedFooter);
				
		return $result;
	}
	
	public function isOK ()
	{
		$result = true;
		
		if ($this->error)
		{
			$result = false;
		}
		
		return $result;
	}
	
	public function getError ()
	{
		$result = false;
		
		if ($this->error)
		{
			$result = $this->error;
			unset($this->error);
		}
		
		return $result;
	}
	
}

?>