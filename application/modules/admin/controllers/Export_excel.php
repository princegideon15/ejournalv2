<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
* File Name: Export_excel.php
* ----------------------------------------------------------------------------------------------------
* Purpose of this file:
* To manage exporting of database file to excel
* ----------------------------------------------------------------------------------------------------
* System Name: Online Research Journal System
* ----------------------------------------------------------------------------------------------------
* Author: -
* ----------------------------------------------------------------------------------------------------
* Date of revision: -
* ----------------------------------------------------------------------------------------------------
* Copyright Notice:
* Copyright (C) 2018 by the Department of Science and Technology - National Research Council of the Philiipines
*/

  class Export_excel extends EJ_Controller
  {
      public function __construct()
      {
          parent::__construct();

          $this->load->model('Export_excel_model');
          $this->load->model('Article_model');
          $this->load->model('Coauthor_model');
      }

      /**
       * Export excel
       *
       * @param [string] $export    determine what to export
       * @param [type] $id      id
       * @return void
       */
      public function export_excel($export, $id=null)
      {
          $log = (
        ($export == 'j') ?  save_log_ej(_UserIdFromSession(), 'just downloaded excel file of journals.') :
             (($export == 'c') ?  save_log_ej(_UserIdFromSession(), 'just downloaded excel file of clients information.') :
              (($export == 'e') ?  save_log_ej(_UserIdFromSession(), 'just downloaded excel file of editorial boards.') :
               (($export == 'v') ?  save_log_ej(_UserIdFromSession(), 'just downloaded excel file of abstract hits.') :
                                     save_log_ej(_UserIdFromSession(), 'just downloaded excel file of articles.'))))
           );

          $tableName = (
        ($export == 'j') ? 'tbljournals' :
                   (($export == 'c') ? 'tblclients'  :
                    (($export == 'v') ? 'tblhits_abstract'  :
                     (($export == 'e') ? 'tbleditorials' :'tblarticles')))
                 );

          $table_data = (
        ($export == 'j') ?  $this->Export_excel_model->fetch_data_journals() :
                     (($export == 'c') ? $this->Export_excel_model->fetch_data_clients()  :
                      (($export == 'v') ? $this->Export_excel_model->fetch_data_abstract()  :
                       (($export == 'e') ? $this->Export_excel_model->fetch_data_editorials() : $this->Export_excel_model->fetch_data_articles($id))))
                  );

          $filename = (
        ($export == 'j') ? 'eJournal Journals' :
                   (($export == 'c') ? 'eJournal Client Information'  :
                    (($export == 'v') ? 'eJournal Abstract Hits'  :
                     (($export == 'e') ? 'eJournal Editorial Boards' :'eJournal Articles')))
                );

          $header = (
        ($export == 'j') ? array('ID','VOLUME','ISSUE','MONTH','YEAR','ISSN','ARTICLES','DATE ADDED') :
                 (($export == 'c') ? array('FULL TEXT PDF','TITLE','NAME','SEX','AFFILIATION','COUNTRY','EMAIL','PURPOSE','ARTICLE REFERENCE ID','IP ADDRESS','DOWNLOAD DATE TIME')  :
                  (($export == 'v') ? array('TITLE OF ARTICLE VIEWED','IP ADDRESS','ARTICLE REFERENCE ID','DATE VIEWED')  :
                   (($export == 'e') ? array('ID','NAME','POSITION','SEX','AFFILIATION','SPECIALIZATION','COUNTRY','EMAIL','DATE ADDED') :
                    array('ID','TITLE','AUTHOR/CO-AUTHORS','ABSTRACT HITS','FULL TEXT DOWNLOADS','DATE ADDED'))))
              );

          $result         = $this->db->list_fields($tableName);
          $table_columns  = array();
          $tbc            = array();

          foreach ($result as $i => $field) {
              array_push($table_columns, $field);
          }

          $objPHPExcel = new PHPExcel();

          $objPHPExcel->getProperties()->setCreator("DOST-NRCP")
                 ->setLastModifiedBy("RDMD-MIS")
                 ->setTitle($filename)
                 ->setSubject($filename)
                 ->setDescription($filename)
                 ->setKeywords($filename)
                 ->setCategory($filename);

          $objPHPExcel->setActiveSheetIndex(0);

          $flag = count($header);

          foreach (range(0, $flag) as $flag) {
              $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($flag)->setAutoSize(true);
          }

          $column = 0;

          $excel_row = 2;

          if ($export == 'a') {
              foreach ($header as $field) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                  $column++;
              }

              foreach ($table_data as $row) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->art_id);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->art_title);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $this->Coauthor_model->get_author_coauthors($row->art_id));
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $this->Article_model->count_abstract($row->art_id));
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $this->Article_model->count_pdf($row->art_id));
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->date_created);

                  $excel_row++;
              }
          } elseif ($export == 'j') {
              foreach ($header as $field) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                  $column++;
              }

              foreach ($table_data as $row) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->jor_id);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->jor_volume);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->jor_issue);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->jor_month);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->jor_year);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->jor_issn);
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $this->Article_model->count_article_by_journal($row->jor_id));
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->date_created);

                  $excel_row++;
              }
          } else {
              foreach ($header as $field) {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                  $column++;
              }

              if ($export == 'c') {
                  $table_columns[0] = 'art_title';
              } elseif ($export == 'v') {
                  $table_columns[0] = 'art_title';
              }

              foreach ($table_data as $row) {
                  $content .= '<tr>';
                  for ($i = 0; $i < $flag; $i++) {
                      if ($table_columns[$i] == 'clt_sex') {
                          $sex = ($row->{$table_columns[$i]} == 1) ? 'Male' : 'Female' ;
                          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $excel_row, $sex);
                      } else {
                          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $excel_row, $row->{$table_columns[$i]});
                      }
                  }

                  $excel_row++;
              }
          }

          ob_end_clean();
          $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'.$filename.'_'.date('Y-m-d_h-i-s').'.xls"');
          $object_writer->save('php://output');
          exit;
      }

      /**
       * Exrpot pdf
       *
       * @param [string] $export    determine what to export
       * @param [type] $id      id
       * @return void
       */
      public function export_pdf($export, $id=null)
      {
          include_once(APPPATH ."/libraries/tcpdf/tcpdf.php");

          $log = (
        ($export == 'j') ?   save_log_ej(_UserIdFromSession(), 'just downloaded pdf file of journals.') :
             (($export == 'c') ?  save_log_ej(_UserIdFromSession(), 'just downloaded pdf file of clients information.') :
              (($export == 'e') ?  save_log_ej(_UserIdFromSession(), 'just downloaded excel file of editorial boards.') :
                (($export == 'v') ? save_log_ej(_UserIdFromSession(), 'just downloaded excel file of abstract hits.') :
                                     save_log_ej(_UserIdFromSession(), 'just downloaded excel file of articles.'))))
           );

          $tableName = (
        ($export == 'j') ? 'tbljournals' :
                   (($export == 'c') ? 'tblclients'  :
                    (($export == 'v') ? 'tblhits_abstract'  :
                      (($export == 'e') ? 'tbleditorials' :'tblarticles')))
                 );

          $table_data = (
        ($export == 'j') ?  $this->Export_excel_model->fetch_data_journals() :
                     (($export == 'c') ? $this->Export_excel_model->fetch_data_clients()  :
                      (($export == 'v') ? $this->Export_excel_model->fetch_data_abstract()  :
                       (($export == 'e') ? $this->Export_excel_model->fetch_data_editorials() : $this->Export_excel_model->fetch_data_articles($id))))
                  );

          $filename = (
        ($export == 'j') ? 'eJournal Journals' :
                   (($export == 'c') ? 'eJournal Client Information'  :
                    (($export == 'v') ? 'eJournal Abstract Hits'  :
                     (($export == 'e') ? 'eJournal Editorial Boards' :'eJournal Articles')))
                );
          $header = (
        ($export == 'j') ? array('ID','VOLUME','ISSUE','MONTH','YEAR','ISSN','ARTICLES','DATE ADDED') :
                 (($export == 'c') ? array('FULL TEXT PDF','TITLE','NAME','SEX','AFFILIATION','COUNTRY','EMAIL','PURPOSE','ARTICLE REFERENCE ID','IP ADDRESS','DOWNLOAD DATE TIME')  :
                  (($export == 'v') ? array('TITLE OF ARTICLE VIEWED','IP ADDRESS','ARTICLE REFERENCE ID','DATE VIEWED')  :
                   (($export == 'e') ? array('ID','NAME','POSITION','SEX','AFFILIATION','SPECIALIZATION','COUNTRY','EMAIL','DATE ADDED') :
                    array('ID','TITLE','AUTHOR/CO-AUTHORS','ABSTRACT HITS','FULL TEXT DOWNLOADS','DATE ADDED'))))
              );

          $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', 'A4-L');
          $obj_pdf->SetCreator(PDF_CREATOR);
          $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
          $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
          $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
          $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
          $obj_pdf->SetDefaultMonospacedFont('helvetica');
          $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
          $obj_pdf->setPrintHeader(false);
          $obj_pdf->setPrintFooter(false);
          $obj_pdf->SetAutoPageBreak(true, 10);
          $obj_pdf->SetFont('helvetica', '', 8);
          $obj_pdf->AddPage('L');
          $content = '';
          $content .= '
    <h3>'.$filename.'</h3></h5><br /><br /><br />
    <table class="table table-bordered table-striped table-responsive" border="1" cellspacing="0" cellpadding="5"><thead><tr>';

          // header
          $result         = $this->db->list_fields($tableName);
          $table_columns  = array();

          foreach ($result as $i => $field) {
              array_push($table_columns, $field);
          }

          $flag = count($header);

          //body
          if ($export == 'a') {
              foreach ($header as $col) {
                  $content .= '<th>'.$col.'</th>';
              }
              $content .= '</tr></thead>';

              foreach ($table_data as $row) {
                  $content .= '<tr>';
                  $content .= '<td>'.$row->art_id.'</td>';
                  $content .= '<td>'.$row->art_title.'</td>';
                  $content .= '<td>'.$this->Coauthor_model->get_author_coauthors($row->art_id).'</td>';
                  $content .= '<td>'.$this->Article_model->count_abstract($row->art_id).'</td>';
                  $content .= '<td>'.$this->Article_model->count_pdf($row->art_id).'</td>';
                  $content .= '<td>'.$row->date_created.'</td>';
                  $content .= '</tr>';
              }
          } elseif ($export == 'j') {
              foreach ($header as $col) {
                  $content .= '<th>'.$col.'</th>';
              }
              $content .= '</tr></thead>';

              foreach ($table_data as $row) {
                  $content .= '<tr>';
                  $content .= '<td>'.$row->jor_id.'</td>';
                  $content .= '<td>'.$row->jor_volume.'</td>';
                  $content .= '<td>'.$row->jor_issue.'</td>';
                  $content .= '<td>'.$row->jor_month.'</td>';
                  $content .= '<td>'.$row->jor_year.'</td>';
                  $content .= '<td>'.$row->jor_issn.'</td>';
                  $content .= '<td>'.$this->Article_model->count_article_by_journal($row->jor_id).'</td>';
                  $content .= '<td>'.$row->date_created.'</td>';
                  $content .= '</tr>';
              }
          } else {
              foreach ($header as $col) {
                  $content .= '<th>'.$col.'</th>';
              }
              $content .= '</tr></thead>';

              if ($export == 'c') {
                  $table_columns[0] = 'art_title';
              } elseif ($export == 'v') {
                  $table_columns[0] = 'art_title';
              }

              foreach ($table_data as $row) {
                  $content .= '<tr>';
                  for ($i = 0; $i < $flag; $i++) {
                      if ($table_columns[$i] == 'clt_sex') {
                          $sex = ($row->{$table_columns[$i]} == 1) ? 'Male' : 'Female' ;
                          $content .=    '<td>'.$sex.'</td>';
                      } else {
                          $content .=    '<td>'.$row->{$table_columns[$i]}.'</td>';
                      }
                  }
                  $content .= '</tr>';
              }
          }
          $content .= '</table>';
          $obj_pdf->writeHTML($content);
          ob_end_clean();
          $obj_pdf->Output($filename.'_'.date('Y-m-d_h-i-s'), 'D');
      }

      /**
       * Export popular articles to excel
       *
       * @return void
       */
      public function export_popular_excel()
      {
          $table_data =  $this->Article_model->top_five();

          $filename = 'List of Articles';

          $header = array('TITLE','AUTHOR/CO-AUTHORS','ABSTRACT VIEW','FULL TEXT PDF DOWNLOAD');

          $objPHPExcel = new PHPExcel();

          $objPHPExcel->getProperties()->setCreator("DOST-NRCP")
                 ->setLastModifiedBy("RDMD-MIS")
                 ->setTitle($filename)
                 ->setSubject($filename)
                 ->setDescription($filename)
                 ->setKeywords($filename)
                 ->setCategory($filename);

          $objPHPExcel->setActiveSheetIndex(0);

          $flag = count($header);

          foreach (range(0, $flag) as $flag) {
              $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($flag)->setAutoSize(true);
          }

          $column = 0;

          $excel_row = 2;


          foreach ($header as $field) {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
              $column++;
          }
          foreach ($table_data as $row) {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->art_title);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,  $this->Coauthor_model->get_author_coauthors($row->art_id));
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $this->Article_model->count_abstract($row->art_id));
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $this->Article_model->count_pdf($row->art_id));

              $excel_row++;
          }

          ob_end_clean();
          $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'.$filename.'_'.date('Y-m-d_h-i-s').'.xls"');
          $object_writer->save('php://output');
          exit;
      }

      /**
       * Export popular articles to pdf
       *
       * @return void
       */
      public function export_popular_pdf()
      {
          include_once(APPPATH ."/libraries/tcpdf/tcpdf.php");

          $table_data =  $this->Article_model->top_five();

          $filename = 'List of Articles';

          $header = array('TITLE','AUTHOR/CO-AUTHORS','ABSTRACT VIEW','FULL TEXT PDF DOWNLOAD');

          $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', 'A4-L');
          $obj_pdf->SetCreator(PDF_CREATOR);
          $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
          $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
          $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
          $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
          $obj_pdf->SetDefaultMonospacedFont('helvetica');
          $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
          $obj_pdf->setPrintHeader(false);
          $obj_pdf->setPrintFooter(false);
          $obj_pdf->SetAutoPageBreak(true, 10);
          $obj_pdf->SetFont('helvetica', '', 8);
          $obj_pdf->AddPage('L');
          $content = '';
          $content .= '<h3>LIST OF ARTICLES</h3><br /><br /><br />
                      <table class="table table-bordered table-striped table-responsive" border="1" cellspacing="0" cellpadding="5"><thead><tr>';


          $flag = count($header);

          foreach ($header as $col) {
              $content .= '<th>'.$col.'</th>';
          }

          $content .= '</tr></thead>';

          foreach ($table_data as $row) {
              $content .= '<tr>';
              $content .= '<td>'.$row->art_title.'</td>';
              $content .= '<td>'.$this->Coauthor_model->get_author_coauthors($row->art_id).'</td>';
              $content .= '<td>'.$this->Article_model->count_abstract($row->art_id).'</td>';
              $content .= '<td>'.$this->Article_model->count_pdf($row->art_id).'</td>';
              $content .= '</tr>';
          }

          $content .= '</table>';
          $obj_pdf->writeHTML($content);
          ob_end_clean();
          $obj_pdf->Output($filename.'_'.date('Y-m-d_h-i-s'), 'D');
      }
  }

/* End of file Export_excel.php */