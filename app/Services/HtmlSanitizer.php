<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Allowed HTML tags for table_body_html content.
     */
    protected static array $allowedTags = [
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
        'colgroup', 'col', 'caption',
        'span', 'div', 'p', 'br', 'hr',
        'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup', 'small',
        'ul', 'ol', 'li',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'a', 'img',
        'input', 'select', 'option', 'textarea', 'button', 'label',
    ];

    /**
     * Allowed HTML attributes (global + tag-specific).
     */
    protected static array $allowedAttributes = [
        'class', 'id', 'style', 'title', 'colspan', 'rowspan', 'scope',
        'width', 'height', 'align', 'valign', 'border', 'cellpadding', 'cellspacing',
        'src', 'alt', 'href', 'target', 'rel',
        'type', 'name', 'value', 'placeholder', 'checked', 'disabled', 'readonly',
        'selected', 'multiple', 'for', 'data-*', 'contenteditable',
        'min', 'max', 'step', 'maxlength', 'minlength', 'pattern',
        'rows', 'cols', 'wrap',
    ];

    /**
     * Dangerous attribute prefixes (event handlers).
     */
    protected static array $dangerousAttributePrefixes = [
        'on', // covers onclick, onerror, onload, onmouseover, etc.
    ];

    /**
     * Sanitize HTML content by removing dangerous elements and attributes.
     */
    public static function sanitize(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        // First pass: strip_tags with allowed list
        $allowedTagString = '<' . implode('><', self::$allowedTags) . '>';
        $html = strip_tags($html, $allowedTagString);

        // Second pass: use DOMDocument to remove dangerous attributes
        $html = self::removeDangerousAttributes($html);

        return $html;
    }

    /**
     * Remove dangerous attributes from HTML elements using DOMDocument.
     */
    protected static function removeDangerousAttributes(string $html): string
    {
        if (empty(trim($html))) {
            return $html;
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);

        // Wrap in a root element to handle fragments
        $wrapped = '<div id="__sanitizer_root__">' . $html . '</div>';
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $wrapped,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $elements = $xpath->query('//*');

        foreach ($elements as $element) {
            if (!$element instanceof \DOMElement) {
                continue;
            }

            // Collect attributes to remove (can't modify during iteration)
            $attributesToRemove = [];

            foreach ($element->attributes as $attr) {
                $attrName = strtolower($attr->name);
                $attrValue = strtolower(trim($attr->value));

                // Remove all event handlers (on*)
                if (str_starts_with($attrName, 'on')) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }

                // Remove javascript: protocol in href, src, action, etc.
                if (in_array($attrName, ['href', 'src', 'action', 'formaction', 'xlink:href'])) {
                    $cleanValue = preg_replace('/\s+/', '', $attrValue);
                    if (preg_match('/^(javascript|vbscript|data\s*:(?!image\/))/i', $cleanValue)) {
                        $attributesToRemove[] = $attr->name;
                        continue;
                    }
                }

                // Remove style attributes containing dangerous expressions
                if ($attrName === 'style') {
                    $cleanStyle = preg_replace('/\s+/', '', $attrValue);
                    if (preg_match('/(expression|javascript|vbscript|url\s*\()/i', $cleanStyle)) {
                        $attributesToRemove[] = $attr->name;
                        continue;
                    }
                }
            }

            foreach ($attributesToRemove as $attrName) {
                $element->removeAttribute($attrName);
            }
        }

        // Extract the inner HTML of our wrapper div
        $root = $dom->getElementById('__sanitizer_root__');
        if (!$root) {
            return $html; // fallback to original if parsing failed
        }

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return $result;
    }
}
