<?php
/**
 * Smarty Internal Plugin Compile While
 *
 * Compiles the {while} tag
 *
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile While Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class Smarty_Internal_Compile_While extends Smarty_Internal_CompileBase
{
    /**
     * Compiles code for the {while} tag
     *
     * @param  array  $args      array with attributes from parser
     * @param  object $compiler  compiler object
     * @param  array  $parameter array with compilation parameter
     * @return string compiled code
     */
    public function compile($args, $compiler, $parameter) {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);
        $this->openTag($compiler, 'while');

        if (!array_key_exists("if condition",$parameter)) {
            $compiler->trigger_template_error("missing while condition", $compiler->lex->taglineno);
        }

        if (is_array($parameter['if condition'])) {
            if (is_array($parameter['if condition']['var'])) {
                $_output = "if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]) || !is_array(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value)) \$_smarty_tpl->createLocalArrayVariable(" . $parameter['if condition']['var']['var'] . ");\n";
                $_output .= "while (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value" . $parameter['if condition']['var']['smarty_internal_index'] . " = " . $parameter['if condition']['value'] . ") {\n";
            } else {
                $_output = "if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "])) \$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "] = new Smarty_Variable(null);\n";
                $_output .= "while (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "]->value = " . $parameter['if condition']['value'] . ") {\n";
            }

            return $_output;
        } else {
            return "while ({$parameter['if condition']}) {\n";
        }
    }

}

/**
 * Smarty Internal Plugin Compile Whileclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class Smarty_Internal_Compile_Whileclose extends Smarty_Internal_CompileBase
{
    /**
     * Compiles code for the {/while} tag
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler) {
        $this->closeTag($compiler, array('while'));

        return "}\n";
    }

}
