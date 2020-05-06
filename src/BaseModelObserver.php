<?php namespace Sysbox\LaravelBaseEntity;

/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2/02/2016
 * Time: 9:42 PM
 */

use Carbon\Carbon;
use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;

/**
 * Class BaseModelObserver.
 *
 * @package App\Modules\Abstracts\Models
 */
class BaseModelObserver
{
    /**
     * Getting the current user id.
     *
     * @return mixed|string
     */
    private function getUserId()
    {
        $userClassname = LaravelBaseEntity::getUserClassName();
        $currentUser = API::user();
        if ($currentUser instanceof $userClassname) {
            return $currentUser->getUserId();
        }

        return  LaravelBaseEntity::getSystemUserId();
    }

    /**
     * Watching the creating event for BaseModel.
     *
     * @param $model
     */
    public function creating(BaseModel $model)
    {
        $user_id = $this->getUserId();
        $now = Carbon::now();
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
        $model->updated_by_id = $this->getUserId();
        $model->updated_at = Carbon::now();
    }

}
