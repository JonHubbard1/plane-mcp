<?php

/**
 * Page Management Example for Plane MCP Server
 *
 * This script demonstrates how to use the Plane MCP server to manage
 * documentation pages with Claude Code.
 */

require_once __DIR__ . '/vendor/autoload.php';

// Example of how Claude Code would interact with our MCP server for page management
class PageManagementExample
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
     * Example workflow: Create and manage documentation pages
     */
    public function pageManagementWorkflow(): void
    {
        echo "=== Plane MCP Page Management Example ===\n\n";

        // 1. List existing pages
        echo "1. Listing existing pages...\n";
        $pagesResult = $this->callMcpMethod('list_pages', ['project_slug' => 'supporthub']);

        if ($pagesResult['success']) {
            echo "Found " . $pagesResult['count'] . " pages:\n";
            foreach ($pagesResult['pages'] as $page) {
                echo "  - " . $page['name'] . "\n";
            }
        } else {
            echo "Error listing pages: " . $pagesResult['error'] . "\n";
        }

        echo "\n";

        // 2. Create a new documentation page
        echo "2. Creating a new documentation page...\n";
        $pageData = [
            'name' => 'AI Help Desk Implementation',
            'content' => '# AI Help Desk Implementation\n\n## Overview\nThe AI Help Desk feature uses Claude API to provide intelligent responses to user queries.\n\n## Architecture\n- Livewire frontend component\n- Claude API integration\n- Knowledge base RAG pipeline\n- Confidence scoring system\n\n## Configuration\nSet the following environment variables:\n- CLAUDE_API_KEY\n- CLAUDE_MODEL\n- CLAUDE_BASE_URL',
            'description' => 'Technical documentation for the AI Help Desk feature implementation'
        ];

        $createPageResult = $this->callMcpMethod('create_page', [
            'project_slug' => 'supporthub',
            'data' => $pageData
        ]);

        $newPageId = null;
        if ($createPageResult['success']) {
            $page = $createPageResult['page'] ?? [];
            $newPageId = $page['id'] ?? 'page-3'; // Use mock ID if not provided
            echo "Successfully created page: " . ($page['name'] ?? 'New Page') . "\n";
            echo "Page ID: " . $newPageId . "\n";
            echo "Message: " . ($createPageResult['message'] ?? 'No message') . "\n";
        } else {
            echo "Error creating page: " . $createPageResult['error'] . "\n";
        }

        echo "\n";

        // 3. Get the newly created page
        if ($newPageId) {
            echo "3. Retrieving the new page...\n";
            $getPageResult = $this->callMcpMethod('get_page', [
                'project_slug' => 'supporthub',
                'page_id' => $newPageId
            ]);

            if ($getPageResult['success']) {
                $page = $getPageResult['page'] ?? [];
                echo "Page retrieved successfully:\n";
                echo "  Name: " . ($page['name'] ?? 'Unknown') . "\n";
                echo "  Description: " . ($page['description'] ?? 'No description') . "\n";
                $content = $page['content'] ?? '';
                echo "  Content preview: " . (substr($content, 0, 50) ?: 'No content') . "...\n";
            } else {
                echo "Error getting page: " . $getPageResult['error'] . "\n";
            }

            echo "\n";

            // 4. Update the page with more information
            echo "4. Updating the page with additional information...\n";
            $updateData = [
                'content' => ($pageData['content'] ?? '') . "\n\n## Testing\nRun the following tests:\n- Unit tests for AI service\n- Feature tests for chat component\n- Integration tests with mock API",
                'description' => 'Updated technical documentation for the AI Help Desk feature implementation'
            ];

            $updatePageResult = $this->callMcpMethod('update_page', [
                'project_slug' => 'supporthub',
                'page_id' => $newPageId,
                'data' => $updateData
            ]);

            if ($updatePageResult['success']) {
                echo "Successfully updated page!\n";
                echo "Message: " . ($updatePageResult['message'] ?? 'No message') . "\n";
            } else {
                echo "Error updating page: " . $updatePageResult['error'] . "\n";
            }

            echo "\n";
        }

        // 5. Create another page for project setup
        echo "5. Creating project setup documentation...\n";
        $setupPageData = [
            'name' => 'Development Environment Setup',
            'content' => '# Development Environment Setup\n\n## Requirements\n- PHP 8.3+\n- Composer\n- Node.js 18+\n- MySQL 8 or SQLite\n\n## Installation\n```bash\ncomposer install\nnpm install\nnpm run build\n```\n\n## Configuration\nCopy `.env.example` to `.env` and configure:\n- Database connection\n- Claude API key\n- Mail settings\n\n## Running Tests\n```bash\nphp artisan test\n```',
            'description' => 'Setup guide for the development environment'
        ];

        $createSetupPageResult = $this->callMcpMethod('create_page', [
            'project_slug' => 'supporthub',
            'data' => $setupPageData
        ]);

        if ($createSetupPageResult['success']) {
            echo "Successfully created setup documentation page!\n";
        } else {
            echo "Error creating setup page: " . $createSetupPageResult['error'] . "\n";
        }

        echo "\n";

        // 6. List all pages again to see the updates
        echo "6. Listing all pages after creation...\n";
        $pagesResult = $this->callMcpMethod('list_pages', ['project_slug' => 'supporthub']);

        if ($pagesResult['success']) {
            echo "Found " . $pagesResult['count'] . " pages:\n";
            foreach ($pagesResult['pages'] as $page) {
                echo "  - " . $page['name'] . "\n";
            }
        } else {
            echo "Error listing pages: " . $pagesResult['error'] . "\n";
        }

        echo "\n=== Page Management Workflow Complete ===\n";
    }
}

// Run the example if this script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $pageManager = new PageManagementExample();
    $pageManager->pageManagementWorkflow();
}