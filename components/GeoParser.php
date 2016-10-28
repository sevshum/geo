<?php
namespace app\components;

class GeoParser
{
    /**
     * √ео-кодирование по запросу (адрес)
     * @param string $query
     * @return array
     */
    public static function getByAddress($query)
    {
        $api = new \Yandex\Geo\Api();
        $api->setQuery($query);
        $api->setArea(0.3, 0.3, 37.620070, 55.753630);
        $api->setLang(\Yandex\Geo\Api::LANG_RU) // локаль ответа
        ->load();
        $response = $api->getResponse();
        $collection = $response->getList();

        if (!$collection) {
            return [];
        }
        $items = [];
        $tmpItem = [];
        foreach ($collection as $item) {
            $tmpItem['lng'] = $item->getLongitude();
            $tmpItem['lat'] = $item->getLatitude();
            $tmpItem['address'] = $item->getAddress();
            $items[] = $tmpItem;
        }
        return $items;
    }

    /**
     * √ео-кодирование по запросу (координаты)
     * @param string $lat
     * @param string $lng
     * @return array
     */
    public static function getByCoord($lng, $lat)
    {
        $res = [];
        $api = new \Yandex\Geo\Api();
        $api->setQuery($lng . ',' . $lat);
        $api->setLang(\Yandex\Geo\Api::LANG_RU); // локаль ответа

        $res['metro'] = self::_getResultsArray($api, \Yandex\Geo\Api::KIND_METRO);
        $res['district'] = self::_getResultsArray($api, \Yandex\Geo\Api::KIND_DISTRICT);
        $res['street'] = self::_getResultsArray($api, \Yandex\Geo\Api::KIND_STREET, 0.003);
        $res['house'] = self::_getResultsArray($api, \Yandex\Geo\Api::KIND_HOUSE, 0.001);

        return $res;
    }

    private static function _getResultsArray(\Yandex\Geo\Api $api, $kind, $length = 0.02)
    {
        $api->setArea($length, $length);
        $api->setKind($kind)->load();

        $response = $api->getResponse();
        $collection = $response->getList();
        $items = [];
        foreach ($collection as $item) {
            $items[] = $item->getAddress();
        }
        return $items;
    }
}