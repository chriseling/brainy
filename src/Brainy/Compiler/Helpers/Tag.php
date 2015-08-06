<?php

namespace Box\Brainy\Compiler\Helpers;


class Tag extends ParseTree
{
    /**
     * Saved block nesting level
     * @var int
     */
    public $saved_block_nesting;

    /**
     * Create parse tree buffer for Smarty tag
     *
     * @param object $parser parser object
     * @param string $data   content
     */
    public function __construct($parser, $data) {
        $this->parser = $parser;
        $this->data = $data;
        $this->saved_block_nesting = $parser->block_nesting_level;
    }

    /**
     * @return string
     */
    public function to_inline_data() {
        return $this->data;
    }

    /**
     * Return buffer content
     *
     * @return string content
     */
    public function to_smarty_php() {
        return $this->data;
    }

    /**
     * Return complied code that loads the evaluated outout of buffer content into a temporary variable
     *
     * @return string template code
     */
    public function assign_to_var() {
        $var = sprintf('$_tmp%d', ++\Box\Brainy\Compiler\Parser::$prefix_number);
        $this->parser->compiler->prefix_code[] = sprintf('ob_start();%s%s=ob_get_clean();', $this->data, $var);

        return $var;
    }

    /**
     * @return bool
     */
    public function can_combine_inline_data() {
        return false;
    }

}
