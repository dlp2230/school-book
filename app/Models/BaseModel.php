<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 11:49
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BaseModel extends Model
{
    protected $prefix = 'hx_';
    /**
     * 取消自动维护时间字段
     * @var bool
     */
    public $timestamps = false;

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }
}