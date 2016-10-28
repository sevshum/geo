<?php

namespace app\models;

use app\components\GeoParser;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class GeoForm extends Model
{
    public $address;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['address'], 'required'],
            [['address'], 'trim'],
        ];
    }

    /**
     * Find address in yandex geo api
     * @return array
     */
    public function find()
    {
        return GeoParser::getByAddress($this->address);
    }

}
