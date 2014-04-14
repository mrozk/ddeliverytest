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
	
	public function getPathByDB(){
		return $_SERVER['DOCUMENT_ROOT'] . '/ddelivery/application/data/ddelivery.db';
	}

    protected function _getProductsFromCart()
    {
    	$products = array();
    	
    	$products[] = new \DDelivery\Order\DDeliveryProduct(1, 20, 13, 25, 0.5, 1000, 1, 'Веселый клоун');
    	$products[] = new \DDelivery\Order\DDeliveryProduct(2, 10, 13, 15, 0.3, 1500, 2, 'Грустный клоун') ;
    	
    	return $products;
    }
    

    public function getAmount()
    {
    	return 100.5;
    }

    /**
     * Должен вернуть url до каталога с статикой
     * @return string
     */
    public function getStaticPath()
    {
        // TODO: Implement getStaticPath() method.
    }

    /**
     * URL до скрипта где вызывается DDelivery::render
     * @return string
     */
    public function getPhpScriptURL()
    {
        // TODO: Implement getPhpScriptURL() method.
    }

}
?>
