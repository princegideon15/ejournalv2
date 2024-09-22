<?php if (!defined('BASEPATH')) {
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
class Export extends OPRS_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Report_model');
		$this->load->model('Manuscript_model');
		$this->load->model('Coauthor_model');
	}
	/** this function export excel from the database */
	public function export_excel($export, $id = null) {
		$table_data = ($export == 'm') ? $this->Report_model->get_list_manus() : $this->Manuscript_model->get_completed_review();
		$filename = ($export == 'm') ? 'List of Manuscripts' : 'List of Reviewers';
		$header = ($export == 'm') ? array('#', 'TITLE', 'STATUS', 'DATE SUBMITTED') : array('#', 'REVIEWER', 'MANUSCRIPT', 'STATUS', 'DATE REVIEWED');
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
		$row_count = 0;
		if ($export == 'm') {
			foreach ($table_data as $row) {
				$row_count++;
				$acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$date = date_format(new DateTime($row->date_created), 'F j, Y');
				$status = (($row->man_status == '1') ? 'New' :
					((($row->man_status == '2') ? 'On-review' :
						((($row->man_status == '3') ? 'Reviewed' :
							((($row->man_status == '4') ? 'For Approval' :
								'Published')))))));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row_count);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $title);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $date);
				$excel_row++;
			}
		} else {
			foreach ($table_data as $row) {
				$row_count++;
				$acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$status = (($row->scr_status == '4') ? 'Approved' :
					((($row->scr_status == '5') ? 'Needs Revision' :
						'Disapproved')));
				$rev_name = $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id);
				$date = date_format(new DateTime($row->date_reviewed), 'F j, Y');
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row_count);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $rev_name);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $title);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $status);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $date);
				$excel_row++;
			}
		}
		ob_end_clean();
		$object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d_h-i-s') . '.xls"');
		$object_writer->save('php://output');
		exit;
	}
	/** this function export pdf from the database */
	public function export_pdf($export, $id = null) {
		include_once APPPATH . "/libraries/tcpdf/tcpdf.php";
		// $log = (
		//  ($export == 'j') ? logs(_UserIdFromSession(), 'just downloaded pdf file of journals.') :
		//  (($export == 'c') ? logs(_UserIdFromSession(), 'just downloaded pdf file of clients information.') :
		//    (($export == 'e') ? logs(_UserIdFromSession(), 'just downloaded excel file of editorial boards.') :
		//      (($export == 'v') ? logs(_UserIdFromSession(), 'just downloaded excel file of abstract hits.') :
		//        logs(_UserIdFromSession(), 'just downloaded excel file of articles.'))))
		// );
		$table_data = ($export == 'm') ? $this->Report_model->get_list_manus() : $this->Manuscript_model->get_completed_review();
		$filename = ($export == 'm') ? 'List of Manuscripts' : 'List of Reviewers';
		$header = ($export == 'm') ? array('#', 'TITLE', 'STATUS', 'DATE SUBMITTED') : array('#', 'REVIEWER', 'MANUSCRIPT', 'STATUS', 'DATE REVIEWED');
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
<h3>' . $filename . '</h3></h5><br /><br /><br />
<table border="1" cellspacing="0" cellpadding="5"><thead><tr>';
		$flag = count($header);
		$row_count = 0;
		//body
		if ($export == 'm') {
			$content .= '<td width="20">' . $header[0] . '</td>
	<td width="350">' . $header[1] . '</td>
	<td width="80">' . $header[2] . '</td>
	<td width="80">' . $header[3] . '</td>
</tr></thead>';
			foreach ($table_data as $row) {
				$row_count++;
				$acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$date = date_format(new DateTime($row->date_created), 'F j, Y');
				$status = (($row->man_status == '1') ? 'New' :
					((($row->man_status == '2') ? 'On-review' :
						((($row->man_status == '3') ? 'Reviewed' :
							((($row->man_status == '4') ? 'For Approval' :
								'Published')))))));
				$content .= '<tr>
	<td  width="20">' . $row_count . '</td>
	<td width="350">' . $title . '</td>
	<td width="80">' . $status . '</td>
	<td width="80">' . $date . '</td>
</tr>';
			}
		} else {
			$content .= '<td width="20">' . $header[0] . '</td>
<td width="100">' . $header[1] . '</td>
<td width="350">' . $header[2] . '</td>
<td width="80">' . $header[3] . '</td>
<td width="80">' . $header[4] . '</td>
</tr></thead>';
			foreach ($table_data as $row) {
				$row_count++;
				$acoa = (empty($this->Coauthor_model->get_author_coauthors($row->row_id))) ? '' : ', ' . $this->Coauthor_model->get_author_coauthors($row->row_id);
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$status = (($row->scr_status == '4') ? 'Approved' :
					((($row->scr_status == '5') ? 'Needs Revision' :
						'Disapproved')));
				$rev_name = $this->Manuscript_model->get_reviewer_name($row->scr_man_rev_id);
				$date = date_format(new DateTime($row->date_reviewed), 'F j, Y');
				$title = $row->man_title . ', ' . $row->man_author . $acoa;
				$content .= '<tr>
<td  width="20">' . $row_count . '</td>
<td  width="100">' . $rev_name . '</td>
<td width="350">' . $title . '</td>
<td width="80">' . $status . '</td>
<td width="80">' . $date . '</td>
</tr>';
			}
		}
		$content .= '</table>';
		$obj_pdf->writeHTML($content);
		ob_end_clean();
		$obj_pdf->Output($filename . '_' . date('Y-m-d_h-i-s'), 'D');
	}
}