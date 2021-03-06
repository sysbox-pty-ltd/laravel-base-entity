<?php
namespace Sysbox\LaravelBaseEntity;


use Illuminate\Database\Eloquent\Model;
use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;

abstract class BaseModel extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Whether this model will have a UUID.
     *
     * @var bool
     */
    protected $needUUID = true;
    /**
     * @var string
     */
    protected $keyType = 'string';
    /**
     * Whether to bypass Observer automatically
     *
     * TODO: need to check why in the future.
     *
     * @var bool
     */
    public static $byPassObserver = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the creator
     */
    public function createdBy()
    {
        return $this->belongsTo(LaravelBaseEntity::getUserClassName(), 'created_by_id');
    }

    /**
     * Get the updater
     */
    public function updatedBy()
    {
        return $this->belongsTo(LaravelBaseEntity::getUserClassName(), 'updated_by_id');
    }

    /**
     * set the active flag to be false.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->active = 0;
        return $this;
    }

    /**
     * Activate a model.
     *
     * @return $this
     */
    public function activate()
    {
        $this->active = 1;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return intval($this->active) === 1;
    }

    /**
     * Scope a query to only include active models.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfActive($query, $active = 1)
    {
        return $query->where($this->table . '.active', intval($active));
    }

    /**
     * Scoping the id column.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeOfId($query, $id)
    {
        return $query->where($this->table . '.id', $id);
    }

    /**
     * Scope of ids.
     *
     * @param $query
     * @param $ids
     *
     * @return mixed
     */
    public function scopeOfIds($query, $ids)
    {
        return $query->whereIn($this->table . '.id', $ids);
    }

    /**
     * Scoping the id not equals.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeOfNotId($query, $id)
    {
        return $query->where($this->table . '.id', '!=', $id);
    }

    /**
     * @param $query
     * @param $ids
     * @return mixed
     */
    public function scopeOfNotIds($query, $ids)
    {
        return $query->whereNotIn($this->table . '.id', $ids);
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfCreatedAtOlderThan($query, $date)
    {
        return $query->where($this->table . '.created_at', '<', trim($date));
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfCreatedAtOlderAndEqualTo($query, $date)
    {
        return $query->where($this->table . '.created_at', '<=', trim($date));
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfCreatedAtNewerThan($query, $date)
    {
        return $query->where($this->table . '.created_at', '>', trim($date));
    }
    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfCreatedAtNewerAndEqualTo($query, $date)
    {
        return $query->where($this->table . '.created_at', '>=', trim($date));
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfCreatedAt($query, $date)
    {
        return $query->where($this->table . '.created_at', '=', $date);
    }

    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfUpdatedAt($query, $date)
    {
        return $query->where($this->table . '.updated_at', '=', $date);
    }
    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfUpdatedAtNewerThan($query, $date)
    {
        return $query->where($this->table . '.updated_at', '>', trim($date));
    }
    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfUpdatedAtNewerAndEqualTo($query, $date)
    {
        return $query->where($this->table . '.updated_at', '>=', trim($date));
    }
    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfUpdatedAtOlderThan($query, $date)
    {
        return $query->where($this->table . '.updated_at', '<', trim($date));
    }
    /**
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeOfUpdatedAtOlderAndEqualTo($query, $date)
    {
        return $query->where($this->table . '.updated_at', '<=', trim($date));
    }

    /**
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeOfCreatedById($query, $userId)
    {
        return $query->where($this->table . '.created_by_id', '=', $userId);
    }
    /**
     *
     */
    public static function boot()
    {
        parent::boot();
        LaravelBaseEntity::bootModel();
        if (static::$byPassObserver !== true) {
            $class = get_called_class();
            $class::observe(new BaseModelObserver());
        }
    }
}
