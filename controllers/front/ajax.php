<?php

class FirstModuleAjaxModuleFrontController extends ModuleFrontController {
    
    public function __construct()
    {
        parent::__construct();
    } 

    public function init()
    {
        parent::init();
    } 

    public function initContent()
    {
        parent::initContent(); 

        if ($this->ajax) 
        { 
            $context = Context::getContext();
            $id_lang = $context->language->id;
            $link = $context->link;
            $productsData = Product::getProducts($id_lang, 0, 5, 'id_product', 'ASC');
            $products = [];
            
            foreach ($productsData as $product) {
                $images = Image::getImages($id_lang, $product['id_product']);
                $productImages = [];

                foreach ($images as $image) {
                    $imageUrl = $link->getImageLink($product['link_rewrite'], $image['id_image']);
                    $productImages[] = $imageUrl;
                }

                $products[] = [
                    'id_product' => $product['id_product'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'images' => $productImages
                ];
            }

            $response = [ 'success' => true, 'data' => $products ]; 
            die(Tools::jsonEncode($response));
        } 
        else 
        {
            die(Tools::jsonEncode(['success' => false, 'message' => 'Invalid request']));
        }
    }
}
