<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class validateFiscalDataFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validar:constanciaFiscal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'valida constancia Fiscal de usuario a traves de la TV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sap_code = 9845903;
        $logExec = "[" . date('Y-m-d H:i:s') . "] Validación de PDF de constancia Fiscal TV: " . $sap_code . "\t";
        Storage::append("logValidaPDFFiscal.txt", $logExec);
    }
}
