<?php
/**
 * Bridge Demo Inventory Seed
 *
 * Populates a fresh Bridge install with a set of realistic demo units
 * so the inventory grid, category tree, filters, and detail pages all
 * demo well out of the box.
 *
 * Runs once on activation, gated by the `bridge_demo_seeded` option.
 * Also exposes a manual re-seed via ?bridge_reseed_demo=1 (admin only)
 * and a WP-CLI command `wp bridge seed`.
 *
 * Photos: drop JPGs into bridge-plugin/assets/demo/units/ named to
 * match the unit slugs below (e.g. 2024-kubota-l3901.jpg). Any that
 * exist get sideloaded to the media library and attached; missing
 * ones are skipped silently so the seed still runs without photos.
 */

defined('ABSPATH') || exit;

// ─── Activation Hook ─────────────────────────────────────────────────────────

register_activation_hook(
    plugin_dir_path(__FILE__) . 'bridge-plugin.php',
    'bridge_maybe_seed_demo_inventory'
);

function bridge_maybe_seed_demo_inventory(): void {
    if (get_option('bridge_demo_seeded')) {
        return;
    }
    bridge_seed_demo_inventory();
    update_option('bridge_demo_seeded', current_time('mysql'));
}

// ─── Manual Re-Seed (admin only) ─────────────────────────────────────────────

add_action('admin_init', function (): void {
    if (empty($_GET['bridge_reseed_demo']) || !current_user_can('manage_options')) {
        return;
    }
    check_admin_referer('bridge_reseed_demo');
    bridge_wipe_demo_inventory();
    bridge_seed_demo_inventory();
    update_option('bridge_demo_seeded', current_time('mysql'));
    wp_safe_redirect(admin_url('edit.php?post_type=equipment&bridge_reseeded=1'));
    exit;
});

// ─── WP-CLI ──────────────────────────────────────────────────────────────────

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('bridge seed', function ($args, $assoc): void {
        if (!empty($assoc['fresh'])) {
            bridge_wipe_demo_inventory();
            WP_CLI::log('Wiped existing demo units.');
        }
        $count = bridge_seed_demo_inventory();
        update_option('bridge_demo_seeded', current_time('mysql'));
        WP_CLI::success("Seeded {$count} demo units.");
    });
}

// ─── The Seed Data ───────────────────────────────────────────────────────────

function bridge_demo_units(): array {
    return array(
        array(
            'slug'        => '2024-kubota-l3901',
            'year'        => '2024',
            'make'        => 'Kubota',
            'model'       => 'L3901',
            'stock'       => 'BR-2026-001',
            'price'       => 28500,
            'condition'   => 'New',
            'category'    => 'Tractors',
            'subcategory' => 'Compact (Under 40 HP)',
            'color'       => 'Orange',
            'meter'       => '2',
            'meter_type'  => 'Hours',
            'featured'    => true,
            'description' => 'Compact 37 HP diesel tractor with 4WD, hydrostatic transmission, and factory loader. Ideal for property maintenance, small-acreage haying, and light utility work.',
        ),
        array(
            'slug'        => '2023-john-deere-5075e',
            'year'        => '2023',
            'make'        => 'John Deere',
            'model'       => '5075E',
            'stock'       => 'BR-2026-002',
            'price'       => 42000,
            'condition'   => 'Used',
            'category'    => 'Tractors',
            'subcategory' => 'Utility (40-99 HP)',
            'color'       => 'Green',
            'meter'       => '412',
            'meter_type'  => 'Hours',
            'featured'    => false,
            'description' => 'Well-maintained 75 HP utility tractor with 9F/3R transmission, 540 PTO, and two rear remotes. Recent service, ready to work.',
        ),
        array(
            'slug'        => '2022-new-holland-t7-230',
            'year'        => '2022',
            'make'        => 'New Holland',
            'model'       => 'T7.230',
            'stock'       => 'BR-2026-003',
            'price'       => 0,
            'call_price'  => true,
            'condition'   => 'Used',
            'category'    => 'Tractors',
            'subcategory' => 'Row Crop (100-174 HP)',
            'color'       => 'Blue',
            'meter'       => '1840',
            'meter_type'  => 'Hours',
            'featured'    => true,
            'description' => 'Row crop tractor with CVT transmission, front axle suspension, and full auto-guidance ready. Cab is clean, tires strong.',
        ),
        array(
            'slug'        => '2024-vermeer-604r',
            'year'        => '2024',
            'make'        => 'Vermeer',
            'model'       => '604R Signature',
            'stock'       => 'BR-2026-004',
            'price'       => 52000,
            'condition'   => 'New',
            'category'    => 'Hay & Forage',
            'subcategory' => 'Round Balers',
            'color'       => 'Yellow',
            'featured'    => false,
            'description' => 'Round baler producing 5x6 bales. Auto-lube system, ramp door, moisture sensor, and net wrap. Backed by full warranty.',
        ),
        array(
            'slug'        => '2023-krone-am283',
            'year'        => '2023',
            'make'        => 'Krone',
            'model'       => 'AM 283 S',
            'stock'       => 'BR-2026-005',
            'price'       => 18500,
            'condition'   => 'Used',
            'category'    => 'Hay & Forage',
            'subcategory' => 'Disc Mowers',
            'length'      => '9 ft 2 in',
            'featured'    => false,
            'description' => 'Trailed disc mower with 9 ft 2 in cutting width. Low-hour, sharp knives, ready for the season.',
        ),
        array(
            'slug'        => '2024-jd-2360',
            'year'        => '2024',
            'make'        => 'John Deere',
            'model'       => '2360',
            'stock'       => 'BR-2026-006',
            'price'       => 32000,
            'condition'   => 'New',
            'category'    => 'Tillage',
            'subcategory' => 'Chisel Plows',
            'length'      => '15 ft',
            'featured'    => false,
            'description' => '15 ft chisel plow with adjustable depth, hydraulic wing lift, and heavy-duty shanks. Built for tough Texas soils.',
        ),
        array(
            'slug'        => '2024-big-tex-14gx',
            'year'        => '2024',
            'make'        => 'Big Tex',
            'model'       => '14GX-20',
            'stock'       => 'BR-2026-007',
            'price'       => 8250,
            'condition'   => 'New',
            'category'    => 'Trailers',
            'subcategory' => 'Equipment Hauler',
            'length'      => '20 ft',
            'color'       => 'Black',
            'featured'    => true,
            'description' => '20 ft gooseneck equipment trailer, 14K GVWR, dovetail with mega ramps, LED lights, spare tire mount.',
        ),
        array(
            'slug'        => '2024-pj-dump-83',
            'year'        => '2024',
            'make'        => 'PJ Trailers',
            'model'       => 'DL-14',
            'stock'       => 'BR-2026-008',
            'price'       => 12900,
            'condition'   => 'New',
            'category'    => 'Trailers',
            'subcategory' => 'Dump',
            'length'      => '14 ft',
            'color'       => 'Gray',
            'featured'    => false,
            'description' => '14 ft low-pro dump trailer, 14K GVWR, tarp kit, spare tire, combo gate. Bumper pull.',
        ),
        array(
            'slug'        => '2023-load-trail-utility',
            'year'        => '2023',
            'make'        => 'Load Trail',
            'model'       => 'SE-83',
            'stock'       => 'BR-2026-009',
            'price'       => 3200,
            'condition'   => 'Used',
            'category'    => 'Trailers',
            'subcategory' => 'Utility',
            'length'      => '16 ft',
            'color'       => 'Black',
            'featured'    => false,
            'description' => '16 ft single-axle utility trailer, ramp gate, wood deck in good shape. Clean title.',
        ),
        array(
            'slug'        => '2024-kubota-rtv-x1140',
            'year'        => '2024',
            'make'        => 'Kubota',
            'model'       => 'RTV-X1140',
            'stock'       => 'BR-2026-010',
            'price'       => 22400,
            'condition'   => 'New',
            'category'    => 'Utility Vehicles',
            'subcategory' => 'Side by Side',
            'color'       => 'Orange',
            'featured'    => true,
            'description' => '4-passenger diesel utility vehicle with hydraulic dump bed, 4WD, and rear differential lock. Work-ready.',
        ),
        array(
            'slug'        => '2022-polaris-ranger-1000',
            'year'        => '2022',
            'make'        => 'Polaris',
            'model'       => 'Ranger XP 1000',
            'stock'       => 'BR-2026-011',
            'price'       => 15800,
            'condition'   => 'Used',
            'category'    => 'Utility Vehicles',
            'subcategory' => 'Side by Side',
            'color'       => 'Green',
            'meter'       => '860',
            'meter_type'  => 'Hours',
            'featured'    => false,
            'description' => 'Full-size 3-seat side-by-side, roof and windshield installed, winch. One-owner, well maintained.',
        ),
        array(
            'slug'        => '2020-ford-f350',
            'year'        => '2020',
            'make'        => 'Ford',
            'model'       => 'F-350 Lariat',
            'stock'       => 'BR-2026-012',
            'price'       => 54900,
            'condition'   => 'Used',
            'category'    => 'Trucks',
            'subcategory' => 'One Ton',
            'color'       => 'White',
            'meter'       => '68000',
            'meter_type'  => 'Miles',
            'featured'    => false,
            'description' => '6.7L Powerstroke diesel, 4x4, crew cab, long bed, gooseneck prep. Serviced, clean carfax.',
        ),
        array(
            'slug'        => '2024-jd-x350',
            'year'        => '2024',
            'make'        => 'John Deere',
            'model'       => 'X350',
            'stock'       => 'BR-2026-013',
            'price'       => 4899,
            'condition'   => 'New',
            'category'    => 'Lawn & Turf',
            'subcategory' => 'Riding Mowers',
            'color'       => 'Green',
            'length'      => '42 in deck',
            'featured'    => false,
            'description' => 'Lawn tractor with 42 in Accel Deep deck, hydrostatic transmission, and 4-year bumper-to-bumper warranty.',
        ),
        array(
            'slug'        => '2024-generic-loader-bucket',
            'year'        => '2024',
            'make'        => 'Everything Attachments',
            'model'       => 'HD Grapple 72',
            'stock'       => 'BR-2026-014',
            'price'       => 2850,
            'condition'   => 'New',
            'category'    => 'Implements & Attachments',
            'subcategory' => 'Buckets and Grapples',
            'length'      => '72 in',
            'featured'    => false,
            'description' => '72 in root grapple bucket, skid-steer quick-attach, dual cylinders. Fits most compact tractor loaders with SSQA.',
        ),
    );
}

// ─── The Seeder ──────────────────────────────────────────────────────────────

function bridge_seed_demo_inventory(): int {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $units    = bridge_demo_units();
    $created  = 0;
    $photodir = plugin_dir_path(__FILE__) . 'assets/demo/units/';

    foreach ($units as $u) {
        // Guard against duplicates on re-run
        $existing = get_posts(array(
            'post_type'   => 'equipment',
            'post_status' => 'any',
            'meta_key'    => 'stock_number',
            'meta_value'  => $u['stock'],
            'numberposts' => 1,
            'fields'      => 'ids',
        ));
        if (!empty($existing)) {
            continue;
        }

        $title   = trim("{$u['year']} {$u['make']} {$u['model']}");
        $post_id = wp_insert_post(array(
            'post_type'   => 'equipment',
            'post_status' => 'publish',
            'post_title'  => $title,
            'post_name'   => $u['slug'],
        ), true);

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        // Core fields via ACF (matches bridge_save_unit_fields path)
        $has_acf = function_exists('update_field');
        $fields  = array(
            'year'            => $u['year'],
            'make'            => $u['make'],
            'model'           => $u['model'],
            'stock_number'    => $u['stock'],
            'price'           => (float) $u['price'],
            'call_for_price'  => !empty($u['call_price']),
            'condition'       => $u['condition'],
            'stock_status'    => 'In Stock',
            'category'        => $u['category'],
            'color'           => $u['color'] ?? '',
            'length'          => $u['length'] ?? '',
            'meter'           => $u['meter'] ?? '',
            'meter_type'      => $u['meter_type'] ?? 'Hours',
            'description'     => $u['description'],
            'featured'        => !empty($u['featured']),
            'show_on_website' => true,
            'facebook_sync'   => false,
            'intake_date'     => current_time('Y-m-d'),
        );

        foreach ($fields as $key => $value) {
            if ($has_acf) {
                update_field($key, $value, $post_id);
            } else {
                update_post_meta($post_id, $key, $value);
            }
        }

        // Subcategory is a serialized array per bridge_save_unit_fields
        update_post_meta($post_id, 'subcategory', array($u['subcategory']));

        // Photo, if a matching file exists
        $photo_path = $photodir . $u['slug'] . '.jpg';
        if (file_exists($photo_path)) {
            $attach_id = bridge_sideload_local_image($photo_path, $post_id, $title);
            if ($attach_id) {
                if ($has_acf) {
                    update_field('gallery', array($attach_id), $post_id);
                } else {
                    update_post_meta($post_id, 'gallery', array($attach_id));
                }
                set_post_thumbnail($post_id, $attach_id);
            }
        }

        // Tag this post as demo-seeded so wipe can find it
        update_post_meta($post_id, '_bridge_demo_seed', 1);

        $created++;
    }

    // Rebuild the Meta catalog CSV if the function is available
    if (function_exists('bridge_schedule_catalog_regeneration')) {
        bridge_schedule_catalog_regeneration();
    }

    return $created;
}

/**
 * Copy a local file into the media library and return the attachment ID.
 * Uses wp_insert_attachment + wp_generate_attachment_metadata rather than
 * media_sideload_image (which expects a URL, not a local path).
 */
function bridge_sideload_local_image(string $path, int $parent_id, string $title): ?int {
    if (!file_exists($path)) {
        return null;
    }

    $upload = wp_upload_dir();
    if (!empty($upload['error'])) {
        return null;
    }

    $filename = wp_unique_filename($upload['path'], basename($path));
    $dest     = trailingslashit($upload['path']) . $filename;

    if (!@copy($path, $dest)) {
        return null;
    }

    $filetype = wp_check_filetype($filename, null);
    $attach   = array(
        'guid'           => trailingslashit($upload['url']) . $filename,
        'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
        'post_title'     => $title,
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $attach_id = wp_insert_attachment($attach, $dest, $parent_id);
    if (is_wp_error($attach_id) || !$attach_id) {
        return null;
    }

    $meta = wp_generate_attachment_metadata($attach_id, $dest);
    wp_update_attachment_metadata($attach_id, $meta);
    update_post_meta($attach_id, '_bridge_demo_seed', 1);

    return (int) $attach_id;
}

/**
 * Remove every unit and attachment tagged with _bridge_demo_seed.
 * Safe: it never touches user-created content.
 */
function bridge_wipe_demo_inventory(): void {
    $ids = get_posts(array(
        'post_type'   => array('equipment', 'attachment'),
        'post_status' => 'any',
        'meta_key'    => '_bridge_demo_seed',
        'meta_value'  => 1,
        'numberposts' => -1,
        'fields'      => 'ids',
    ));
    foreach ($ids as $id) {
        wp_delete_post($id, true);
    }
}

// ─── Admin notice after re-seed ──────────────────────────────────────────────

add_action('admin_notices', function (): void {
    if (empty($_GET['bridge_reseeded'])) {
        return;
    }
    echo '<div class="notice notice-success is-dismissible"><p>Bridge demo inventory re-seeded.</p></div>';
});
