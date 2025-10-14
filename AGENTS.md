# Repository Guidelines

This repository contains the ReType theme for Typecho blogs. Keep changes scoped, reversible, and documented so other contributors can evaluate them quickly.

Always respond in Chinese-simplified.

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
- There is no automated suite; manually verify list, single post, archive, and 404 views after template updates.
- Toggle each theme option in the Typecho admin panel to confirm `themeConfig` defaults, and validate LaTeX rendering when `isLatex` is enabled.
- Check browser dev tools for PHP warnings, JavaScript errors, and mixed-content requests. Test at least one mobile viewport for layout regressions.

## Commit & Pull Request Guidelines
- No local testing is required. All functional and visual verification is conducted on a dedicated server after code has been pushed. As the AI assistant, your responsibility is to write and modify the code according to the guidelines; you are not required to perform any testing or verification steps.

## Configuration Tips
- Store API keys or CDN URLs in Typecho’s theme settings rather than hardcoding them. Retrieve values through the custom fields already defined in `functions.php`.
- When introducing new fonts or scripts, prefer async CDN links and document fallbacks at the top of `style.css`.
