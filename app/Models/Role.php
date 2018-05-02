<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Laratrust\Models\LaratrustRole;

/**
 * App\Models\Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @property-read mixed $h_id
 * @property-read mixed $selected_permission_ids
 */
class Role extends LaratrustRole
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'description'
    ];

    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    protected $appends = [
        'hId',
        'selectedPermissionIds',
    ];

    public function getHIdAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }

    public function getSelectedPermissionIdsAttribute()
    {
        $ids = [];
        foreach ($this->permissions as $p) {
            array_push($ids, $p->id);
        }
        return $ids;
    }
}
