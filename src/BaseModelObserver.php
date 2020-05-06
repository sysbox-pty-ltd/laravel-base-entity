<?php
namespace Sysbox\LaravelBaseEntity;

/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2/02/2016
 * Time: 9:42 PM
 */

use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;

/**
 * Class BaseModelObserver.
 *
 * @package App\Modules\Abstracts\Models
 */
class BaseModelObserver
{

    /**
     * Watching the creating event for BaseModel.
     *
     * @param $model
     */
    public function creating(BaseModel $model)
    {
        $user_id = LaravelBaseEntity::getCurrentUserId();
        $now = LaravelBaseEntity::getNow();
        if (trim($model->id) === '') {
            $model->id = LaravelBaseEntity::genHashId(get_class($model), $user_id, $now);
        }
        $model->active = 1;
        $model->created_at = $now;
        $model->updated_at = $now;
        $model->created_by_id = $user_id;
        $model->updated_by_id = $user_id;
    }

    /**
     * Watching updating event for a BaseModel.
     *
     * @param BaseModel $model
     */
    public function updating(BaseModel $model)
    {
        $model->updated_by_id = LaravelBaseEntity::getCurrentUserId();
        $model->updated_at = LaravelBaseEntity::getNow();
    }

}
