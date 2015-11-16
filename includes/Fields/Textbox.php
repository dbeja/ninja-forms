<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_Textbox
 */
class NF_Fields_Textbox extends NF_Abstracts_Input
{
    protected $_name = 'textbox';

    protected $_section = 'common';

    protected $_aliases = array( 'input' );

    protected $_type = 'textbox';

    protected $_templates = 'textbox';

    public function __construct()
    {
        parent::__construct();

        $this->_settings = $this->load_settings(
            array( 'label', 'label_pos', 'required', 'placeholder', 'default', 'classes', 'input_limit_set' )
        );

        $this->_nicename = __( 'Textbox', 'ninja-forms' );
    }
}
