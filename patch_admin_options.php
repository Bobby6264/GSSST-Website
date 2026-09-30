<?php
$content = file_get_contents('/home/bobby6264/Desktop/Bobby/CIC/wp-content/themes/cic-theme/inc/admin/admin-options.php');

$content = str_replace(
    "__('News & Ticker Settings', 'cic-theme'),\n        __('News & Ticker', 'cic-theme'),",
    "__('News & Events Settings', 'cic-theme'),\n        __('News & Events', 'cic-theme'),",
    $content
);

$content = str_replace(
    "// Top Level Menu: Header & Footer (as requested, separate common page)\n    add_menu_page(\n        __('Header & Footer Settings', 'cic-theme'),\n        __('Header & Footer', 'cic-theme'),\n        \$capability,\n        'cic-header-footer',\n        'cic_render_header_footer_page',\n        'dashicons-layout',\n        26\n    );",
    "// Submenu: Header & Footer\n    add_submenu_page(\n        'cic-landing-hero',\n        __('Header & Footer Settings', 'cic-theme'),\n        __('Header & Footer', 'cic-theme'),\n        \$capability,\n        'cic-header-footer',\n        'cic_render_header_footer_page'\n    );\n\n    // Submenu: Faculty Page\n    add_submenu_page(\n        'cic-landing-hero',\n        __('Faculty Page Settings', 'cic-theme'),\n        __('Faculty Page', 'cic-theme'),\n        \$capability,\n        'cic-faculty-page',\n        'cic_render_faculty_page_settings'\n    );",
    $content
);

$content = str_replace(
    "\$allowed_pages = array(\n        'toplevel_page_cic-landing-hero',\n        'landing-page_page_cic-landing-news',\n        'landing-page_page_cic-landing-about-head',\n        'landing-page_page_cic-landing-dept',\n        'landing-page_page_cic-landing-academics',\n        'landing-page_page_cic-landing-gallery',\n        'toplevel_page_cic-header-footer'\n    );\n\n    if (!in_array(\$hook, \$allowed_pages, true)) {\n        return;\n    }",
    "if (strpos(\$hook, 'cic-') === false) {\n        return;\n    }",
    $content
);

file_put_contents('/home/bobby6264/Desktop/Bobby/CIC/wp-content/themes/cic-theme/inc/admin/admin-options.php', $content);
