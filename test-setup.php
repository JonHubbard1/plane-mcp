#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Technoliga\PlaneMcp\PlaneClient;
use Technoliga\PlaneMcp\PlaneMcpServer;
use Technoliga\PlaneMcp\McpHandler;

echo "Plane MCP Server Test Script\n";
echo "============================\n\n";

// Try to load environment variables
try {
    if (file_exists('.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        echo "Environment loaded successfully.\n";
    } else {
        echo "Warning: .env file not found. Please copy .env.example to .env and configure your settings.\n";
        echo "Using default configuration for testing...\n\n";

        // Set some default environment variables for testing
        $_ENV['PLANE_BASE_URL'] = 'https://api.plane.so';
        $_ENV['PLANE_API_TOKEN'] = 'test_token';
        $_ENV['PLANE_DEFAULT_PROJECT'] = 'test-project';
    }
} catch (Exception $e) {
    echo "Warning: Could not load .env file: " . $e->getMessage() . "\n";
    echo "Using default configuration for testing...\n\n";
}

// Test class instantiation
try {
    $config = [
        'base_url' => $_ENV['PLANE_BASE_URL'] ?? 'https://api.plane.so',
        'api_token' => $_ENV['PLANE_API_TOKEN'] ?? 'test_token',
    ];

    echo "Initializing PlaneClient...\n";
    $client = new PlaneClient($config);
    echo "✓ PlaneClient instantiated successfully\n";

    echo "Initializing PlaneMcpServer...\n";
    $server = new PlaneMcpServer($client);
    echo "✓ PlaneMcpServer instantiated successfully\n";

    echo "Initializing McpHandler...\n";
    $handler = new McpHandler($server);
    echo "✓ McpHandler instantiated successfully\n";

    // Show available tools
    echo "\nAvailable MCP Tools:\n";
    echo "--------------------\n";
    $tools = $handler->getAvailableTools();
    foreach ($tools as $name => $info) {
        echo "- {$name}: {$info['description']}\n";
    }

    echo "\n✓ All components loaded successfully!\n";
    echo "\nTo use this with Claude Code, you'll need to:\n";
    echo "1. Configure your Plane API credentials in .env\n";
    echo "2. Run the server with: php server.php\n";
    echo "3. Connect Claude Code to the MCP server endpoint\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}