<?php
/**
 * Smarty plugin
 *
 * @package Brainy
 * @subpackage PluginsFunction
 */

/**
 * Smarty {html_table} function plugin
 *
 * Type:     function<br>
 * Name:     html_table<br>
 * Date:     Feb 17, 2003<br>
 * Purpose:  make an html table from an array of data<br>
 * Params:
 * <pre>
 * - loop       - array to loop through
 * - cols       - number of columns, comma separated list of column names
 *                or array of column names
 * - rows       - number of rows
 * - table_attr - table attributes
 * - th_attr    - table heading attributes (arrays are cycled)
 * - tr_attr    - table row attributes (arrays are cycled)
 * - td_attr    - table cell attributes (arrays are cycled)
 * - trailpad   - value to pad trailing cells with
 * - caption    - text for caption element
 * - vdir       - vertical direction (default: "down", means top-to-bottom)
 * - hdir       - horizontal direction (default: "right", means left-to-right)
 * - inner      - inner loop (default "cols": print $options['loop'] line by line,
 *                $options['loop'] will be printed column by column otherwise)
 * </pre>
 * Examples:
 * <pre>
 * {table loop=$data}
 * {table loop=$data cols=4 tr_attr='"bgcolor=red"'}
 * {table loop=$data cols="first,second,third" tr_attr=$colors}
 * </pre>
 *
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author credit to Messju Mohr <messju at lammfellpuschen dot de>
 * @author credit to boots <boots dot smarty at yahoo dot com>
 * @version 1.1
 * @link http://www.smarty.net/manual/en/language.function.html.table.php {html_table}
 *          (Smarty online manual)
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 */
function smarty_function_html_table($params, $template) {
    $options = array(
        'table_attr' => 'border="1"',
        'tr_attr' => '',
        'th_attr' => '',
        'td_attr' => '',
        'cols' => 3,
        'cols_count' => 3,
        'rows' => 3,
        'trailpad' => '&nbsp;',
        'vdir' => 'down',
        'hdir' => 'right',
        'inner' => 'cols',
        'caption' => '',
        'loop' => null,
    );

    if (!isset($params['loop'])) {
        trigger_error("html_table: missing 'loop' parameter", E_USER_WARNING);

        return;
    }

    foreach ($params as $_key => $_value) {
        switch ($_key) {
            case 'loop':
                $options[$_key] = (array) $_value;
                break;

            case 'cols':
                if (is_array($_value) && !empty($_value)) {
                    $options['cols'] = $_value;
                    $cols_count = count($_value);
                } elseif (!is_numeric($_value) && is_string($_value) && !empty($_value)) {
                    $options['cols'] = explode(',', $_value);
                    $cols_count = count($options['cols']);
                } elseif (!empty($_value)) {
                    $cols_count = (int) $_value;
                } else {
                    $cols_count = $options['cols'];
                }
                break;

            case 'rows':
                $options[$_key] = (int) $_value;
                break;

            case 'table_attr':
            case 'trailpad':
            case 'hdir':
            case 'vdir':
            case 'inner':
            case 'caption':
                $options[$_key] = (string) $_value;
                break;

            case 'tr_attr':
            case 'td_attr':
            case 'th_attr':
                $options[$_key] = $_value;
                break;
        }
    }

    $loop_count = count($options['loop']);
    if (empty($params['rows'])) {
        /* no rows specified */
        $options['rows'] = ceil($loop_count / $cols_count);
    } elseif (empty($params['cols'])) {
        if (!empty($params['rows'])) {
            /* no cols specified, but rows */
            $cols_count = ceil($loop_count / $options['rows']);
        }
    }

    $output = "<table {$options['table_attr']}>\n";

    if (!empty($options['caption'])) {
        $output .= '<caption>' . $options['caption'] . "</caption>\n";
    }

    if (is_array($options['cols'])) {
        $options['cols'] = ($options['hdir'] == 'right') ? $options['cols'] : array_reverse($options['cols']);
        $output .= "<thead><tr>\n";

        for ($r = 0; $r < $cols_count; $r++) {
            $output .= '<th' . smarty_function_html_table_cycle('th', $options['th_attr'], $r) . '>';
            $output .= $options['cols'][$r];
            $output .= "</th>\n";
        }
        $output .= "</tr></thead>\n";
    }

    $output .= "<tbody>\n";
    for ($r = 0; $r < $options['rows']; $r++) {
        $output .= "<tr" . smarty_function_html_table_cycle('tr', $options['tr_attr'], $r) . ">\n";
        $rx = ($options['vdir'] == 'down') ? $r * $cols_count : ($options['rows']-1 - $r) * $cols_count;

        for ($c = 0; $c < $cols_count; $c++) {
            $x = ($options['hdir'] == 'right') ? $rx + $c : $rx + $cols_count-1 - $c;
            if ($options['inner'] != 'cols') {
                /* shuffle x to loop over rows*/
                $x = floor($x / $cols_count) + ($x % $cols_count) * $options['rows'];
            }

            if ($x < $loop_count) {
                $output .= "<td" . smarty_function_html_table_cycle('td', $options['td_attr'], $c) . ">" . $options['loop'][$x] . "</td>\n";
            } else {
                $output .= "<td" . smarty_function_html_table_cycle('td', $options['td_attr'], $c) . ">{$options['trailpad']}</td>\n";
            }
        }
        $output .= "</tr>\n";
    }
    $output .= "</tbody>\n";
    $output .= "</table>\n";

    return $output;
}

function smarty_function_html_table_cycle($name, $var, $no) {
    if (!is_array($var)) {
        $ret = $var;
    } else {
        $ret = $var[$no % count($var)];
    }

    return $ret ? ' ' . $ret : '';
}
