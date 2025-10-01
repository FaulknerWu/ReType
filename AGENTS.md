# Repository Guidelines

Always respond in Chinese-simplified.

## Project Structure & Module Organization
- Root PHP templates (`index.php`, `post.php`, `page.php`, `archive.php`) define the Typecho theme layouts and rely on Bootstrap markup.
- `functions.php` configures logo, sidebar blocks, and the LaTeX toggle; extend it for any new custom fields or helper widgets.
- Static assets live in `style.css`, `normalize.css`, and `img/`; keep marketplace imagery in `screenshot.png` and prefer a future `partials/` folder for shared snippets.

## Build, Test, and Development Commands
- Theme changes load directly in Typecho, so no build step is required.
- Run `php -l *.php` or `find . -name "*.php" -print0 | xargs -0 php -l` before commits to catch syntax errors.
- Preview CSS updates in a local Typecho instance and package releases with `zip -r ReType.zip . -x "*.git*"`.

## Coding Style & Naming Conventions
- Follow PSR-12: 4-space indentation, namespace declarations first, and guard clauses via early returns.
- Keep template variables in lowerCamelCase and reuse flags like `isLatex`; wrap user-visible strings with `_t()` for localization.
- Prefix custom CSS classes with `retype-` and reference theme files via `$this->options->themeUrl()` for portability.

## Testing Guidelines
- Validate templates using a sample Typecho site with posts, comments, archives, and LaTeX content enabled.
- Check responsive behavior on mobile, tablet, and desktop, plus simplified Chinese and RTL layouts when relevant.
- Exercise each sidebar checkbox combination and document new manual test cases in this guide if automation is absent.

## Commit & Pull Request Guidelines
- Use conventional commits (`feat:`, `fix:`, `style:`, `chore:`) with concise imperative summaries referencing touched templates when useful.
- Provide pull request descriptions detailing user-facing impact, manual test steps, and any CDN or configuration adjustments.
- Attach screenshots or GIFs for visual tweaks and link related Typecho issues or discussions when available.
