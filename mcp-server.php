<?php

/**
 * Plane MCP Server - HTTP Implementation
 *
 * This script implements the Model Context Protocol (MCP) server
 * that can communicate with Claude Code and other AI tools.
 */

require_once __DIR__ . '/vendor/autoload.php';

use Technoliga\PlaneMcp\PlaneClient;
use Technoliga\PlaneMcp\PlaneMcpServer;
use Technoliga\PlaneMcp\McpHandler;

class PlaneMcpHttpServer
{
    private PlaneClient $client;
    private PlaneMcpServer $server;
    private McpHandler $handler;

    public function __construct()
    {
        // Load environment variables
        if (file_exists(__DIR__ . '/.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        }

        // Initialize components
        $config = [
            'base_url' => $_ENV['PLANE_BASE_URL'] ?? 'https://api.plane.so',
            'api_token' => $_ENV['PLANE_API_TOKEN'] ?? '',
            'cache_enabled' => filter_var($_ENV['PLANE_CACHE_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'cache_ttl' => (int) ($_ENV['PLANE_CACHE_TTL'] ?? 300),
            'timeout' => (int) ($_ENV['PLANE_TIMEOUT'] ?? 30),
        ];

        $this->client = new PlaneClient($config);
        $this->server = new PlaneMcpServer($this->client);
        $this->handler = new McpHandler($this->server);
    }

    public function handleRequest(): void
    {
        // Set JSON headers
        header('Content-Type: application/json');

        // Get request method
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'POST') {
            // Handle POST requests (MCP method calls)
            $input = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON payload']);
                return;
            }

            $methodName = $input['method'] ?? null;
            $params = $input['params'] ?? [];

            if (!$methodName) {
                http_response_code(400);
                echo json_encode(['error' => 'Method name is required']);
                return;
            }

            // Handle the request
            $result = $this->handler->handleRequest($methodName, $params);

            // Set appropriate HTTP status code based on success
            if (!$result['success']) {
                http_response_code(500);
            }

            echo json_encode($result);
        } elseif ($method === 'GET') {
            // Handle GET requests (server info)
            echo json_encode([
                'name' => 'Plane MCP Server',
                'version' => '1.0.0',
                'description' => 'MCP server for Plane project management tool',
                'tools' => $this->handler->getAvailableTools()
            ]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    }

    public function getHandler(): McpHandler
    {
        return $this->handler;
    }
}

// Handle the request if this script is called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $server = new PlaneMcpHttpServer();
    $server->handleRequest();
}