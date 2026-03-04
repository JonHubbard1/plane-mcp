<?php

/**
 * Example of creating standardized work items with the Plane MCP Server
 *
 * This script demonstrates how to use the new create_standardized_work_item
 * method to create work items with proper naming conventions and descriptions.
 */

require_once __DIR__ . '/vendor/autoload.php';

class StandardizedWorkItemExample
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
     * Example workflow: Create standardized work items
     */
    public function createStandardizedWorkItems(): void
    {
        echo "=== Creating Standardized Work Items ===\n\n";

        // Example 1: Create a work item for the Knowledge Base module in v1.0 cycle
        echo "1. Creating work item for Knowledge Base implementation...\n";
        $result1 = $this->callMcpMethod('create_standardized_work_item', [
            'project_slug' => 'supporthub',
            'module' => 'M02',
            'cycle' => 'v1.0',
            'task_name' => 'Implement search functionality',
            'priority' => 'high',
            'context' => [
                'feature_area' => 'Knowledge Base',
                'estimated_hours' => '8',
                'dependencies' => 'Database schema ready'
            ]
        ]);

        if ($result1['success']) {
            echo "Successfully created work item!\n";
            echo "Message: " . ($result1['message'] ?? 'No message') . "\n\n";
        } else {
            echo "Error creating work item: " . $result1['error'] . "\n\n";
        }

        // Example 2: Create a work item for User Management module in v1.1 cycle
        echo "2. Creating work item for User Management enhancements...\n";
        $result2 = $this->callMcpMethod('create_standardized_work_item', [
            'project_slug' => 'supporthub',
            'module' => 'M04',
            'cycle' => 'v1.1',
            'task_name' => 'Add role-based access control',
            'priority' => 'medium',
            'context' => [
                'feature_area' => 'User Management',
                'estimated_hours' => '12',
                'security_considerations' => 'RBAC implementation must follow least privilege principle'
            ]
        ]);

        if ($result2['success']) {
            echo "Successfully created work item!\n";
            echo "Message: " . ($result2['message'] ?? 'No message') . "\n\n";
        } else {
            echo "Error creating work item: " . $result2['error'] . "\n\n";
        }

        // Example 3: Create a work item for Device Health Monitor module in v2.0 cycle
        echo "3. Creating work item for Device Health Monitor...\n";
        $result3 = $this->callMcpMethod('create_standardized_work_item', [
            'project_slug' => 'supporthub',
            'module' => 'M15',
            'cycle' => 'v2.0',
            'task_name' => 'Implement health reporting dashboard',
            'priority' => 'high',
            'context' => [
                'feature_area' => 'Device Health Monitor',
                'estimated_hours' => '16',
                'integration_points' => 'Mac agent, health reporting API'
            ]
        ]);

        if ($result3['success']) {
            echo "Successfully created work item!\n";
            echo "Message: " . ($result3['message'] ?? 'No message') . "\n\n";
        } else {
            echo "Error creating work item: " . $result3['error'] . "\n\n";
        }

        echo "=== Standardized Work Item Creation Complete ===\n";
    }
}

// Run the example if this script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $example = new StandardizedWorkItemExample();
    $example->createStandardizedWorkItems();
}