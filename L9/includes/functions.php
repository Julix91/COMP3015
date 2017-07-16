<?php
//Display as rows code from COMP4662
function query_results_as_table($records, $title = null) {
   if($title != null) {
	  $title = '<h3>'.$title.'</h3>'.PHP_EOL;
   }
   if (!is_array($records) || empty($records)) { return false; }
   $header = table_row($records[0], 'th');
   $rows = array();
   foreach ($records as $record) {
	  $rows[] = table_row($record, 'td');
   }
   echo $title.'<table>'.PHP_EOL.$header.join($rows).'</table>'.PHP_EOL.PHP_EOL;
   return true;
}

function table_row($record, $element) {
   $values = array();
   foreach ($record as $field_name => $field_value) {
	  $values[] = $element == 'th' ? $field_name : $field_value;
   }
   return '<tr><'.$element.'>'.join('</'.$element.'><'.$element.'>', $values).'</'.$element.'></tr>'.PHP_EOL;
}
