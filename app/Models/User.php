<?php

namespace App\Models;

use App\Contracts\UserContract;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail,UserContract
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'pic',
         'bio',
         'pvt',
        'last_login_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


  public function getAccountDetails($id)
  {
      return $this->where(['id'=>$id])->first();
  }

  public function updateProfilePictureName($filename,$id)
  {
      return $this->where(['id'=>$id])->update([
          'pic'=>$filename
      ]);
  }


  public function editBioMessage($bioMessage,$id)
  {
      return $this->where(['id'=>$id])->update([
         'bio'=>$bioMessage
      ]);
  }

  public function changePassword($hashPassword,$id)
  {
      return $this->where(['id'=>$id])->update([
          'password'=>$hashPassword
      ]);
  }


  public function editProfileStatus($profileStatus , $id)
{
    return $this->where(['id'=>$id])->update([
       'pvt'=>$profileStatus
    ]);
}


  public function allPaginatedUsers($itemsPerPage)
  {
      return $this->paginate($itemsPerPage,[
          'pic','name','id'
      ]);
  }


    public function followers()
    {
        // TODO: Implement followers() method.
        return $this->hasMany('App\Models\Followers','userid','id');
    }

    public function followingYou()
    {
        // TODO: Implement followingYou() method.
        return $this->hasMany('App\Models\Followers','followed_userid','id');
    }


    public function isFollowingUser($followerUserId,$id)
    {

        return $this->whereHas("followers", function ($q) use ($followerUserId, $id) {
            $q->where(['userid'=>$id,'followed_userid'=>$followerUserId]);
        })->count();

    }


    public function createNewFollower($followerUserId,$id)
    {
        return Followers::followUser($id,$followerUserId);
    }

    public function unFollowerUser($followerUserId, $id)
    {
        // TODO: Implement unFollowerUser() method.
        return Followers::unFollowUser($id,$followerUserId);
    }


    public function allUsersFollowingYou($id)
    {
        // TODO: Implement allUsersFollowingYou() method.
     $itemPerPage = 10;
     return $this->where(['id'=>$id])->with('followingYou')->paginate($itemPerPage);

    }

    public function allUsersYouAreFollowing($id)
    {
        // TODO: Implement allUsersYouAreFollowing() method.
        $itemPerPage = 10;
        return $this->where(['id'=>$id])->with('followers')->paginate($itemPerPage);

    }


}
