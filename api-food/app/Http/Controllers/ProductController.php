<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenFoodService;
use App\Models\Product;
use Carbon\Carbon;
use Exception;

class ProductController extends Controller
{
    protected $openFoodService;

    public function __construct(OpenFoodService $openFoodService)
    {
        $this->openFoodService = $openFoodService;
    }

    /**
     * Displays API details, such as database connection status, uptime, and memory usage.
     * 
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function apiDetails()
    {
        try {
            $dbConnection = \DB::connection()->getPdo() ? true : false;
            $cronLastRun = cache('cron_last_run', 'Awaiting execution...');
            $requestStart = Carbon::createFromTimestamp(request()->server('REQUEST_TIME_FLOAT'));
            $uptime = $requestStart->diffForHumans(now(), true);
            $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2) . ' MB';

            return response()->json([
                'db_connected'  => $dbConnection,
                'cron_last_run' => $cronLastRun,
                'uptime'        => $uptime,
                'memory_usage'  => $memoryUsage,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve API details.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieves a paginated list of all products from the database.
     * 
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function index()
    {
        try {
            $products = Product::paginate(10);
            return response()->json($products, 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch products.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieves a single product based on the provided code.
     * 
     * @param string $code
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function show($code)
    {
        try {
            $product = Product::where('code', $code)->first();

            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            return response()->json($product, 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve product.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Updates product details based on the provided code.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function update(Request $request, $code)
    {
        try {
            $product = Product::where('code', $code)->first();
            
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $product->update($request->all());

            return response()->json(['message' => 'Product updated successfully.'], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update product.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Moves the product status to 'trash'.
     * 
     * @param string $code
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function destroy($code)
    {
        try {
            $product = Product::where('code', $code)->first();

            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            $product->status = 'trash';
            $product->save();

            return response()->json(['message' => 'Product moved to trash successfully.'], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to move product to trash.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lists available files from Open Food Facts.
     * 
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function filesOpenFood()
    {
        try {
            $files = $this->openFoodService->fetchIndexFile();
            return response()->json($files, 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch files from Open Food Facts.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imports a file from Open Food Facts.
     * 
     * @param string $file
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function importFileOpenFood($file)
    {
        try {
            $products = $this->openFoodService->fetchFile(fileUrl: $file);

            if (empty($products)) {
                return response()->json(['message' => 'File does not exist or is empty.'], 404);
            }

            foreach($products as $product) {
                $product["status"] = "published";
                $product["imported_t"] = Carbon::now();

                Product::updateOrCreate(["code" => $product["code"]], $product);
            }

            return response()->json(['message' => 'File imported successfully.'], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to import file.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imports all available files from Open Food Facts.
     * 
     * @return \Illuminate\Http\JsonResponse 
     * @version 1.0.0
     * @author Samuel Verissimo
    */
    public function importAllFilesOpenFood()
    {
        try {
            $files = $this->openFoodService->fetchIndexFile();

            foreach($files as $file) {
                $products = $this->openFoodService->fetchFile(fileUrl: $file);

                foreach($products as $product) {
                    $product["status"] = "published";
                    $product["imported_t"] = Carbon::now();
    
                    Product::updateOrCreate(["code" => $product["code"]], $product);
                }
            }

            return response()->json(['message' => 'All files imported successfully.'], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to import all files.', 
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}