# Plane MCP Server - Standardized Work Item Creation

This extension to the Plane MCP Server adds functionality for creating standardized work items with consistent naming conventions and comprehensive descriptions that include Claude Code implementation guides.

## New Features

### Standardized Work Item Creation

The new `create_standardized_work_item` method automatically generates:

1. **Consistent Naming Convention**: `[Module-Cycle] Task Name`
   - Example: `[M02-v1.0] Implement search functionality`

2. **Structured Descriptions** with:
   - Module and cycle identification
   - Priority level
   - Additional context (optional)
   - Claude Code Implementation Guide including:
     - Analysis section
     - Implementation steps
     - Testing guidelines
     - Success criteria

## Usage

### Via MCP Protocol

Call the `create_standardized_work_item` method with these parameters:

```json
{
  "method": "create_standardized_work_item",
  "params": {
    "project_slug": "supporthub",
    "module": "M02",
    "cycle": "v1.0",
    "task_name": "Implement search functionality",
    "priority": "high",
    "context": {
      "feature_area": "Knowledge Base",
      "estimated_hours": "8",
      "dependencies": "Database schema ready"
    }
  }
}
```

### Required Parameters

- `project_slug` (string, required): The project identifier
- `module` (string, required): Module identifier (format: M followed by digits, e.g., M01)
- `cycle` (string, required): Cycle identifier (format: v followed by digits, e.g., v1.0)
- `task_name` (string, required): The task name

### Optional Parameters

- `priority` (string, optional): Priority level (low, medium, high) - defaults to medium
- `context` (array, optional): Additional context information for the task

## Example Output

When you create a work item with the parameters above, it will generate a work item named:
`[M02-v1.0] Implement search functionality`

With a description that includes:
```
Module: M02
Cycle: v1.0
Priority: high

Context:
feature_area: Knowledge Base
estimated_hours: 8
dependencies: Database schema ready

---

### Claude Code Implementation Guide

#### Analysis
This work item involves implementing the 'Implement search functionality' functionality as part of the M02 module in the v1.0 cycle...

#### Implementation Steps
1. Analyze the requirements and existing codebase to understand the scope
2. Design the implementation approach considering best practices
3. Implement the necessary code changes with proper error handling
4. Write comprehensive unit and feature tests to validate functionality
5. Document the implementation for future reference
6. Perform integration testing to ensure compatibility with existing features

#### Testing
Verify that the implementation works correctly by:
- Running all existing tests to ensure no regressions
- Executing new tests specific to this feature
- Performing manual testing of the user interface where applicable
- Checking edge cases and error conditions

#### Success Criteria
The 'Implement search functionality' functionality should be fully operational and meet these criteria:
- All implemented features work as specified in the requirements
- Code follows established patterns and conventions
- Tests cover all major functionality and edge cases
- No performance degradation or security vulnerabilities introduced
- Documentation is clear and comprehensive
```

## Benefits

1. **Consistency**: All work items follow the same naming and description format
2. **Clarity**: Clear module/cycle organization visible in work item titles
3. **AI Integration**: Built-in Claude Code implementation guides for automated development
4. **Validation**: Automatic validation of module and cycle formats
5. **Extensibility**: Easy to add additional context and customization

## Requirements

- Module identifiers must follow the format: `M` followed by digits (e.g., `M01`, `M15`)
- Cycle identifiers must follow the format: `v` followed by digits and optional decimal (e.g., `v1.0`, `v2.5`)
- Priority levels must be one of: `low`, `medium`, `high`