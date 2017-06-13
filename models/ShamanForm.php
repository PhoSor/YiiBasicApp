<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;

class ShamanForm extends Model
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $curlname;
    public $token;
    public $secretKey;
    public $decodedKey;
    public $roleName;
    public $errorMessage;

    public function rules()
    {
        return [
            [['email', 'password', 'curlname'], 'required'],
            ['email', 'email'],
        ];
    }
}