# Palm Test Task

**Contributors:** malak4web  
**Tags:** discussions, community, ai, summary, gemini  
**Requires at least:** 5.0  
**Tested up to:** 6.8
**Requires PHP:** 7.4  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

A WordPress plugin that creates a custom post type for community discussions and provides AI-powered content summarization using Google's Gemini API.

## 🚀 Features

- **Custom Post Type**: Creates "Community Discussions" post type for organizing community content
- **AI Summarization**: Automatically generates summaries of discussion content using Google Gemini 2.0 Flash
- **Meta Box Integration**: Adds a summary meta box to discussion posts with one-click summary generation
- **AJAX-powered**: Smooth user experience with asynchronous summary generation
- **Enhanced Security**: Comprehensive security measures including nonce verification, capability checks

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Google Gemini API key
- jQuery (included with WordPress)

## 🔧 Installation

1. **Download the plugin** to your WordPress plugins directory:
   ```
   wp-content/plugins/palm_test_task/
   ```

2. **Activate the plugin** through the WordPress admin panel:
   - Go to Plugins > Installed Plugins
   - Find "Palm Test Task" and click "Activate"

3. **Configure API Key**:
   - The plugin includes a hardcoded Gemini API key in `palm_test_task.php`
   - For production use, consider moving this to WordPress options or environment variables

## 🎯 Usage

### Creating Community Discussions

1. Navigate to **Community Discussions** in your WordPress admin menu
2. Click **Add New** to create a new discussion
3. Write your content in the main editor
4. Use the **Summary** meta box to generate AI-powered summaries

### Generating Summaries

1. In the Summary meta box, click the **"Generate Summary"** button
2. The plugin will send your content to Google Gemini API
3. A concise summary will be generated and displayed below the button
4. The summary is automatically saved as post meta

## 🏗️ Project Structure

```
palm_test_task/
├── palm_test_task.php      # Main plugin file with CPT registration
├── meta.php                # Meta box functionality and display
├── summary_provider.php    # AI summary generation logic
├── script.js              # Frontend JavaScript for AJAX calls
└── README.md              # This documentation file
```

## 🔌 Technical Details

### Custom Post Type
- **Slug**: `discussions`
- **Public**: Yes
- **Archive**: Enabled
- **Rewrite**: `/discussions/`

### API Integration
- **Service**: Google Gemini 2.0 Flash
- **Endpoint**: `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent`
- **Authentication**: API key in `X-goog-api-key` header

### AJAX Actions
- **Action**: `wp_ajax_generate_summary`
- **Data**: `post_id` (POST parameter)
- **Response**: JSON success/error with appropriate messages

## 🛠️ Customization

### Modifying the Summary Prompt
Edit the prompt in `summary_provider.php` line 42:
```php
'text' => "Please generate a summary of the following content: " . $content
```

### Changing Post Type Labels
Modify the labels array in `palm_test_task.php` lines 18-22:
```php
$labels = array(
    'name' => 'Community Discussions',
    'singular_name' => 'Community Discussion',
    'menu_name' => 'Community Discussions',
);
```

### Updating API Key
Change the API key in `palm_test_task.php` line 20:
```php
define('GEMINI_API_KEY', 'your-new-api-key-here');
```

## 🔒 Security Considerations

- The plugin uses WordPress nonces for meta box security
- Input validation is implemented for post IDs
- API responses are sanitized before display
- User capability checks are in place for summary generation

## 📝 Changelog

### Version 1.0.0
- Initial release
- Custom post type creation
- AI summary generation
- Meta box integration

## 👨‍💻 Author

**Malak Younan**
- LinkedIn: [Malak4web](https://www.linkedin.com/in/malaak4web/)
- GitHub: [Malak4web](https://github.com/Malak4web/)
- Upwork: [Malak Younan](https://www.upwork.com/freelancers/~016902c320cece15fa)

### Previous WordPress Plugins
I have developed several WordPress plugins available on the official repository:
- [MK Posts Slider](https://wordpress.org/plugins/mk-posts-slider/) - A responsive posts slider plugin
- [PDF Flip Book by Kenrys](https://wordpress.org/plugins/pdf-flip-book-by-kenrys/) - Interactive PDF flip book viewer

**Note**: I have more plugins and projects available on my Upwork profile. Feel free to check out my portfolio for additional work examples.
