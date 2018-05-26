<?php
/*
Element Description: BATC Archive CQ-TV PDF
*/

// Element Class
class vc_archive_cq_tv_pdf extends WPBakeryShortCode {

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_archive_cq_tv_pdf_mapping' ) );
        add_shortcode( 'vc_archive_cq_tv_pdf', array( $this, 'vc_archive_cq_tv_pdf_html' ) );
    }

    // Element Mapping
    public function vc_archive_cq_tv_pdf_mapping() {

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => __('Archive CQ-TV PDF', 'text-domain'),
                'base' => 'vc_archive_cq_tv_pdf',
                'description' => __('', 'text-domain'),
                'category' => __('BATC Elements', 'text-domain'),
                'params' => array(

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'issue_number',
                        'heading' => __( 'Issue Number', 'text-domain' ),
                        'param_name' => 'issue_number',
                        'value' => __( '', 'text-domain' ),
                        'description' => __( '', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Button Settings',
                    ),

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'pdf_link',
                        'heading' => __( 'Enter the original URL of the PDF from the Media Library here.', 'text-domain' ),
                        'param_name' => 'pdf_url',
                        'value' => __( '', 'text-domain' ),
                        'description' => __( '', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Button Settings',
                    ),


                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'image_link',
                        'heading' => __( 'Enter the original URL of the PDF image from the Media Library here.', 'text-domain' ),
                        'param_name' => 'image_url',
                        'value' => __( '', 'text-domain' ),
                        'description' => __( '', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Button Settings',
                    ),

                ),
            )
        );

    }


    // Element HTML
    public function vc_archive_cq_tv_pdf_html( $atts ) {

        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'issue_number'   => '',
                    'pdf_url'   => '',
                    'image_url'   => '',
                ),
                $atts
            )
        );

        // Fill $html var with data
        $class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
        $html = '
        <a href="'.$pdf_url.'" target="_blank">
        <img src="'.$image_url.'">
        </a>
        <p class="issue_number">CQ-TV '.$issue_number.'</p>';

        return $html;

    }

} // End Element Class


// Element Class Init
new vc_archive_cq_tv_pdf();
?>
