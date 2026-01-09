 You are creating a specification sheet from a non-technical stakeholder. Please create or edit a single markdown document called {file_name}_spec.md . 

# Code to Specification Conversion Guide

## Overview

This document outlines the process for converting software code into comprehensive specification documents. The goal is to create clear, non-technical documentation that describes what the software should do, focusing on desired behaviors and ideal functionality.

## Specification Requirements

### Primary Objectives

When creating a specification sheet for a file, focus on the following key areas:

#### 1. Functional Description
- **What the file does**: Provide a clear, plain-English explanation of the file's intended primary function
- **Core responsibilities**: List the main tasks and duties the file handles
- **Business purpose**: Explain how the file serves the overall application or system
- **Value proposition**: If a file encapsulates a single meaningful concept, reduces duplication or complexity, and adds clarity or flexibility to the system, then it has a strong value proposition.
⚠️ If it exists only as a thin wrapper, adds layers without abstraction, or obscures rather than clarifies, its value proposition is weak.

### File Evaluation Framework

Before creating a specification, evaluate the file using these comprehensive criteria to ensure it provides genuine value:

#### 1. Problem-Solution Fit

- **What problem does the file solve?**: Identify the specific issue or need the file addresses
- **Responsibility encapsulation**: Does it encapsulate a specific responsibility or concept (e.g., "User Authentication," "Report Generation")?
- **System impact**: Would the system be worse off-more complex, less maintainable-if this file didn't exist?
- **Purpose clarity**: How clearly does it express its purpose?
- **API self-explanation**: Is the name and API self-explanatory?
- **Single responsibility**: Does it follow single responsibility principle (SRP), or is it overloaded with concerns?

#### 2. Functional Value

**Features and Utility**
- **Operations provided**: What operations, behaviors, or abstractions does the file provide?
- **Use case alignment**: Do those operations align with real use cases in the software?

**Reusability**
- **System reuse**: Can the file be reused in multiple parts of the system (or even across projects)?
- **Modularity**: Is it modular enough to stand alone without tight coupling?

#### 3. Non-Functional Value

**Maintainability**
- **Code quality**: Is the file easy to read, test, and extend?
- **Complexity reduction**: Does it reduce complexity elsewhere in the codebase?

**Scalability & Performance**
- **Efficiency**: Does it efficiently handle the scale of data or requests expected?
- **Overhead assessment**: Or does it introduce unnecessary overhead?

**Risk Reduction**
- **Logic isolation**: Does it isolate fragile logic, external dependencies, or security-sensitive functionality, reducing the blast radius of changes?

#### 4. Strategic Alignment

**Consistency**
- **Architectural alignment**: Does it align with the architectural style or domain model?
- **Cognitive load**: Does it reduce cognitive load for future developers by following familiar patterns?

**Longevity**
- **Foundation assessment**: Is this file a foundational building block likely to endure, or a short-term patch?

**Opportunity Cost**
- **Alternative evaluation**: Could the same functionality have been better implemented elsewhere (library, framework feature, or different abstraction)?

## Documentation Standards

### Content Guidelines
- use **plain English** rather than technical jargon
- focus on **conceptual understanding** rather than implementation details
- provide **context** for why the file exists within the larger system
- keep explanations **concise but comprehensive**
- Explicate incomplete functionality and describe it using "should" statements
- Descriptions of current buggy or undesired behavior

## Exclusions

This specification process explicitly **should NOT** include:
- Detailed technical implementation descriptions
- Code examples or syntax explanations
- Method-by-method breakdowns
- Performance or optimization details

## Expected Outcome

The final specification document should enable any stakeholder to understand:
- What role the file plays in the system
- What purpose it serves and how it is named
- Whether its current name serves the codebase well
- How it contributes to the overall application architecture
- What ideal behaviors and functionality the file exhibits