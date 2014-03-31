<?php 

namespace DDelivery\Adapter;

class DShopAdapterImpl extends DShopAdapter
{
    /**
     * Возвращает API ключ, вы можете получить его для Вашего приложения в личном кабинете
     * @return string
     */
	public function getApiKey()
	{
		return '4bf43a2cd2be3538bf4e35ad8191365d'; 
	}
	
    public function getProductsFromCart()
    {
    	$products = array();
    	$prduct_item = array( 'id' => 1, 'width' => 2, 'height' => 6,
                              'length' => 2, 'weight' => 1, 'price' => 100, 'quantity' => 2 );
    	
    	$prduct_item2 = array( 'id' => 2, 'width' => 3, 'height' => 1,
    			'length' => 4, 'weight' => 1, 'price' => 200, 'quantity' => 1  );
    	
    	$products[] = new \DDelivery\Order\DDeliveryProduct( $prduct_item );
    	$products[] = new \DDelivery\Order\DDeliveryProduct( $prduct_item2 );
    	
    	return $products;
    }
    
    public function getOrderPrice()
    {
    	
    }
}
?>
