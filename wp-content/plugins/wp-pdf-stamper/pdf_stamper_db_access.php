<?php
global $wpdb;
define('WP_PDF_STAMPED_FILES_TABLE_NAME', $wpdb->prefix . "pdf_stamped_files_tbl");

class WpPdfStamperDbAccess
{
   function  __construct() {
      die();
   }
   function WpPdfStamperDbAccess()
   {
      die();
   }
   static function find($inTable, $condition)
   {
      global $wpdb;

      if(empty($condition))
      {
          return null;
      }
      $resultset = $wpdb->get_row("SELECT * FROM $inTable WHERE $condition", OBJECT);
      return $resultset;
   }
   static function findAll($inTable, $condition=null, $orderby=null)
   {
      global $wpdb;
      $condition = empty ($condition)? '' : ' WHERE ' .$condition;
      $condition .= empty($orderby)? '': ' ORDER BY ' . $orderby;
      $resultSet = $wpdb->get_results("SELECT * FROM $inTable $condition ", OBJECT);
      return $resultSet;
   }
   static function delete($fromTable, $condition)
   {
      global $wpdb;
      $resultSet = $wpdb->query("DELETE FROM $fromTable WHERE $condition ");
      return $resultSet;
   }
   static function insert($inTable, $fields)
   {
      global $wpdb;
      $fieldss = '';
      $valuess = '';
      $first = true;
      foreach($fields as $field=>$value)
      {
         if($first)
            $first = false;
         else
         {
            $fieldss .= ' , ';
            $valuess .= ' , ';
         }
         $fieldss .= " $field ";
         $valuess .= " '" .esc_sql($value)."' ";
      }

      $query = " INSERT INTO $inTable ($fieldss) VALUES ($valuess)";

      $results = $wpdb->query($query);
      return $results;
   }
   static function update($inTable, $condition, $fields)
   {
      global $wpdb;
      $query = " UPDATE $inTable SET ";
      $first = true;
      foreach($fields as $field=>$value)
      {
         if($first) $first = false; else $query .= ' , ';
         $query .= " $field = '" . esc_sql($value) ."' ";
      }

      $query .= empty($condition)? '': " WHERE $condition ";
      $results = $wpdb->query($query);
      return $results;
   }
}
