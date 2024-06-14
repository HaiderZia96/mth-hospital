<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'admin_user-management_module-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-list', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-create', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-download', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-delete', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-dashboard', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-list', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-show', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-download', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-delete', 'group_id' => 2, 'module_id'=>1],

            //Manager Module Seeder


//Department Seeder
            ['name' => 'manager_department_department-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-activity-log', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-activity-log-trash', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_department-swap', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_department_update-status', 'group_id' => 3, 'module_id'=>2],

//            Team Member Seeder
            ['name' => 'manager_team_member-list', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-create', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-delete', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-show', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-edit', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-activity-log', 'group_id' => 4, 'module_id'=>2],
            ['name' => 'manager_team_member-activity-log-trash', 'group_id' => 4, 'module_id'=>2],

            //      Event Category Seeder
            ['name' => 'manager_event_category-list', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-create', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-delete', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-show', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-edit', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-activity-log', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_category-activity-log-trash', 'group_id' => 5, 'module_id'=>2],

            //       News & Events Seeder
            ['name' => 'manager_event_news-list', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-create', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-delete', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-show', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-edit', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-activity-log', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-activity-log-trash', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_news-swap', 'group_id' => 5, 'module_id'=>2],

            //       Achievement Seeder
            ['name' => 'manager_achievement_award-list', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-create', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-delete', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-show', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-edit', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-activity-log', 'group_id' => 7, 'module_id'=>2],
            ['name' => 'manager_achievement_award-activity-log-trash', 'group_id' => 7, 'module_id'=>2],

            //     Contact Us Seeder
            ['name' => 'manager_user-management_contact-list', 'group_id' => 1, 'module_id'=>2],
            ['name' => 'manager_user-management_contact-delete', 'group_id' => 1, 'module_id'=>2],
            ['name' => 'manager_user-management_contact-show', 'group_id' => 1, 'module_id'=>2],
            ['name' => 'manager_user-management_contact-activity-log', 'group_id' => 1, 'module_id'=>2],
            ['name' => 'manager_user-management_contact-activity-log-trash', 'group_id' => 1, 'module_id'=>2],


            //   Research Seeder
            ['name' => 'manager_research_research-list', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-create', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-delete', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-show', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-edit', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-activity-log', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-activity-log-trash', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_research-swap', 'group_id' => 8, 'module_id'=>2],
            //Create Research Attachments
            ['name' => 'manager_research_attachment-list', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_attachment-create', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_attachment-show', 'group_id' => 8, 'module_id'=>2],
            ['name' => 'manager_research_attachment-delete', 'group_id' => 8, 'module_id'=>2],

            //  Conference Seeder
            ['name' => 'manager_event_conference-list', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-create', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-delete', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-show', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-edit', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-activity-log', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-activity-log-trash', 'group_id' => 5, 'module_id'=>2],
            ['name' => 'manager_event_conference-swap', 'group_id' => 5, 'module_id'=>2],

        ];
        foreach ($permissions as $permission){
            Permission::create($permission);
        }
    }
}
