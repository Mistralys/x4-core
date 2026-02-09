# Implementation Plan Template

> **📋 FOR PLANNER AGENTS: How to Use This Template**
>
> This template provides a proven structure for creating session-independent implementation plans. Follow these steps:
>
> 1. **Replace ALL placeholders** in `[BRACKETS]` with specific project details
> 2. **Fill in the Project Overview** section completely before starting work packages
> 3. **Break work into 3-7 packages** - each should be 1-3 hours of focused work
> 4. **Map dependencies** between packages using the visual diagram format
> 5. **Include complete code templates** in each work package (not just outlines)
> 6. **Document all design decisions** with rationale in Key Design Decisions table
> 7. **List specific files** to create/modify with exact paths and line ranges where possible
> 8. **Add verification checklists** for each package to ensure correctness
> 9. **Update manifest requirements** - reference AGENTS.md maintenance rules
> 10. **Include testing commands** that can be copy-pasted to verify implementation
>
> **Critical Success Factors:**
> - Each work package must be **independently understandable** months later
> - Include **why** decisions were made, not just what to do
> - Provide **complete context** - assume the reader knows nothing about current session
> - Link to **reference implementations** in the existing codebase
> - Document **expected output** formats (JSON structure, API responses, etc.)
> - Add **troubleshooting sections** for common failure modes
>
> **Before finalizing:**
> - [ ] All placeholders replaced with specific values
> - [ ] Code templates are syntactically correct and follow project conventions
> - [ ] File paths are absolute and match project structure (check file-tree.md)
> - [ ] Design decisions align with constraints.md rules
> - [ ] Patterns match tech-stack.md established patterns
> - [ ] Manifest update requirements are complete and specific
> - [ ] Each work package has clear verification criteria
> - [ ] Dependencies between packages are accurately mapped
> - [ ] Knowledge transfer section addresses future agent needs
>
> **Delete this instruction block** before saving the final plan.

---

# [FEATURE_NAME] - Implementation Plan

> **Created:** [DATE]  
> **Status:** Not Started  
> **Estimated Total Time:** [X-Y] hours  
> **Dependencies:** [List external dependencies: projects, tools, data files]

---

## 🎯 Project Overview

### Objective
[Clear 2-3 sentence description of what this implementation achieves and why it matters]

### Business Value
- [Concrete benefit 1]
- [Concrete benefit 2]
- [Concrete benefit 3]
- [Future capability this enables]

### Architectural Approach
[Describe the pattern(s) being used from tech-stack.md]

**Pattern:** [Pattern name from tech-stack.md]
- **[ComponentType1]** ([ClassName]) - [Purpose]
- **[ComponentType2]** ([ClassName]) - [Purpose]
- **[ComponentType3]** ([ClassName]) - [Purpose]

### Key Design Decisions

| Decision | Rationale |
|----------|-----------|
| **[Decision 1]** | [Why this approach was chosen over alternatives] |
| **[Decision 2]** | [How it aligns with existing architecture] |
| **[Decision 3]** | [What problem it solves] |
| **[Decision 4]** | [What constraint it satisfies from constraints.md] |

### System Context

**Current State:**
- [What exists today]
- [What's missing that this adds]
- [Where data/functionality currently lives]

**After Implementation:**
- [New capabilities available]
- [How users/systems will interact with it]
- [Integration points with existing system]

### [Data Structure / API / Protocol] Details

[For data-driven features: show JSON structure, XML schema, etc.]
[For API features: show endpoints, request/response formats]
[For UI features: show component hierarchy, props structure]

```[FORMAT]
[Example of the main data structure or interface this feature works with]
```

**Key Fields/Properties:**
- **[Field1]:** [Type] - [Purpose and constraints]
- **[Field2]:** [Type] - [Purpose and constraints]
- **[Field3]:** [Type] - [Purpose and constraints]

### Required Manifest Updates

Per [AGENTS.md maintenance rules](../../AGENTS.md#manifest-maintenance-rules), manifest must be updated when [what triggers updates]:

| Manifest Document | Required Updates | Sections |
|-------------------|------------------|----------|
| [file-tree.md](../project-manifest/file-tree.md) | [What structure to add] | [Specific section names] |
| [public-api.md](../project-manifest/public-api.md) | [What API to document] | [Namespace/class locations] |
| [data-flows.md](../project-manifest/data-flows.md) | [What flow to add] | [Flow name] |
| [tech-stack.md](../project-manifest/tech-stack.md) | [What to add/update] | [Section names] |
| [constraints.md](../project-manifest/constraints.md) | [If any rules added] | [Rule category] |

---

## 📦 Work Package Breakdown

This plan is divided into **[N] independent work packages** that can be implemented incrementally. Each package includes complete context for pickup without prior session knowledge.

### Package Dependencies

```
[Show dependency diagram using ASCII art or describe textually]

Example:
WP1 (Class A) ─┐
               ├─> WP2 (Class B) ─┐
WP3 (Class C) ─┘                   ├─> WP5 (Integration)
                                   │
WP4 (Tests)    ────────────────────┘
```

**Implementation Order:**
1. [Describe which packages can be done in parallel]
2. [Describe which must be sequential]
3. [Explain why this order matters]

---

## 🔨 Work Package 1: [PACKAGE_NAME]

**Status:** Not Started  
**Estimated Time:** [X] hours  
**Dependencies:** [None | WP# | External dependency]  
**Assigned To:** Unassigned

### Goal
[One sentence describing what this package achieves]

### Context
- **Pattern:** [Pattern name] (see [link to tech-stack.md section])
- **Example:** Study `[path/to/reference/file.php]` for similar structure
- **Constraints:** [Link to relevant constraints.md sections] - [Key rules that apply]

### Files to Create

#### `[path/to/new/file.php]`

**Class Structure:**
```php
<?php
namespace [Namespace];

use [Imports];

/**
 * [Class purpose and responsibilities]
 * 
 * [Usage example or pattern notes]
 */
class [ClassName] [extends/implements] [BaseClass/Interface]
{
    [Include complete or near-complete implementation]
    [Include all properties with types]
    [Include all method signatures with PHPDoc]
    [Include key implementation logic]
    [Mark areas that need customization with // TODO or [CUSTOMIZE]]
}
```

[If multiple files, repeat above structure for each]

### Files to Modify

#### `[path/to/existing/file.php]`

**Location:** [Describe where in the file to make changes - method name, line range if known]

**Change 1: [Description]**

[Show the OLD code with context]
```php
[3-5 lines before the change]
[The code to be changed]
[3-5 lines after the change]
```

**Change to:**
```php
[3-5 lines before the change]
[The NEW code to replace it]
[3-5 lines after the change]
```

[Repeat for each change]

### Implementation Steps

1. [Concrete action 1 with exact commands if applicable]
2. [Concrete action 2]
3. [Concrete action 3]
4. Verify PHP syntax: `php -l [path/to/file.php]`
5. Run PHPStan: `composer phpstan`
6. [Additional verification steps]

### Verification Checklist

- [ ] [Specific verification item 1]
- [ ] [Specific verification item 2]
- [ ] [Code follows naming conventions from constraints.md]
- [ ] [Implements required interface/pattern correctly]
- [ ] [All properties have type declarations]
- [ ] [All methods have PHPDoc with @param and @return]
- [ ] PHPStan passes with no errors
- [ ] [Feature-specific verification]

### Reference Files
- Pattern: `[path/to/pattern/example.php]`
- Interface: `[path/to/interface.php]`
- Base class: `[path/to/base.php]`
- [Other relevant references]

### Testing Note
[Describe how to test this package, or note if testing must wait for later packages]

```[language]
[Provide copy-paste testing commands or code]
```

---

## 🔨 Work Package 2: [PACKAGE_NAME]

**Status:** Not Started  
**Estimated Time:** [X] hours  
**Dependencies:** [WP# that must complete first]  
**Assigned To:** Unassigned

### Goal
[One sentence describing what this package achieves]

[Continue with same structure as WP1]

---

[Repeat Work Package template for WP3, WP4, etc.]

---

## 🔨 Work Package [N]: Documentation & Tests

**Status:** Not Started  
**Estimated Time:** [X] hours  
**Dependencies:** [Usually depends on all previous WPs]  
**Assigned To:** Unassigned

### Goal
Update project manifest documentation and create comprehensive unit tests.

### Context
Per [AGENTS.md maintenance rules](../../AGENTS.md#manifest-maintenance-rules), manifest must be updated when [what changed].

### Files to Create

#### Unit Tests Directory Structure

```
tests/[TestPath]/
├── [Feature]Test.php       # Test [what]
├── [Feature]sTest.php      # Test [what]
├── [Feature]FinderTest.php # Test [what]
└── [Other]Test.php         # Test [what]
```

#### `tests/[TestPath]/[Feature]Test.php`

```php
<?php
namespace [TestNamespace];

use PHPUnit\Framework\TestCase;
use [ClassUnderTest];

class [Feature]Test extends TestCase
{
    [Include several complete test method examples]
    [Show the pattern for testing this feature]
    [Include setup/teardown if needed]
    [Test both success and failure cases]
}
```

[Repeat for additional test classes]

### Files to Modify

#### `docs/agents/project-manifest/file-tree.md`

**Location:** [Describe where in file-tree.md to add the new structure]

**Add this structure:**
```markdown
### [Section]/

[Description of what this section contains]

\`\`\`
[path/to/new/]
├── [File1].php              # [Purpose]
├── [File2].php              # [Purpose]
└── [File3].php              # [Purpose]
\`\`\`
```

#### `docs/agents/project-manifest/public-api.md`

**Location:** Add new section after `[RelatedSection]`.

**Add complete namespace documentation:**

```markdown
## [Namespace]

### [ClassName1]

[Description of class purpose]

**Properties:**
- [List public constants or key properties]

**Methods:**
\`\`\`php
[List all public method signatures with types]
public function methodName(Type $param): ReturnType
\`\`\`

**Usage:**
\`\`\`php
[Show concrete usage example]
\`\`\`

### [ClassName2]

[Repeat for each public class in namespace]
```

#### `docs/agents/project-manifest/data-flows.md`

**Location:** Add new section after "[Related Flow]".

**Add this diagram:**

```markdown
## [Flow Name]

[Description of what this flow accomplishes and when it occurs]

\`\`\`mermaid
flowchart TD
    [Create complete mermaid diagram showing the flow]
    [Include all major steps]
    [Show decision points]
    [Indicate data transformations]
    [Mark entry and exit points]
    
    style [EntryNode] fill:#e1f5ff
    style [ExitNode] fill:#4caf50,color:#fff
\`\`\`

**[Data/Component] Mapping:**

| [Source] | [Property/Attribute] | [Destination] |
|----------|---------------------|---------------|
| [Item1]  | [Detail1]           | [Target1]     |
| [Item2]  | [Detail2]           | [Target2]     |

[If applicable, show data structure relationships with ASCII art]
```

#### `docs/agents/project-manifest/tech-stack.md`

**Location 1:** Find "[DataFiles/Components/Section]" section.

**Add to list:**
```markdown
- `[path/to/file]` - [Description of what it contains/does]
```

**Location 2:** Find "[Pattern] Pattern" examples.

**Add to examples:**
```markdown
- **[Feature]** (`[Class1]`/`[Class2]`) - [Description with key capabilities]
```

### Implementation Steps

1. Create test directory: `tests/[TestPath]/`
2. Create all test files ([list files])
3. Run tests: `composer test` or `vendor/bin/phpunit tests/[TestPath]/`
4. Fix any failing tests
5. Update all manifest documents ([list documents])
6. Verify manifest consistency with code
7. Run full build to ensure no regressions: `composer build`

### Verification Checklist

- [ ] All test files created in correct directory structure
- [ ] All tests pass: `composer test`
- [ ] Code coverage for [feature] classes > [X]%
- [ ] `file-tree.md` shows [feature] structure
- [ ] `public-api.md` documents all public methods
- [ ] `data-flows.md` includes [feature] flow diagram
- [ ] `tech-stack.md` lists [new files and patterns]
- [ ] No manifest contradictions with code
- [ ] PHPStan passes: `composer phpstan`
- [ ] Full build succeeds: `composer build`

### Testing Commands

```powershell
# Run all tests
composer test

# Run only [feature] tests
vendor/bin/phpunit tests/[TestPath]/

# Run specific test
vendor/bin/phpunit tests/[TestPath]/[Feature]Test.php

# Check coverage (if configured)
vendor/bin/phpunit --coverage-html coverage/
```

### Reference Files
- Test pattern: `tests/[SimilarFeature]/[Similar]Test.php`
- Manifest examples: All files in `docs/agents/project-manifest/`

---

## 📊 Progress Tracking

### Overall Status

| Package | Status | Completion | Time Spent | Notes |
|---------|--------|------------|------------|-------|
| **WP1** [Name] | Not Started | 0% | 0h | [Brief description] |
| **WP2** [Name] | Not Started | 0% | 0h | [Brief description] |
| **WP3** [Name] | Not Started | 0% | 0h | [Brief description] |
| **WP4** [Name] | Not Started | 0% | 0h | [Brief description] |
| **WP[N]** [Name] | Not Started | 0% | 0h | [Brief description] |
| **Total** | Not Started | 0% | 0h / [X]h | - |

### Completion Criteria

Project is complete when:
- [ ] All [N] work packages marked as "Complete"
- [ ] [Key deliverable 1] exists with [expected characteristics]
- [ ] `composer [command]` runs successfully
- [ ] `composer build` includes [new functionality]
- [ ] `composer test` passes all [feature] tests
- [ ] `composer phpstan` passes with no errors
- [ ] All manifest documents updated
- [ ] Usage example works:
  ```[language]
  [Provide concrete usage example that tests the feature end-to-end]
  ```

---

## 🎓 Knowledge Transfer

### For Future Agents

When picking up this work:

1. **Start with AGENTS.md** - Read the full agent operating system guide
2. **Review Project Manifest** - Especially [list key documents relevant to this feature]
3. **Check [deliverable] status** - Does it exist? Is it current? Is it valid?
4. **Verify prerequisites** - [List what must exist before starting]
5. **Follow WP order** - Don't skip dependencies (WP[X] requires WP[Y,Z])
6. **Test incrementally** - Run PHPStan and tests after each WP
7. **Update manifest** - Don't batch updates, do them immediately

### Key Patterns to Understand

1. **[Pattern 1 Name]**
   - Study: `[ReferenceClass]` as reference
   - [Key concept 1]
   - [Key concept 2]
   - [Key concept 3]

2. **[Pattern 2 Name]**
   - Study: `[ReferenceClass]` for [what aspect]
   - [Key concept 1]
   - [Key concept 2]

3. **[Flow/Process Name]**
   - [Step 1 description]
   - [Step 2 description]
   - [Why this order matters]

### Common Pitfalls

1. **[Pitfall 1]**
   - Symptom: [What you'll see when this happens]
   - Cause: [Why it occurs]
   - Solution: [How to fix it]

2. **[Pitfall 2]**
   - Symptom: [What you'll see]
   - Cause: [Root issue]
   - Solution: [Resolution steps]

3. **[Pitfall 3]**
   - Symptom: [Observable problem]
   - Cause: [Underlying reason]
   - Solution: [Fix procedure]

### External Dependencies

- **[Dependency 1]** - [What it provides and why needed]
- **[Dependency 2]** - [Relationship to this feature]
- **[Dependency 3]** - [When it's used in the flow]

---

## 📞 Support & Questions

### Decision Points Requiring User Input

[List any decisions that were NOT made in this plan and WHY they need user input]

1. **[Decision 1]** - [What needs to be decided and implications]
2. **[Decision 2]** - [Options available and trade-offs]

### Useful Debugging Commands

```[language]
// [Description of what this checks]
[Command or code snippet to copy-paste]

// [Description of another debug technique]
[Another useful command]

// [Description of verification method]
[Final debugging approach]
```

---

## 🏁 Final Notes

[Summary paragraph about the implementation approach and why it was chosen]

**Estimated Lines of Code:**
- [Component1]: ~[X] lines
- [Component2]: ~[Y] lines
- [Component3]: ~[Z] lines
- Tests: ~[T] lines
- **Total: ~[N] lines**

**Key Success Metrics:**
- [Measurable outcome 1]
- [Measurable outcome 2]
- [Measurable outcome 3]
- [Quality metric]

**Remember:** [Key advice for implementers - what to focus on, what to avoid, what pattern to follow religiously]

---

**End of Implementation Plan**
