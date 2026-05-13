<?php
 include 'fpdf/fpdf.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if format is selected and valid
    if (isset($_POST['format']) && ($_POST['format'] == 'pdf' || $_POST['format'] == 'excel')) {
        $format = $_POST['format'];

        // Sample data (you can replace this with your actual statement data)
        $statement = [
            ['Date', 'Description', 'Amount'],
            ['2024-07-24', 'Payment received', '$500'],
            ['2024-07-25', 'Invoice #123', '$300'],
            ['2024-07-26', 'Expense - Office supplies', '$50']
        ];

        // Export to PDF
        if ($format == 'pdf') {
           // Include FPDF library file

            // Create PDF using FPDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(40,10,'Statement', 0, 1, 'C');
            $pdf->Ln();

            // Output data in a table format
            foreach ($statement as $row) {
                foreach ($row as $column) {
                    $pdf->Cell(60, 10, $column, 1, 0, 'C');
                }
                $pdf->Ln();
            }

            // Output the PDF as download
            $pdf->Output('statement.pdf', 'D');
        }

        // Export to Excel
        elseif ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="statement.xls"');
            header('Cache-Control: max-age=0');

            // Output Excel data
            $output = fopen('php://output', 'w');
            foreach ($statement as $row) {
                fputcsv($output, $row, "\t");
            }
            fclose($output);
            exit;
        }
    }
}
