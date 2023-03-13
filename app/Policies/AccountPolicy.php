<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserType;

class AccountPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $sender
     * @param  \App\Models\User  $receiver
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $sender, User $receiver)
    {
        /*CHECKING PERMISSION BASED ON ROLES */
        if($sender->type==UserType::Normal()){
            //A basic user can only access to himself
            if($sender->id!=$receiver->id){
                return false;
            }
        }else if($sender->type==UserType::Manager()){
            //An admin user can only access to admin users or basic users
            if($receiver->type==UserType::Admin()){
                return false;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $sender
     * @param  \App\Models\User  $receiver
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $sender, User $receiver)
    {
        /*CHECKING PERMISSION BASED ON ROLES */
        if($sender->type=='user'){
            //A basic user can only access to himself
            if($sender->id!=$receiver->id){
                return false;
            }
        }else if($sender->type=='manager'){
            //An admin user can only access to admin users or basic users
            if($receiver->type=='admin'){
                return false;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $sender
     * @param  \App\Models\User  $receiver
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $sender, User $receiver)
    {
        /*CHECKING PERMISSION BASED ON ROLES */
        if($sender->type=='user'){
            //A basic user can only access to himself
            if($sender->id!=$receiver->id){
                return false;
            }
        }else if($sender->type=='manager'){
            //An admin user can only access to admin users or basic users
            if($receiver->type=='admin'){
                return false;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
