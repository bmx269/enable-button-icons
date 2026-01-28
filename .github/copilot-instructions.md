# GitHub Copilot Instructions for WordPress 6+ Standards

## Project Overview
This Figma plugin exports WordPress theme.json files and block patterns following WordPress 6+ standards. All code generation must adhere to official WordPress documentation and best practices.

## WordPress PHP Development Standards

### Expert Guidelines
You are an expert in WordPress, PHP, and related web development technologies.

### Key Principles
- Write concise, technical responses with accurate PHP examples
- Follow WordPress coding standards and best practices
- Use object-oriented programming when appropriate, focusing on modularity
- Prefer iteration and modularization over duplication
- Use descriptive function, variable, and file names
- Use lowercase with hyphens for directories (e.g., wp-content/themes/my-theme)
- Favor hooks (actions and filters) for extending functionality

### PHP/WordPress Standards
- Use PHP 7.4+ features when appropriate (e.g., typed properties, arrow functions)
- Follow WordPress PHP Coding Standards
- Use strict typing when possible: `declare(strict_types=1);`
- Utilize WordPress core functions and APIs when available
- File structure: Follow WordPress theme and plugin directory structures and naming conventions
- Implement proper error handling and logging:
  - Use WordPress debug logging features
  - Create custom error handlers when necessary
  - Use try-catch blocks for expected exceptions
- Use WordPress's built-in functions for data validation and sanitization
- Implement proper nonce verification for form submissions
- Utilize WordPress's database abstraction layer (wpdb) for database interactions
- Use prepare() statements for secure database queries
- Implement proper database schema changes using dbDelta() function

### Dependencies
- WordPress (latest stable version)
- Composer for dependency management (when building advanced plugins or themes)

### WordPress Best Practices
- Use WordPress hooks (actions and filters) instead of modifying core files
- Implement proper theme functions using functions.php
- Use WordPress's built-in user roles and capabilities system
- Utilize WordPress's transients API for caching
- Implement background processing for long-running tasks using wp_cron()
- Use WordPress's built-in testing tools (WP_UnitTestCase) for unit tests
- Implement proper internationalization and localization using WordPress i18n functions
- Implement proper security measures (nonces, data escaping, input sanitization)
- Use wp_enqueue_script() and wp_enqueue_style() for proper asset management
- Implement custom post types and taxonomies when appropriate
- Use WordPress's built-in options API for storing configuration data
- Implement proper pagination using functions like paginate_links()

### Key Conventions
1. Follow WordPress's plugin API for extending functionality
2. Use WordPress's template hierarchy for theme development
3. Implement proper data sanitization and validation using WordPress functions
4. Use WordPress's template tags and conditional tags in themes
5. Implement proper database queries using $wpdb or WP_Query
6. Use WordPress's authentication and authorization functions
7. Implement proper AJAX handling using admin-ajax.php or REST API
8. Use WordPress's hook system for modular and extensible code
9. Implement proper database operations using WordPress transactional functions
10. Use WordPress's WP_Cron API for scheduling tasks

---

## Testing & Quality Assurance

### Security Testing Requirements

#### Input Validation & Sanitization
- **Always sanitize user input**: Use `sanitize_text_field()`, `sanitize_email()`, `sanitize_url()`, etc.
- **Escape output**: Use `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`, `wp_kses_post()`
- **SQL injection prevention**: Use `$wpdb->prepare()` for all database queries with user input
- **XSS prevention**: Never output unsanitized user data; use appropriate escaping functions
- **Test with special characters**: Input strings like `<script>alert('XSS')</script>`, `'; DROP TABLE--`, `../../../etc/passwd`

#### Authentication & Authorization
- **Verify nonces**: Use `wp_verify_nonce()` and `check_ajax_referer()` for all form submissions
- **Check capabilities**: Use `current_user_can()` before allowing privileged operations
- **Test permission bypassing**: Attempt actions as logged-out user, subscriber, editor, admin
- **Validate user ownership**: Ensure users can only modify their own content

#### File & Upload Security
- **Validate file types**: Check both extension and MIME type
- **Limit file sizes**: Set maximum upload sizes
- **Store uploads securely**: Use WordPress upload directory structure
- **Prevent directory traversal**: Validate file paths don't contain `../`
- **Test malicious uploads**: Try uploading .php files disguised as images

### Code Standards Validation

#### PHP Code Standards (PHPCS)
```bash
# Install WordPress Coding Standards
composer require --dev wp-coding-standards/wpcs

# Configure PHPCS
./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs

# Run standards check
./vendor/bin/phpcs --standard=WordPress path/to/your/files

# Auto-fix issues
./vendor/bin/phpcbf --standard=WordPress path/to/your/files
```

#### JavaScript/CSS Standards (ESLint/Stylelint)
```bash
# Run WordPress ESLint
npm run lint:js

# Run Stylelint
npm run lint:css

# Auto-fix issues
npm run lint:js -- --fix
npm run lint:css -- --fix
```

### Performance Testing

#### Frontend Performance
- **Measure page load time**: Use Chrome DevTools Performance tab
- **Check asset sizes**: Images should be optimized, CSS/JS minified
- **Test caching**: Verify browser caching headers are set
- **Lazy load images**: Implement lazy loading for below-fold images
- **Test on slow connections**: Use Chrome DevTools network throttling

#### Database Performance
- **Profile slow queries**: Use Query Monitor plugin
- **Check query counts**: Aim for <10 queries per page load
- **Test with large datasets**: Create 1000+ posts/users and test performance
- **Verify indexes**: Ensure custom tables have proper indexes
- **Use object caching**: Test with Redis/Memcached when available

#### PHP Performance
```bash
# Profile with Xdebug
php -d xdebug.mode=profile script.php

# Check memory usage
memory_get_peak_usage(true)

# Measure execution time
$start = microtime(true);
// code to profile
echo 'Execution time: ' . (microtime(true) - $start);
```

### Accessibility Testing (WCAG 2.1 AA)

#### Automated Testing
```bash
# Install pa11y
npm install -g pa11y

# Test accessibility
pa11y http://localhost:8000

# Test color contrast
pa11y --runner axe http://localhost:8000
```

#### Manual Testing
- **Keyboard navigation**: Tab through all interactive elements
- **Screen reader testing**: Use NVDA (Windows) or VoiceOver (Mac)
- **Color contrast**: Use WebAIM Contrast Checker (min 4.5:1 for normal text)
- **Focus indicators**: Visible focus states on all interactive elements
- **ARIA labels**: Proper labels for icon buttons, form fields
- **Semantic HTML**: Use proper heading hierarchy, landmarks

### Browser & Device Testing

#### Browser Compatibility
- Test on latest versions of:
  - Chrome/Edge (Chromium)
  - Firefox
  - Safari (macOS and iOS)
  - Mobile browsers (Chrome Android, Safari iOS)

#### Responsive Testing
```bash
# Test various viewport sizes
- Mobile: 375px, 414px
- Tablet: 768px, 1024px
- Desktop: 1280px, 1920px
```

#### Tools
- BrowserStack or LambdaTest for cross-browser testing
- Chrome DevTools Device Mode
- Real device testing when possible

### WordPress-Specific Testing

#### Block Editor Testing
- **Test in Gutenberg**: Verify blocks work in block editor
- **Test patterns**: Ensure patterns insert correctly
- **Test block variations**: All variations render properly
- **Test in different themes**: Verify compatibility with popular themes
- **Test with Full Site Editing**: Verify compatibility with block themes

#### Plugin Compatibility
- Test with common plugins:
  - Yoast SEO
  - WooCommerce
  - Contact Form 7
  - Jetpack
  - Wordfence

#### Multisite Testing
- Test in multisite environment
- Verify network activation works
- Test site-specific vs network-wide settings

### Automated Testing

#### Unit Tests (PHPUnit)
```php
<?php
/**
 * Test class for button icon functionality
 */
class Test_Button_Icons extends WP_UnitTestCase {

	public function test_icon_attributes_registered() {
		$block = WP_Block_Type_Registry::get_instance()->get_registered( 'core/button' );
		$this->assertArrayHasKey( 'icon', $block->attributes );
	}

	public function test_icon_sanitization() {
		$dirty_icon = '<script>alert("xss")</script><svg>icon</svg>';
		$clean_icon = sanitize_icon( $dirty_icon );
		$this->assertStringNotContainsString( '<script>', $clean_icon );
	}
}
```

#### Integration Tests
```bash
# Install WP-CLI test suite
wp scaffold plugin-tests my-plugin

# Run tests
cd /tmp/wordpress-tests-lib
./bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Execute tests
vendor/bin/phpunit
```

#### End-to-End Tests (Playwright/Cypress)
```javascript
test('Button with icon renders correctly', async ({ page }) => {
	await page.goto('http://localhost:8000');
	await page.click('[aria-label="Add block"]');
	await page.click('button:has-text("Button")');
	await page.click('[aria-label="Icon settings"]');
	await page.click('[aria-label="Select arrow-right icon"]');

	// Verify icon appears
	const icon = await page.locator('.wp-block-button__link-icon svg');
	await expect(icon).toBeVisible();
});
```

### Pre-Commit Checklist

Before committing code, verify:
- [ ] All security functions (`esc_*`, `sanitize_*`, `wp_verify_nonce`) are used appropriately
- [ ] No `$_GET`, `$_POST`, `$_REQUEST` used directly without sanitization
- [ ] All database queries use `$wpdb->prepare()`
- [ ] No `eval()`, `create_function()`, or similar dangerous functions
- [ ] PHPCS shows no errors for WordPress standards
- [ ] ESLint shows no errors
- [ ] All user-facing strings are internationalized with `__()`, `_e()`, etc.
- [ ] PHPDoc blocks present for all functions with `@since` tags
- [ ] Manual testing completed in multiple browsers
- [ ] Accessibility tested with keyboard and screen reader
- [ ] Performance profiled (no N+1 queries, assets optimized)

### Deployment Testing

#### Pre-Production Testing
- Test on staging environment identical to production
- Run full test suite
- Performance test with production-like data volume
- Security scan with Wordfence or Sucuri

#### Production Monitoring
- Monitor error logs for PHP warnings/errors
- Track performance metrics (Time to First Byte, page load times)
- Monitor database query performance
- Set up uptime monitoring
- Enable WordPress debug log on staging (not production)

---

## Quick Reference Links
- [WordPress Pattern Registration](https://developer.wordpress.org/themes/patterns/registering-patterns/)
- [Theme.json Documentation](https://developer.wordpress.org/themes/advanced-topics/theme-json/)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Security Handbook](https://developer.wordpress.org/apis/security/)
- [WordPress Performance](https://developer.wordpress.org/advanced-administration/performance/optimization/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)