# Agent Implementation Plans

This directory contains implementation plans for significant features being added to x4-core. Each plan is designed to be **session-independent** and can be picked up by any agent at any time, even months after creation.

## Purpose

Implementation plans serve multiple purposes:
1. **Break down complex features** into manageable work packages
2. **Preserve context** so work can be resumed after interruptions
3. **Document design decisions** with rationale for future reference
4. **Provide complete implementation details** including code templates
5. **Ensure consistency** with project architecture and patterns
6. **Track progress** on multi-session implementations

## Files in this Directory

### 📋 `implementation-plan-template.md`
**Reusable template for creating new implementation plans.**

Use this template when planning any significant feature (3+ hours of work). It includes:
- Instructions for planner agents at the top
- All necessary sections with placeholders
- Proven work package structure
- Manifest update requirements
- Testing and verification checklists

**When to use:** Starting any new feature that requires multiple work sessions or careful planning.

### 📦 `engine-metadata.md`
**Example implementation plan for adding engine performance data system.**

A complete, production-ready plan showing:
- How to break a feature into 5 independent work packages
- Complete code templates for all classes
- Detailed data extraction from X4 XML files
- Full manifest integration requirements
- Comprehensive testing approach

**Status:** Not Started (as of Feb 9, 2026)

**When to reference:** When planning similar data extraction features or Collection-Item pattern implementations.

## Creating a New Implementation Plan

### Quick Start

1. **Copy the template:**
   ```powershell
   Copy-Item implementation-plan-template.md your-feature-name.md
   ```

2. **Read the instructions** at the top of the template (planner agent guidance)

3. **Replace all `[PLACEHOLDERS]`** with specifics for your feature

4. **Delete the instruction block** at the top before finalizing

5. **Validate against checklist** in the instruction block

### Planning Guidelines

**DO:**
- ✅ Include complete code templates (not just outlines)
- ✅ Provide specific file paths (use file-tree.md)
- ✅ Link to reference implementations in codebase
- ✅ Document WHY decisions were made
- ✅ Add verification checklists for each work package
- ✅ Show expected output formats (JSON, XML, etc.)
- ✅ Include troubleshooting for common failures
- ✅ Map dependencies between work packages
- ✅ Update manifest requirement table
- ✅ Provide copy-paste testing commands

**DON'T:**
- ❌ Leave placeholders unfilled
- ❌ Assume future readers have current context
- ❌ Skip rationale for design decisions
- ❌ Omit code templates ("just implement X" is not enough)
- ❌ Forget manifest updates (breaks future agents)
- ❌ Create circular dependencies between packages
- ❌ Write vague verification criteria
- ❌ Leave external dependencies undocumented

### Work Package Best Practices

**Size:** 1-3 hours of focused implementation work per package

**Independence:** Each package should include:
- Complete context (assume reader knows nothing)
- All required code templates
- Specific files to create/modify
- Verification steps
- Reference to similar implementations
- Testing approach

**Dependencies:** 
- Map clearly in a visual diagram
- Explain WHY a dependency exists
- Minimize coupling where possible
- Note which packages can be parallelized

## Integration with AGENTS.md

All implementation plans MUST follow patterns and constraints from:
- [AGENTS.md](../../AGENTS.md) - Agent operating system
- [Project Manifest](../project-manifest/README.md) - Architecture documentation

**Before finalizing any plan:**
1. Review [tech-stack.md](../project-manifest/tech-stack.md) for applicable patterns
2. Check [constraints.md](../project-manifest/constraints.md) for rules
3. Verify file paths match [file-tree.md](../project-manifest/file-tree.md)
4. Follow [data-flows.md](../project-manifest/data-flows.md) for data operations
5. Plan manifest updates per [AGENTS.md maintenance rules](../../AGENTS.md#manifest-maintenance-rules)

## Plan Lifecycle

### 1. Planning Phase
- Create plan from template
- Fill in all details
- Review against checklist
- Get user approval on design decisions

### 2. Implementation Phase
- Work through packages in dependency order
- Update progress tracking table after each package
- Run verification checklists
- Update manifest documents immediately (don't batch)

### 3. Completion Phase
- Mark all packages complete
- Run all verification commands
- Update plan status to "Complete"
- Archive or keep for reference

### 4. Maintenance Phase
- Update plan if requirements change
- Document lessons learned
- Reference in future related work

## Naming Conventions

Plans should be named descriptively:
- `feature-name.md` - New feature implementations
- `refactor-component-name.md` - Refactoring work
- `fix-issue-description.md` - Complex bug fixes
- `integrate-system-name.md` - Integration work

Use lowercase with hyphens, be specific and descriptive.

## Examples for Common Scenarios

### Data Extraction Feature
See: `engine-metadata.md`
- Collection-Item pattern
- XML parsing and extraction
- JSON data file generation
- Finder for filtering

### UI Component
*Template sections to emphasize:*
- Component hierarchy and props
- CSS/JS file organization
- Integration with existing UI
- Browser testing approach

### API Endpoint
*Template sections to emphasize:*
- Request/response formats
- Error handling
- Authentication/authorization
- Integration testing

### Database Schema Change
*Template sections to emphasize:*
- Migration approach
- Data transformation
- Rollback strategy
- Testing with real data

## Questions?

If you're unsure whether your feature needs a full implementation plan:

**Use a plan if:**
- Implementation will take 3+ hours
- Multiple files need creation/modification
- Architecture decisions are required
- Work may span multiple sessions
- Pattern adherence is critical
- Manifest updates are needed

**Skip the plan if:**
- Simple bug fix (< 30 min)
- Single file change
- No architectural impact
- Can be completed in one session
- No manifest updates needed

When in doubt, create a plan. The time investment pays off in reduced cognitive load and better quality.

---

**Maintained by:** AI Agents following [AGENTS.md](../../AGENTS.md)  
**Last Updated:** February 9, 2026
