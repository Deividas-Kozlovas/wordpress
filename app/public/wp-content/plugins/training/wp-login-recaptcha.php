<?php
/*
Plugin Name: WP Login reCAPTCHA
Description: Adds reCAPTCHA to the WordPress login and registration forms.
Version: 1.0
Author: Your Name
*/

// Plugin code will go here
function wp_login_recaptcha_enqueue_script()
{
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
}
add_action('login_enqueue_scripts', 'wp_login_recaptcha_enqueue_script');
function wp_login_recaptcha_add_to_form()
{
    echo '<div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>';
}
add_action('login_form', 'wp_login_recaptcha_add_to_form');
function wp_login_recaptcha_validate($errors)
{
    if (!isset($_POST['g-recaptcha-response'])) {
        $errors->add('recaptcha_error', __('Please complete the reCAPTCHA.'));
    } else {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $secret_key = 'YOUR_SECRET_KEY'; // Replace with your reCAPTCHA Secret Key

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $secret_key,
                'response' => $recaptcha_response
            )
        ));

        if (is_wp_error($response)) {
            $errors->add('recaptcha_error', __('There was an error verifying reCAPTCHA.'));
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);

            if (!$data['success']) {
                $errors->add('recaptcha_error', __('Please complete the reCAPTCHA.'));
            }
        }
    }
}

add_action('wp_authenticate', 'wp_login_recaptcha_validate', 10, 1);
function wp_register_recaptcha_add_to_form()
{
    echo '<div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>';
}
add_action('register_form', 'wp_register_recaptcha_add_to_form');
function wp_register_recaptcha_validate($errors)
{
    if (!isset($_POST['g-recaptcha-response'])) {
        $errors->add('recaptcha_error', __('Please complete the reCAPTCHA.'));
    } else {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $secret_key = 'YOUR_SECRET_KEY'; // Replace with your reCAPTCHA Secret Key

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $secret_key,
                'response' => $recaptcha_response
            )
        ));

        if (is_wp_error($response)) {
            $errors->add('recaptcha_error', __('There was an error verifying reCAPTCHA.'));
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);

            if (!$data['success']) {
                $errors->add('recaptcha_error', __('Please complete the reCAPTCHA.'));
            }
        }
    }
}

add_action('registration_errors', 'wp_register_recaptcha_validate', 10, 1);
