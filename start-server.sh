#!/bin/bash

# Plane MCP Server Startup Script

echo "Starting Plane MCP Server..."
echo "============================"

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Warning: .env file not found!"
    echo "Please copy .env.example to .env and configure your settings."
    echo ""
fi

# Start the server
echo "Server starting on http://localhost:8080"
echo "Press Ctrl+C to stop the server"
echo ""

php -S localhost:8080 server.php