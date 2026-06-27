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
        foreach($saveList as $save){
            // $user=User::firstOrCreate(['name'=>$save['name'],'email'=>$save['email']],$save);
            $user=User::create($save);
            if($save['email']=='super@lila.com'){
                $user->assignRole($roleWeb);
                $user->assignRole($roleApi);
            }elseif($save['email']=='admin@lila.com'){
                $user->assignRole($roleWebAdmin);
                $user->assignRole($roleApiAdmin);
            }
            //user other
        
        }
    }
}
