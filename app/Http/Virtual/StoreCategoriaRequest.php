<?php




namespace App\Virtual;




/**

 * @OA\Schema(

 *      title="Requisicao para nova Categoria",

 *      description="Requisição enviada via Body para uma nova Categoria",

 *      type="object",

 *      required={"nome_da_categoria"}

 * )

 */




class StoreCategoriaRequest

{

    /**

     * @OA\Property(

     *      title="nome da categoria",

     *      description="Nome da nova categoria",

     *      example="Bicicleta"

     * )

     * 

     * @var string

     */




    public $nome_da_categoria;
}
