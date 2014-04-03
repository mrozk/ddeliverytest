<?php
/**
*
* @package    DDelivery
*
* @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
*
* @license    GNU General Public License version 2 or later; see LICENSE.txt
*
* @author  mrozk <mrozk2012@gmail.com>
*/
namespace DDelivery;
use DDelivery\Adapter\DShopAdapter;
use DDelivery\DataBase\City;
use DDelivery\DataBase\SQLite;
use DDelivery\Point\DDeliveryPointSelf;
use DDelivery\Sdk\DDeliverySDK;
use DDelivery\Order\DDeliveryOrder;
use DDelivery\Adapter\DShopAdapterImpl;
use DDelivery\Point\DDeliveryInfo;
use DDelivery\Point\DDeliveryAbstractPoint;

/**
 * DDeliveryUI - Обертка рабочих классов, для взаимодействия 
 * с системой DDelivery
 *
 * @package  DDelivery
 */
class DDeliveryUI
{
    /**
     * Поддерживаемые способы доставки
     * @var int[]
     */
    public $supportedTypes;
    /**
     * @var int
     */
    public $deliveryType = 0;

    /**
	 * Api обращения к серверу ddelivery
	 * 
	 * @var DDeliverySDK
	 */
    public  $sdk;
	
    /**
     * Адаптер магазина CMS
     * @var DShopAdapter
     */
    private $shop;
    
    /**
     * Заказ DDelivery
     * @var DDeliveryOrder
     */
    private $order;

    /**
     * @param DShopAdapter $dShopAdapter
     */
    public function __construct(DShopAdapter $dShopAdapter)
    {
        $this->sdk = new Sdk\DDeliverySDK($dShopAdapter->getApiKey(), true);
        
        $this->shop = $dShopAdapter;

        $this->order = new DDeliveryOrder($this->shop->getProductsFromCart());

        SQLite::$dbUri = $dShopAdapter->getPathByDB();

    }
    
    /**
     * Получить город по ip адресу
     * @var string $ip
     * 
     * @return array;
     */
    public function getCityByIp( $ip )
    {
    	$response = $this->sdk->getCityByIp( $ip );
    	
    	if( $response->success )
    	{
    		return $response->response;
    	}
    	else 
    	{
    		return 0;
    	}
    	
    }
    
    /**
     * Получить объект заказа
     * @var string $ip
     *
     * @return DDeliveryOrder;
     */
    public function getOrder( )
    {
        return $this->order;
    }
    
    /**
     * Получить информацию о заказе для точки
     * @var int $id
     * @deprecated
     * @return DDeliveryInfo;
     */
    public function getDeliveryInfoForPoint( $id )
    {
    	
    	$declaredPrice = 0;
    	$response = $this->sdk->calculatorPickup( $id, $this->order->getDimensionSide1(), 
    			                                  $this->order->getDimensionSide2(), $this->order->getDimensionSide3(), 
                                                  $this->order->getWeight(), $declaredPrice );

    	if(count( $response->success) )
    	{
    		
    		return new Point\DDeliveryInfo( $response->response );
    		
    	}
    	return null; 
    }
    
    /**
     * Получить курьерские точки для города
     * @var int $cityID
     *
     * @return array DDeliveryAbstractPoint;
     */
    public function getCourierPointsForCity( $cityID )
    {
    	$response = $this->sdk->calculatorCourier( $cityID, $this->order->getDimensionSide1(),
                                                   $this->order->getDimensionSide2(), $this->order->getDimensionSide3(),
                                                   $this->order->getWeight(), 0 );
    	
    	if( $response->success )
        {
    		$points = array();
    		if( count( $response->response ) )
    		{
    			foreach ($response->response as $p)
    			{
    				$point = new \DDelivery\Point\DDeliveryPointCourier();
    				$deliveryInfo = new \DDelivery\Point\DDeliveryInfo( $p );
    				$point->setDeliveryInfo($deliveryInfo);
    				$points[] = $point;
    			}
    		}
    		return $points;
        }
        else
        {
        	return 0;
        }
    	
    	
    }
    
    /**
     * Получить компании самовывоза для города
     * @var int $cityID
     *
     * @return array DDeliveryAbstractPoint;
     */
    public function getSelfDeliveryInfoForCity( $cityID )
    {
    	
    	$response = $this->sdk->calculatorPickupForCity( $cityID, $this->order->getDimensionSide1(),
                                                         $this->order->getDimensionSide2(), 
    			                                         $this->order->getDimensionSide3(),
                                                         $this->order->getWeight(), 0 );
    	
    	if( $response->success )
    	{
    		return $response->response;
    	}
    	else
    	{
    		return 0;
    	}
    }
    
    /**
     * Получить компании самовывоза для города
     * @var int $cityID
     *
     * @return array DDeliveryAbstractPoint;
     */
    public function getDeliveryInfoForPointID( $pointID )
    {
    	 
    	$response = $this->sdk->calculatorPickupForCompany( $pointID, $this->order->getDimensionSide1(),
    			$this->order->getDimensionSide2(),
    			$this->order->getDimensionSide3(),
    			$this->order->getWeight(), 0 );
    	if( $response->success )
    	{
    		return new Point\DDeliveryInfo( $response->response );
    	}
    	else
    	{
    		return 0;
    	}
    }
    
    private function _getOrderedDeliveryInfo( $companyInfo )
    {
    	$deliveryInfo = array();
    	foreach ( $companyInfo as $c )
    	{
    		$id = $c['delivery_company'];
    		$deliveryInfo[$id] = new Point\DDeliveryInfo( $c );
    	}
    	return $deliveryInfo;
    }
    
    /**
     *
     * отправить заказ на самовывоз
     *
     */
    public function createSelfOrder( )
    {
        /** @var DDeliveryPointSelf $point */
    	$point = $this->order->getPoint();
    	
    	if( $point == null || !$point->get('_id')  )
    	{
            throw new DDeliveryException('Empty delivery point');
    	}
    	
    	$pointID = $point->get('_id');
    	$dimensionSide1 = $this->order->getDimensionSide1();
    	$dimensionSide2 = $this->order->getDimensionSide2();
    	$dimensionSide3 = $this->order->getDimensionSide3();
    	$goods_description = $this->order->getGoodsDescription();
    	$weight = $this->order->getWeight();
    	$confirmed = $this->order->getConfirmed();
    	$to_name = $this->order->getToName();
    	$to_phone = $this->order->getToPhone();
    	
    	$this->sdk->addSelfOrder( $pointID, $dimensionSide1, $dimensionSide2,
                                  $dimensionSide3, $confirmed, $weight, $to_name,
                                  $to_phone, $goods_description, $declaredPrice, $paymentPrice );
    }
    
    /**
     * Получить компании самовывоза  для города с их 
     * полным описанием, и координатами их филиалов
     * 
     * 
     * @var int $cityID
     *
     * @return array DDeliveryAbstractPoint;
     */
    public function getSelfPoints( $cityID )
    {
    	
    	$points = $this->getSelfPointsForCityAndCompany(null, $cityID);
    	
    	$companyInfo = $this->getSelfDeliveryInfoForCity( $cityID );
    	
    	$deliveryInfo = $this->_getOrderedDeliveryInfo( $companyInfo );
    	
    	if( count( $points ) )
    	{
    		foreach ( $points as $item )
    		{
    			$companyID = $item->get('company_id');
    			
    			if( array_key_exists( $companyID, $deliveryInfo ) )
    			{
    				$item->setDeliveryInfo( $deliveryInfo[$companyID] );
    				
    			}
    		}
    	}
    	else 
    	{
    		throw new \DDelivery\DDeliveryException("Точек самовывоза не найдено");
    	}
    	
    	return $points;
    	
    }
    /**
     * Получить информацию о точке самовывоза по ее ID  и по ID города
     * 
     * @var mixed $cityID
     * @var mixed $companyIDs
     *
     * @return array;
     */
    public function getSelfPointsForCityAndCompany( $companyIDs, $cityID )
    {	
    	
    	$points = array();
    	
    	$response = $this->sdk->getSelfDeliveryPoints( $companyIDs, $cityID );
    	
    	if( $response->success )
    	{	
    		foreach ( $response->response as $p )
    		{	
    			    $points[] = new \DDelivery\Point\DDeliveryPointSelf( $p );
    		}
    	}
    	
    	return $points;
    }
    
    public function setOrderPoint( $point )
    {
    	$this->order->setPoint( $point );
    }
    
    public function setOrderToPhone( $phone )
    {
    	$this->order->setToPhone( $phone );
    }
    
    public function setOrderToName( $name )
    {
    	$this->order->setToName( $name );
    }
    
    public function setOrderToFlat( $flat )
    {
    	$this->order->setToFlat( $flat );
    }
    
    public function setOrderToHouse( $house )
    {
    	$this->order->setToHouse( $house );
    }
    
    public function setOrderToEmail( $email )
    {
    	$this->order->setToE( $house );
    }

    /**
     * Вызывается для рендера текущей странички
     * @param array $post
     * @throws DDeliveryException
     * @todo метод не финальный
     */
    public function render($post)
    {
        $deliveryType = (int) (isset($post['type']) ? $post['type'] : 0);
        $cityId = (int) (isset($post['city_id']) ? $post['city_id'] : 0);

        $supportedTypes = $this->shop->getSupportedType();

        if(!is_array($supportedTypes))
            $supportedTypes = array($supportedTypes);

        $this->supportedTypes = $supportedTypes;

        // Проверяем поддерживаем ли мы этот тип доставки
        if($deliveryType && !in_array($deliveryType, $supportedTypes))
            $deliveryType = 0;

        if(count($supportedTypes) > 1 && !$deliveryType) {
            echo $this->renderDeliveryTypeForm();
            return;
        }
        if(!$deliveryType)
            $deliveryType = reset($supportedTypes);

        $this->deliveryType = $deliveryType;

        switch($deliveryType) {
            case DDeliverySDK::TYPE_SELF:
                echo $this->renderMap($cityId);
                break;
            case DDeliverySDK::TYPE_COURIER:

                break;
            default:
                throw new DDeliveryException('Not support delivery type');
                break;
        }
    }

    protected function getCityByDisplay($cityId)
    {
        $cityDB = new City();
        $topCityId = $this->sdk->getTopCityId();
        $cityList = $cityDB->getCityListById($topCityId, true);

        // Складываем массивы получаем текущий город наверху, потом его и выберем
        if(isset($cityList[$cityId])){
            $cityData = $cityList[$cityId];
            unset($cityList[$cityId]);
            array_unshift($cityList, $cityData);
        }else{
            array_unshift($cityList, $cityDB->getCityById($cityId));
        }
        foreach($cityList as $key => $cityData){
            $cityList[$key]['display_name'] = $cityDB->getDisplayCityName($cityData);
        }
        return $cityList;
    }

    /**
     * Страница с картой
     * @param int $cityId
     * @return string
     */
    protected function renderMap($cityId)
    {
        $cityList = $this->getCityByDisplay($cityId);

        ob_start();
        include(__DIR__ . '/../../templates/map.php');
        $content = ob_get_contents();
        ob_end_clean();
        return json_encode(array('html'=>$content, 'js'=>''));
    }

    /**
     * Возвращает страницу с формой выбора способа доставки
     * @return string
     */
    protected function renderDeliveryTypeForm()
    {
        $cityId = (int)$this->shop->getClientCityId();

        if(!$cityId){
            $sdkResponse = $this->sdk->getCityByIp($_SERVER['REMOTE_ADDR']);
            if($sdkResponse && $sdkResponse->success && isset($sdkResponse->response['city_id'])) {
                $cityId = (int)$sdkResponse->response['city_id'];
            }
            if(!$cityId) {
                $topCityId = $this->sdk->getTopCityId();
                $cityId = reset($topCityId); // Самый большой город
            }
        }
        $cityList = $this->getCityByDisplay($cityId);

        $order = $this->order;

        $order->declaredPrice = $this->shop->getDeclaredPrice($order->getProducts());

        $this->sdk->calculatorPickupForCity($cityId,
            $order->getDimensionSide1(), $order->getDimensionSide2(), $order->getDimensionSide3(), $order->getWeight(),
            $order->declaredPrice
        );



        ob_start();
        include(__DIR__.'/../../templates/typeForm.php');
        $content = ob_get_contents();
        ob_end_clean();

        return json_encode(array('html'=>$content, 'js'=>''));
    }


}