<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
	public function run()
	{
		$user = User::create([
			"name_ar" => "بصمة",
			"name_en" => "Fingerprint",
			"email" => "finger@finger.com",
			"password" => bcrypt("finger@123"),
			"roles_name" => ["owner", "partner"],
			"phone" => "01090770686",
			"phone_parent" => "01090770686",
			"balance" => 5000,
			"status" => 1,
		]);
		$user2 = User::create([
			"name_ar" => "يوسف السعدني",
			"name_en" => "yousef alsaadany",
			"email" => "joe@" . env("DOMAIN"),
			"password" => bcrypt("finger@123"),
			"roles_name" => ["owner", "partner"],
			"phone" => "0118286749",
			"phone_parent" => "0118286749",
			"balance" => 55200,
			"status" => 1,
		]);

		$role = Role::create(["name" => "owner"]);

		$permissions = Permission::pluck("id", "id")->all();
		$role->syncPermissions($permissions);
		$user->assignRole([$role->id]);

		$user_2 = User::create([
			"name_ar" => "test",
			"name_en" => "test",
			"email" => "test@" . env("DOMAIN"),
			"password" => bcrypt("123456789"),
			"roles_name" => [""],
			"phone" => "01090770686",
			"phone_parent" => "01090770686",
			"balance" => 0,
			"status" => 1,
		]);
		$rolePartner = Role::create(["name" => "partner"]);
		$user_4 = User::create([
			"name_ar" => "الخزنة",
			"name_en" => "bank",
			"email" => "bank@" . env("DOMAIN"),
			"password" => bcrypt("123456789"),
			"roles_name" => ["partner"],
			"phone" => "01090770686",
			"phone_parent" => "01090770686",
			"balance" => 0,
			"status" => 1,
		]);
		$user_4->assignRole([$rolePartner->id]);
		$user->assignRole([$rolePartner->id]);
	}
}
