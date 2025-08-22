<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MaintenanceRequestSearch
{
    /**
     * Apply comprehensive search functionality to MaintenanceRequest queries
     *
     * @param Builder $query
     * @param string $searchTerm
     * @return Builder
     */
    public function applyMaintenanceRequestSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('description', 'LIKE', "%{$searchTerm}%")
              ->orWhere('ticket_number', 'LIKE', "%{$searchTerm}%")
              ->orWhere('priority', 'LIKE', "%{$searchTerm}%")
              ->orWhere('status', 'LIKE', "%{$searchTerm}%")
              ->orWhere('id', 'LIKE', "%{$searchTerm}%")
              ->orWhere('rejection_reason', 'LIKE', "%{$searchTerm}%")
              
              // User relationships
              ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                  $userQuery->where('name', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('phone', 'LIKE', "%{$searchTerm}%")

                           ->orWhereHas('department', function($deptQuery) use ($searchTerm) {
                               $deptQuery->where('name', 'LIKE', "%{$searchTerm}%");
                           })
                           ->orWhereHas('sector', function($sectorQuery) use ($searchTerm) {
                               $sectorQuery->where('name', 'LIKE', "%{$searchTerm}%");
                           })
                           ->orWhereHas('division', function($divisionQuery) use ($searchTerm) {
                               $divisionQuery->where('name', 'LIKE', "%{$searchTerm}%");
                           })
                           ->orWhereHas('jobPosition', function($jobQuery) use ($searchTerm) {
                               $jobQuery->where('title', 'LIKE', "%{$searchTerm}%");
                           });
              })
              
              // Item relationships
              ->orWhereHas('item', function($itemQuery) use ($searchTerm) {
                  $itemQuery->where('name', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('unit', 'LIKE', "%{$searchTerm}%")

                           ->orWhereHas('categories', function($categoryQuery) use ($searchTerm) {
                               $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
                           });
              })
              
              // Request categories
              ->orWhereHas('categories', function($categoryQuery) use ($searchTerm) {
                  $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('description', 'LIKE', "%{$searchTerm}%");
              })
              
              // Assignments and technicians
              ->orWhereHas('assignments', function($assignmentQuery) use ($searchTerm) {
                  $assignmentQuery->where('director_notes', 'LIKE', "%{$searchTerm}%")
                                 ->orWhereHas('technician', function($techQuery) use ($searchTerm) {
                                     $techQuery->where('name', 'LIKE', "%{$searchTerm}%")
                                              ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                                              ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                                              ->orWhereHas('department', function($deptQuery) use ($searchTerm) {
                                                  $deptQuery->where('name', 'LIKE', "%{$searchTerm}%");
                                              });
                                 });
              })
              
              // Work logs
              ->orWhereHas('workLogs', function($workLogQuery) use ($searchTerm) {
                  $workLogQuery->where('description', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('completion_notes', 'LIKE', "%{$searchTerm}%");
              })
              
              // Updates
              ->orWhereHas('updates', function($updateQuery) use ($searchTerm) {
                  $updateQuery->where('update_text', 'LIKE', "%{$searchTerm}%")
                             ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                                 $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
                             });
              });
        });
    }
}