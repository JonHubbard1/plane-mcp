<?php

/**
 * Plane MCP Server Entry Point
 *
 * This script serves as the main entry point for the Plane MCP server.
 * It handles incoming requests and routes them to the appropriate handlers.
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/mcp-server.php';

// Handle the request
$server = new PlaneMcpHttpServer();
$server->handleRequest();