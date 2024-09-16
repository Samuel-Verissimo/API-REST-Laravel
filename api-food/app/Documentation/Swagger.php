<?php

namespace App\Documentation;

/**
 * @OA\Info(
 *    title="V4 Company | Backend Challenge 20230105",
 *    version="1.0.0",
 *    description="API para utilizar os dados do projeto Open Food Facts, fornecendo informações nutricionais dos produtos."
 * )
 *
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoint para autenticação"
 * )
 * 
 * @OA\Tag(
 *     name="Healthcheck",
 *     description="Detalhes da API"
 * )
 *
 * @OA\Tag(
 *     name="Produtos",
 *     description="Endpoints relacionados aos produtos"
 * )
 * 
 * @OA\Tag(
 *     name="Bônus",
 *     description="Endpoints de integrações com Open Food Facts"
 * )
 * 
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 * )
*/

class Swagger
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autenticar o usuário e gerar o JWT",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="pwd_test")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso, retorna o JWT",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas."
     *     ),
     * )
    */
    private function _docLogin(){}

    /**
     * @OA\Get(
     *     path="/api/",
     *     summary="Obter detalhes da API",
     *     tags={"Healthcheck"},
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da API recuperados com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="db_connected", type="boolean", example=true),
     *             @OA\Property(property="cron_last_run", type="string", example="Awaiting execution..."),
     *             @OA\Property(property="uptime", type="string", example="1 hour ago"),
     *             @OA\Property(property="memory_usage", type="string", example="12.5 MB")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao recuperar os detalhes da API."
     *     )
     * )
    */
    private function _docApiDetails(){}

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Listar todos os produtos com paginação",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos retornada com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Produto 1"),
     *                 @OA\Property(property="price", type="number", example=100.50)
     *             )),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar produtos."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docIndex(){}

    /**
     * @OA\Get(
     *     path="/api/products/{code}",
     *     summary="Obter detalhes de um produto específico",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="Código do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto encontrado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Produto 1"),
     *             @OA\Property(property="price", type="number", example=99.99)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao recuperar o produto."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docShow(){}

    /**
     * @OA\Put(
     *     path="/api/products/{code}",
     *     summary="Atualizar um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="Código do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_name", type="string", example="Nome de teste"),
     *             @OA\Property(property="url", type="string", example="URL de teste")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar o produto."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docUpdate(){}

    /**
     * @OA\Delete(
     *     path="/api/products/{code}",
     *     summary="Excluir um produto (mover para 'trash')",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="Código do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto movido para 'trash' com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao mover o produto para 'trash'."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docDelete(){}

    /**
     * @OA\Get(
     *     path="/api/food/files",
     *     summary="Listar os arquivos disponíveis na Open Foods Facts",
     *     tags={"Bônus"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Arquivos listados com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar os arquivos da Open Food Facts."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docOpenFoodStringFiles(){}

    /**
     * @OA\Post(
     *     path="/api/food/import/{file}",
     *     summary="Importar um arquivo disponível na Open Foods Facts",
     *     tags={"Bônus"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="file",
     *         in="path",
     *         required=true,
     *         description="Nome do arquivo",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Arquivo importado com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Arquivo não encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao importar o arquivo."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docImportFileOpenFood(){}

    /**
     * @OA\Post(
     *     path="/api/food/import/all/files",
     *     summary="Importar todos os arquivos disponíveis na Open Foods Facts",
     *     tags={"Bônus"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Todos os arquivos foram importados com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Arquivos não encontrados."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao importar todos os arquivos."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sem autorização."
     *     )
     * )
    */
    private function _docImportAllFilesOpenFood(){}
}
