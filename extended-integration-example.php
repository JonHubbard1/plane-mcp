<?php

/**
 * Extended Integration Example for Plane MCP Server
 *
 * This script demonstrates how to integrate the Plane MCP server
 * with Claude Code or other AI tools, including the new capabilities
 * for creating modules and cycles.
 */

require_once __DIR__ . '/vendor/autoload.php';

// Example of how Claude Code would interact with our MCP server
class ClaudeCodeIntegrationExample
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
     * Example workflow: Check project status and create modules/cycles
     */
    public function extendedWorkflow(): void
    {
        echo "=== Extended Plane MCP Integration Example ===\n\n";

        // 1. List all projects
        echo "1. Listing projects...\n";
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

        // 2. Get details of a specific project
        echo "2. Getting project details...\n";
        $projectResult = $this->callMcpMethod('get_project', ['project_slug' => 'supporthub']);

        if ($projectResult['success']) {
            $project = $projectResult['project'];
            echo "Project: " . $project['name'] . "\n";
            echo "Description: " . $project['description'] . "\n";
        } else {
            echo "Error getting project: " . $projectResult['error'] . "\n";
        }

        echo "\n";

        // 3. List existing modules
        echo "3. Listing modules...\n";
        $modulesResult = $this->callMcpMethod('list_modules', ['project_slug' => 'supporthub']);

        if ($modulesResult['success']) {
            echo "Found " . $modulesResult['count'] . " modules:\n";
            foreach ($modulesResult['modules'] as $module) {
                echo "  - " . $module['name'] . "\n";
            }
        } else {
            echo "Error listing modules: " . $modulesResult['error'] . "\n";
        }

        echo "\n";

        // 4. Create a new module
        echo "4. Creating a new module...\n";
        $moduleData = [
            'name' => 'Device Health Monitor',
            'description' => 'Monitor device health and report issues'
        ];

        $createModuleResult = $this->callMcpMethod('create_module', [
            'project_slug' => 'supporthub',
            'data' => $moduleData
        ]);

        if ($createModuleResult['success']) {
            echo "Successfully created module: " . $createModuleResult['module']['name'] . "\n";
            echo "Message: " . ($createModuleResult['message'] ?? 'No message') . "\n";
        } else {
            echo "Error creating module: " . $createModuleResult['error'] . "\n";
        }

        echo "\n";

        // 5. List existing cycles
        echo "5. Listing cycles...\n";
        $cyclesResult = $this->callMcpMethod('list_cycles', ['project_slug' => 'supporthub']);

        if ($cyclesResult['success']) {
            echo "Found " . $cyclesResult['count'] . " cycles:\n";
            foreach ($cyclesResult['cycles'] as $cycle) {
                echo "  - " . $cycle['name'] . "\n";
            }
        } else {
            echo "Error listing cycles: " . $cyclesResult['error'] . "\n";
        }

        echo "\n";

        // 6. Create a new cycle
        echo "6. Creating a new cycle...\n";
        $cycleData = [
            'name' => 'V2 Integration Features',
            'description' => 'Implementation of integration features for Version 2',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+4 weeks'))
        ];

        $createCycleResult = $this->callMcpMethod('create_cycle', [
            'project_slug' => 'supporthub',
            'data' => $cycleData
        ]);

        if ($createCycleResult['success']) {
            echo "Successfully created cycle: " . $createCycleResult['cycle']['name'] . "\n";
            echo "Message: " . ($createCycleResult['message'] ?? 'No message') . "\n";
        } else {
            echo "Error creating cycle: " . $createCycleResult['error'] . "\n";
        }

        echo "\n";

        // 7. List issues for the project
        echo "7. Listing issues...\n";
        $issuesResult = $this->callMcpMethod('list_issues', ['project_slug' => 'supporthub']);

        if ($issuesResult['success']) {
            echo "Found " . $issuesResult['count'] . " issues:\n";
            foreach ($issuesResult['issues'] as $issue) {
                echo "  - " . $issue['name'] . " (" . $issue['priority'] . ")\n";
            }
        } else {
            echo "Error listing issues: " . $issuesResult['error'] . "\n";
        }

        echo "\n=== Extended Workflow Complete ===\n";
    }
}

// Run the example if this script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $integration = new ClaudeCodeIntegrationExample();
    $integration->extendedWorkflow();
}