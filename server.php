<?php

/**
 * Plane MCP Server Entry Point
 *
 * This script serves as the main entry point for the Plane MCP server.
 * It handles incoming requests and routes them to the appropriate handlers.
 */

require_once __DIR__ . '/vendor/autoload.php';

use Technoliga\PlaneMcp\PlaneClient;
use Technoliga\PlaneMcp\PlaneMcpServer;
use Technoliga\PlaneMcp\McpHandler;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize the Plane MCP components
try {
    $config = [
        'base_url' => $_ENV['PLANE_BASE_URL'] ?? 'https://api.plane.so',
        'api_token' => $_ENV['PLANE_API_TOKEN'] ?? '',
        'cache_enabled' => filter_var($_ENV['PLANE_CACHE_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'cache_ttl' => (int) ($_ENV['PLANE_CACHE_TTL'] ?? 300),
        'timeout' => (int) ($_ENV['PLANE_TIMEOUT'] ?? 30),
    ];

    $client = new PlaneClient($config);
    $server = new PlaneMcpServer($client);
    $handler = new McpHandler($server);

    // If this is an HTTP request, process it
    if (isset($_SERVER['REQUEST_METHOD'])) {
        // Handle HTTP requests
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $method = $input['method'] ?? null;
            $params = $input['params'] ?? [];

            if ($method) {
                $result = $handler->handleRequest($method, $params);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Method not specified']);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Return available tools for GET requests
            echo json_encode([
                'name' => 'Plane MCP Server',
                'version' => '1.0.0',
                'tools' => $handler->getAvailableTools()
            ]);
        }
    }

    // For CLI usage, you might want to expose the handler differently
    return $handler;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server initialization failed: ' . $e->getMessage()]);
}