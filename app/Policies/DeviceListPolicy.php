<?php

namespace App\Policies;

use App\Models\DeviceList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->id === $deviceList->user_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeviceList  $deviceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DeviceList $deviceList)
    {
        echo $deviceList;
        return $user->id === $deviceList->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->id === $deviceList->user_id;

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeviceList  $deviceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DeviceList $deviceList)
    {
        return $user->id === $deviceList->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeviceList  $deviceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DeviceList $deviceList)
    {
        return $user->id === $deviceList->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeviceList  $deviceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DeviceList $deviceList)
    {
        return $user->id === $deviceList->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DeviceList  $deviceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DeviceList $deviceList)
    {
        return $user->id === $deviceList->user_id;
    }
}
