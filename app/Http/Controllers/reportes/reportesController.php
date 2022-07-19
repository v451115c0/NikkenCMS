<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\ChartColor;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
            case 'analisis_Mk_Inc_SisAgua':
                return $this->analisis_Mk_Inc_SisAgua($parametros);
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

    public function analisis_Mk_Inc_SisAgua($parametros){
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['MOKUTEKI ', 'PIWATER', 'KIT NORMAL ', 'WATERFALL ', 'WATERFALL + OPTIMIZER', 'OPTIMIZER', 'PIWATER + OPTIMIZER ', 'AQUAPOURT'],
                [19798, 2367, 2132, 1667, 143, 121, 47, 43],
                ['75.23%', '8.99%', '8.10%', '6.33%', '0.54%', '0.46%', '0.18%', '0.16%'],
            ]
        );

        $colors = [
            'cccccc', '00abb8', 'b8292f', 'eb8500',
        ];
        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$1', null, 1), // 2011
        ];
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$1:$H$1', null, 4), // Q1 to Q4
        ];
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$A$2:$H$2', null, 4, [], null, $colors),
        ];
        $labelLayout = new Layout();
        $labelLayout
            ->setShowVal(true)
            ->setLabelFontColor(new ChartColor('FFFF00'))
            ->setLabelFillColor(new ChartColor('accent2', null, 'schemeClr'));
        $dataSeriesValues1[0]->setLabelLayout($labelLayout);

        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART,
            null,
            range(0, count($dataSeriesValues1) - 1),
            $dataSeriesLabels1,
            $xAxisTickValues1,
            $dataSeriesValues1
        );

        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);

        $plotArea1 = new PlotArea($layout1, [$series1]);
        $legend1 = new ChartLegend(ChartLegend::POSITION_RIGHT, null, false);

        $title1 = new Title('Test Bar Chart');

        $chart1 = new Chart(
            'chart1',
            $title1,
            $legend1,
            $plotArea1,
            true,
            DataSeries::EMPTY_AS_GAP,
            null,
            null
        );
        $majorGridlinesY = new GridLines();
        $majorGridlinesY->setLineColorProperties('FF0000');
        $minorGridlinesY = new GridLines();
        $minorGridlinesY->setLineStyleProperty('dash', Properties::LINE_STYLE_DASH_ROUND_DOT);
        $chart1
            ->getChartAxisY()
            ->setMajorGridlines($majorGridlinesY)
            ->setMinorGridlines($minorGridlinesY);
        $majorGridlinesX = new GridLines();
        $majorGridlinesX->setLineColorProperties('FF00FF');
        $minorGridlinesX = new GridLines();
        $minorGridlinesX->activateObject();
        $chart1
            ->getChartAxisX()
            ->setMajorGridlines($majorGridlinesX)
            ->setMinorGridlines($minorGridlinesX);

        $chart1->setTopLeftPosition('A13');
        $chart1->setBottomRightPosition('J26');

        $worksheet->addChart($chart1);


        $filename = "test.xlsx";
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);
        $myFile = public_path("test.xlsx");
    	return response()->download($myFile);
    }
}
