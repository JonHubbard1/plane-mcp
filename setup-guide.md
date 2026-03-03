# Plane MCP Project Setup Guide

This document provides instructions for setting up and using the Plane MCP server with your Plane instance.

## Project Information

- **Project Name**: Plane MCP
- **Description**: MCP server for integrating with Plane project management tool, enabling AI assistants like Claude Code to interact directly with Plane instances for project management automation.
- **Project ID**: 18997011-9d69-4e75-90e4-7fb77d0b3001

## Getting Started

### 1. Repository Setup

1. Create a new GitHub repository named `plane-mcp`
2. Clone the repository locally:
   ```bash
   git clone https://github.com/your-username/plane-mcp.git
   cd plane-mcp
   ```

3. Copy the project files from the local Plane_MCP directory:
   ```bash
   cp -r /Users/jonhubbard/Herd/SupportHub/Plane_MCP/* .
   ```

4. Initialize the repository:
   ```bash
   git add .
   git commit -m "Initial commit: Plane MCP Server"
   git push origin main
   ```

### 2. Local Development Setup

1. Install PHP dependencies:
   ```bash
   composer install
   ```

2. Configure environment variables:
   ```bash
   cp .env.example .env
   # Edit .env with your Plane credentials
   ```

3. Start the development server:
   ```bash
   php -S localhost:8080 server.php
   ```

### 3. Connecting to Claude Code

1. Configure Claude Code to connect to the MCP server at `http://localhost:8080`
2. Test the connection by listing projects:
   ```javascript
   const projects = await mcp.call('list_projects', {});
   ```

## Available Documentation Pages

The following documentation pages have been created in your Plane project:

1. **Project Overview** - Introduction to the Plane MCP project
2. **Installation Guide** - How to set up the MCP server
3. **API Documentation** - Detailed API reference for all MCP methods
4. **Integration Guide** - How to integrate with Claude Code
5. **Development Guide** - Information for developers contributing to the project

## Next Steps

1. Review the documentation pages in Plane
2. Test the MCP server with Claude Code
3. Customize the configuration for your specific needs
4. Contribute improvements back to the project

## Support

For support, please contact Jon Hubbard at Technoliga Ltd.