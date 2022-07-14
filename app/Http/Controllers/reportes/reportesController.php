<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use PHPExcel_Worksheet_Drawing;

class reportesController extends Controller{
    public function selectData173($query){
        $connect = DB::connection('173');
            $info = $connect->select($query);
        \DB::disconnect('173');
        return $info;
    }

    public function getReport(Request $request){
        $reporte = request()->r;
        $parametros = request()->data;

        switch($reporte){
            case 'vjapong1':
                return $this->vjapong1($parametros);
            break;
        }
    }

    public function vjapong1($parametros){
        $fileName='Convocatoria Grupo 1 ' . rand();
        $grupo1 = $this->selectData173("SELECT Associateid,AssociateName,Rango,Tel,Email,Pais,Estado,VP_Jul,VP_Ago,VP_Sept,VP_Oct,VP_Acumulado, Cumple_VPAcumulado,Cumple_VP_MesJul,Cumple_VP_MesAgo,Cumple_VP_MesSept,Cumple_VP_MesOct,VGP_Jul,VGP_Ago,VGP_Sept,VGP_Oct,VGP_Acumulado, Cumple_VGPAcumulado,Cumple_VGP_MesJul,Cumple_VGP_MesAgo,Cumple_VGP_MesSept,Cumple_VGP_MesOct,CumpleTodo,Premio FROM RETOS_ESPECIALES.dbo.Report_ConvJapon_Grupo1 WITH(nolock);");
        
        \Excel::create($fileName, function($excel) use ($grupo1) {
            $excel->sheet('VOL', function($sheet) use ($query, $update) {
                $sheet->mergeCells('A1:AC1');
                $sheet->setFontFamily('Century Gothic');

                $sheet->cell('A1', function($cell) use ($update){
                    $cell->setValue('NIKKEN LATAM');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                    $cell->setFontSize(15);
                });

                $sheet->cell('A3', function($cell){
                    $cell->setValue('Codigo de influencer');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('B3', function($cell){
                    $cell->setValue('Nombre');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('C3', function($cell){
                    $cell->setValue('Rango');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('D3', function($cell){
                    $cell->setValue('Código Patrocinador');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('E3', function($cell){
                    $cell->setValue('Nombre Patrocinador');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('F3', function($cell){
                    $cell->setValue('VP_Diciembre');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('G3', function($cell){
                    $cell->setValue('VP Enero');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('H3', function($cell){
                    $cell->setValue('País');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('I3', function($cell){
                    $cell->setValue('Télefono');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('J3', function($cell){
                    $cell->setValue('Email');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                $sheet->cell('K3', function($cell){
                    $cell->setValue('Fecha incorporación');
                    $cell->setAlignment('center'); //Centramos contenido
                    $cell->setFontWeight('bold'); //Negritas
                });

                // Mostramos los registros
                /*foreach ($query as $idx => $row){
                    $idx = ($idx  + 4);
                    $sheet->cell('A'.$idx, function($cell) use ($row) {
                        $cell->setValue($row->Associateid);
                    });
                }*/
            });
        })->export('xls');
    }

    public function analisis_Mk_Inc_SisAgua(){
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['', 2010, 2011, 2012],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 0],
            ]
        );
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_AREACHART,
            DataSeries::GROUPING_PERCENT_STACKED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new ChartLegend(ChartLegend::POSITION_TOPRIGHT, null, false);

        $title = new Title('Test %age-Stacked Area Chart');
        $yAxisLabel = new Title('Value ($k)');

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea,
            true,
            DataSeries::EMPTY_AS_GAP,
            null,
            $yAxisLabel
        );
        $chart->setTopLeftPosition('A7');
        $chart->setBottomRightPosition('H20');
        $worksheet->addChart($chart);
        $filename = $helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);
    }
}
