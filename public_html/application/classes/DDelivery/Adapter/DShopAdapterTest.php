<?php 

namespace DDelivery\Adapter;

class DShopAdapterTest extends DShopAdapter
{


    protected $cmsOrderStatus = array( DDStatusProvider::ORDER_IN_PROGRESS => 1,
                                       DDStatusProvider::ORDER_CONFIRMED => 2,
                                       DDStatusProvider::ORDER_IN_STOCK => 3,
                                       DDStatusProvider::ORDER_IN_WAY => 'Заказ в пути',
                                       DDStatusProvider::ORDER_DELIVERED => 'Заказ доставлен',
                                       DDStatusProvider::ORDER_RECEIVED => 'Заказ получен',
                                       DDStatusProvider::ORDER_RETURN => 'Возврат заказа',
                                       DDStatusProvider::ORDER_CUSTOMER_RETURNED => 'Клиент вернул заказ',
                                       DDStatusProvider::ORDER_PARTIAL_REFUND => 'Частичный возврат заказа',
                                       DDStatusProvider::ORDER_RETURNED_MI => 'Возвращен в ИМ',
                                       DDStatusProvider::ORDER_WAITING => 'Ожидание',
                                       DDStatusProvider::ORDER_CANCEL => 'Отмена' );


    /**
     * Возвращает API ключ, вы можете получить его для Вашего приложения в личном кабинете
     * @return string
     */
	public function getApiKey()
	{
		return '4bf43a2cd2be3538bf4e35ad8191365d'; 
	}
	
	public function getPathByDB(){
		
		return '/home/mrozk/git/ddelivery_front_module/public_html/tests/ddelivery.db';
	}

    protected function _getProductsFromCart()
    {
    	$products = array();
    	
    	$products[] = new \DDelivery\Order\DDeliveryProduct( 1, 2, 6, 2, 
    			                                             1, 100, 2, 'Пиджак' );
    	$products[] = new \DDelivery\Order\DDeliveryProduct(2, 3, 1,  
    			                                            1, 1, 200, 1,'Куртка кожанная') ;
    	
    	return $products;
    }
    
    public function getAmount()
    {
    	return 100.5;
    }
    
    
    public function getOrderPrice()
    {
    	
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

    public function setCmsOrderStatus( $orderID, $status )
    {

    }

    public function isStatusToSendOrder(  $status, $order )
    {
        return true;
    }

    public function getShopOrderInfo( $orderID )
    {
        return array('id' => $orderID, 'status' => 4,'payment' => 6 );
    }


    public function isTestMode()
    {
        return false;
    }
}
?>
