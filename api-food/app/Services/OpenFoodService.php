<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenFoodService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://challenges.coode.sh/food/data/json/';
    }

    /**
     * Buscar os nomes dos arquivos externamente
     * @return array
    */
    public function fetchIndexFile()
    {
        try {
            $indexUrl = $this->baseUrl . 'index.txt';
            $response = Http::get($indexUrl);

            $files = array_filter(explode("\n", $response->body()), function ($file) {
                return !empty(trim($file));  
            });
    
            return $files;

        } catch (\Exception $e) {
            Log::channel('import')->error('Erro ao buscar o arquivo de index: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Baixar e processar dados da URL do arquivo 
     * @param mixed $fileUrl
     * @return array
    */
    public function fetchFile($fileUrl)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        try {
            Log::channel('import')->info('Iniciando download do arquivo: ' . $fileUrl);

            $response = Http::timeout(300)->get($this->baseUrl . $fileUrl);

            if ($response->status() !== 200) {
                Log::channel('import')->warning('Arquivo não encontrado: ' . $fileUrl);
                return [];
            }

            // Salvar o arquivo compactado temporariamente
            $gzFile = $response->body();
            $tempGzFile = tempnam(sys_get_temp_dir(), 'openfood') . '.gz';
            file_put_contents($tempGzFile, $gzFile);

            $resource = gzopen($tempGzFile, 'r');

            if (!$resource) {
                throw new \Exception('Não foi possível abrir o arquivo gzip.');
            }

            $products = [];
            $count = 0;

            // Processar cada linha como um objeto JSON
            while (!gzeof($resource) && $count < 100) {
                $line = gzgets($resource);     
                $product = json_decode($line, true);
    
                if (json_last_error() === JSON_ERROR_NONE && !empty($product)) {
                    $product['code'] = ltrim($product['code'], '"'); 
                    $products[] = $product;
                    $count++;
                }
            }

            gzclose($resource);  
            unlink($tempGzFile);  

            Log::channel('import')->info('Leitura de 100 produtos concluída.');

            return $products;  

        } catch (\Exception $e) {
            Log::channel('import')->error('Erro ao processar o arquivo: ' . $fileUrl . ' - ' . $e->getMessage());
            return [];
        }
    }
}
