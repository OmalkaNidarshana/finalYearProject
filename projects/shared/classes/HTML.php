<?php

class HTML{

	function HTML(){}

	function textFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='text' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function fileFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='file' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}
	
	function buttonFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='button' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function checkboxFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='checkbox' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function dateFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='date' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function hiddenFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='hidden' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function numberFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='number' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function phoneFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='tel' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}
	function radioButtonFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='radio' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function submitButtonFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='submit' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}
    
	function passFeild($name,$value,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html = "<input type ='password' name ='".$name."' value='".$value."'".$attr.">";
		return $html;
	}

	function formStart($action,$method,$id){
		$html = '<form action="'.$action.'" method="'.$method.'" id="'.$id.'">';
		return $html;
	}

	function formEnd(){
		$html = '</form>';
		return $html;
	}

	function lblFeild($lbl,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html ='<label '.$attr.'>'.$lbl.'</label>';
		return $html;
	}

	function textArea($id,$name,$maxRow,$maxCol,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		$html ='<textarea id="'.$id.'" name="'.$name.'" rows="'.$maxRow.'" cols="'.$maxCol.'"'.$attr.'">';
		return $html;
	}

	function selectFeild($id,$name,$optionArray,$isMulti=false,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		if($isMulti){
			$multi = 'multiple';
		}else{
			$multi ='';
		}
		$html ='<select id="'.$id.'" name="'.$name.'"'.$attr.'"'.$multi.'"'.$attr.'">';
			foreach($optionArray as $value=>$lbl){
				$html .='<option value="'.$value.'">'.$lbl.'</option>';
			}
		$html .='</select>';
		return $html;
	}

	function openCloseTable($isOpen,$ishover=false,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		if($isOpen){
			if($ishover){
				$html = '<table class="table table-hover"'.$attr.'>';
			}else{	
				$html = '<table '.$attr.'>';
			}
			$html .= '<tbody>';
		}else{
			$html = '</tbody>';
			$html .= '</table>';
		}
		return $html;
	}

	function openCloseTr($isOpen,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		if($isOpen){
			$html = '<tr '.$attr.'>';
		}else{
			$html = '</tr>';
		}
		return $html;
	}

	function openCloseTd($isOpen,$attr=array()){
		$attr = HTML::makeInputAttribute($attr);
		if($isOpen){
			$html = '<td '.$attr.'>';
		}else{
			$html = '</td>';
		}
		return $html;
	}

	function makeInputAttribute($arr=array('')){
		$attrArray =array();
		foreach($arr as $key=>$value){
			$attrArray[] = $key."='".$value."'";
		}
		//print_rr($attrArray);
		$inputAttr = implode(" ",$attrArray);
		return $inputAttr;
	}
}

?>