<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;


class CityModel extends BaseModel
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'city';
    }

    /*
     * 获取城市列表
     * **/
    public function getCityList()
    {
        $result = $this->where('status',1)->orderBy('city_name','asc')->get()->toArray();

        $data = [];
        if(!empty($result)){
            foreach($result as $item){
                $data[$item['city_id']] = $item;
            }
        }
        return $data;
    }


}