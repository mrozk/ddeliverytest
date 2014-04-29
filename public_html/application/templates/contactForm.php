<?/**
 * @var int $requiredFieldMask
 * @var \DDelivery\Order\DDeliveryOrder $order
 */
use DDelivery\Adapter\DShopAdapter;

?>
<div class="map-popup">

    <div class="map-popup__head">
        <p>Уточнение улицы</p>

        <div class="map-popup__head__close">&nbsp;</div>
    </div>
    <!--map-popup__head end-->
    <div class="map-popup__main">
        <form id="main_form" method="post" action="">
            <div class="map-popup__main__form">
                <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_LAST_NAME || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_SECOND_NAME && !$order->secondName) ):?>
                    <div class="row clearfix">
                        <div class="row__title">
                            <label for="second_name">Фамилия</label>
                        </div>
                        <div class="row__inp <?//error?>">
                            <input type="text" title="Иванов" id="second_name" name="second_name" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_SECOND_NAME):?>required="required"<?endif;?> value="<?=htmlspecialchars(trim($order->secondName))?>"/>
                            <div class="error-box">
                                <i>&nbsp;</i> Поле обязательное для заполнения
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_FIRST_NAME || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_FIRST_NAME && !$order->firstName ) ):?>
                    <div class="row clearfix">
                        <div class="row__title">
                            <label for="first_name">Имя</label>
                        </div>
                        <div class="row__inp">
                            <input type="text" title="Иван" id="first_name" name="first_name" value="<?=htmlspecialchars(trim($order->firstName))?>" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_FIRST_NAME):?>required="required"<?endif;?>/>
                            <div class="error-box">
                                <i>&nbsp;</i> Поле обязательное для заполнения
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_PHONE || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_PHONE  && !$order->getToPhone() ) ):?>
                    <div class="row clearfix">
                        <div class="row__title">
                            <label for="phone">Мобильный телефон</label>
                        </div>
                        <div class="row__inp">
                            <input type="tel" class="phone-mask" id="phone" name="phone" value="<?=htmlspecialchars(trim($order->getToPhone()))?>" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_PHONE):?>required="required"<?endif;?>/>
                            <div class="error-box">
                                <i>&nbsp;</i> Поле обязательное для заполнения
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS && !$order->getToStreet() ) ):?>
                    <div class="row clearfix">
                        <div class="row__title">
                            <label for="address">Адрес</label>
                        </div>
                        <div class="row__inp">
                            <input type="text" title="Улица" id="address" name="address" value="<?=htmlspecialchars(trim($order->getToStreet()))?>" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS):?>required="required"<?endif;?>/>
                            <div class="error-box">
                                <i>&nbsp;</i> Поле обязательное для заполнения
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <?if(
                    ($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_HOUSING || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSING ))
                    || ($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_HOUSE || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSE && !$order->getToHouse() ))
                    || ($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_HOUSING || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSING && !$order->getToHouse() ))
                    || ($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_FLAT || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_FLAT && !$order->getToFlat() ))
                ):?>
                    <div class="row row_pl clearfix">
                        <div class="row__inp">
                            <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_HOUSE || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSE && !$order->getToHouse() )):?>
                                <input type="text" title="Дом" class="small" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSE):?>required="required"<?endif;?> name="address_house" value="<?=htmlspecialchars(trim($order->getToHouse()))?>"/>
                            <?endif;?>
                            <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_HOUSING || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSING )):?>
                                <input type="text" title="Корпус" class="small" name="address_housing"  value="<?=htmlspecialchars(trim($order->getToHousing()))?>" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_HOUSING):?>required="required"<?endif;?>/>
                            <?endif;?>
                            <?if($requiredFieldMask & DShopAdapter::FIELD_EDIT_ADDRESS_FLAT || ($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_FLAT && !$order->getToFlat() )):?>
                                <input type="text" title="Квартира" class="small" name="address_flat" value="<?=htmlspecialchars(trim($order->getToFlat()))?>" <?if($requiredFieldMask & DShopAdapter::FIELD_REQUIRED_ADDRESS_FLAT):?>required="required"<?endif;?>/>
                            <?endif;?>
                            <div class="error-box">
                                <i>&nbsp;</i> Поле обязательное для заполнения
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <div class="row clearfix">
                    <div class="row__title">
                        <label for="comment">Коментарий</label>
                    </div>
                    <div class="row__inp">
                        <textarea id="comment" name="comment" title="Напишите комментарий"></textarea>
                    </div>
                </div>
                <div class="row-btns clearfix">
                    <a class="prev" href="javascript:void(0)"><i>&nbsp;</i>назад</a>
                    <a class="next" href="javascript:void(0)"><i>&nbsp;</i>Доставить по этому адресу</a>
                </div>

            </div>
        </form>
    </div>
    <div class="map-popup__bott">
        <a href="http://ddelivery.ru" target="blank">Сервис доставки DDelivery.ru</a>
    </div>

</div>