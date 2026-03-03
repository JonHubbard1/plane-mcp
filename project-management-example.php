<?php

/**
 * Project Management Example for Plane MCP Server
 *
 * This script demonstrates how to use the Plane MCP server to manage
 * projects with Claude Code.
 */

require_once __DIR__ . '/vendor/autoload.php';

// Example of how Claude Code would interact with our MCP server for project management
class ProjectManagementExample
{
    private string $mcpServerUrl;

    public function __construct(string $mcpServerUrl = 'http://localhost:8080')
    {
        $this->mcpServerUrl = $mcpServerUrl;
    }

    /**
     * Call an MCP method
     */
    public function callMcpMethod(string $method, array $params = []): array
    {
        $payload = [
            'method' => $method,
            'params' => $params
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($payload)
            ]
        ]);

        $response = file_get_contents($this->mcpServerUrl, false, $context);

        if ($response === false) {
            return ['success' => false, 'error' => 'Failed to connect to MCP server'];
        }

        $decoded = json_decode($response, true);
        return $decoded ?: ['success' => false, 'error' => 'Invalid JSON response'];
    }

    /**
     * Example workflow: Create and manage projects
     */
    public function projectManagementWorkflow(): void
    {
        echo "=== Plane MCP Project Management Example ===\n\n";

        // 1. List existing projects
        echo "1. Listing existing projects...\n";
        $projectsResult = $this->callMcpMethod('list_projects');

        if ($projectsResult['success']) {
            echo "Found " . $projectsResult['count'] . " projects:\n";
            foreach ($projectsResult['projects'] as $project) {
                echo "  - " . $project['name'] . " (" . $project['identifier'] . ")\n";
            }
        } else {
            echo "Error listing projects: " . $projectsResult['error'] . "\n";
        }

        echo "\n";

        // 2. Create a new project
        echo "2. Creating a new project...\n";
        $projectData = [
            'name' => 'Client Portal System',
            'identifier' => 'client-portal',
            'description' => 'Portal system for client self-service and support ticket management'
        ];

        $createProjectResult = $this->callMcpMethod('create_project', [
            'data' => $projectData
        ]);

        $newProjectId = null;
        if ($createProjectResult['success']) {
            $project = $createProjectResult['project'] ?? [];
            $newProjectId = $project['id'] ?? 'proj-3'; // Use mock ID if not provided
            echo "Successfully created project: " . ($project['name'] ?? 'New Project') . "\n";
            echo "Project ID: " . $newProjectId . "\n";
            echo "Identifier: " . ($project['identifier'] ?? 'unknown') . "\n";
            echo "Message: " . ($createProjectResult['message'] ?? 'No message') . "\n";
        } else {
            echo "Error creating project: " . $createProjectResult['error'] . "\n";
        }

        echo "\n";

        // 3. Get the newly created project
        if ($newProjectId) {
            echo "3. Retrieving the new project...\n";
            $getProjectResult = $this->callMcpMethod('get_project', [
                'project_slug' => 'client-portal'
            ]);

            if ($getProjectResult['success']) {
                $project = $getProjectResult['project'] ?? [];
                echo "Project retrieved successfully:\n";
                echo "  Name: " . ($project['name'] ?? 'Unknown') . "\n";
                echo "  Identifier: " . ($project['identifier'] ?? 'Unknown') . "\n";
                echo "  Description: " . ($project['description'] ?? 'No description') . "\n";
            } else {
                echo "Error getting project: " . $getProjectResult['error'] . "\n";
            }

            echo "\n";

            // 4. Update the project with more information
            echo "4. Updating the project with additional information...\n";
            $updateData = [
                'description' => 'Portal system for client self-service and support ticket management. Includes authentication, dashboard, and ticket management features.'
            ];

            $updateProjectResult = $this->callMcpMethod('update_project', [
                'project_slug' => 'client-portal',
                'data' => $updateData
            ]);

            if ($updateProjectResult['success']) {
                echo "Successfully updated project!\n";
                echo "Message: " . ($updateProjectResult['message'] ?? 'No message') . "\n";
            } else {
                echo "Error updating project: " . $updateProjectResult['error'] . "\n";
            }

            echo "\n";

            // 5. Create initial documentation for the project
            echo "5. Creating initial project documentation...\n";
            $docPageData = [
                'name' => 'Project Overview',
                'content' => '# Client Portal System\n\n## Purpose\nProvide clients with self-service capabilities and support ticket management.\n\n## Features\n- User authentication\n- Dashboard with key metrics\n- Support ticket submission\n- Knowledge base access\n\n## Technology Stack\n- Laravel 12\n- Livewire 3\n- MySQL 8\n- Tailwind CSS',
                'description' => 'Overview and technical details for the Client Portal System'
            ];

            $createDocResult = $this->callMcpMethod('create_page', [
                'project_slug' => 'client-portal',
                'data' => $docPageData
            ]);

            if ($createDocResult['success']) {
                echo "Successfully created project documentation!\n";
            } else {
                echo "Error creating documentation: " . $createDocResult['error'] . "\n";
            }

            echo "\n";

            // 6. Create initial modules for the project
            echo "6. Creating initial project modules...\n";
            $modules = [
                ['name' => 'Authentication', 'description' => 'User registration, login, and password management'],
                ['name' => 'Dashboard', 'description' => 'Client dashboard with metrics and quick actions'],
                ['name' => 'Ticket Management', 'description' => 'Support ticket creation, tracking, and resolution']
            ];

            foreach ($modules as $moduleData) {
                $createModuleResult = $this->callMcpMethod('create_module', [
                    'project_slug' => 'client-portal',
                    'data' => $moduleData
                ]);

                if ($createModuleResult['success']) {
                    echo "  Created module: " . $moduleData['name'] . "\n";
                } else {
                    echo "  Error creating module " . $moduleData['name'] . ": " . $createModuleResult['error'] . "\n";
                }
            }

            echo "\n";

            // 7. Create initial development cycle
            echo "7. Creating initial development cycle...\n";
            $cycleData = [
                'name' => 'Phase 1 - MVP',
                'description' => 'Minimum viable product with core authentication and dashboard features',
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+6 weeks'))
            ];

            $createCycleResult = $this->callMcpMethod('create_cycle', [
                'project_slug' => 'client-portal',
                'data' => $cycleData
            ]);

            if ($createCycleResult['success']) {
                echo "Successfully created development cycle!\n";
            } else {
                echo "Error creating cycle: " . $createCycleResult['error'] . "\n";
            }
        }

        echo "\n";

        // 8. List all projects again to see the new one
        echo "8. Listing all projects after creation...\n";
        $projectsResult = $this->callMcpMethod('list_projects');

        if ($projectsResult['success']) {
            echo "Found " . $projectsResult['count'] . " projects:\n";
            foreach ($projectsResult['projects'] as $project) {
                echo "  - " . $project['name'] . " (" . $project['identifier'] . ")\n";
            }
        } else {
            echo "Error listing projects: " . $projectsResult['error'] . "\n";
        }

        echo "\n=== Project Management Workflow Complete ===\n";
    }
}

// Run the example if this script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $projectManager = new ProjectManagementExample();
    $projectManager->projectManagementWorkflow();
}