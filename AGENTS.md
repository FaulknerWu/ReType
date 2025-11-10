# Repository Guidelines

This repository contains the ReType theme for Typecho blogs. Keep changes scoped, reversible, and documented so other contributors can evaluate them quickly.

## MCP Tools

| Scenario                                                     | Preferred MCP         |
| ------------------------------------------------------------ | --------------------- |
| Break complex objectives into multi-step plans and pick tools per step | `sequential-thinking` |
| Retrieve authoritative, up-to-date SDK documentation by library ID (first step for technical research) | `context7`            |
| Supplement official findings or perform technical/web research when `context7` lacks coverage | `exa`                 |
| `exa` unavailable or broad general web coverage required     | `duckduckgo`          |
| Explore `deepwiki` repository docs, topic structures, or export pages in bulk | `deepwiki`            |
| Retrieve HTML/Markdown/JSON from known URLs (content reading, not discovery) | `fetch`               |

## MCP Tool Details

### 1. `sequential-thinking`

**Inputs** (all map directly to tool parameters):

- `thought` *(string)*: Narrative of the current reasoning step.
- `nextThoughtNeeded` *(boolean)*: Signal whether another thought should follow immediately.
- `thoughtNumber` *(integer)*: 1-based counter for the current step.
- `totalThoughts` *(integer)*: Best-effort estimate of total steps; update it as understanding evolves.
- `isRevision` *(boolean, optional)* and `revisesThought` *(integer, optional)*: Flag and identify the earlier thought being corrected.
- `branchFromThought` *(integer, optional)* and `branchId` *(string, optional)*: Define alternative exploration paths off a specific thought.
- `needsMoreThoughts` *(boolean, optional)*: Mark that more analysis is required even after an apparent conclusion.

**Trigger Scenarios**: Use for complex planning, strategy evaluation, exploratory branching, hypothesis testing, long-running analyses where scope is unclear, or any task that benefits from filtering irrelevant information over multiple steps.

**Invocation Strategy**:

- **Initialization**: Set an initial `totalThoughts`, state the overarching objective, and immediately log `thoughtNumber=1`.
- **Progression**: Increment `thoughtNumber` sequentially, updating `totalThoughts` when scope expands, and keep `nextThoughtNeeded=true` until the reasoning can pause.
- **Branching**: Populate `branchFromThought`/`branchId` when exploring an alternative path so later steps can reference the right lineage.
- **Revision**: Use `isRevision=true` with `revisesThought` to explicitly correct or refine earlier reasoning instead of overwriting it silently.
- **Convergence**: Flip `needsMoreThoughts` to `false` only when the solution is stable; otherwise leave it `true` to prompt continued analysis.

**Skip Policy**: Invoke `sequential-thinking` by default. You may omit it only for trivial, single-step tasks such as acknowledging a greeting, formatting a short status update, or making an obvious one-line edit; whenever you skip it, explicitly state the reason in that response.

### 2. `context7`

**Tooling Capabilities**:
- **Library Resolution**: `resolve-library-id` to locate `context7`-compatible library identifiers
- **Documentation Access**: `get-library-docs` with configurable token budget and topic focus

**Trigger Scenarios**: Official SDK or API research, version-specific documentation needs, keeping references up to date

**Invocation Strategy**:
- **Library Selection**: resolve the library first and prioritize candidates with strong trust scores and snippet coverage
- **Document Retrieval**: tailor `get-library-docs` `tokens` and `topic` parameters to the relevant section
- **Prompt Crafting**: include the resolved library ID explicitly in prompts for deterministic context
- **Cross-Validation**: supplement with `exa` web search if the material may have changed recently
- **Fallback Handling**: when `resolve-library-id` fails or official documentation is missing the needed topic, switch to `exa` immediately and note the fallback in your response

### 3. `exa`

**Tooling Capabilities**:
- **Code and SDK Discovery**: `get_code_context_exa` for high-quality API snippets
- **Live Web Search**: `web_search_exa` with adjustable `numResults` and query terms

**Trigger Scenarios**: Latest technical references, third-party examples, research tasks, complex API investigations

**Invocation Strategy**:
- **Focused Technical Research**: prioritize `get_code_context_exa` and tune the token budget to the depth required
- **General Information**: run `web_search_exa` with clear keywords and bounded result counts
- **Synthesis**: capture key findings for reuse and compare them against `context7` outputs when needed
- **Load Management**: avoid redundant large queries by reusing cached insights where possible
- **Search Order**: treat `exa` as the secondary step after `context7`; if both are unavailable, proceed according to the `duckduckgo` fallback policy

### 4. `duckduckgo`

**Tooling Capabilities**:
- **Search Engine Access**: `duckduckgo_web_search` with `query`, `count`, and `safeSearch` controls

**Trigger Scenarios**: Broad web coverage, news retrieval, supplementary verification when `exa` is insufficient

**Invocation Strategy**:
- **Complementary Search**: engage when wider coverage or alternative viewpoints are required
- **Safety Controls**: choose an appropriate `safeSearch` level for the task
- **Result Triage**: evaluate snippets and sources, optionally follow up with `fetch` for full content
- **Record Keeping**: organize relevant findings promptly to minimize repeated queries
- **Usage Constraint**: run `duckduckgo` only after confirming `exa` is unavailable, and document that fallback in your notes or response

### 5. `deepwiki`

**Tooling Capabilities**:
- **Targeted Q&A**: `ask_question` for focused repository questions (`owner/repo`)
- **Content Reading**: `read_wiki_contents` for detailed wiki topics
- **Structure Overview**: `read_wiki_structure` to map wiki organization

**Trigger Scenarios**: Global understanding of open-source projects, architecture summaries, topic deep dives

**Invocation Strategy**:
- **Structural Scan**: start with `read_wiki_structure` to identify major themes
- **Focused Reading**: select key sections through `read_wiki_contents`
- **Clarifications**: resolve specific design or implementation questions via `ask_question`
- **Knowledge Integration**: combine findings `context7` outputs for comprehensive documentation
- **Fallback Handling**: when the repository lacks wiki content or responses are empty, switch to `exa` for coverage and record that fallback

### 6. `fetch`

**Tooling Capabilities**:
- **Content Fetching**: `fetch` with `url`, `max_length`, `start_index`, and `raw` options

**Trigger Scenarios**: Direct access to known URLs, markdown or HTML extraction, incremental reading of long-form pages

**Invocation Strategy**:

- **Rapid Retrieval**: call `fetch` on the target URL for an immediate markdown summary
- **Chunked Reading**: adjust `start_index` to inspect large documents sequentially
- **Raw Content**: set `raw=true` for untouched HTML and regulate output using `max_length`
- **Search Collaboration**: pair with `exa` or `duckduckgo` to collect source URLs and then capture the full text
- **Usage Constraint**: invoke `fetch` only after another tool supplies a concrete URL; do not employ it for discovery or search

## Rules for AI

- Always respond in Simplified Chinese in every user-facing message.
- Keep `sequential-thinking` enabled by default. You may skip it only for trivial single-step tasks (e.g., greetings, simple status acknowledgements, or manifest one-line edits); when you do, state the concrete reason in that turn.
- For detailed technical or API questions, query official sources via `context7` first; if `resolve-library-id` fails or the needed documentation is absent, switch to `exa` immediately and note the fallback.
- For repository-level structural questions, invoke `deepwiki` first; if it returns no data, move to `exa` for coverage and record that fallback.
- For non-technical research, use `exa`; if `exa` cannot be used, fall back to `duckduckgo` and record that fallback.
- Use `fetch` only to retrieve content from known URLs supplied by other tools; never treat it as a search mechanism.
- Surface any uncertainty immediately and request clarification whenever user instructions are ambiguous before proceeding.
- For problems caused by architectural defects, all patch-style fixes are forbidden. A refactoring plan must be proposed, justified, and explicitly approved by the user before implementation.
- If your answer relies on external materials or speculation, you must state your sources, your confidence in the information, and point out what has not yet been verified.

## Project Structure & Module Organization
- `index.php`, `archive.php`, `page.php`, `post.php`, and `404.php` implement the main template routes; mirror Typecho’s hierarchy when adding new views.
- `functions.php` defines configurable options (`themeConfig`, `themeFields`). Extend these helpers instead of introducing global state or ad-hoc settings.
- Shared layout fragments live in `header.php`, `footer.php`, `comments.php`, and `post.php`; import them where possible instead of duplicating markup.
- Assets sit in `img/`, while `style.css` holds the primary theme rules and `normalize.css` handles resets. Keep media additions under 200 kB or serve them from a CDN.

## Build, Test, and Development Commands
- This project has no build process or local development commands. All development involves directly editing the theme files, which are then pushed to a server for deployment.

## Coding Style & Naming Conventions
- **Indentation and Spacing:** Use 4-space indentation for PHP and CSS. Follow PSR-12 spacing and keep braces aligned exactly as in existing templates.
- **Naming Conventions:** Prefer `camelCase` for PHP helpers (`themeConfig`, `renderPostMeta`) and `kebab-case` for CSS class names (`post-meta`, `nav-link`).
- **Localization and CSS Variables:** Wrap user-facing strings with `_t()` to keep localization support intact, and centralize repeated CSS values (like colors or font sizes) as variables near the top of `style.css`.
- **Write Clear, Self-Documenting Code:** Strive for clarity in function and variable names. Use comments to explain complex or non-obvious logic (the "why"), not to restate what the code does (the "what"). For new PHP functions, add a brief PHPDoc block explaining its purpose, parameters, and return value.
- **Prioritize Security:** Always escape user-generated content or dynamic data before rendering it in HTML to prevent Cross-Site Scripting (XSS) attacks. Use Typecho's built-in functions or PHP's `htmlspecialchars()` where appropriate.
- **Limit Logical Complexity:** Keep functions concise and focused on a single responsibility. Avoid deeply nested conditional statements (e.g., more than three levels of `if`/`else`/`foreach`) to improve readability and maintainability.
- **Maintain Separation of Concerns:** Keep files focused on a single purpose. For instance, template files (`post.php`, `page.php`) should primarily handle presentation logic, while complex data processing or reusable utility functions should reside in `functions.php`. If a new component becomes sufficiently complex, consider extracting it into its own partial PHP file (e.g., `partials/author-bio.php`) and including it where needed.
- **Embrace Reusability (DRY Principle):** Don't Repeat Yourself. If you find yourself writing the same block of HTML or PHP logic in multiple templates (e.g., displaying post tags on both the index and post pages), abstract it into a reusable function in `functions.php` or a partial template file. Similarly, for CSS, group shared styles into reusable utility classes (e.g., `.text-center`, `.flex-container`) instead of duplicating properties across different component-specific rules.
- **Use Semantic HTML:** Employ meaningful HTML5 tags like `<main>`, `<article>`, `<nav>`, and `<aside>` instead of generic `<div>`s. This improves accessibility, SEO, and the overall structure of the document.
- **Scope CSS Class Names:** Prefix theme-specific class names (e.g., `.retype-card`, `.retype-author`) to prevent conflicts with plugins or user-generated content styles. This creates a more robust and isolated component.

## Testing Guidelines
- There is no automated suite.No local testing is required. 

## Commit & Pull Request Guidelines
- All functional and visual verification is conducted on a dedicated server after code has been pushed. As the AI assistant, your responsibility is to write and modify the code according to the guidelines; you are not required to perform any testing or verification steps.

## Configuration Tips
- Store API keys or CDN URLs in Typecho’s theme settings rather than hardcoding them. Retrieve values through the custom fields already defined in `functions.php`.
- When introducing new fonts or scripts, prefer async CDN links and document fallbacks at the top of `style.css`.
