<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now=now();
        $passSuper=Hash::make('pas123');
        $passSuperV2=Hash::make('pas12345');
        $saveList=[
            [
                'name'=>'Super-Admin',
                'email'=>'super@lila.com',
                'password'=>$passSuper,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Administrator',
                'email'=>'admin@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Customer Service',
                'email'=>'cs@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Cost Control',
                'email'=>'cc@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Purchasing',
                'email'=>'pc@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Management Stok',
                'email'=>'ms@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
            [
                'name'=>'Kitchen',
                'email'=>'kc@lila.com',
                'password'=>$passSuperV2,
                'email_verified_at'=>$now,
                'created_at'=>$now,
            ],
        ];
        $roleWeb = Role::where('name', 'super_admin')->where('guard_name', 'web')->first();
        $roleApi = Role::where('name', 'super_admin')->where('guard_name', 'api')->first();
        $roleWebAdmin = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        $roleApiAdmin = Role::where('name', 'admin')->where('guard_name', 'api')->first();
        $roleWebCs = Role::where('name', 'customer_service')->where('guard_name', 'web')->first();
        $roleApiCs = Role::where('name', 'customer_service')->where('guard_name', 'api')->first();
        $roleWebCc = Role::where('name', 'cost_control')->where('guard_name', 'web')->first();
        $roleApiCc = Role::where('name', 'cost_control')->where('guard_name', 'api')->first();
        $roleWebPc = Role::where('name', 'purchasing')->where('guard_name', 'web')->first();
        $roleApiPc = Role::where('name', 'purchasing')->where('guard_name', 'api')->first();
        $roleWebMs = Role::where('name', 'management_stok')->where('guard_name', 'web')->first();
        $roleApiMs = Role::where('name', 'management_stok')->where('guard_name', 'api')->first();
        $roleWebKc = Role::where('name', 'kitchen')->where('guard_name', 'web')->first();
        $roleApiKc = Role::where('name', 'kitchen')->where('guard_name', 'api')->first();
        foreach($saveList as $save){
            $user=User::updateOrCreate(
                ['email'=>$save['email']],
                $save
            );
            if($save['email']=='super@lila.com'){
                $user->assignRole($roleWeb);
                $user->assignRole($roleApi);
            }elseif($save['email']=='admin@lila.com'){
                $user->assignRole($roleWebAdmin);
                $user->assignRole($roleApiAdmin);
            }elseif($save['email']=='cs@lila.com'){
                $user->assignRole($roleWebCs);
                $user->assignRole($roleApiCs);
            }elseif($save['email']=='cc@lila.com'){
                $user->assignRole($roleWebCc);
                $user->assignRole($roleApiCc);
            }elseif($save['email']=='pc@lila.com'){
                $user->assignRole($roleWebPc);
                $user->assignRole($roleApiPc);
            }elseif($save['email']=='ms@lila.com'){
                $user->assignRole($roleWebMs);
                $user->assignRole($roleApiMs);
            }elseif($save['email']=='kc@lila.com'){
                $user->assignRole($roleWebKc);
                $user->assignRole($roleApiKc);
            }
            //user other

        }
    }
}
