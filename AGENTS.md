# AI Agent Operating System - X4 Core

> **Version:** 1.0  
> **Last Updated:** February 8, 2026  
> **Purpose:** Source of Truth and Operating Procedures for AI Agents

---

## 🎯 Core Philosophy

### 1. **Manifest First, Code Second**
The **Project Manifest** is the authoritative source of truth. Agents MUST consult documentation before reading implementation code. This saves tokens and ensures architectural consistency.

### 2. **Context Efficiency**
Use the manifest and file-tree to minimize unnecessary file system searches. Every token counts.

### 3. **High Integrity**
If code contradicts the manifest, the **code is likely wrong** and should be flagged for correction.

---

## 📚 Project Manifest - Start Here!

### 🎯 Location
`/docs/agents/project-manifest/`

### 📖 Manifest Documents (Read in Order)

| Priority | Document | Purpose | When to Use |
|----------|----------|---------|-------------|
| **1** | [README.md](docs/agents/project-manifest/README.md) | Entry point, navigation, quick reference | **ALWAYS START HERE** |
| **2** | [tech-stack.md](docs/agents/project-manifest/tech-stack.md) | Runtime, dependencies, architectural patterns | Understanding patterns before coding |
| **3** | [constraints.md](docs/agents/project-manifest/constraints.md) | Non-negotiable rules and conventions | **BEFORE writing any code** |
| **4** | [public-api.md](docs/agents/project-manifest/public-api.md) | All public signatures (NO implementations) | Finding methods, understanding APIs |
| **5** | [file-tree.md](docs/agents/project-manifest/file-tree.md) | Complete directory structure | Locating files, understanding organization |
| **6** | [data-flows.md](docs/agents/project-manifest/data-flows.md) | How data moves through the system | Implementing features, debugging flows |

---

## 🚀 Quick Start Workflow

### For New Agents Entering the Codebase

```
┌─────────────────────────────────────────────────────────────┐
│ STEP 1: Read Project Manifest README                        │
│ Location: /docs/agents/project-manifest/README.md           │
│ Time: 2-3 min                                               │
│ Output: High-level understanding of the project             │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ STEP 2: Understand Architectural Patterns                   │
│ Document: tech-stack.md                                     │
│ Focus: Collection-Item pattern, Finder pattern, UI patterns │
│ Time: 5 min                                                 │
│ Output: Know the 8 core patterns used throughout           │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ STEP 3: Internalize Constraints                            │
│ Document: constraints.md                                    │
│ Focus: Naming conventions, interfaces, code style          │
│ Time: 5 min                                                 │
│ Output: Know what's allowed and what's forbidden           │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ STEP 4: Reference Public API as Needed                     │
│ Document: public-api.md                                     │
│ Usage: Lookup method signatures without reading source     │
│ Output: Know what methods exist and their contracts        │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ STEP 5: Locate Files Using File Tree                       │
│ Document: file-tree.md                                      │
│ Usage: Find files without grepping the filesystem          │
│ Output: Direct paths to relevant files                     │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ STEP 6: Understand Data Flows                              │
│ Document: data-flows.md                                     │
│ Usage: See how UI → Database → JSON flows work            │
│ Output: Mental model of system interactions                │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ READY: Begin Implementation                                 │
│ • Follow patterns from tech-stack.md                       │
│ • Obey rules from constraints.md                           │
│ • Reference public-api.md for signatures                   │
│ • Update manifest when adding features                     │
└─────────────────────────────────────────────────────────────┘
```

**Total Onboarding Time:** ~15-20 minutes  
**Token Efficiency:** High (avoids reading 142 source files)

---

## 📝 Manifest Maintenance Rules

**CRITICAL:** When making code changes, you MUST update the corresponding manifest documents. Failure to do so breaks the contract with future agents.

### Change → Document Mapping Table

| Code Change | Documents to Update | Specific Sections |
|-------------|---------------------|-------------------|
| **Add new Collection class** | `tech-stack.md`, `public-api.md`, `file-tree.md`, `data-flows.md` | Collection-Item Pattern, Database namespace, src/X4/Database/, Database Build Flow |
| **Add new UI Component** | `tech-stack.md`, `public-api.md`, `file-tree.md`, `data-flows.md` | UI Component Pattern, UI namespace, src/X4/UI/, UI Component Creation Flow |
| **Add new Page class** | `public-api.md`, `file-tree.md`, `data-flows.md` | UI\Page namespace, src/X4/UI/Page/, Page Rendering Flow |
| **Add new Exception class** | `tech-stack.md`, `public-api.md`, `file-tree.md` | Exception Hierarchy, Root/Database/UI namespace, appropriate folder |
| **Add new Finder class** | `tech-stack.md`, `public-api.md`, `file-tree.md`, `data-flows.md` | Finder Pattern, Database namespace, src/X4/Database/*/Finder.php, Database Query Flow |
| **Add new Extractor class** | `tech-stack.md`, `public-api.md`, `file-tree.md`, `data-flows.md` | Extraction-Builder Pattern, Database namespace, src/X4/Database/*/, Database Build Flow |
| **Add public method** | `public-api.md` | Relevant class section |
| **Add public constant** | `public-api.md` | Relevant class section |
| **Change naming convention** | `constraints.md`, `tech-stack.md` | Naming Conventions section |
| **Add new architectural pattern** | `tech-stack.md`, `data-flows.md`, `constraints.md` | Architectural Patterns, relevant flow section, Architectural Constraints |
| **Add new Composer dependency** | `tech-stack.md`, `constraints.md` | Core Dependencies, Adding Dependencies |
| **Add new data file** | `file-tree.md`, `data-flows.md` | data/ directory, Data Storage Layer |
| **Change file I/O approach** | `constraints.md`, `tech-stack.md` | File I/O Constraints, relevant pattern |
| **Add new UI layer** | `public-api.md`, `file-tree.md`, `data-flows.md` | UI namespace, src/X4/UI/, relevant flow |
| **Change error handling** | `constraints.md`, `tech-stack.md` | Error Handling, Exception Hierarchy |
| **Add Composer script** | `tech-stack.md`, `constraints.md` | Composer Scripts, Build Process |
| **Add new test suite** | `constraints.md`, `file-tree.md` | Testing Constraints, tests/ directory |
| **Change session usage** | `constraints.md`, `data-flows.md` | Session Management, Message Flow |
| **Add translation language** | `constraints.md`, `public-api.md` | Localization, Database\Translations namespace |
| **Modify database build order** | `data-flows.md`, `constraints.md` | Database Build Flow, Build Process |

### Maintenance Checklist

Before committing code changes:
- [ ] Identified which manifest documents need updates
- [ ] Updated all relevant manifest documents
- [ ] Verified no contradictions between code and manifest
- [ ] Updated "Last Updated" date in affected documents
- [ ] Updated version number if architectural changes made

---

## ⚡ Efficiency Rules - Search Smart

### **RULE 1: Manifest Before Source**
**NEVER** read source files for information that's in the manifest.

#### Decision Tree
```
Need to find a file?
    ├─ YES → Check file-tree.md
    └─ NO  → Continue
    
Need to understand a method signature?
    ├─ YES → Check public-api.md
    └─ NO  → Continue
    
Need to know what's allowed?
    ├─ YES → Check constraints.md
    └─ NO  → Continue
    
Need to understand a pattern?
    ├─ YES → Check tech-stack.md
    └─ NO  → Continue
    
Need to see how data flows?
    ├─ YES → Check data-flows.md
    └─ NO  → Continue
    
ONLY THEN → Read source files for implementation details
```

### **RULE 2: File Tree First**
Before using `grep_search` or `file_search`:
1. Open [file-tree.md](docs/agents/project-manifest/file-tree.md)
2. Navigate the visual tree structure
3. Identify exact file paths
4. Read specific files directly

**Saves:** 80% of filesystem operations

### **RULE 3: Public API First**
Before reading class files:
1. Open [public-api.md](docs/agents/project-manifest/public-api.md)
2. Search for the class name
3. Review signatures and contracts
4. Only read source if implementation logic needed

**Saves:** 90% of source file reads

### **RULE 4: Data Flow First**
Before implementing a feature:
1. Open [data-flows.md](docs/agents/project-manifest/data-flows.md)
2. Find the relevant flow pattern
3. Follow the established pattern
4. Reference public-api.md for specific signatures

**Saves:** Hours of architecture discovery

### **RULE 5: Constraints Always**
Before writing ANY code:
1. Open [constraints.md](docs/agents/project-manifest/constraints.md)
2. Review relevant sections
3. Follow established rules exactly
4. Never deviate without explicit user approval

**Prevents:** Architecture violations and rework

---

## 🚨 Failure Protocol & Decision Matrix

### When Encountering Issues

| Scenario | Action | Priority | Documents to Consult |
|----------|--------|----------|---------------------|
| **Ambiguous requirement** | Use most restrictive interpretation from constraints.md | MUST | constraints.md |
| **Manifest/Code conflict** | Trust manifest, flag code for correction | MUST | All manifest docs |
| **Missing documentation** | Document the gap, implement conservatively, update manifest | MUST | constraints.md for conventions |
| **Unclear pattern** | Find similar pattern in tech-stack.md, follow it exactly | MUST | tech-stack.md, data-flows.md |
| **Unknown method signature** | Check public-api.md before reading source | MUST | public-api.md |
| **Untested code path** | Write tests following constraints.md, mark as new coverage | SHOULD | constraints.md (Testing) |
| **Performance concern** | Follow "No Premature Optimization" rule | SHOULD | constraints.md (Performance) |
| **Security question** | Follow constraints.md security rules strictly | MUST | constraints.md (Security) |
| **Naming uncertainty** | Follow constraints.md naming conventions exactly | MUST | constraints.md (Naming) |
| **Architectural decision** | Match existing patterns from tech-stack.md | MUST | tech-stack.md |
| **File location uncertainty** | Use file-tree.md structure | MUST | file-tree.md |
| **Data flow confusion** | Study data-flows.md diagrams | MUST | data-flows.md |
| **Dependency question** | Check tech-stack.md allowed dependencies | MUST | tech-stack.md, constraints.md |
| **File I/O approach** | Use synchronous only (constraints.md) | MUST | constraints.md (File I/O) |
| **Error handling** | Follow exception hierarchy in tech-stack.md | MUST | tech-stack.md, constraints.md |
| **User request conflicts with constraints** | Flag conflict, request clarification | MUST | constraints.md |

### Conflict Resolution Priority

When faced with conflicting information:

```
1. constraints.md (Non-negotiable rules)
2. public-api.md (Established contracts)
3. tech-stack.md (Architectural patterns)
4. data-flows.md (Established flows)
5. file-tree.md (Structural organization)
6. Source code (Implementation details)
7. User request (May conflict with architecture)
```

If user request conflicts with items 1-6, **explicitly state the conflict** and request clarification.

---

## 📊 Project Statistics

### Core Metrics
- **Language:** PHP 8.4+
- **Architecture:** Layered (UI → Application → Database → Storage)
- **Primary Pattern:** Collection-Item with Finders
- **Total Classes:** 142
- **Total Lines (est.):** ~15,000
- **Data Files:** 8 JSON files + 7 translation files
- **UI Framework:** Bootstrap 5 + jQuery
- **Testing:** PHPUnit 9.5+

### Domain Organization
- **Database Layer:** 80+ classes (Factions, Wares, Ships, Modules, Blueprints, etc.)
- **UI Layer:** 40+ classes (Components, Pages, DataGrid, AJAX)
- **XML Layer:** 10+ classes (DOM utilities, Finders)
- **Core Layer:** 5 classes (Application, Exceptions)

### Key Numbers
- **Collections:** 8 major (Factions, Wares, Ships, Modules, Blueprints, DataSources, Translations, MacroIndex)
- **Finders:** 4 (Wares, Ships, Modules, Blueprints)
- **UI Components:** 5 (Button, Icon, Text, DataGrid, Console)
- **Page Types:** 3 (BasePage, BasePageWithNav, BaseSubPage)
- **Languages Supported:** 7 (English, German, French, Spanish, Italian, Russian, Korean)

---

## 🔍 Quick Reference Commands

### Finding Information

```bash
# Find a class location
→ Open file-tree.md, search for class name

# Find method signature
→ Open public-api.md, search for class name

# Understand a pattern
→ Open tech-stack.md, find pattern section

# See data flow
→ Open data-flows.md, find flow diagram

# Check if something is allowed
→ Open constraints.md, search for topic
```

### Common Tasks

```bash
# Add a new ware type
→ Study: tech-stack.md (Collection-Item Pattern)
→ Follow: data-flows.md (Database Build Flow)
→ Reference: constraints.md (Collection-Item Interface)
→ Update: All 4 documents + public-api.md + file-tree.md

# Add a new page
→ Study: data-flows.md (Page Rendering Flow)
→ Extend: BasePage or BasePageWithNav
→ Reference: public-api.md (UI\Page namespace)
→ Update: public-api.md + file-tree.md

# Filter a collection
→ Study: data-flows.md (Database Query Flow)
→ Use: *Defs::getInstance()->find*()->select*()->getAll()
→ Reference: public-api.md (Finder signatures)

# Create UI component
→ Study: data-flows.md (UI Component Creation Flow)
→ Use: Fluent interface pattern
→ Reference: public-api.md (UI namespace)
```

---

## 🛡️ Guardrails

### What Agents MUST Do
- ✅ Read manifest before source code
- ✅ Follow all constraints.md rules
- ✅ Update manifest when adding features
- ✅ Use established patterns from tech-stack.md
- ✅ Reference public-api.md for signatures
- ✅ Follow data flows from data-flows.md
- ✅ Flag conflicts between manifest and code
- ✅ Ask for clarification when uncertain

### What Agents MUST NOT Do
- ❌ Skip reading constraints.md
- ❌ Deviate from established patterns
- ❌ Add code without updating manifest
- ❌ Use async file I/O (synchronous only)
- ❌ Add database queries (in-memory only)
- ❌ Ignore naming conventions
- ❌ Create exceptions outside hierarchy
- ❌ Modify render() method in pages
- ❌ Add dependencies without checking constraints
- ❌ Use eval() or execute user code

---

## 📖 Additional Resources

### External Documentation
- [Composer](https://getcomposer.org/) - Dependency management
- [PHPUnit](https://phpunit.de/) - Testing framework
- [PHPStan](https://phpstan.org/) - Static analysis
- [Bootstrap 5](https://getbootstrap.com/docs/5.1/) - UI framework

### Related Projects
- [X4 Data Extractor](https://github.com/Mistralys/x4-data-extractor) - Game data extraction
- [X4 Game Notes](https://github.com/Mistralys/x4-game-notes) - Documentation

### Support
- **Issues:** GitHub Issues
- **Questions:** README.md contact information

---

## 🔄 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | Feb 8, 2026 | Initial AGENTS.md with complete manifest integration |

---

## 💡 Pro Tips for Maximum Efficiency

1. **Bookmark the manifest README** - It's your starting point for everything
2. **Keep public-api.md open** - Reference it constantly
3. **Memorize the 8 patterns** - Collection-Item, Finder, Extraction-Builder, UI Component, Page-SubPage, Exception Hierarchy, Static Factory, Extended DOM
4. **Trust the manifest** - If code conflicts, the manifest is probably right
5. **Update as you go** - Don't batch manifest updates, do them immediately
6. **Use the decision tree** - It saves tokens and time
7. **Follow the flows** - data-flows.md has all the answers
8. **Respect constraints** - They're there for good reasons

---

**Remember:** This manifest represents hundreds of hours of architectural decisions. Respect it, follow it, and update it. Future agents (and humans) depend on it.

---

## 🎓 Success Criteria

An agent has successfully integrated with this codebase when:
- ✅ Can navigate to any file using file-tree.md
- ✅ Can find any method signature using public-api.md
- ✅ Knows all 8 architectural patterns by heart
- ✅ Never violates constraints.md rules
- ✅ Updates manifest with every code change
- ✅ Follows established data flows
- ✅ Writes code indistinguishable from existing codebase

**Estimated Time to Proficiency:** 20 minutes with manifest, 4+ hours without
