<?php
namespace DDelivery\Point;

class DDeliveryPointSelf extends DDeliveryAbstractPoint{

    protected $allowParams = array('_id', 'name', 'city_id', 'city', 'region',
                                    'region_id', 'city_type', 'postal_code', 'area',
                                    'kladr', 'company', 'company_id', 'company_code',
                                    'metro', 'description_in', 'description_out',
                                    'indoor_place', 'address', 'schedule', 'longitude',
                                    'latitude', 'type', 'status', 'has_fitting_room',
                                    'is_cash', 'is_card');
    
    
}