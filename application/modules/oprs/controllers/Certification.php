<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once(APPPATH.'libraries/tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class Certification extends TCPDF {

  
    //Page header
    public function Header() {
        // Logo
        $nrcp = '/var/www/html/ejournal/assets/images/nrcp.png';
        // $nrcp = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/nrcp.png';
        $this->Image($nrcp, 15, 10, 25, '', 'PNG', '', 'L', false, 300, '', false, false, 0, false, false, false);

        $this->Ln(10);
        $this->SetFont('helvetica', 'R', 11);
        $this->Cell(0, 0, 'Republic of the Philippines', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(4);
        $this->SetFont('helvetica', 'R', 11);
        $this->Cell(0, 0, 'Department of Science and Technology', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->SetFont('helvetica', 'B',11);
        $this->Cell(0, 0, 'NATIONAL RESEARCH COUNCIL OF THE PHILIPPINES (NRCP)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->SetFont('helvetica', 'R',11);
        $this->Cell(0, 0, '(Pambansang Sanggunian sa Pananaliksik ng Pilipinas)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(7);
        $this->writeHTML("<hr>", true, false, false, false, '');

        $dost = '/var/www/html/ejournal/assets/images/dost.png';
        // $dost = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/dost.png';
        $this->Image($dost, 10, 11, 23, '', 'PNG', '', 'R', false, 300, 'R', false, false, 0, false, false, false);
    
       
    }

    // Page footer
    public function Footer() { 
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        
        $this->writeHTML("<hr>", true, false, false, false, '');
        // Page number
        $this->Cell(0, 0, 'NRCP', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        
            
        $nrcp = '/var/www/html/ejournal/assets/images/nrcp.png';
        // $nrcp = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/nrcp.png';
        $this->Image($nrcp, 180, 284, 6, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $dost = '/var/www/html/ejournal/assets/images/dost.png';
        // $dost = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/dost.png';
        $this->Image($dost, 174, 284.5, 5, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

       
    }

    public function generate_cert($rev_id, $manus_id){


        $dbhost = 'localhost';
        $dbuser = 'skms';
        $dbpass = '$km$@NRCP@1933';
        $dbname = 'dboprs';
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if($mysqli->connect_errno ) {
        printf("Connect failed: %s<br />", $mysqli->connect_error);
        exit();
        }
        
        $sql = 'SELECT * FROM tblreviewers r join tblmanuscripts m on m.row_id = r.rev_man_id WHERE rev_id LIKE "'.$rev_id.'" AND rev_man_id LIKE '.$manus_id.'';
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $rev_name = $row['rev_name'];             
            $man_title = $row['man_title'];             
            $rev_title = $row['rev_title'];             
            $rev_specialization = $row['rev_specialization'];              
        }
        $mysqli->close();


        ob_start(); 
        // create new PDF document
        $pdf = new Certification(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);


        $year = date('Y');
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('eJournal');
        $pdf->SetTitle('NRCP_Reviewer_Certification_'.$year);
        $pdf->SetSubject('NRCP_Reviewer_Certification_'.$year);
        $pdf->SetKeywords('NRCP_Reviewer_Certification_'.$year);
    
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
        }
    
        // ---------------------------------------------------------
    
        // set font
        $pdf->SetFont('helvetica', 'R', 12);

        // add a page
        $pdf->AddPage();

        // create columns content
        $left_column = '<h5>NRCP GOVERNING BOARD <br/>2022-2023</h5>
                        
                        <span style="font-size:8.5px;"><strong>LESLIE MICHELLE M. DALMACIO, Ph.D.</strong> 
                        <br/><em>President<em>
                        <br/><em>Chair</em>, Medical Science
                        
                        <br/><br/>

                        <strong>MARIBEL G. NONATO, Ph.D.</strong>
                        <br/><em>Vice President</em>
                        <br/><em>Chair<em/>, Chemical Sciences
                        
                        <br/><br/>
                        
                        <strong>MARIAN P. DE LEON, Ph.D.</strong>
                        <br/><em>Corporate Secretary</em>
                        <br/><em>Chair</em>, Biological Sciences
                        
                        <br/><br/>
                        
                        <strong>MICHAEL ANGELO B. PROMENTILLA, Ph.D.</strong>
                        <br/><em>Treasurer</em>
                        <br/><em>Chair</em>, Engineering and Industrial Research
                        
                        <br/><br/>
                        
                        <strong>AIMEE LYNN A. BARRION-DUPO, Ph.D.</strong>
                        <br/><em>Assistant Corporate Secretary</em>
                        <br/><em>Member-at-Large</em>
                        
                        <br/><br/>
                        
                        <strong>RIO JOHN T. DUCUSIN, Ph.D.</strong>
                        <br/><em>Assistant Treasurer</em>
                        <br/><em>Chair</em>, Veterinary Medicine
                        
                        <br/><br/>
                        
                        <strong>MARIE PAZ E. MORALES, Ph.D.</strong>
                        <br/><em>Chair</em>, Governmental, Educational,
                        <br/>and International Policies
                        
                        <br/><br/>
                        
                        <strong>MA. LOUISE ANTONETTE N. DE LAS PE�AS, Ph.D.</strong>
                        <br/><em>Chair</em>, Mathematical Sciences
                        
                        <br/><br/>
                        
                        <strong>ERNA C. AROLLADO, Ph.D.</strong>
                        <br/><em>Chair</em>, Pharmaceutical Sciences
                        
                        <br/><br/>
                        
                        <strong>RENATO SA. VEGA, DSc</strong>
                        <br/><em>Chair</em>, Agricultrual and Forestry
                        
                        <br/><br/>
                        
                        <strong>LAWRENCE B. DACUYCUY, DEc</strong>
                        <br/><em>Chair</em>, Social Sciences
                        
                        <br/><br/>
                        
                        <strong>JOSE PERICO H. ESGUERRA, Ph.D.</strong>
                        <br/><em>Chair</em>, Physics
                        
                        <br/><br/>
                        
                        <strong>JOYCE L. ARRIOLA, Ph.D.</strong>
                        <br/><em>Chair</em>, Humanities
                        
                        <br/><br/>
                        
                        <br/><strong>BETCHAIDA D. PAYOT, Ph.D.</strong>
                        <br/><em>Chair</em>, Earth and Space Sciences
                        
                        <br/><br/>

                        <strong>GISELA P. CONCEPCION, Ph.D.</strong>
                        <br/><em>Member-at-Large</em>
                        
                        <br/><br/>
                        
                        <strong>LEAH J. BUENDIA, Ph.D.</strong>
                        <br/><em>DOST Represnetative
                        <br/>to the NRCP Governing Board
                        <br/>DOST Undersecretary
                        <br/>for Research and Development</em>
                        
                        <br/><br/>
                        
                        <strong>SECRETARIAT</strong>
                        
                        <br/><br/>
                        
                        <strong>BERNARDO N. SEPEDA, Ed. D</strong>
                        <br/><em>Executive Director</em>
                        
                        </span>';

       
        
        // $right_column = '<b>CERTIFICATION</b>';
        $html = '<br><h2 style="text-align:center;">CERTIFICATION</h2><br>';

        $dbhost = 'localhost';
        $dbuser = 'skms';
        $dbpass = '$km$@NRCP@1933';
        $dbname = 'dboprs';
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if($mysqli->connect_errno ) {
        printf("Connect failed: %s<br />", $mysqli->connect_error);
        exit();
        }
        
        $sql = 'SELECT * FROM tblemail_notif_contents WHERE row_id LIKE 17';
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $email_content = $row['enc_content'];             
        }
        $mysqli->close();
    
        $dost = '/var/www/html/ejournal/assets/images/sepeda.png';
        // $dost = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/sepeda.png';
        // Image example with resizing
        $pdf->Image($dost, 110, 145, 60, 40, 'PNG', '', '', true, 100, '', false, false, 0, false, false, false);
        
        //replace reserved words
        $date_issue = date('F d, Y');

        $new_spec_array = array();
        $specializations_explode = explode(",", $rev_specialization);
        foreach($specializations_explode as $key => $value){
            array_push($new_spec_array, rtrim($value));
        }
        
        if(count($specializations_explode) > 1){
            $rev_specialization =  implode(",", $new_spec_array);
    
            if(strpos($rev_specialization, 'and') !== false){
                $specializations_explode = explode(",", $rev_specialization);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
            }else{
                
                $spec_array = substr_replace($rev_specialization, ', and ', strrpos($rev_specialization, ','), 1);
                $specializations_explode = explode(",", $spec_array);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
            }
        }else{
            $specializations_explode = explode(",", $rev_specialization);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
        }

        $email_content = str_replace('[REVIEWER]',strtoupper($rev_title) . ' ' . strtoupper($rev_name), $email_content);
        $email_content = str_replace('[MANUSCRIPT]',rtrim ($man_title), $email_content);
        $email_content = str_replace('[TITLE]',$rev_title, $email_content);
        $email_content = str_replace('[LAST NAME]',$rev_name, $email_content);
        $email_content = str_replace('[SPECIALIZATION]',$specializations, $email_content);
        $email_content = str_replace('[DATE]',$date_issue, $email_content);

        $html2 = '<span style="text-align:justify">'.$email_content.'</span>';

    
        // $cert_esig = '<br><br><br><br><span style="line-height:40%">
        //                 <span style="text-align:right;"><h3>BERNARDO N. SEPEDA, Ed. D</h3></span>
        //                 <span style="text-align:center;"><p>Executive Director</p></span>
        //               </span>';
        

        // print a block of text using Write()
        // $pdf->writeHTML($html2, true, false, true, false, '');


        // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
        // get current vertical position
        $y = $pdf->getY();

        // set color for background
        $pdf->SetFillColor(255, 255, 200);

        // set color for text
        $pdf->SetTextColor(0, 0, 0);

        // write the first column
        $pdf->writeHTMLCell(60, '', '', $y, $left_column, 0, 0, 0, true, 'L', true);

        // set color for background
        $pdf->SetFillColor(215, 235, 255);

        // set color for text
        $pdf->SetTextColor(0, 0, 0);

        // write the second column
        $pdf->writeHTMLCell(120, '', '', '', $html . $html2 . $cert_esig, 0, 1, 0, true, 'J', true);

        
        $pdf->Ln(15,$pdf->y,200,$pdf->y);
        // reset pointer to the last page
        $pdf->lastPage();

        // ---------------------------------------------------------

        
        // ---------------------------------------------------------
         ob_end_clean();
        //Close and output PDF document
        $pdf->Output($rev_name . '_NRCP_Reviewer_Certification.pdf', 'D');
    }

    public function generate_cert_local_test($rev_id, $manus_id){


        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'dboprs';
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if($mysqli->connect_errno ) {
        printf("Connect failed: %s<br />", $mysqli->connect_error);
        exit();
        }
        
        $sql = 'SELECT * FROM tblreviewers r join tblmanuscripts m on m.row_id = r.rev_man_id WHERE rev_id LIKE "'.$rev_id.'" AND rev_man_id LIKE '.$manus_id.'';
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $rev_name = $row['rev_name'];             
            $man_title = $row['man_title'];             
            $rev_title = $row['rev_title'];             
            $rev_specialization = $row['rev_specialization'];              
        }
        $mysqli->close();


        ob_start(); 
        // create new PDF document
        $pdf = new Certification(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $year = date('Y');
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('eJournal');
        $pdf->SetTitle('NRCP_Reviewer_Certification_'.$year);
        $pdf->SetSubject('NRCP_Reviewer_Certification_'.$year);
        $pdf->SetKeywords('NRCP_Reviewer_Certification_'.$year);
    
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
        }
    
        // ---------------------------------------------------------
    
        // set font
        $pdf->SetFont('helvetica', 'R', 12);

        // add a page
        $pdf->AddPage();

        // create columns content
        $left_column = '<h5>NRCP GOVERNING BOARD <br/>2022-2023</h5>
                        
                        <span style="font-size:8.5px;"><strong>CRISTINE D. VILLAGONZALO, Dr. rer. nat.</strong> 
                        <br/>President
                        <br/>Chair
                        <br/>Physics
                        
                        <br/><br/>

                        <strong>LESLIE MICHELLE M. DALMACIO, Ph.D.</strong>
                        <br/>Vice President
                        <br/>Chair
                        <br/>Medical Sciences
                        
                        <br/><br/>
                        
                        <strong>MARIE PAZ E. MORALES, Ph.D. .</strong>
                        <br/>Corporate Secretary
                        <br/>Chair
                        <br/>
                        Governmental, Educational,
                        and International Policies
                        
                        <br/><br/>
                        
                        <strong>MARIBEL G. NONATO, Ph.D.</strong>
                        <br/>Treasurer
                        <br/>Chair
                        <br/>Chemical Sciences
                        
                        <br/><br/>
                        
                        <strong>MARIAN P. DE LEON, Ph.D.</strong>
                        <br/>Assistant Corporate Secretary
                        <br/>Chair
                        <br/>Biological Sciences
                        
                        <br/><br/>
                        
                        <strong>MICHAEL ANGELO B. PROMENTILLA, Ph.D.</strong>
                        <br/>Assistant Treasurer
                        <br/>Chair
                        <br/>Engineering and Industrial Research
                        
                        <br/><br/>
                        
                        <strong>FIDEL R. NEMENZO, DSc</strong>
                        <br/>Chair
                        <br/>Mathematical Sciences
                        
                        <br/><br/>
                        
                        <strong>ERNA C. AROLLADO, Ph.D.</strong>
                        <br/>Chair
                        <br/>Pharmaceutical  Sciences
                        
                        <br/><br/>
                        
                        <strong>RENATO SA. VEGA, DSc</strong>
                        <br/>Chair
                        <br/>Agriculture and Forestry
                        
                        <br/><br/>
                        
                        <strong>LAWRENCE B. DACUYCUY, DEc</strong>
                        <br/>Chair
                        <br/>Social Sciences
                        
                        <br/><br/>
                        
                        <strong>HOPE S. YU, Ph.D.</strong>
                        <br/>Chair
                        <br/>Humanities
                        
                        <br/><br/>
                        
                        <strong>KARLO L. QUEA�O, Ph.D.</strong>
                        <br/>Chair
                        <br/>Earth and Space Sciences
                        
                        <br/><br/>
                        
                        <strong>RIO JOHN T. DUCUSIN, Ph.D.</strong>
                        <br/>Chair
                        <br/>Veterinary Medicine
                        
                        <br/><br/>
                        
                        <strong>AIMEE LYNN A. BARRION-DUPO, Ph.D. </strong>
                        <br/>Member-at-Large
                        
                        <br/><br/>
                        
                        <strong>GISELA P. CONCEPCION, Ph.D.</strong>
                        <br/>Member-at-Large
                        
                        <br/><br/>
                        
                        <strong>LEAH J. BUENDIA, Ph.D.</strong>
                        <br/>DOST Represnetative
                        <br/>to the NRCP Governing Board
                        <br/>DOST Undersecretary
                        <br/>for Research and Development
                        
                        <br/><br/>
                        
                        <strong>SECRETARIAT</strong>
                        
                        <br/><br/>
                        
                        <strong>BERNARDO N. SEPEDA, Ed. D</strong>
                        <br/>Executive Director
                        
                        </span>';

       
        
        // $right_column = '<b>CERTIFICATION</b>';
        $html = '<br><h2 style="text-align:center;">CERTIFICATION</h2><br>';

        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'dboprs';
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if($mysqli->connect_errno ) {
        printf("Connect failed: %s<br />", $mysqli->connect_error);
        exit();
        }
        
        $sql = 'SELECT * FROM tblemail_notif_contents WHERE row_id LIKE 20';
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $email_content = $row['enc_content'];             
        }
        $mysqli->close();
    
        // $dost = '/var/www/html/ejournal/assets/images/sepeda.png';
        $dost = $_SERVER['DOCUMENT_ROOT'] . '/ejournal/assets/images/sepeda.png';
        // Image example with resizing
        $pdf->Image($dost, 110, 145, 70, 30, 'PNG', '', '', true, 100, '', false, false, 0, false, false, false);
        
        //replace reserved words
        $date_issue = date('F d, Y');

        $new_spec_array = array();
        $specializations_explode = explode(",", $rev_specialization);
        foreach($specializations_explode as $key => $value){
            array_push($new_spec_array, rtrim($value));
        }
        
        if(count($specializations_explode) > 1){
            $rev_specialization =  implode(",", $new_spec_array);
    
            if(strpos($rev_specialization, 'and') !== false){
                $specializations_explode = explode(",", $rev_specialization);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
            }else{
                
                $spec_array = substr_replace($rev_specialization, ', and ', strrpos($rev_specialization, ','), 1);
                $specializations_explode = explode(",", $spec_array);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
            }
        }else{
            $specializations_explode = explode(",", $rev_specialization);
                $specializations_implode = implode(', ', $specializations_explode);
                $specializations = rtrim($specializations_implode);
        }

        $email_content = str_replace('[REVIEWER]',strtoupper($rev_title) . ' ' . strtoupper($rev_name), $email_content);
        $email_content = str_replace('[MANUSCRIPT]',rtrim ($man_title), $email_content);
        $email_content = str_replace('[TITLE]',$rev_title, $email_content);
        $email_content = str_replace('[LAST NAME]',$rev_name, $email_content);
        $email_content = str_replace('[SPECIALIZATION]',$specializations, $email_content);
        $email_content = str_replace('[DATE]',$date_issue, $email_content);

        $html2 = '<span style="text-align:justify">'.$email_content.'</span>';

    
        // $cert_esig = '<br><br><br><br><span style="line-height:40%">
        //                 <span style="text-align:right;"><h3>MARIETA BA�EZ SUMAGAYSAY, Ph.D.</h3></span>
        //                 <span style="text-align:center;"><p>Executive Director</p></span>
        //               </span>';
        

        // print a block of text using Write()
        // $pdf->writeHTML($html2, true, false, true, false, '');


        // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
        // get current vertical position
        $y = $pdf->getY();

        // set color for background
        $pdf->SetFillColor(255, 255, 200);

        // set color for text
        $pdf->SetTextColor(0, 0, 0);

        // write the first column
        $pdf->writeHTMLCell(60, '', '', $y, $left_column, 0, 0, 0, true, 'L', true);

        // set color for background
        $pdf->SetFillColor(215, 235, 255);

        // set color for text
        $pdf->SetTextColor(0, 0, 0);

        // write the second column
        // $pdf->writeHTMLCell(120, '', '', '', $html . $html2 . $cert_esig, 0, 1, 0, true, 'J', true);
        $pdf->writeHTMLCell(120, '', '', '', $html . $html2, 0, 1, 0, true, 'J', true);

        
        $pdf->Ln(15,$pdf->y,200,$pdf->y);
        // reset pointer to the last page
        $pdf->lastPage();

        // ---------------------------------------------------------

        
        // ---------------------------------------------------------
         ob_end_clean();
        //Close and output PDF document
        $pdf->Output($rev_name . '_NRCP_Reviewer_Certification.pdf', 'D');
    }

}



