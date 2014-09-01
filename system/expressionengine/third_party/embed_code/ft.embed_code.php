<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Embed_code_ft extends EE_Fieldtype {

    var $info = array(
        'name'      => 'Embed Code',
        'version'   => '1.0'
    );

    // --------------------------------------------------------------------

    public function display_field($data)
    {
        return NULL;
    }

    public function accepts_content_type($name)
    {
        return ($name == 'grid');
    }

    // --------------------------------------------------------------------
    
    /**
     * grid_display_settings
     * 
     * @access  public
     * @param   mixed $data
     * @return  array
     */
    public function grid_display_settings($data)
    {
        $embed_prefix = (isset($data['embed_prefix'])) ? $data['embed_prefix'] : '';

        return array(
            EE_Fieldtype::grid_settings_row(lang('embed_prefix'), form_input('embed_prefix', $embed_prefix), FALSE)
        );
    }

    // --------------------------------------------------------------------
    
    /**
     * grid_display_field
     * 
     * @access  public
     * @param   mixed $data
     * @return  string
     */
    public function grid_display_field($data)
    {
        $embed_prefix = (isset($this->settings['embed_prefix'])) ? $this->settings['embed_prefix'] : '';

        ee()->cp->load_package_js('cell');

        ee()->cp->add_to_foot(
            "<script>
                var bindEvents = ['display', 'remove', 'afterSort'];
                for (var i in bindEvents) {
                    Grid.bind(\"embed_code\", bindEvents[i], function(cell){
                       embed_code_init('".$this->settings['grid_field_id']."', '".$embed_prefix."');
                    });
                };
            </script>"
        );

        $input_properties = array(
            'name'  => '', 
            'value' => "{".$embed_prefix,
            'readonly'=>'true',
            'class' => 'embed_code',
            'style' => "text-align: center; ",
            'onclick' => 'select()'
        );

        return form_input( $input_properties );
    }
}
// END Embed_code class

/* End of file ft.embed_code.php */
/* Location: ./system/expressionengine/third_party/embed_code/ft.embed_code.php */