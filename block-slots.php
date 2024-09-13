<?php

/**
 * Plugin Name:       Block Slots
 * Description:       A WordPress Gutenberg plugin to output blocks from a CPT "Block Slots".
 * Version:           1.0.0
 * Author:            elf02
 * Author URI:        https://elf02.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       elf02-block-slots
 */

namespace elf02\BlockSlots;

if (!defined('ABSPATH')) {
    exit;
}


function editor_assets()
{
    $asset_meta = include plugin_dir_path(__FILE__) . 'build/js/editor.asset.php';

    wp_enqueue_script(
        'elf02-block-slots-editor',
        plugin_dir_url(__FILE__) . '/build/js/editor.js',
        $asset_meta['dependencies'],
        $asset_meta['version']
    );
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\editor_assets');


function register_cpt()
{
    $labels = [
        'name'               => __('Block Slots', 'elf02-block-slots'),
        'singular_name'      => __('Block Slot', 'elf02-block-slots'),
        'add_new'            => __('Add New', 'elf02-block-slots'),
        'add_new_item'       => __('Add New Block Slot', 'elf02-block-slots'),
        'edit_item'          => __('Edit Block Slot', 'elf02-block-slots'),
        'new_item'           => __('New Block Slot', 'elf02-block-slots'),
        'view_item'          => __('View Block Slot', 'elf02-block-slots'),
        'search_items'       => __('Search Block Slots', 'elf02-block-slots'),
        'not_found'          => __('No Block Slots found', 'elf02-block-slots'),
        'not_found_in_trash' => __('No Block Slots found in Trash', 'elf02-block-slots'),
        'parent_item_colon'  => __('Parent Block Slot:', 'elf02-block-slots'),
        'menu_name'          => __('Block Slots', 'elf02-block-slots'),
    ];

    $args = [
        'labels'              => $labels,
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'revisions'],
        'public'              => false,
        'publicly_queryable'  => is_admin(),
        'show_ui'             => true,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-layout',
        'show_in_menu'        => 'themes.php',
    ];

    register_post_type('block_slot', $args);
}
add_action('init', __NAMESPACE__ . '\register_cpt');


function frontend_query_vars($pre_render, $parsed_block)
{
    if (
        isset($parsed_block['attrs']['namespace']) &&
        $parsed_block['attrs']['namespace'] === 'elf02/block-slots-query-loop'
    ) {
        add_filter(
            'query_loop_block_query_vars',
            function ($query, $block) {
                if (
                    isset(
                        $block->context['query']['postType'],
                        $block->context['query']['include'][0]
                    ) &&
                    $block->context['query']['postType'] === 'block_slot'
                ) {

                    return [
                        'post_type' => 'block_slot',
                        'p' => intval($block->context['query']['include'][0])
                    ];
                }

                return $query;
            },
            10,
            2
        );
    }

    return $pre_render;
}
add_filter('pre_render_block', __NAMESPACE__ . '\frontend_query_vars', 10, 2);
