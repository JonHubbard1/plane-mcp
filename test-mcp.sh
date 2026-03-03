#!/bin/bash

# Test script for Plane MCP Server

echo "Testing Plane MCP Server..."
echo "==========================="

# Start the server in the background
echo "Starting server..."
php -S localhost:8080 server.php > server.log 2>&1 &
SERVER_PID=$!

# Wait a moment for server to start
sleep 2

# Test GET request (should return server info)
echo "Testing GET request..."
curl -s http://localhost:8080

echo ""
echo ""
echo "Testing POST request (list_projects)..."
# Test POST request
curl -s -X POST http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"method": "list_projects", "params": {}}'

echo ""
echo ""
echo "Stopping server..."
kill $SERVER_PID

echo "Test complete!"