<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteController {
    private $tramiteModel;

    public function __construct() {
        $this->tramiteModel = new Tramite();
    }

    public function index() {
        // Solo funcionarios y admin
        if (!in_array($_SESSION['user_role'], ['aduanas', 'admin'])) {
            header('Location: /');
            exit;
        }
        $db = Database::getInstance();
        $pasos = $db->find('pasos_fronterizos', ['activo' => true]);
        require_once __DIR__ . '/../views/reportes/index.php';
    }

    public function generar() {
        if (!in_array($_SESSION['user_role'], ['aduanas', 'admin'])) {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $paso = $_GET['paso'] ?? '';
        $formato = $_GET['formato'] ?? 'pdf';

        $inicioTimestamp = (new DateTime($fechaInicio))->getTimestamp() * 1000;
        $finTimestamp = (new DateTime($fechaFin))->getTimestamp() * 1000;

        $tramites = $this->tramiteModel->getEstadisticas($inicioTimestamp, $finTimestamp, $paso);

        if ($formato === 'excel') {
            $this->exportarExcel($tramites, $fechaInicio, $fechaFin);
        } else {
            $this->exportarPDF($tramites, $fechaInicio, $fechaFin);
        }
    }

    private function exportarExcel($tramites, $fechaInicio, $fechaFin) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte Aduanas');

        $sheet->setCellValue('A1', 'Paso Fronterizo');
        $sheet->setCellValue('B1', 'Total Trámites');
        $sheet->setCellValue('C1', 'Aprobados');
        $sheet->setCellValue('D1', 'Rechazados');
        $sheet->setCellValue('E1', 'Pendientes');

        $row = 2;
        foreach ($tramites as $t) {
            $sheet->setCellValue('A' . $row, $t->_id);
            $sheet->setCellValue('B' . $row, $t->total_tramites);
            $sheet->setCellValue('C' . $row, $t->aprobados);
            $sheet->setCellValue('D' . $row, $t->rechazados);
            $sheet->setCellValue('E' . $row, $t->pendientes);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte_' . $fechaInicio . '_' . $fechaFin . '.xlsx"');
        $writer->save('php://output');
        exit;
    }

    private function exportarPDF($tramites, $fechaInicio, $fechaFin) {
        $html = '<h1>Reporte de Trámites Aduaneros</h1>';
        $html .= '<p>Periodo: ' . $fechaInicio . ' al ' . $fechaFin . '</p>';
        $html .= '<p>Generado por: ' . htmlspecialchars($_SESSION['user_name'] ?? 'Sistema') . '</p>';
        $html .= '<table border="1" cellpadding="5" style="width:100%; border-collapse:collapse;">';
        $html .= '<tr style="background:#002B5C; color:white;"><th>Paso</th><th>Total</th><th>Aprobados</th><th>Rechazados</th><th>Pendientes</th></tr>';
        foreach ($tramites as $t) {
            $html .= '<tr>';
            $html .= '<td>' . $t->_id . '</td>';
            $html .= '<td>' . $t->total_tramites . '</td>';
            $html .= '<td>' . $t->aprobados . '</td>';
            $html .= '<td>' . $t->rechazados . '</td>';
            $html .= '<td>' . $t->pendientes . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<p style="margin-top:20px; font-size:12px; color:#666;">Fecha de generación: ' . date('d/m/Y H:i') . '</p>';

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('reporte_' . $fechaInicio . '_' . $fechaFin . '.pdf', ['Attachment' => 1]);
        exit;
    }
}