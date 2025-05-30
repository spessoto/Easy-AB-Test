<?php
/**
 * Plugin Name: Simple AB Test Redirect
 * Description: Plugin avançado e seguro para múltiplos testes A/B com URLs de gatilho, redirecionamento, logs detalhados, rastreamento de conversões, relatórios gráficos, notificações e auditoria.
 * Version: 3.3.2
 * Author: Caio Spessoto
 * Text Domain: simple-ab-test-redirect
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('SABTR_PLUGIN_FILE', __FILE__);

/* -----------------------------
 * 0. Load Text Domain for i18n
 * ----------------------------- */
add_action('plugins_loaded', 'sabtr_load_textdomain');
function sabtr_load_textdomain() {
    load_plugin_textdomain('simple-ab-test-redirect', false, dirname(plugin_basename(SABTR_PLUGIN_FILE)) . '/languages/');
}

/* -----------------------------
 * 1. Custom Post Type: ab_test
 * ----------------------------- */
add_action('init', 'sabtr_register_cpt');
function sabtr_register_cpt() {
    $labels = array(
        'name'               => _x( 'Testes A/B', 'post type general name', 'simple-ab-test-redirect' ),
        'singular_name'      => _x( 'Teste A/B', 'post type singular name', 'simple-ab-test-redirect' ),
        'menu_name'          => _x( 'Testes A/B', 'admin menu', 'simple-ab-test-redirect' ),
        'name_admin_bar'     => _x( 'Teste A/B', 'add new on admin bar', 'simple-ab-test-redirect' ),
        'add_new'            => _x( 'Adicionar Novo', 'teste a/b', 'simple-ab-test-redirect' ),
        'add_new_item'       => __( 'Adicionar Novo Teste A/B', 'simple-ab-test-redirect' ),
        'new_item'           => __( 'Novo Teste A/B', 'simple-ab-test-redirect' ),
        'edit_item'          => __( 'Editar Teste A/B', 'simple-ab-test-redirect' ),
        'view_item'          => __( 'Ver Teste A/B', 'simple-ab-test-redirect' ),
        'all_items'          => __( 'Todos os Testes A/B', 'simple-ab-test-redirect' ),
        'search_items'       => __( 'Procurar Testes A/B', 'simple-ab-test-redirect' ),
        'parent_item_colon'  => __( 'Testes A/B Pai:', 'simple-ab-test-redirect' ),
        'not_found'          => __( 'Nenhum teste A/B encontrado.', 'simple-ab-test-redirect' ),
        'not_found_in_trash' => __( 'Nenhum teste A/B encontrado na lixeira.', 'simple-ab-test-redirect' )
    );

    register_post_type('ab_test', array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-randomize',
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'rewrite'            => false,
    ));
}

/* -----------------------------
 * 2. Metabox para URLs, Proporção e Conversão
 * ----------------------------- */
add_action('add_meta_boxes', 'sabtr_add_meta_box');
function sabtr_add_meta_box() {
    add_meta_box(
        'sabtr_meta_box',
        __('Configurações do Teste A/B', 'simple-ab-test-redirect'),
        'sabtr_meta_box_callback',
        'ab_test',
        'normal',
        'high'
    );
}

function sabtr_meta_box_callback($post) {
    wp_nonce_field('sabtr_save_meta', 'sabtr_meta_nonce');

    $trigger_url = get_post_meta($post->ID, '_sabtr_trigger_url', true);
    $url_a = get_post_meta($post->ID, '_sabtr_url_a', true);
    $url_b = get_post_meta($post->ID, '_sabtr_url_b', true);
    $percentage_b = intval(get_post_meta($post->ID, '_sabtr_percentage_b', true) ?: 50);
    $conversion_url = get_post_meta($post->ID, '_sabtr_conversion_url', true);

    ?>
    <p>
        <label for="sabtr_trigger_url"><strong><?php _e('Página de Gatilho (URL Completa):', 'simple-ab-test-redirect'); ?></strong></label><br>
        <input type="url" id="sabtr_trigger_url" name="sabtr_trigger_url" value="<?php echo esc_url($trigger_url); ?>" size="70" required>
        <small><?php _e('A URL onde o teste A/B será ativado. Ex: https://seudominio.com/pagina-alvo/', 'simple-ab-test-redirect'); ?></small>
    </p>
    <p>
        <label for="sabtr_url_a"><strong><?php _e('URL da Variante A (Controle - Completa):', 'simple-ab-test-redirect'); ?></strong></label><br>
        <input type="url" id="sabtr_url_a" name="sabtr_url_a" value="<?php echo esc_url($url_a); ?>" size="70">
        <small><?php _e('Deixe em branco para usar a "Página de Gatilho" como Variante A.', 'simple-ab-test-redirect'); ?></small>
    </p>
    <p>
        <label for="sabtr_url_b"><strong><?php _e('URL da Variante B (Variação - Completa):', 'simple-ab-test-redirect'); ?></strong></label><br>
        <input type="url" id="sabtr_url_b" name="sabtr_url_b" value="<?php echo esc_url($url_b); ?>" size="70" required>
        <small><?php _e('A URL alternativa para o teste.', 'simple-ab-test-redirect'); ?></small>
    </p>
    <p>
        <label for="sabtr_percentage_b"><strong><?php _e('Proporção de tráfego para Variante B (%):', 'simple-ab-test-redirect'); ?></strong></label><br>
        <input type="number" id="sabtr_percentage_b" name="sabtr_percentage_b" value="<?php echo esc_attr($percentage_b); ?>" min="0" max="100" required>
        <small><?php _e('Ex: 50 para dividir o tráfego 50/50. O restante irá para a Variante A.', 'simple-ab-test-redirect'); ?></small>
    </p>
    <hr>
    <p>
        <label for="sabtr_conversion_url"><strong><?php _e('URL de Conversão (Opcional - Completa):', 'simple-ab-test-redirect'); ?></strong></label><br>
        <input type="url" id="sabtr_conversion_url" name="sabtr_conversion_url" value="<?php echo esc_url($conversion_url); ?>" size="70">
        <small><?php _e('A URL que, ao ser visitada, registra uma conversão para a variante que o usuário viu. Ex: página de agradecimento.', 'simple-ab-test-redirect'); ?></small>
    </p>
    <?php
}

add_action('save_post_ab_test', 'sabtr_save_meta');
function sabtr_save_meta($post_id) {
    if ( ! isset($_POST['sabtr_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sabtr_meta_nonce'])), 'sabtr_save_meta') ) {
        return;
    }
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $url_fields = ['sabtr_trigger_url', 'sabtr_url_a', 'sabtr_url_b', 'sabtr_conversion_url'];
    foreach ($url_fields as $field_key_suffix) {
        if (isset($_POST[$field_key_suffix])) {
            update_post_meta($post_id, '_' . $field_key_suffix, esc_url_raw(trim(wp_unslash($_POST[$field_key_suffix]))));
        }
    }

    if (isset($_POST['sabtr_percentage_b'])) {
        update_post_meta($post_id, '_sabtr_percentage_b', intval($_POST['sabtr_percentage_b']));
    }
}

/* -----------------------------
 * 3. Tabelas de Log (Acesso e Conversão)
 * ----------------------------- */
register_activation_hook(SABTR_PLUGIN_FILE, 'sabtr_create_plugin_tables');
function sabtr_create_plugin_tables() {
    sabtr_create_access_log_table();
    sabtr_create_conversion_log_table();
}

function sabtr_create_access_log_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ab_test_logs';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        test_id BIGINT UNSIGNED NOT NULL,
        variant CHAR(1) NOT NULL,
        ip_address VARCHAR(100) NOT NULL,
        user_agent TEXT NOT NULL,
        date_accessed DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        is_correction BOOLEAN DEFAULT FALSE,
        KEY idx_test_id (test_id),
        KEY idx_variant (variant),
        KEY idx_date_accessed (date_accessed)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function sabtr_create_conversion_log_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ab_test_conversions';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        test_id BIGINT UNSIGNED NOT NULL,
        variant CHAR(1) NOT NULL,
        ip_address VARCHAR(100) NOT NULL,
        user_agent TEXT NOT NULL,
        date_converted DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        KEY idx_test_id (test_id),
        KEY idx_variant (variant),
        KEY idx_date_converted (date_converted)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


/* -----------------------------
 * 4. Redirecionamento, Logging de Acesso e Rastreamento de Conversão
 * ----------------------------- */

/**
 * Gets the current URL, optionally stripping the query string, and normalizes it.
 *
 * @param bool $include_query_string Whether to include the query string. Default false.
 * @return string The normalized current URL.
 */
function sabtr_get_current_url_normalized($include_query_string = false) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';

    if (!$include_query_string) {
        $request_uri_parts = explode('?', $request_uri, 2);
        $request_uri = $request_uri_parts[0];
    }

    $full_url = $protocol . $host . $request_uri;
    return trailingslashit(esc_url_raw(trim($full_url)));
}

/**
 * Normalizes a URL from post meta for comparison.
 *
 * @param string $url_from_meta The URL from post meta.
 * @param bool $include_query_string Whether to include the query string. Default false.
 * @return string The normalized URL.
 */
function sabtr_normalize_meta_url($url_from_meta, $include_query_string = false) {
    if (empty($url_from_meta)) {
        return '';
    }
    $url_to_normalize = trim($url_from_meta);

    if (!$include_query_string) {
        $url_parts = explode('?', $url_to_normalize, 2);
        $url_to_normalize = $url_parts[0];
    }
    return trailingslashit(esc_url_raw($url_to_normalize));
}


function sabtr_get_visitor_ip() {
    $ip_address = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR'])));
        $ip_address = trim($ips[0]);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip_address = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }
    return filter_var($ip_address, FILTER_VALIDATE_IP) ? $ip_address : '0.0.0.0';
}


add_action('template_redirect', 'sabtr_handle_redirects_and_conversions', 1);
function sabtr_handle_redirects_and_conversions() {
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX) || (defined('DOING_CRON') && DOING_CRON)) {
        return;
    }

    $tests = get_posts(array(
        'post_type' => 'ab_test',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'suppress_filters' => true
    ));

    if (empty($tests)) {
        return;
    }

    // Determine if query strings should be considered for matching.
    // For most trigger/conversion pages, path is key. Query strings can vary (e.g. marketing tags).
    // Set to true if your tests specifically rely on query string variations.
    $match_query_string = apply_filters('sabtr_match_query_string_for_tests', false);

    $normalized_current_url = sabtr_get_current_url_normalized($match_query_string);
    $redirect_performed = false;

    // For debugging (remove in production)
    // error_log('[SABTR DEBUG] Current Normalized URL (' . ($match_query_string ? 'with QS' : 'no QS') . '): ' . $normalized_current_url);

    foreach ($tests as $test_post) {
        if ($redirect_performed) break;

        $test_id = $test_post->ID;
        $trigger_url_meta = get_post_meta($test_id, '_sabtr_trigger_url', true);
        $url_a_config_meta = get_post_meta($test_id, '_sabtr_url_a', true);
        $url_b_config_meta = get_post_meta($test_id, '_sabtr_url_b', true);

        $normalized_trigger_url = sabtr_normalize_meta_url($trigger_url_meta, $match_query_string);
        $normalized_url_a = sabtr_normalize_meta_url($url_a_config_meta, $match_query_string);
        $normalized_url_b = sabtr_normalize_meta_url($url_b_config_meta, $match_query_string);

        if (empty($normalized_url_a)) {
            $normalized_url_a = $normalized_trigger_url;
        }

        $percentage_b = intval(get_post_meta($test_id, '_sabtr_percentage_b', true) ?: 50);

        if (empty($normalized_trigger_url) || empty($normalized_url_b)) {
            // error_log("[SABTR DEBUG] Test ID $test_id skipped: Missing trigger or URL B. Trigger: '$normalized_trigger_url', URL B: '$normalized_url_b'");
            continue;
        }

        // For debugging (remove in production)
        // error_log("[SABTR DEBUG] Test ID: $test_id | Trigger: '$normalized_trigger_url' | A: '$normalized_url_a' | B: '$normalized_url_b'");

        $cookie_key_variant = 'sabtr_variant_' . $test_id;
        $is_correction = false;

        if ($normalized_current_url === $normalized_trigger_url) {
            // error_log("[SABTR DEBUG] Test ID $test_id: Current URL MATCHES Trigger URL.");
            $variant_chosen = '';
            if (isset($_COOKIE[$cookie_key_variant]) && in_array($_COOKIE[$cookie_key_variant], ['A', 'B'], true)) {
                $variant_chosen = sanitize_text_field(wp_unslash($_COOKIE[$cookie_key_variant]));
                // error_log("[SABTR DEBUG] Test ID $test_id: Found existing cookie, variant: $variant_chosen");
            } else {
                $random_number = mt_rand(1, 100);
                $variant_chosen = ($random_number <= $percentage_b) ? 'B' : 'A';
                $cookie_expiration = time() + (int) apply_filters('sabtr_cookie_expiration_seconds', 30 * DAY_IN_SECONDS);
                setcookie($cookie_key_variant, $variant_chosen, $cookie_expiration, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
                // error_log("[SABTR DEBUG] Test ID $test_id: New visitor, chose variant: $variant_chosen, cookie set.");
            }
            sabtr_log_access($test_id, $variant_chosen, $is_correction);

            if ($variant_chosen === 'B' && $normalized_current_url !== $normalized_url_b) {
                // error_log("[SABTR DEBUG] Test ID $test_id: Redirecting to B: $normalized_url_b");
                wp_safe_redirect($normalized_url_b, 302, 'WordPress SABTR Plugin');
                $redirect_performed = true; exit;
            } elseif ($variant_chosen === 'A' && $normalized_current_url !== $normalized_url_a) {
                if ($normalized_url_a !== $normalized_trigger_url) { // Only redirect if variant A is different from trigger
                    // error_log("[SABTR DEBUG] Test ID $test_id: Redirecting to A: $normalized_url_a");
                    wp_safe_redirect($normalized_url_a, 302, 'WordPress SABTR Plugin');
                    $redirect_performed = true; exit;
                }
            }
            break;
        } elseif (isset($_COOKIE[$cookie_key_variant]) && in_array($_COOKIE[$cookie_key_variant], ['A', 'B'], true)) {
            $persisted_variant = sanitize_text_field(wp_unslash($_COOKIE[$cookie_key_variant]));
            if ($persisted_variant === 'B' && $normalized_current_url !== $normalized_url_b) {
                if ($normalized_current_url === $normalized_url_a || ($normalized_current_url === $normalized_trigger_url && $normalized_trigger_url !== $normalized_url_b)) {
                    $is_correction = true; sabtr_log_access($test_id, $persisted_variant, $is_correction);
                    // error_log("[SABTR DEBUG] Test ID $test_id: Correcting to B: $normalized_url_b");
                    wp_safe_redirect($normalized_url_b, 302, 'WordPress SABTR Plugin');
                    $redirect_performed = true; exit;
                }
            } elseif ($persisted_variant === 'A' && $normalized_current_url !== $normalized_url_a) {
                 if ($normalized_current_url === $normalized_url_b || ($normalized_current_url === $normalized_trigger_url && $normalized_trigger_url !== $normalized_url_a)) {
                    $is_correction = true; sabtr_log_access($test_id, $persisted_variant, $is_correction);
                    // error_log("[SABTR DEBUG] Test ID $test_id: Correcting to A: $normalized_url_a");
                    wp_safe_redirect($normalized_url_a, 302, 'WordPress SABTR Plugin');
                    $redirect_performed = true; exit;
                }
            }
        }
    }

    if (!$redirect_performed) {
        foreach ($tests as $test_post) {
            $test_id = $test_post->ID;
            $conversion_url_meta = get_post_meta($test_id, '_sabtr_conversion_url', true);
            $normalized_conversion_url = sabtr_normalize_meta_url($conversion_url_meta, $match_query_string);

            if (empty($normalized_conversion_url)) continue;

            // error_log("[SABTR DEBUG] Test ID $test_id: Checking conversion. Current: '$normalized_current_url', Conversion Target: '$normalized_conversion_url'");

            $cookie_key_variant = 'sabtr_variant_' . $test_id;
            $cookie_key_converted = 'sabtr_converted_' . $test_id;

            if ($normalized_current_url === $normalized_conversion_url) {
                // error_log("[SABTR DEBUG] Test ID $test_id: Current URL MATCHES Conversion URL.");
                if (isset($_COOKIE[$cookie_key_variant]) && in_array($_COOKIE[$cookie_key_variant], ['A', 'B'], true)) {
                    // error_log("[SABTR DEBUG] Test ID $test_id: Variant cookie found: ".$_COOKIE[$cookie_key_variant]);
                    if (!isset($_COOKIE[$cookie_key_converted])) {
                        $variant_shown = sanitize_text_field(wp_unslash($_COOKIE[$cookie_key_variant]));
                        sabtr_log_conversion($test_id, $variant_shown);
                        // error_log("[SABTR DEBUG] Test ID $test_id: Logged conversion for variant $variant_shown. Setting converted cookie.");
                        $conversion_cookie_expiration = time() + YEAR_IN_SECONDS;
                        setcookie($cookie_key_converted, $variant_shown, $conversion_cookie_expiration, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
                        break;
                    } else {
                        // error_log("[SABTR DEBUG] Test ID $test_id: Conversion already logged (cookie found).");
                    }
                } else {
                    // error_log("[SABTR DEBUG] Test ID $test_id: Variant cookie not found, cannot log conversion.");
                }
            }
        }
    }
}

function sabtr_log_access($test_id, $variant, $is_correction = false) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ab_test_logs';
    $ip_address = sabtr_get_visitor_ip();
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_textarea_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

    $inserted = $wpdb->insert($table_name, array(
        'test_id'       => intval($test_id),
        'variant'       => sanitize_text_field($variant),
        'ip_address'    => $ip_address,
        'user_agent'    => $user_agent,
        'date_accessed' => current_time('mysql'),
        'is_correction' => (bool) $is_correction,
    ));
    // if ($inserted) { error_log("[SABTR LOG] Access logged for Test ID $test_id, Variant $variant."); }
    // else { error_log("[SABTR ERROR] Failed to log access for Test ID $test_id. DB Error: " . $wpdb->last_error); }
    sabtr_notify_admin_log($test_id, $variant, $ip_address, $user_agent, 'access');
}

function sabtr_log_conversion($test_id, $variant) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ab_test_conversions';
    $ip_address = sabtr_get_visitor_ip();
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_textarea_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

    $inserted = $wpdb->insert($table_name, array(
        'test_id'        => intval($test_id),
        'variant'        => sanitize_text_field($variant),
        'ip_address'     => $ip_address,
        'user_agent'     => $user_agent,
        'date_converted' => current_time('mysql'),
    ));
    // if ($inserted) { error_log("[SABTR LOG] Conversion logged for Test ID $test_id, Variant $variant."); }
    // else { error_log("[SABTR ERROR] Failed to log conversion for Test ID $test_id. DB Error: " . $wpdb->last_error); }

    if (get_option('sabtr_enable_email_notification', true)) {
        sabtr_notify_admin_log($test_id, $variant, $ip_address, $user_agent, 'conversion');
    }
}

/* -----------------------------
 * 5. Notificação por e-mail com limite
 * ----------------------------- */
function sabtr_already_notified_today($test_id, $ip_address, $type = 'access') {
    $transient_key = 'sabtr_notified_' . $type . '_' . md5($test_id . $ip_address . date('Y-m-d'));
    return (bool) get_transient($transient_key);
}

function sabtr_set_notified_today($test_id, $ip_address, $type = 'access') {
    $transient_key = 'sabtr_notified_' . $type . '_' . md5($test_id . $ip_address . date('Y-m-d'));
    set_transient($transient_key, true, DAY_IN_SECONDS);
}

function sabtr_notify_admin_log($test_id, $variant, $ip_address, $user_agent, $type = 'access') {
    if ( ! get_option('sabtr_enable_email_notification', true) ) {
        return;
    }
    if ( sabtr_already_notified_today($test_id, $ip_address, $type) ) {
        return;
    }

    $admin_email = get_option('admin_email');
    $test_title = get_the_title($test_id);
    $subject_prefix = ($type === 'conversion') ? __('Nova Conversão no Teste A/B:', 'simple-ab-test-redirect') : __('Novo acesso ao Teste A/B:', 'simple-ab-test-redirect');
    $subject = sprintf('%s %s (ID: %d)', $subject_prefix, $test_title, $test_id);

    $message  = sprintf(__('Teste A/B: %s (ID: %d)', 'simple-ab-test-redirect'), esc_html($test_title), $test_id) . "
";
    $message .= sprintf(__('Variante Designada/Convertida: %s', 'simple-ab-test-redirect'), esc_html($variant)) . "
";
    $message .= sprintf(__('Endereço IP: %s', 'simple-ab-test-redirect'), esc_html($ip_address)) . "
";
    $message .= sprintf(__('User Agent: %s', 'simple-ab-test-redirect'), esc_html($user_agent)) . "
";
    $log_date_label = ($type === 'conversion') ? __('Data/Hora da Conversão:', 'simple-ab-test-redirect') : __('Data/Hora do Acesso:', 'simple-ab-test-redirect');
    $message .= sprintf('%s %s', $log_date_label, current_time('mysql')) . "
";

    wp_mail($admin_email, $subject, $message);
    sabtr_set_notified_today($test_id, $ip_address, $type);
}

/* -----------------------------
 * 6. Logs de auditoria (ações no admin)
 * ----------------------------- */
function sabtr_log_audit_action($message) {
    if ( ! get_option('sabtr_enable_audit_log', true) ) return;
    $upload_dir = wp_upload_dir();
    $log_dir_path = trailingslashit($upload_dir['basedir']) . 'sabtr-audit-logs/';
    if (!file_exists($log_dir_path)) wp_mkdir_p($log_dir_path);
    $log_file = $log_dir_path . 'sabtr_audit_' . date('Y-m-d') . '.log';
    $timestamp = current_time('mysql');
    $formatted_message = sprintf("[%s] %s
", $timestamp, $message);
    if (function_exists('error_log')) error_log($formatted_message, 3, $log_file);
    else file_put_contents($log_file, $formatted_message, FILE_APPEND | LOCK_EX);
}

add_action('save_post_ab_test', function($post_ID, $post, $update) {
    if (wp_is_post_revision($post_ID) || wp_is_post_autosave($post_ID)) return;
    if ( ! current_user_can('edit_post', $post_ID) ) return;
    $action = $update ? __('Atualizado', 'simple-ab-test-redirect') : __('Criado', 'simple-ab-test-redirect');
    $user = wp_get_current_user();
    $message = sprintf(
        __('Teste A/B %s - ID: %d ("%s") por %s (%s)', 'simple-ab-test-redirect'),
        $action, $post_ID, $post->post_title, $user->display_name, $user->user_email
    );
    sabtr_log_audit_action($message);
}, 10, 3);

add_action('before_delete_post', function($post_ID, $post) {
    if ('ab_test' !== $post->post_type) return;
    if ( ! current_user_can('delete_post', $post_ID) ) return;
    $user = wp_get_current_user();
    $message = sprintf(
        __('Deletado Teste A/B - ID: %d ("%s") por %s (%s)', 'simple-ab-test-redirect'),
        $post_ID, $post->post_title, $user->display_name, $user->user_email
    );
    sabtr_log_audit_action($message);
}, 10, 2);


/* -----------------------------
 * 7. Configurações do Plugin (Admin Page)
 * ----------------------------- */
add_action('admin_menu', 'sabtr_add_settings_page');
function sabtr_add_settings_page() {
    add_options_page(
        __('Configurações Simple AB Test', 'simple-ab-test-redirect'),
        __('AB Test Config', 'simple-ab-test-redirect'),
        'manage_options', 'sabtr_settings', 'sabtr_render_settings_page'
    );
}

add_action('admin_init', 'sabtr_register_settings');
function sabtr_register_settings() {
    register_setting('sabtr_settings_group', 'sabtr_enable_email_notification', ['type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean', 'default' => true]);
    register_setting('sabtr_settings_group', 'sabtr_enable_audit_log', ['type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean', 'default' => true]);
    register_setting('sabtr_settings_group', 'sabtr_db_log_cleanup_days', ['type' => 'integer', 'sanitize_callback' => 'absint', 'default' => 60]);
}

function sabtr_render_settings_page() {
    if ( ! current_user_can('manage_options') ) wp_die(esc_html__('Você não tem permissão para acessar esta página.', 'simple-ab-test-redirect'));

    if (isset($_GET['sabtr_notice']) && $_GET['sabtr_notice'] === 'plugin_reset_success') {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Transients do plugin foram limpos. Cookies de rastreamento do plugin foram removidos do seu navegador.', 'simple-ab-test-redirect') . '</p></div>';
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const cookies = document.cookie.split(';');
                for (let i = 0; i < cookies.length; i++) {
                    let cookie = cookies[i];
                    let eqPos = cookie.indexOf('=');
                    let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                    name = name.trim();
                    if (name.startsWith('sabtr_variant_') || name.startsWith('sabtr_converted_')) {
                        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;SameSite=Lax';
                    }
                }
                setTimeout(function() {
                    if (window.history.replaceState) {
                        const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=sabtr_settings';
                        window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
                    }
                }, 200);
            });
        </script>
        <?php
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('sabtr_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Ativar notificações por e-mail para acessos/conversões?', 'simple-ab-test-redirect'); ?></th>
                    <td><input type="checkbox" name="sabtr_enable_email_notification" value="1" <?php checked(1, get_option('sabtr_enable_email_notification', true)); ?> />
                        <p class="description"><?php _e('Envia um e-mail ao administrador para cada novo acesso ou conversão (limitado por IP/dia/tipo).', 'simple-ab-test-redirect'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Ativar logs de auditoria de administração?', 'simple-ab-test-redirect'); ?></th>
                    <td><input type="checkbox" name="sabtr_enable_audit_log" value="1" <?php checked(1, get_option('sabtr_enable_audit_log', true)); ?> />
                         <p class="description"><?php _e('Registra ações como criação, atualização e exclusão de testes A/B.', 'simple-ab-test-redirect'); ?></p>
                    </td>
                </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Manter logs de acesso e conversão no banco de dados por (dias):', 'simple-ab-test-redirect'); ?></th>
                    <td><input type="number" name="sabtr_db_log_cleanup_days" value="<?php echo esc_attr(get_option('sabtr_db_log_cleanup_days', 60)); ?>" min="7" max="365" />
                         <p class="description"><?php _e('Logs mais antigos que este período serão excluídos automaticamente. (Mín: 7, Máx: 365)', 'simple-ab-test-redirect'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Salvar Configurações', 'simple-ab-test-redirect')); ?>
        </form>
        <hr>
        <h2><?php _e('Gerenciamento de Logs de Auditoria (Arquivos)', 'simple-ab-test-redirect'); ?></h2>
        <p>
            <?php
            $download_log_url = wp_nonce_url(admin_url('admin-post.php?action=sabtr_download_audit_log'), 'sabtr_download_audit_log_action', 'sabtr_nonce_dl_audit');
            $clear_log_url = wp_nonce_url(admin_url('admin-post.php?action=sabtr_clear_audit_log'), 'sabtr_clear_audit_log_action', 'sabtr_nonce_clear_audit');
            ?>
            <a href="<?php echo esc_url($download_log_url); ?>" class="button"><?php _e('Baixar Arquivo de Log de Auditoria (Hoje)', 'simple-ab-test-redirect'); ?></a>
            <a href="<?php echo esc_url($clear_log_url); ?>" class="button" onclick="return confirm('<?php echo esc_js(__('Tem certeza que deseja limpar TODOS os arquivos de log de auditoria? Esta ação não pode ser desfeita.', 'simple-ab-test-redirect')); ?>');"><?php _e('Limpar Todos os Logs de Auditoria', 'simple-ab-test-redirect'); ?></a>
        </p>
         <p class="description">
            <?php
            $upload_dir = wp_upload_dir();
            $log_dir_path = trailingslashit($upload_dir['basedir']) . 'sabtr-audit-logs/';
            printf( esc_html__('Os arquivos de log de auditoria são armazenados em: %s. A limpeza automática remove arquivos de log de auditoria com mais de 30 dias.', 'simple-ab-test-redirect'), '<code>' . esc_html($log_dir_path) . '</code>' );
            ?>
        </p>
        <hr>
        <h2><?php _e('Redefinir Dados do Plugin (Para Testes)', 'simple-ab-test-redirect'); ?></h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="sabtr_reset_plugin_data">
            <?php wp_nonce_field('sabtr_reset_action_nonce', 'sabtr_reset_nonce_field'); ?>
            <p class="description"><?php _e('Esta ação limpará os cookies de rastreamento de variantes e conversões do seu navegador e os transients de notificação do WordPress. Útil para simular um novo visitante. Isso NÃO excluirá seus testes A/B criados, logs de acesso, logs de conversão ou logs de auditoria.', 'simple-ab-test-redirect'); ?></p>
            <?php submit_button(__('Limpar Cookies de Rastreamento e Transients', 'simple-ab-test-redirect'), 'delete', 'sabtr_reset_submit', false, ['onclick' => 'return confirm("' . esc_js(__('Tem certeza que deseja limpar os cookies de rastreamento do plugin do seu navegador e os transients de notificação? Seus dados de teste (logs, etc.) não serão afetados.', 'simple-ab-test-redirect')) . '");']); ?>
        </form>
    </div>
    <?php
}

/* -----------------------------
 * 8. Admin Post Handlers (Download/Clear Audit Log, Reset Plugin)
 * ----------------------------- */
add_action('admin_post_sabtr_download_audit_log', function() {
    if ( ! isset($_GET['sabtr_nonce_dl_audit']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['sabtr_nonce_dl_audit'])), 'sabtr_download_audit_log_action') ) {
        wp_die(esc_html__('Falha na verificação de segurança (nonce).', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    }
    if ( ! current_user_can('manage_options') ) wp_die(esc_html__('Acesso negado.', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    $upload_dir = wp_upload_dir();
    $log_dir_path = trailingslashit($upload_dir['basedir']) . 'sabtr-audit-logs/';
    $log_file = $log_dir_path . 'sabtr_audit_' . date('Y-m-d') . '.log';
    if (!file_exists($log_file)) wp_die(esc_html__('Arquivo de log de auditoria para hoje não encontrado.', 'simple-ab-test-redirect'));
    header('Content-Description: File Transfer'); header('Content-Type: text/plain'); header('Content-Disposition: attachment; filename="sabtr_audit_' . date('Y-m-d') . '.log"');
    header('Expires: 0'); header('Cache-Control: must-revalidate'); header('Pragma: public'); header('Content-Length: ' . filesize($log_file));
    readfile($log_file); exit;
});

add_action('admin_post_sabtr_clear_audit_log', function() {
    if ( ! isset($_GET['sabtr_nonce_clear_audit']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['sabtr_nonce_clear_audit'])), 'sabtr_clear_audit_log_action') ) {
         wp_die(esc_html__('Falha na verificação de segurança (nonce).', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    }
    if ( ! current_user_can('manage_options') ) wp_die(esc_html__('Acesso negado.', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    $upload_dir = wp_upload_dir();
    $log_dir_path = trailingslashit($upload_dir['basedir']) . 'sabtr-audit-logs/';
    if (is_dir($log_dir_path)) {
        $files = glob($log_dir_path . 'sabtr_audit_*.log');
        foreach ($files as $file) if (is_file($file)) unlink($file);
    }
    wp_redirect(admin_url('options-general.php?page=sabtr_settings&sabtr_notice=audit_logs_cleared')); exit;
});

add_action('admin_post_sabtr_reset_plugin_data', function() {
    if ( ! isset($_POST['sabtr_reset_nonce_field']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sabtr_reset_nonce_field'])), 'sabtr_reset_action_nonce') ) {
        wp_die(esc_html__('Falha na verificação de segurança (nonce).', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    }
    if ( ! current_user_can('manage_options') ) {
        wp_die(esc_html__('Acesso negado.', 'simple-ab-test-redirect'), esc_html__('Erro', 'simple-ab-test-redirect'), 403);
    }

    global $wpdb;
    // Clear sabtr_notified transients
    $transient_pattern = $wpdb->esc_like('_transient_sabtr_notified_') . '%';
    $sql = $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $transient_pattern );
    $transients = $wpdb->get_col( $sql );
    foreach ( $transients as $transient_name_with_prefix ) {
        $transient_key = str_replace('_transient_', '', $transient_name_with_prefix);
        if ( !empty($transient_key) ) {
            delete_transient( $transient_key );
        }
    }
    // Clear sabtr_notified timeout transients
    $timeout_transient_pattern = $wpdb->esc_like('_transient_timeout_sabtr_notified_') . '%';
    $sql_timeout = $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $timeout_transient_pattern );
    $timeout_transients = $wpdb->get_col( $sql_timeout );
    foreach($timeout_transients as $timeout_transient_name) {
        delete_option($timeout_transient_name); // Transients with timeouts are stored as two options
    }

    wp_redirect(admin_url('options-general.php?page=sabtr_settings&sabtr_notice=plugin_reset_success'));
    exit;
});


add_action('admin_notices', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'sabtr_settings') {
        if (isset($_GET['sabtr_notice']) && $_GET['sabtr_notice'] === 'audit_logs_cleared') {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Logs de auditoria foram limpos com sucesso.', 'simple-ab-test-redirect') . '</p></div>';
        }
    }
});

/* -----------------------------
 * 9. Limpeza automática de logs
 * ----------------------------- */
register_activation_hook(SABTR_PLUGIN_FILE, 'sabtr_schedule_cleanup_events');
function sabtr_schedule_cleanup_events() {
    sabtr_create_plugin_tables();
    if (!wp_next_scheduled('sabtr_daily_audit_log_cleanup')) {
        wp_schedule_event(time(), 'daily', 'sabtr_daily_audit_log_cleanup');
    }
    if (!wp_next_scheduled('sabtr_daily_db_log_cleanup')) {
        wp_schedule_event(time(), 'daily', 'sabtr_daily_db_log_cleanup');
    }
}

register_deactivation_hook(SABTR_PLUGIN_FILE, 'sabtr_clear_scheduled_events');
function sabtr_clear_scheduled_events() {
    wp_clear_scheduled_hook('sabtr_daily_audit_log_cleanup');
    wp_clear_scheduled_hook('sabtr_daily_db_log_cleanup');
}

add_action('sabtr_daily_audit_log_cleanup', 'sabtr_perform_audit_log_cleanup');
function sabtr_perform_audit_log_cleanup() {
    $upload_dir = wp_upload_dir();
    $log_dir_path = trailingslashit($upload_dir['basedir']) . 'sabtr-audit-logs/';
    $days_to_keep = apply_filters('sabtr_audit_log_file_retention_days', 30);
    if (is_dir($log_dir_path)) {
        $files = glob($log_dir_path . 'sabtr_audit_*.log');
        foreach ($files as $file) {
            if (is_file($file) && (time() - filemtime($file)) > ($days_to_keep * DAY_IN_SECONDS)) {
                unlink($file);
            }
        }
    }
}

add_action('sabtr_daily_db_log_cleanup', 'sabtr_perform_db_log_cleanup');
function sabtr_perform_db_log_cleanup() {
    global $wpdb;
    $days_to_keep = absint(get_option('sabtr_db_log_cleanup_days', 60));
    if ($days_to_keep <= 0) return;
    $cutoff_date = date('Y-m-d H:i:s', time() - ($days_to_keep * DAY_IN_SECONDS));
    $access_log_table = $wpdb->prefix . 'ab_test_logs';
    $wpdb->query($wpdb->prepare("DELETE FROM $access_log_table WHERE date_accessed < %s", $cutoff_date));
    $conversion_log_table = $wpdb->prefix . 'ab_test_conversions';
    $wpdb->query($wpdb->prepare("DELETE FROM $conversion_log_table WHERE date_converted < %s", $cutoff_date));
}

/* -----------------------------
 * 10. Relatório Detalhado com Gráficos (Admin Page)
 * ----------------------------- */
add_action('admin_menu', 'sabtr_add_report_page');
function sabtr_add_report_page() {
    $hook_suffix = add_submenu_page(
        'edit.php?post_type=ab_test',
        __('Relatório Testes A/B', 'simple-ab-test-redirect'),
        __('Relatório Gráfico', 'simple-ab-test-redirect'),
        'manage_options',
        'sabtr_report',
        'sabtr_render_report_page'
    );
    add_action('admin_print_scripts-' . $hook_suffix, 'sabtr_enqueue_report_scripts');
}

function sabtr_enqueue_report_scripts() {
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', array(), '3.9.1', true);
}

function sabtr_render_report_page() {
    if (!current_user_can('manage_options')) wp_die(esc_html__('Você não tem permissão para acessar esta página.', 'simple-ab-test-redirect'));
    global $wpdb;
    $log_table = $wpdb->prefix . 'ab_test_logs';
    $conversion_table = $wpdb->prefix . 'ab_test_conversions';

    $tests = get_posts(['post_type' => 'ab_test', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);

    echo '<div class="wrap"><h1>' . esc_html__('Relatório Gráfico e Detalhado dos Testes A/B', 'simple-ab-test-redirect') . '</h1>';

    if (empty($tests)) {
        echo '<p>' . esc_html__('Nenhum teste A/B publicado encontrado.', 'simple-ab-test-redirect') . '</p></div>';
        return;
    }

    $chart_data_array = array();

    echo '<h2>' . esc_html__('Dados Detalhados', 'simple-ab-test-redirect') . '</h2>';
    echo '<table class="widefat fixed striped sabtr-report-table">';
    echo '<thead><tr>';
    echo '<th>' . esc_html__('Teste (ID)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Started', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Duration', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Página Gatilho', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Var. A (Controle)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Var. B (Variação)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('URL Conversão', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Acessos A', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Conversões A', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Taxa Conv. A (%)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Acessos B', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Conversões B', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Taxa Conv. B (%)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Uplift (B vs A)', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('% Tráfego B', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Last Activity', 'simple-ab-test-redirect') . '</th>';
    echo '<th>' . esc_html__('Logs', 'simple-ab-test-redirect') . '</th>';
    echo '</tr></thead><tbody>';

    foreach ($tests as $test_post) {
        $test_id = $test_post->ID;
        $test_title = get_the_title($test_id);
        $test_start_date = get_the_date('Y-m-d', $test_id);
        $duration_days = round((time() - strtotime($test_start_date)) / DAY_IN_SECONDS);

        $trigger_url_meta = get_post_meta($test_id, '_sabtr_trigger_url', true);
        $url_a_config_meta = get_post_meta($test_id, '_sabtr_url_a', true);
        $url_b_config_meta = get_post_meta($test_id, '_sabtr_url_b', true);
        $conversion_url_meta = get_post_meta($test_id, '_sabtr_conversion_url', true);

        $trigger_url_display = !empty($trigger_url_meta) ? $trigger_url_meta : '';
        $url_a_display = !empty($url_a_config_meta) ? $url_a_config_meta : sprintf(__('Mesma que Gatilho', 'simple-ab-test-redirect'));
        $url_b_display = !empty($url_b_config_meta) ? $url_b_config_meta : '';
        $conversion_url_display = !empty($conversion_url_meta) ? $conversion_url_meta : __('N/A', 'simple-ab-test-redirect');

        $percentage_b = intval(get_post_meta($test_id, '_sabtr_percentage_b', true) ?: 50);

        $access_a = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $log_table WHERE test_id = %d AND variant = 'A' AND is_correction = 0", $test_id));
        $access_b = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $log_table WHERE test_id = %d AND variant = 'B' AND is_correction = 0", $test_id));

        $conversions_a = 0;
        $conversions_b = 0;
        if (!empty($conversion_url_meta)) {
            $conversions_a = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $conversion_table WHERE test_id = %d AND variant = 'A'", $test_id));
            $conversions_b = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $conversion_table WHERE test_id = %d AND variant = 'B'", $test_id));
        }

        $rate_a_val = ($access_a > 0) ? (($conversions_a / $access_a) * 100) : 0;
        $rate_b_val = ($access_b > 0) ? (($conversions_b / $access_b) * 100) : 0;
        $rate_a_display = sprintf('%.2f', $rate_a_val);
        $rate_b_display = sprintf('%.2f', $rate_b_val);

        $uplift_display = __('N/A', 'simple-ab-test-redirect');
        $uplift_class = 'sabtr-uplift-neutral';
        if ($access_a > 0 || $access_b > 0) {
            if ($rate_a_val > 0) {
                $uplift = (($rate_b_val - $rate_a_val) / $rate_a_val) * 100;
                $uplift_display = sprintf('%+.2f%%', $uplift);
                $uplift_class = ($uplift > 0) ? 'sabtr-uplift-positive' : (($uplift < 0) ? 'sabtr-uplift-negative' : 'sabtr-uplift-neutral');
            } elseif ($rate_b_val > 0) {
                $uplift_display = __('+&infin;%', 'simple-ab-test-redirect'); // Positive infinity if A is 0 and B is positive
                $uplift_class = 'sabtr-uplift-positive';
            } else { // Both rates are 0
                $uplift_display = __('0.00%', 'simple-ab-test-redirect');
            }
        }

        $last_access_date = $wpdb->get_var($wpdb->prepare("SELECT MAX(date_accessed) FROM $log_table WHERE test_id = %d", $test_id));
        $last_conversion_date = $wpdb->get_var($wpdb->prepare("SELECT MAX(date_converted) FROM $conversion_table WHERE test_id = %d", $test_id));
        $last_activity_timestamp = null;
        if ($last_access_date && $last_conversion_date) {
            $last_activity_timestamp = max(strtotime($last_access_date), strtotime($last_conversion_date));
        } elseif ($last_access_date) {
            $last_activity_timestamp = strtotime($last_access_date);
        } elseif ($last_conversion_date) {
            $last_activity_timestamp = strtotime($last_conversion_date);
        }
        $last_activity_display = $last_activity_timestamp ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $last_activity_timestamp) : __('N/A', 'simple-ab-test-redirect');

        $total_accesses_for_test = $access_a + $access_b;
        $total_conversions_for_test = $conversions_a + $conversions_b;

        echo '<tr>';
        echo '<td><strong><a href="'.get_edit_post_link($test_id).'">' . esc_html($test_title) . '</a></strong> (' . esc_html($test_id) . ')</td>';
        echo '<td>' . esc_html($test_start_date) . '</td>';
        echo '<td>' . sprintf(esc_html__('%d days', 'simple-ab-test-redirect'), $duration_days) . '</td>';
        echo '<td>' . ($trigger_url_display ? '<a href="'.esc_url($trigger_url_display).'" target="_blank" title="'.esc_attr($trigger_url_display).'">'.esc_html(wp_html_excerpt($trigger_url_display, 30, '&hellip;')).'</a>' : '&mdash;') . '</td>';
        echo '<td>' . ($url_a_config_meta ? '<a href="'.esc_url($url_a_config_meta).'" target="_blank" title="'.esc_attr($url_a_config_meta).'">'.esc_html(wp_html_excerpt($url_a_config_meta, 30, '&hellip;')).'</a>' : esc_html($url_a_display)) . '</td>';
        echo '<td>' . ($url_b_display ? '<a href="'.esc_url($url_b_display).'" target="_blank" title="'.esc_attr($url_b_display).'">'.esc_html(wp_html_excerpt($url_b_display, 30, '&hellip;')).'</a>' : '&mdash;') . '</td>';
        echo '<td>' . ($conversion_url_meta ? '<a href="'.esc_url($conversion_url_meta).'" target="_blank" title="'.esc_attr($conversion_url_meta).'">'.esc_html(wp_html_excerpt($conversion_url_meta, 30, '&hellip;')).'</a>' : esc_html($conversion_url_display)) . '</td>';
        echo '<td>' . intval($access_a) . '</td>';
        echo '<td>' . intval($conversions_a) . '</td>';
        echo '<td>' . esc_html($rate_a_display) . '%</td>';
        echo '<td>' . intval($access_b) . '</td>';
        echo '<td>' . intval($conversions_b) . '</td>';
        echo '<td>' . esc_html($rate_b_display) . '%</td>';
        echo '<td><span class="' . esc_attr($uplift_class) . '">' . esc_html($uplift_display) . '</span></td>';
        echo '<td>' . esc_html($percentage_b) . '%</td>';
        echo '<td>' . esc_html($last_activity_display) . '</td>';
        echo '<td><button class="button button-small sabtr-view-logs-btn" data-testid="' . esc_attr($test_id) . '">' . esc_html__('View Logs', 'simple-ab-test-redirect') . '</button></td>';
        echo '</tr>';

        $chart_data_array[] = array(
            'id' => $test_id,
            'title' => $test_title,
            'access_a' => intval($access_a),
            'access_b' => intval($access_b),
            'conversions_a' => intval($conversions_a),
            'conversions_b' => intval($conversions_b),
            'rate_a' => $rate_a_val,
            'rate_b' => $rate_b_val,
            'total_accesses' => $total_accesses_for_test,
            'total_conversions' => $total_conversions_for_test,
            'has_conversion_url' => !empty($conversion_url_meta)
        );
    }
    echo '</tbody></table>';

    echo '<h2>' . esc_html__('Gráficos de Desempenho', 'simple-ab-test-redirect') . '</h2>';
    echo '<div id="sabtr-charts-container">';
    foreach ($chart_data_array as $data) {
        echo '<div class="sabtr-chart-wrapper" style="margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px dashed #ccc;">';
        echo '<h3>' . sprintf(esc_html__('Teste: %s (ID: %d)', 'simple-ab-test-redirect'), esc_html($data['title']), esc_html($data['id'])) . '</h3>';

        echo '<p style="text-align: center; font-weight: bold; margin-bottom: 15px;">';
        echo sprintf(esc_html__('Total de Acessos: %d', 'simple-ab-test-redirect'), esc_html($data['total_accesses']));
        if ($data['has_conversion_url']) {
            echo ' | ' . sprintf(esc_html__('Total de Conversões: %d', 'simple-ab-test-redirect'), esc_html($data['total_conversions']));
        }
        echo '</p>';

        echo '<div style="display: flex; flex-wrap: wrap; gap: 20px;">';
        echo '<div style="flex: 1; min-width: 300px; max-width: 45%; height:300px; border: 1px solid #ddd; padding:10px; border-radius: 4px;"><canvas id="sabtr-chart-access-' . esc_attr($data['id']) . '"></canvas></div>';
        if ($data['has_conversion_url']) {
            echo '<div style="flex: 1; min-width: 300px; max-width: 45%; height:300px; border: 1px solid #ddd; padding:10px; border-radius: 4px;"><canvas id="sabtr-chart-conversion-' . esc_attr($data['id']) . '"></canvas></div>';
        }
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    echo '<h3>' . esc_html__('Observações do Relatório:', 'simple-ab-test-redirect') . '</h3>';
    echo '<ul>';
    echo '<li>' . esc_html__('Os "Acessos" contam as designações iniciais de variantes. Logs de "correção" não são incluídos.', 'simple-ab-test-redirect') . '</li>';
    echo '<li>' . esc_html__('As "Conversões" são registradas quando um visitante com uma variante designada acessa a URL de Conversão. Um cookie é usado para tentar registrar apenas uma conversão por visitante por teste.', 'simple-ab-test-redirect') . '</li>';
    echo '<li>' . esc_html__('A "Taxa de Conversão" é calculada como (Conversões / Acessos) * 100.', 'simple-ab-test-redirect') . '</li>';
    echo '<li>' . esc_html__('O cálculo de significância estatística não está implementado nesta versão. Considere usar ferramentas externas para análises mais profundas.', 'simple-ab-test-redirect') . '</li>';
    echo '</ul>';

    echo '</div>';
    ?>
    <div id="sabtr-logs-modal" style="display:none; background: #f1f1f1; padding: 20px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; box-shadow: 0 0 15px rgba(0,0,0,0.2); width: 80%; max-width: 700px; max-height: 80vh; overflow-y: auto;">
        <h2 id="sabtr-modal-title"></h2>
        <div id="sabtr-modal-content-access"><h3><?php _e('Recent Access Logs', 'simple-ab-test-redirect'); ?></h3><div class="sabtr-logs-container"></div></div>
        <div id="sabtr-modal-content-conversion"><h3><?php _e('Recent Conversion Logs', 'simple-ab-test-redirect'); ?></h3><div class="sabtr-logs-container"></div></div>
        <button id="sabtr-close-modal-btn" class="button button-secondary" style="margin-top:15px;"><?php _e('Close', 'simple-ab-test-redirect'); ?></button>
    </div>
    <style>
        .sabtr-report-table th, .sabtr-report-table td { padding: 8px 10px; word-break: break-word; }
        .sabtr-report-table td a { text-decoration: none; }
        .sabtr-report-table td a:hover { text-decoration: underline; }
        .sabtr-chart-wrapper h3 { margin-top: 20px; margin-bottom: 10px; }
        .sabtr-uplift-positive { color: #28a745; font-weight: bold; }
        .sabtr-uplift-negative { color: #dc3545; font-weight: bold; }
        .sabtr-uplift-neutral { color: #6c757d; }
        #sabtr-logs-modal .sabtr-logs-container { max-height: 250px; overflow-y: auto; border: 1px solid #ddd; margin-top: 10px; padding: 10px; background: #fff; }
        #sabtr-logs-modal table { margin-top: 0; }
        #sabtr-logs-modal table td, #sabtr-logs-modal table th { font-size: 12px; padding: 6px 8px; }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart === 'undefined') {
                // console.error('Simple AB Test: Chart.js não carregado.');
                return;
            }

            const chartData = <?php echo wp_json_encode($chart_data_array); ?>;
            // console.log('Simple AB Test: Dados para gráficos:', chartData);

            const commonChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }
            };

            chartData.forEach(function(test) {
                // console.log('Simple AB Test: Processando teste para gráfico:', test);
                const accessCtx = document.getElementById('sabtr-chart-access-' + test.id);
                if (accessCtx) {
                    try {
                        new Chart(accessCtx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['<?php echo esc_js(__('Variante A', 'simple-ab-test-redirect')); ?>', '<?php echo esc_js(__('Variante B', 'simple-ab-test-redirect')); ?>'],
                                datasets: [{
                                    label: '<?php echo esc_js(__('Acessos', 'simple-ab-test-redirect')); ?>',
                                    data: [test.access_a, test.access_b],
                                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                                    borderWidth: 1
                                }]
                            },
                            options: { ...commonChartOptions, plugins: { ...commonChartOptions.plugins, title: { display: true, text: '<?php echo esc_js(__('Comparativo de Acessos', 'simple-ab-test-redirect')); ?>' } } }
                        });
                        // console.log('Simple AB Test: Gráfico de acesso para teste ID ' + test.id + ' renderizado.');
                    } catch (e) {
                        // console.error('Simple AB Test: Erro ao renderizar gráfico de acesso para teste ID ' + test.id + ':', e);
                    }
                } else {
                    // console.warn('Simple AB Test: Canvas de acesso não encontrado para teste ID ' + test.id);
                }

                if (test.has_conversion_url) {
                    const conversionCtx = document.getElementById('sabtr-chart-conversion-' + test.id);
                    if (conversionCtx) {
                         try {
                            new Chart(conversionCtx.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: ['<?php echo esc_js(__('Variante A', 'simple-ab-test-redirect')); ?>', '<?php echo esc_js(__('Variante B', 'simple-ab-test-redirect')); ?>'],
                                    datasets: [{
                                    label: '<?php echo esc_js(__('Conversion Rate (%)', 'simple-ab-test-redirect')); ?>',
                                    data: [test.rate_a, test.rate_b],
                                        backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 159, 64, 0.6)'],
                                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)'],
                                        borderWidth: 1
                                    }]
                                },
                            options: { ...commonChartOptions, plugins: { ...commonChartOptions.plugins, title: { display: true, text: '<?php echo esc_js(__('Conversion Rate Comparison', 'simple-ab-test-redirect')); ?>' } } }
                            });
                            // console.log('Simple AB Test: Gráfico de conversão para teste ID ' + test.id + ' renderizado.');
                        } catch (e) {
                            // console.error('Simple AB Test: Erro ao renderizar gráfico de conversão para teste ID ' + test.id + ':', e);
                        }
                    } else {
                        // console.warn('Simple AB Test: Canvas de conversão não encontrado para teste ID ' + test.id);
                    }
                }
            });

            const modal = document.getElementById('sabtr-logs-modal');
            const closeBtn = document.getElementById('sabtr-close-modal-btn');
            const modalTitle = document.getElementById('sabtr-modal-title');
            const accessLogsContainer = document.querySelector('#sabtr-modal-content-access .sabtr-logs-container');
            const conversionLogsContainer = document.querySelector('#sabtr-modal-content-conversion .sabtr-logs-container');

            document.querySelectorAll('.sabtr-view-logs-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const testId = this.dataset.testid;
                    const testTitle = this.closest('tr').querySelector('td:first-child strong a').textContent;
                    modalTitle.textContent = '<?php echo esc_js(__('Logs for Test:', 'simple-ab-test-redirect')); ?> ' + testTitle + ' (ID: ' + testId + ')';

                    accessLogsContainer.innerHTML = '<?php echo esc_js(__('Loading...', 'simple-ab-test-redirect')); ?>';
                    conversionLogsContainer.innerHTML = '<?php echo esc_js(__('Loading...', 'simple-ab-test-redirect')); ?>';
                    modal.style.display = 'block';

                    // Fetch Access Logs
                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'sabtr_fetch_logs',
                            nonce: '<?php echo wp_create_nonce('sabtr_fetch_logs_nonce'); ?>',
                            test_id: testId,
                            log_type: 'access'
                        })
                    }).then(response => response.json()).then(data => {
                        renderLogs(accessLogsContainer, data.data);
                    });

                    // Fetch Conversion Logs
                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'sabtr_fetch_logs',
                            nonce: '<?php echo wp_create_nonce('sabtr_fetch_logs_nonce'); ?>',
                            test_id: testId,
                            log_type: 'conversion'
                        })
                    }).then(response => response.json()).then(data => {
                        renderLogs(conversionLogsContainer, data.data);
                    });
                });
            });

            closeBtn.addEventListener('click', function() { modal.style.display = 'none'; });
            window.addEventListener('click', function(event) {
                if (event.target === modal) { modal.style.display = 'none'; }
            });

            function renderLogs(container, logs) {
                if (!logs || logs.length === 0) {
                    container.innerHTML = '<p><?php echo esc_js(__('No logs found.', 'simple-ab-test-redirect')); ?></p>';
                    return;
                }
                let html = '<table class="wp-list-table widefat striped fixed"><thead><tr><th><?php echo esc_js(__('Date', 'simple-ab-test-redirect')); ?></th><th><?php echo esc_js(__('Variant', 'simple-ab-test-redirect')); ?></th><th><?php echo esc_js(__('IP Address', 'simple-ab-test-redirect')); ?></th><th><?php echo esc_js(__('User Agent (Excerpt)', 'simple-ab-test-redirect')); ?></th></tr></thead><tbody>';
                logs.forEach(log => {
                    html += `<tr><td>${log.date}</td><td>${log.variant}</td><td>${log.ip_address}</td><td>${log.user_agent.substring(0,50)}...</td></tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            }
        });
    </script>
    <?php
}

/* -----------------------------
 * 11. AJAX Handler for Log Fetching
 * ----------------------------- */
add_action('wp_ajax_sabtr_fetch_logs', 'sabtr_ajax_fetch_logs_callback');
function sabtr_ajax_fetch_logs_callback() {
    check_ajax_referer('sabtr_fetch_logs_nonce', 'nonce');

    $test_id = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
    $log_type = isset($_POST['log_type']) ? sanitize_text_field(wp_unslash($_POST['log_type'])) : 'access';

    if (!$test_id || !current_user_can('manage_options')) {
        wp_send_json_error(__('Invalid request or permissions.', 'simple-ab-test-redirect'));
        return;
    }

    global $wpdb;
    $results = array();
    $limit = 20;

    if ($log_type === 'access') {
        $table_name = $wpdb->prefix . 'ab_test_logs';
        $date_column = 'date_accessed';
        $raw_logs = $wpdb->get_results($wpdb->prepare(
            "SELECT {$date_column} as date, variant, ip_address, user_agent FROM {$table_name} WHERE test_id = %d ORDER BY {$date_column} DESC LIMIT %d",
            $test_id, $limit
        ));
    } elseif ($log_type === 'conversion') {
        $table_name = $wpdb->prefix . 'ab_test_conversions';
        $date_column = 'date_converted';
        $raw_logs = $wpdb->get_results($wpdb->prepare(
            "SELECT {$date_column} as date, variant, ip_address, user_agent FROM {$table_name} WHERE test_id = %d ORDER BY {$date_column} DESC LIMIT %d",
            $test_id, $limit
        ));
    } else {
        wp_send_json_error(__('Invalid log type.', 'simple-ab-test-redirect'));
        return;
    }

    if ($raw_logs) {
        foreach($raw_logs as $log_entry) {
            $results[] = array(
                'date' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log_entry->date)),
                'variant' => esc_html($log_entry->variant),
                'ip_address' => esc_html(preg_replace('/([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)/', '$1.$2.XX.XX', $log_entry->ip_address)), // Basic IP masking
                'user_agent' => esc_html($log_entry->user_agent)
            );
        }
    }
    wp_send_json_success($results);
}

/* -----------------------------
 * 12. Link de Configurações na lista de plugins
 * ----------------------------- */
add_filter('plugin_action_links_' . plugin_basename(SABTR_PLUGIN_FILE), 'sabtr_add_settings_link');
function sabtr_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=sabtr_settings') . '">' . __('Configurações', 'simple-ab-test-redirect') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
?>
