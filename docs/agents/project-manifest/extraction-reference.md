# Extraction Logic and Data Location Reference

> **Navigation Hub for X4 Data Extraction Documentation**

**Version:** 1.0  
**Last Updated:** February 9, 2026  
**Target Audience:** AI Agents, PHP Developers, X4 Modders

---

## 📋 Table of Contents

This reference is split into focused modules for easier navigation and maintenance:

| # | Document | Purpose | When to Use |
|---|----------|---------|-------------|
| **1** | [XML Sources & Schema](extraction-reference/xml-sources.md) | XML file locations, extractor inventory, schema reference | Finding where data lives, understanding game file structure |
| **2** | [Extraction Patterns](extraction-reference/patterns.md) | Macro resolution, Two-Phase/Single-Phase patterns, DOM queries | Understanding how extractors work, implementing new extractors |
| **3** | [Advanced Features](extraction-reference/advanced.md) | Variant IDs, data source inheritance | Handling DLCs, distinguishing similar items |
| **4** | [Equipment Compatibility](extraction-reference/equipment.md) | Equipment matching algorithm for ships | Filtering compatible equipment, understanding slot systems |
| **5** | [Troubleshooting](extraction-reference/troubleshooting.md) | Common errors and solutions | Debugging extraction failures, fixing build issues |
| **6** | [Development Guide](extraction-reference/development-guide.md) | Step-by-step extractor creation | Creating new extractors from scratch |

---

## 🎯 Quick Navigation

### By Task

**I want to...**

- **Find where shield data is stored** → [XML Sources & Schema](extraction-reference/xml-sources.md#xml-schema-quick-reference)
- **Understand macro resolution** → [Extraction Patterns](extraction-reference/patterns.md#macro-resolution-system)
- **Create a new extractor** → [Development Guide](extraction-reference/development-guide.md)
- **Debug "Macro not found" error** → [Troubleshooting](extraction-reference/troubleshooting.md#error-1-missing-macro-error)
- **Understand variant IDs** → [Advanced Features](extraction-reference/advanced.md#variant-id-system)
- **Filter compatible engines** → [Equipment Compatibility](extraction-reference/equipment.md)
- **See all extractors** → [XML Sources & Schema](extraction-reference/xml-sources.md#extractor-inventory)

### By Expertise Level

**Beginner (New to X4 Core):**
1. Start with [XML Sources & Schema](extraction-reference/xml-sources.md) - Understand data locations
2. Read [Extraction Patterns](extraction-reference/patterns.md) - Learn how extraction works
3. Try [Development Guide](extraction-reference/development-guide.md) - Create your first extractor

**Intermediate (Familiar with extractors):**
1. [Advanced Features](extraction-reference/advanced.md) - Understand variant IDs and DLCs
2. [Equipment Compatibility](extraction-reference/equipment.md) - Learn equipment matching
3. [Troubleshooting](extraction-reference/troubleshooting.md) - Common issues and solutions

**Advanced (Building complex extractors):**
1. [Extraction Patterns](extraction-reference/patterns.md) - Deep dive into patterns
2. [Equipment Compatibility](extraction-reference/equipment.md) - Complex filtering logic
3. [Development Guide](extraction-reference/development-guide.md) - Best practices and pitfalls

---

## 📊 System Overview

### Data Flow

```
Game Files (CAT/DAT)
    ↓ [x4-data-extractor unpacks]
Extracted XML (output/vanilla/, output/ego_dlc_*)
    ↓ [X4 Core extractors read]
JSON Files (data/*.json)
    ↓ [Runtime collections load]
In-Memory Collections (ShipDefs, EngineDefs, etc.)
    ↓ [Application uses]
UI + Business Logic
```

### Core Architecture

**11 Extractors:**
- MacroIndexExtractor → Builds macro→file mapping
- DataSourcesExtractor → Detects DLCs
- TranslationsExtractor → Language files
- FactionsExtractor → Races/factions
- WaresExtractor → All game items
- EnginesExtractor → Engine equipment
- ShieldsExtractor → Shield equipment
- WeaponsExtractor → Weapon equipment
- ModulesExtractor → Station modules
- ShipsExtractor → Ships with equipment
- BlueprintsExtractor → Crafting recipes

**2 Extraction Patterns:**
- **Two-Phase:** Main extractor filters wares → Macro extractor extracts details
- **Single-Phase:** Direct XML parsing without macro resolution

**Build Dependency Order:**
```
MacroIndex (no dependencies)
  ↓
DataSources (needs MacroIndex)
  ↓
Translations, Factions (need DataSources)
  ↓
Wares (needs Factions)
  ↓
Equipment (needs Wares)
  ↓
Ships (needs Equipment)
  ↓
Blueprints (needs Ships)
```

---

## 🚀 Getting Started

### For AI Agents

**First-time setup (15-20 minutes):**

1. **Read [AGENTS.md](../../AGENTS.md)** (15 min) - Overall project context
2. **Read [constraints.md](constraints.md)** (5 min) - Code rules
3. **Bookmark this page** - Your navigation hub

**Creating an extractor:**

1. Read [XML Sources & Schema](extraction-reference/xml-sources.md) - Locate data
2. Read [Extraction Patterns](extraction-reference/patterns.md) - Choose pattern
3. Follow [Development Guide](extraction-reference/development-guide.md) - Step-by-step
4. Use [Troubleshooting](extraction-reference/troubleshooting.md) - When things break

### For Human Developers

**Quick Start:**

1. Review [XML Sources & Schema](extraction-reference/xml-sources.md) - Understand data layout
2. Examine existing extractors in `src/X4/Database/*/`
3. Follow [Development Guide](extraction-reference/development-guide.md) for new extractors
4. Keep [Troubleshooting](extraction-reference/troubleshooting.md) handy

---

## 📚 Related Documents

### Project Manifest System

This extraction reference is part of the larger project manifest:

- **[AGENTS.md](../../AGENTS.md)** - AI agent operating system
- **[Project Manifest README](README.md)** - Main navigation hub
- **[tech-stack.md](tech-stack.md)** - Architectural patterns
  - See Collection-Item Pattern for runtime collections
  - See Extraction-Builder Pattern for extractor architecture
- **[data-flows.md](data-flows.md)** - Runtime data flows
  - See Database Build Flow for extraction visualization
- **[public-api.md](public-api.md)** - Class signatures
  - See Database namespace for extractor APIs
- **[constraints.md](constraints.md)** - Non-negotiable rules
  - See File I/O (synchronous only)
  - See Error Handling patterns
- **[file-tree.md](file-tree.md)** - Directory structure
  - See src/X4/Database/ for extractor locations

### Reading Order

**For Extractor Development:**
1. [AGENTS.md](../../AGENTS.md) (15 min) - Project context
2. [constraints.md](constraints.md) (10 min) - Rules
3. **[Extraction Patterns](extraction-reference/patterns.md)** (15 min) - Core concepts
4. **[Development Guide](extraction-reference/development-guide.md)** (30 min) - Step-by-step
5. [tech-stack.md](tech-stack.md) (optional) - Deeper patterns

---

## 🔄 Maintenance

### When to Update

**Add new extractor:**
1. Update [XML Sources & Schema](extraction-reference/xml-sources.md) - Add to inventory table
2. Update [Development Guide](extraction-reference/development-guide.md) - Add as example (optional)

**Change extraction algorithm:**
1. Update [Extraction Patterns](extraction-reference/patterns.md) - Document new pattern
2. Update [Development Guide](extraction-reference/development-guide.md) - Update examples

**Discover new error:**
1. Update [Troubleshooting](extraction-reference/troubleshooting.md) - Add error + solution

**Game version update:**
1. Update [XML Sources & Schema](extraction-reference/xml-sources.md) - Verify paths still valid
2. Update [Extraction Patterns](extraction-reference/patterns.md) - Update if XML schema changed

### Modular Benefits

Each document can be updated independently:
- **Smaller commits** - Only change relevant file
- **Parallel editing** - Multiple people can work simultaneously
- **Focused updates** - No need to scroll through 3000+ lines
- **Version control** - Clearer diffs and change history
- **Easier review** - Review only the changed document

---

## 📖 Document Statistics

- **Total Documents:** 7 (1 hub + 6 focused modules)
- **Total Content:** ~3,600 lines across all documents
- **Extractors Documented:** 11
- **Error Scenarios:** 6 with solutions
- **Code Examples:** 100+ PHP snippets
- **Patterns:** 2 (Two-Phase, Single-Phase)

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | February 9, 2026 | Initial modular structure with 6 focused documents |

---

**Document Status:** 🟢 Complete  
**Architecture:** Modular (7 documents)  
**Maintenance:** Update individual modules as needed, keep this hub synchronized
