<?php

namespace Technoliga\PlaneMcp;

class WorkItemDescriptionGenerator
{
    /**
     * Generate a standardized work item description with Claude Code implementation guide
     *
     * @param string $moduleName The module name (e.g., 'M01')
     * @param string $cycleName The cycle name (e.g., 'v1.0')
     * @param string $taskName The task name
     * @param string $priority The priority level (low, medium, high)
     * @param array $additionalContext Optional additional context for the task
     * @return array The formatted data for creating/updating an issue
     */
    public static function generateStandardizedDescription(
        string $moduleName,
        string $cycleName,
        string $taskName,
        string $priority = 'medium',
        array $additionalContext = []
    ): array {
        // Create the standardized name format
        $standardizedName = "[$moduleName-$cycleName] $taskName";

        // Generate the description with Claude Code implementation guide
        $descriptionHtml = self::generateDescriptionHtml($moduleName, $cycleName, $taskName, $priority, $additionalContext);

        return [
            'name' => $standardizedName,
            'description_html' => $descriptionHtml,
            'priority' => $priority
        ];
    }

    /**
     * Generate the HTML description with Claude Code implementation guide
     */
    protected static function generateDescriptionHtml(
        string $moduleName,
        string $cycleName,
        string $taskName,
        string $priority,
        array $additionalContext
    ): string {
        $html = "<p><strong>Module:</strong> $moduleName<br>\n";
        $html .= "<strong>Cycle:</strong> $cycleName<br>\n";
        $html .= "<strong>Priority:</strong> $priority</p>\n";

        if (!empty($additionalContext)) {
            $html .= "<p><strong>Context:</strong><br>\n";
            foreach ($additionalContext as $key => $value) {
                $html .= "$key: $value<br>\n";
            }
            $html .= "</p>\n";
        }

        $html .= "<hr>\n";
        $html .= "<h3>Claude Code Implementation Guide</h3>\n";
        $html .= "<h4>Analysis</h4>\n";
        $html .= "<p>This work item involves implementing the '$taskName' functionality ";
        $html .= "as part of the $moduleName module in the $cycleName cycle. ";
        $html .= "Based on the project specifications, this task requires careful attention to integration ";
        $html .= "with existing features and adherence to established coding standards.</p>\n";

        $html .= "<h4>Implementation Steps</h4>\n";
        $html .= "<ol>\n";
        $html .= "<li>Analyze the requirements and existing codebase to understand the scope</li>\n";
        $html .= "<li>Design the implementation approach considering best practices</li>\n";
        $html .= "<li>Implement the necessary code changes with proper error handling</li>\n";
        $html .= "<li>Write comprehensive unit and feature tests to validate functionality</li>\n";
        $html .= "<li>Document the implementation for future reference</li>\n";
        $html .= "<li>Perform integration testing to ensure compatibility with existing features</li>\n";
        $html .= "</ol>\n";

        $html .= "<h4>Testing</h4>\n";
        $html .= "<p>Verify that the implementation works correctly by:</p>\n";
        $html .= "<ul>\n";
        $html .= "<li>Running all existing tests to ensure no regressions</li>\n";
        $html .= "<li>Executing new tests specific to this feature</li>\n";
        $html .= "<li>Performing manual testing of the user interface where applicable</li>\n";
        $html .= "<li>Checking edge cases and error conditions</li>\n";
        $html .= "</ul>\n";

        $html .= "<h4>Success Criteria</h4>\n";
        $html .= "<p>The '$taskName' functionality should be fully operational and meet these criteria:</p>\n";
        $html .= "<ul>\n";
        $html .= "<li>All implemented features work as specified in the requirements</li>\n";
        $html .= "<li>Code follows established patterns and conventions</li>\n";
        $html .= "<li>Tests cover all major functionality and edge cases</li>\n";
        $html .= "<li>No performance degradation or security vulnerabilities introduced</li>\n";
        $html .= "<li>Documentation is clear and comprehensive</li>\n";
        $html .= "</ul>\n";

        return $html;
    }

    /**
     * Validate that the required information is provided for creating a work item
     *
     * @param array $params The parameters for creating a work item
     * @return array Validation result with success flag and error message if applicable
     */
    public static function validateWorkItemParams(array $params): array
    {
        // Check for required parameters
        $requiredFields = ['module', 'cycle', 'task_name'];

        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || empty($params[$field])) {
                return [
                    'success' => false,
                    'error' => "Missing required parameter: $field"
                ];
            }
        }

        // Validate module format (should be M followed by digits)
        if (!preg_match('/^M\d+$/', $params['module'])) {
            return [
                'success' => false,
                'error' => "Invalid module format. Expected format: M followed by digits (e.g., M01)"
            ];
        }

        // Validate cycle format (should be v followed by digits and optional decimal)
        if (!preg_match('/^v\d+(\.\d+)?$/', $params['cycle'])) {
            return [
                'success' => false,
                'error' => "Invalid cycle format. Expected format: v followed by digits (e.g., v1.0)"
            ];
        }

        // Validate priority if provided
        $validPriorities = ['low', 'medium', 'high'];
        if (isset($params['priority']) && !in_array($params['priority'], $validPriorities)) {
            return [
                'success' => false,
                'error' => "Invalid priority. Must be one of: " . implode(', ', $validPriorities)
            ];
        }

        return [
            'success' => true,
            'message' => 'All required parameters are valid'
        ];
    }
}