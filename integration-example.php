<?php

/**
 * Integration Example for Plane MCP Server
 *
 * This script demonstrates how to integrate the Plane MCP server
 * with Claude Code or other AI tools.
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
     * Example workflow: Check project status and create an issue if needed
     */
    public function checkAndCreateIssueWorkflow(): void
    {
        echo "=== Plane MCP Integration Example ===\n\n";

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

        // 3. List issues for the project
        echo "3. Listing issues...\n";
        $issuesResult = $this->callMcpMethod('list_issues', ['project_slug' => 'supporthub']);

        if ($issuesResult['success']) {
            echo "Found " . $issuesResult['count'] . " issues:\n";
            foreach ($issuesResult['issues'] as $issue) {
                echo "  - " . $issue['name'] . " (" . $issue['priority'] . ")\n";
            }
        } else {
            echo "Error listing issues: " . $issuesResult['error'] . "\n";
        }

        echo "\n=== Workflow Complete ===\n";
    }
}

// Run the example if this script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $integration = new ClaudeCodeIntegrationExample();
    $integration->checkAndCreateIssueWorkflow();
}