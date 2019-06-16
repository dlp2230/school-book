<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;


class ActivityModel extends BaseModel
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'activity';
    }

    /*
     * 城市展列表
     * @param[cityId] $cityId 城市Id
     * **/
    public function getActivityByCityList($cityId)
    {
        $result = $this->where('city_id',$cityId)->orderBy('session','asc')->get();
        $data = [];
        if(!empty($result)){
            $result = $result->toArray();
            foreach($result as $item){
                $data[$item['activity_id']] = $item;
            }
        }

        return $data;
    }

    /*
     * 当前展届
     * @param[cityId] $cityId 城市ID
     * **/
    public function getActivityCurrent($cityId,$dateTime)
    {
        $result = $this->where('city_id',$cityId)
                       ->where('begin_date','<',$dateTime)
                       ->where('end_date','>',$dateTime)
                       ->where('status',1)
                       ->first();

        return !empty($result) ? $result->toArray() : [];

    }

}