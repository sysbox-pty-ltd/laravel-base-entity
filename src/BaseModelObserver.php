<?php namespace Sysbox\LaravelBaseEntity;

/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2/02/2016
 * Time: 9:42 PM
 */

use Carbon\Carbon;

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
    protected function getUserId()
    {
        if (! API::user() instanceof User) {
            return getenv('SYSTEM_USER_ID');
        }

        return API::user()->id;
    }

    /**
     * Generate Hash for Base Model.
     *
     * @param $class_name
     * @param Carbon|null $time
     * @param string $salt
     *
     * @return string
     */
    protected function genHash($class_name, $userId = null, Carbon $time = null)
    {
        if (! $time instanceof Carbon) {
            $time = Carbon::now();
        }
        if (trim($userId) === '') {
            $userId = $this->getUserId();
        }

        return md5(implode('_', [$class_name, $userId, $time->getTimestamp(), random_int(0, PHP_INT_MAX)]));
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
            $model->id = $this->genHash(get_class($model), $user_id, $now);
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
        $user_id = getenv('SYSTEM_USER_ID');
        if (API::user() instanceof User) {
            $user_id = API::user()->id;
        }
        $model->updated_by_id = $user_id;
        $model->updated_at = Carbon::now();
    }
}
