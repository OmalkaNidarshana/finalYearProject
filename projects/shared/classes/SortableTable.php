<?php

class SortableTable{
	var $link;
	var $table;
	var $formatter;
	var $priKey ;
	var $colList;
	var $pageNum = 1;
	var $fltr;
	var $ordBy;
	var $idList;
	var $pageSize = 25;
	var $colHeader = array();
	var $totalFound;
	var $colData = array();
	var $fullDataIdList;
	var $summaryOrder;
	var $isPaging = false;
	var $pageNumbers;
	var $isRawAction = false;
	var $rawActions = array();
	var $headerLevelData;
	function SortableTable($link){
		 $this->link = $link;
	}

	function addColumn($fld,$lbl){
		$this->colHeader[$fld] = $lbl;
		$this->summaryOrder[] = $fld;

	}

	function setTable($table){$this->table = $table ; }
	function setFormatter($formatter){$this->formatter = $formatter; }
	function setPriKey($priKey){$this->priKey = $priKey; }
	function setColumList($colList){$this->colList = $colList; }
	function setPageNum($pageNum){$this->pageNum = $pageNum; }
	function setFilters($fltr){$this->fltr = $fltr; }
	function setOrdBy($ordBy){$this->ordBy = $ordBy; }
	function setHeaderLevelData($headerLevelData){$this->headerLevelData = $headerLevelData; }
	function getTableTotalRec(){
		$this->totalFound = getPageRecordNum($this->link,$this->table,$this->priKey,$this->fltr,$this->ordBy);

	}

	function getFullIdSet(){
		$this->fullDataIdList = getPageRecordIds($this->link,$this->table,$this->priKey,$this->fltr,$this->ordBy);
		//print_r($this->fullDataIdList);
	}

	function getPagingIds(){
		if($this->totalFound > $this->pageSize){
			$this->isPaging = true;
			list($idList,$this->pageNumbers) = getChunckDataByPageSize($this->fullDataIdList,$this->pageSize);
			$this->idList = $idList[$this->pageNum];
		}else{
			$this->idList = $this->fullDataIdList;
		}
	}

	function getTableData(){
		$this->colData = getPageDataSet($this->link,$this->table,$this->priKey,$this->colList,$this->idList);
	}

	function loadPageData(){
		$this->getTableTotalRec();
		$this->getFullIdSet();
		$this->getPagingIds();
		$this->getTableData();
		
	}

	function paging(){
		$i =1;
		$html ='<div class="dataTables_paginate paging_simple_numbers col-sm-8" id="example1_paginate">
			<ul class="pagination">';
			$html .='<li class="paginate_button previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li>';
			for($i; $i<=$this->pageNumbers; $i++){	
				if( $i == $this->pageNum)
					$html .='<li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="'.$i.'" tabindex="'.$i.'-1">'.$i.'</a></li>';
				else
					$html .='<li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="'.$i.'" tabindex="'.$i.'-1">'.$i.'</a></li>';
			}
			$html .='<li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">Next</a></li>
			</ul>';
		$html .='</div>';
		if( !empty($this->headerLevelData) ){
			$html .='<div class="pagination col-sm-4 headerLvl" >';
				$html .=$this->headerLevelData;
			$html .='</div>';
		}

		return $html; 
	}
	
	function htmlHeader(){
		$tableHead = '<thead><tr>';
		foreach( $this->colHeader as $fld=>$lbl){
			$tableHead .= '<th style="cursor: pointer;" title="Sort from '.$lbl.'">'.$lbl.'</th>';
		}
		if($this->isRawAction){
			$tableHead .= '<th>Actions</th>';
		}
		$tableHead .= '</tr></thead>';
		return $tableHead;

	}

	function htmlTable(){
		$table = '';
		if( $this->isPaging){
			$table .= $this->paging();
	  	}else{
			$table .='<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate"></div>';
		}
		$table .= '<table class="table table-hover" table cellspacing="1" cellpadding="2" id="sortableTable">';
		$table .= $this->htmlHeader();
		$table .= '<tbody>';
		$cnt = count($this->summaryOrder);
		if( !empty($this->colData) ){
			foreach( $this->colData as $data){
				$table .= '<tr>';
				foreach( $this->summaryOrder as $id) {
					$table .= '<td class="summarytable">';
					if( isset($data[$id]) ){
						if( !empty($this->formatter) )
							$table .= $this->formatter->formatters($id,$data[$id],$data);
						else
							$table .=$data[$id];
					}else{
						$table .= $this->formatter->formatters($id,'',$data);
					}
					$table .= '</td>';
				}
				if($this->isRawAction){
					$table .= '<td class="summarytable">';
						foreach($this->rawActions as $icon){

							
						}
					$table .= '</td>';
				}
				$table .= '</tr>';
			}
		}else{
			$table .= '<tr><td colspan="'.$cnt.'" style="color: red;">No records found.</td></tr>';
		}
		$table .='</tbody>';
		$table .= '</table>';
		if( $this->isPaging){
			$table .= $this->paging();
	  	}else{
			$table .='<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate"></div>';
		}
	  return $table;
	}

}

?>