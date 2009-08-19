<?php
/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

/* Helper class for form/input management */

class Form {

	// Functions for displaying input controls -----------------------------
	
	public static function textbox($name, $values, $size, $maxlen) {
		print '<input type="text" name="'. $name .'" value="'. htmlentities($values[$name]) .'" size="'. $size .'" maxlength="'. $maxlen .'" />';
	}

	public static function textbox_html($name, $values, $size, $maxlen) {
		return '<input type="text" name="'. $name .'" value="'. htmlentities($values[$name]) .'" size="'. $size .'" maxlength="'. $maxlen .'" />';
	}

	public static function pwbox($name, $values, $size, $maxlen) {
		print '<input type="password" name="'. $name .'" value="'. htmlentities($values[$name]) .'" size="'. $size .'" maxlength="'. $maxlen .'" />';
	}

	public static function submitButton($label) {
		print '<input type="submit" value="'. htmlentities($label) .'"/>';
	}

	public static function textarea($name, $values, $rows, $cols) {
		print '<textarea name="'. $name .'" rows="'. $rows .'" cols="'. $cols .'" >'. htmlentities($values[$name]) .'</textarea>';
	}

	public static function textarea_html($name, $values, $rows, $cols) {
		return '<textarea name="'. $name .'" rows="'. $rows .'" cols="'. $cols .'" >'. htmlentities($values[$name]) .'</textarea>';
	}

	public static function checkbox($name, $values, $element_value) {
		print '<input type="checkbox" name="'. $name .'" value="'. $element_value .'" ';
		if ($element_value == $values[$name]) {
			print 'checked="checked" '; 
		}
		print '/>';
	}

	public static function radioButton($name, $values, $element_value) {
		print '<input type="radio" name="'. $name .'" value="'. $element_value .'" ';
		if ($element_value == $values[$name]) {
			print ' checked="checked" '; 
		}
		print '/>';
	}

	public static function hidden($name, $values) {
		print '<input type="hidden" name="'. $name .'" value="'. htmlentities($values[$name]) .'" />';
	}

	public static function hidden_html($name, $values) {
		return '<input type="hidden" name="'. $name .'" value="'. htmlentities($values[$name]) .'" />';
	}


	// listbox - display a listbox, supporting multiple selections
	// Params:
	// name - element name
	// options - array of list options
	// selected - 2D array - elements by current selection list
	// multiple - boolean indicating whether multiple selection is allowed

	public static function listbox($name, $options, $selected, $multiple=false) {
		// Print select tag --
		print '<select name="'. $name;
		// If multiple selections are allowed, turn the name into an array name
		if ($multiple) {
			print '[]" multiple="multiple';
		}
		print '">';

		// Set up the selection list --
		$selected_options = array();
		if ($multiple) {
			foreach ($selected[$name] as $val) {
				$selected_options[$val] = true;
			}
		}
		else {
			$selected_options[$selected[$name]] = true; 
		}

		// Print out the option tags
		foreach ($options as $option => $label) {
			print '<option value="'. htmlentities($label) .'"';
			if ($selected_options[$label]) {
				print ' selected="selected"';
			}
			print '>'. htmlentities($label) .'</option>';
		}
		
		print '</select>';
	}

	public static function listbox_html($name, $options, $selected, $multiple=false) {
		// Generate the HTML for a listbox and return it
		$html = '<select name="'. $name;
		// If multiple selections are allowed, turn the name into an array name
		if ($multiple) {
			$html .= '[]" multiple="multiple';
		}
		$html .= '">';

		// Set up the selection list --
		$selected_options = array();
		if ($multiple) {
			foreach ($selected[$name] as $val) {
				$selected_options[$val] = true;
			}
		}
		else {
			$selected_options[$selected[$name]] = true; 
		}

		// Print out the option tags
		foreach ($options as $option => $label) {
			$html .= '<option value="'. htmlentities($label) .'"';
			if ($selected_options[$label]) {
				$html .= ' selected="selected"';
			}
			$html .= '>'. htmlentities($label) .'</option>';
		}
		
		$html .= '</select>';
		
		return $html;
	}


	// Form Validation Routines -------------------------------------------------

	public static function isValidInt($value) {
		return ($value == strval(intval($value)));
	}

	public static function isValidFloat($value) {
		return ($value == strval(floatval($value)));
	}

	public static function isBlank($value) {
		return (strlen(trim($value)) == 0);
	}

	public static function isInRange($value, $min, $max) {
		return (($value >= $min) && ($value <= $max));
	}

	public static function isInLength($value, $len) {
		return (strlen(trim($value)) <= $len);
	}

}

?>