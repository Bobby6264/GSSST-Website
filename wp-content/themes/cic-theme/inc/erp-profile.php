<?php
/** IIT Kharagpur public faculty profile extraction. */
if (!defined('ABSPATH')) exit;

function cic_erp_text($node) {
    return trim(preg_replace('/\s+/u', ' ', $node->textContent));
}

function cic_erp_section_items($node) {
    $items = array();
    $xpath = new DOMXPath($node->ownerDocument);
    foreach ($xpath->query('.//li', $node) as $li) {
        $value = cic_erp_text($li);
        if ($value && strcasecmp($value, 'No Record Found.') !== 0) $items[] = $value;
    }
    if (!$items) {
        foreach ($xpath->query('.//p', $node) as $item) {
            $value = cic_erp_text($item);
            if ($value && strlen($value) < 1200 && strcasecmp($value, 'No Record Found.') !== 0) $items[] = $value;
        }
    }
    return array_values(array_unique($items));
}

function cic_erp_extract_section($xpath, $labels) {
    foreach ($xpath->query('//*[self::h2 or self::h3 or self::h4 or self::button or self::a or @role="tab"]') as $label_node) {
        $label = strtolower(cic_erp_text($label_node));
        if (!in_array($label, $labels, true)) continue;

        // Bootstrap and similar tab systems point to a panel by ID.
        $target = $label_node->getAttribute('data-bs-target') ?: $label_node->getAttribute('data-target');
        if (!$target) $target = $label_node->getAttribute('href');
        if (!$target) $target = $label_node->getAttribute('aria-controls');
        if ($target && $target[0] === '#') $target = substr($target, 1);
        if ($target && preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $target)) {
            $panel = $xpath->query('//*[@id="' . $target . '"]')->item(0);
            if ($panel) return $panel;
        }

        // For headings, collect the section up to the next heading.
        if (in_array(strtolower($label_node->nodeName), array('h2', 'h3', 'h4'), true)) {
            $fragment = $label_node->ownerDocument->createElement('div');
            for ($next = $label_node->nextSibling; $next; $next = $next->nextSibling) {
                if ($next instanceof DOMElement && preg_match('/^h[2-4]$/i', $next->nodeName)) break;
                $fragment->appendChild($next->cloneNode(true));
            }
            if (cic_erp_text($fragment)) {
                $label_node->ownerDocument->documentElement->appendChild($fragment);
                return $fragment;
            }
        }
    }
    return null;
}

function cic_erp_parse_profile($html) {
    if (!class_exists('DOMDocument')) return new WP_Error('dom_missing', 'The server needs the PHP DOM extension to read this profile.');
    $dom = new DOMDocument();
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $xpath = new DOMXPath($dom);

    $areas_node = cic_erp_extract_section($xpath, array('research areas'));
    $statement_node = cic_erp_extract_section($xpath, array('research statement'));
    $publications_node = cic_erp_extract_section($xpath, array('selected publications'));
    $projects_node = cic_erp_extract_section($xpath, array('current projects'));

    $areas_list = $areas_node ? $xpath->query('.//ul|.//ol', $areas_node)->item(0) : null;
    $areas = $areas_list ? cic_erp_section_items($areas_list) : ($areas_node ? cic_erp_section_items($areas_node) : array());
    $statement = $statement_node ? cic_erp_text($statement_node) : '';
    $publications = $publications_node ? cic_erp_section_items($publications_node) : array();
    $projects = $projects_node ? cic_erp_section_items($projects_node) : array();

    // Empty lists on the institute page mean there are no listed records.
    if ($statement && strcasecmp($statement, 'No Record Found.') === 0) $statement = '';
    return array(
        'research_areas' => array_slice($areas, 0, 30),
        'research_statement' => mb_substr($statement, 0, 10000),
        'selected_publications' => array_slice($publications, 0, 50),
        'current_projects' => array_slice($projects, 0, 50),
        '_found' => array(
            'research_areas' => (bool) $areas_node,
            'research_statement' => (bool) $statement_node,
            'selected_publications' => (bool) $publications_node,
            'current_projects' => (bool) $projects_node,
        ),
    );
}
