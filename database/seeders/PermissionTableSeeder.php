<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
	public function run()
	{
		//Permissions
		$permissions = [
			"users" => ["show_users", "create_users", "edit_users", "remove_users"],
			"roles" => ["show_roles", "create_roles", "edit_roles", "remove_roles"],
			"permissions" => ["show_permissions", "create_permissions", "edit_permissions", "remove_permissions"],
			"sections" => ["show_sections", "create_sections", "edit_sections", "remove_sections"],
			"courses" => ["show_courses", "create_courses", "edit_courses", "remove_courses"],
			"offers" => ["show_offers", "create_offers", "edit_offers", "remove_offers"],
			"chapters" => ["show_chapters", "create_chapters", "edit_chapters", "remove_chapters"],
			"lectures" => ["show_lectures", "create_lectures", "edit_lectures", "remove_lectures"],
			"orders" => ["show_orders", "create_orders", "edit_orders", "remove_orders"],
			"coupons" => ["show_coupons", "create_coupons", "edit_coupons", "remove_coupons"],
			"asks" => ["show_asks", "create_asks", "edit_asks", "remove_asks"],

			"comments" => ["show_comments", "create_comments", "edit_comments", "remove_comments"],
			"reviews" => ["show_reviews", "create_reviews", "edit_reviews", "remove_reviews"],
			"supports" => ["show_supports", "create_supports", "edit_supports", "remove_supports"],
			"currencies" => ["show_currencies", "create_currencies", "edit_currencies", "remove_currencies"],
			"payment_methods" => ["show_payment_methods", "create_payment_methods", "edit_payment_methods", "remove_payment_methods"],
			"banks" => ["show_banks", "create_banks", "edit_banks", "remove_banks"],
			"expenses" => ["show_expenses", "create_expenses", "edit_expenses", "remove_expenses"],
			"settings" => ["show_settings", "create_settings", "edit_settings", "remove_settings"],
			"questions" => ["show_questions", "create_questions", "edit_questions", "remove_questions"],
			"teachers" => ["show_teachers", "create_teachers", "edit_teachers", "remove_teachers"],
			"subjects" => ["show_subjects", "create_subjects", "edit_subjects", "remove_subjects"],
			"certificates" => ['show_certificates', 'create_certificates', 'edit_certificates', 'remove_certificates'],
			"bank_categories" => ['show_bank_categories', 'create_bank_categories', 'edit_bank_categories', 'remove_bank_categories'],
			"bank_questions" => ['show_bank_questions', 'create_bank_questions', 'edit_bank_questions', 'remove_bank_questions'],

			"dashboard",
			"whatsapp",
			"pay_expenses",
			"details_expenses",
			"edit_courses_files",
			"order_toggle_status",
			"comment_toggle_status",
			"review_toggle_status",
			'certificate_toggle_status',
			"remove_chat_never",
			"restore_chat",
			"bank_transactions",
			"bank_transaction_operation",
			"setting_backup_database",
			"setting_backup_files",
			"setting_clear_cash",
			"show_reports",
			"reports_teachers",
			"reports_courses",
			"reports_lectures",
			"reports_offers",
			"reports_students",
			"transactions",
			"export",
			"print",
		];

		foreach ($permissions as $key => $permission) {
			if (gettype($key) != "integer") {
				foreach ($permission as $permissionItem) {
					Permission::create(["name" => $permissionItem, "permission_name" => $key]);
				}
			} else {
				Permission::create(["name" => $permission]);
			}
		}
	}
}
