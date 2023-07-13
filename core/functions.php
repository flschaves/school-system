<?php
/**
 * Main functions
 *
 * The main functions of project
 */

/**
 * Insert an user on database
 *
 * @param array  $user_data Array with user data to insert.
 * @return int   The newly created or actual updated user's ID
 */
function insert_user( $user_data ) {

    global $db;

    $defaults = array(
        'ID', 'usuario', 'senha', 'tipo', 'nome',
        'nascimento', 'rg', 'cpf', 'endereco', 'bairro',
        'cidade', 'complemento', 'telefone', 'curso',
        'tempo_esp',
    );

    // Remove empty and non authorized values
    $user_data = filter_args( $defaults, $user_data );

    // Encrypt user password
    if ( ! empty( $user_data[ 'senha'] ) ) {
        $user_data[ 'senha' ] = md5( $user_data[ 'senha' ] );
    }

    // Update
    if ( isset( $user_data['ID'] ) and $user_data['ID'] > 0 ) {
        // Create string with data
        $fields = '';
        foreach ( $user_data as $key => $data ) {
            if ( $key == 'ID' ) {
                continue;
            }
            $fields .= $key . ' = :' . $key . ', ';
        }
        $fields = trim( $fields, ', ' );

        // Update
        $user_id = $db->query( "UPDATE usuarios SET {$fields} WHERE ID = :ID", $user_data );
        
    // Insert
    } else {
        // Create string with data
        $fields = implode( ', ', array_keys( $user_data ) );
        $values = ':' . implode( ', :', array_keys( $user_data) );

        // Insert
        $user_id = $db->query( "INSERT INTO usuarios( {$fields} ) VALUES( {$values} )", $user_data );
    }

    return $user_id;
}

/**
 * Insert a course on database
 *
 * @param array  $course_data Array with course data to insert.
 * @return int   The newly created or actual updated course's ID
 */
function insert_course( $course_data ) {

    global $db;

    $defaults = array(
        'ID', 'professor', 'nome', 'descricao', 
        'numero_sala', 'valor_mensalidade'
    );

    // Remove empty and non authorized values
    $course_data = filter_args( $defaults, $course_data );

    // Update
    if ( isset( $course_data['ID'] ) and $course_data['ID'] > 0 ) {
        // Create string with data
        $fields = '';
        foreach ( $course_data as $key => $data ) {
            if ( $key == 'ID' ) {
                continue;
            }
            $fields .= $key . ' = :' . $key . ', ';
        }
        $fields = trim( $fields, ', ' );

        // Update
        $course_id = $db->query( "UPDATE cursos SET {$fields} WHERE ID = :ID", $course_data );
        
    // Insert
    } else {
        // Create string with data
        $fields = implode( ', ', array_keys( $course_data ) );
        $values = ':' . implode( ', :', array_keys( $course_data) );

        // Insert
        $course_id = $db->query( "INSERT INTO cursos( {$fields} ) VALUES( {$values} )", $course_data );
    }

    return $course_id;
}

/**
 * Insert an note on database
 *
 * @param array  $note_data Array with note data to insert.
 * @return int   The newly created or actual updated note's ID
 */
function insert_note( $note_data ) {

    global $db;

    $defaults = array(
        'ID', 'usuario_id', 'curso_id',
        'data_avaliacao', 'nota'
    );

    // Remove empty and non authorized values
    $note_data = filter_args( $defaults, $note_data );

    // Update
    if ( isset( $note_data['ID'] ) and $note_data['ID'] > 0 ) {
        // Create string with data
        $fields = '';
        foreach ( $note_data as $key => $data ) {
            if ( $key == 'ID' ) {
                continue;
            }
            $fields .= $key . ' = :' . $key . ', ';
        }
        $fields = trim( $fields, ', ' );

        // Update
        $note_id = $db->query( "UPDATE notas SET {$fields} WHERE ID = :ID", $note_data );
        
    // Insert
    } else {
        // Create string with data
        $fields = implode( ', ', array_keys( $note_data ) );
        $values = ':' . implode( ', :', array_keys( $note_data) );

        // Insert
        $note_id = $db->query( "INSERT INTO notas( {$fields} ) VALUES( {$values} )", $note_data );
    }

    return $note_id;
}

/**
 * Combine two arrays excluding extra values
 *
 * @param array  $defaults  Entire list of supported attributes.
 * @param array  $atts      User defined attributes.
 * @return array Combined and filtered attribute list.
 */
function filter_args( $defaults, $atts ) {
    $atts = (array) $atts;
    $out = array();

    foreach ( $defaults as $name ) {
        if ( array_key_exists( $name, $atts ) )
            $out[ $name ] = $atts[ $name ];
    }

    return $out;
}

/**
 * Require the template file with system environment.
 *
 * The globals are set up for the template file to ensure that the system
 * environment is available from within the function. The query variables are
 * also available.
 *
 * @param string $template_file  Path to template file.
 * @param bool   $require_once   Whether to require_once or require. Default true.
 */
function load_template( $template_file, $require_once = true ) {
    global $db, $current_user, $errors;

    if ( $require_once ) {
        require_once( $template_file );
    } else {
        require( $template_file );
    }
}

/**
 * Require header template
 *
 * @return void
 */
function get_header() {
    load_template( ABSPATH . TEMPLATEPATH . '/header.php' );
}

/**
 * Require footer template
 *
 * @return void
 */
function get_footer() {
    load_template( ABSPATH . TEMPLATEPATH . '/footer.php' );
}

/**
 * Return formatted date string to mysql
 *
 * @param string    $date Date string
 * @return string date formatted to mysql
 */
function date2mysql( $date ){
    return implode( '-', array_reverse( explode('/', $date ) ) );
}

/**
 * Return formatted date string from
 *
 * @param string    $date Date string
 * @return string date formatted from mysql
 */
function datefrommysql( $date ){
    return implode( '/', array_reverse( explode( '-', $date ) ) );
}

/**
 * Return type name by code
 *
 * @param int      Code of user type
 * @return string  User type name
 **/
function get_user_type_by_code( $cod ) {
    switch ( $cod ) {
        case 2:
            $type = 'Administrador';
            break;
        case 1:
            $type = 'Professor';
            break;
        default:
            $type = 'Aluno';
            break;
    }

    return $type;
}

/**
 * Return formatted value with mask
 *
 * @param $val string    Value to format
 * @param $mask string   Mask format
 * @return string        Formatted value
 **/
function mask( $val, $mask ) {
    $maskared = '';
    $k = 0;
    for ( $i = 0; $i <= strlen( $mask ) - 1; $i++ ) {
        if ( $mask[ $i ] == '#' ) {
            if ( isset( $val[ $k ] ) ) 
                $maskared .= $val[ $k++ ];
        } else {
            if ( isset( $mask[ $i ] ) )
                $maskared .= $mask[ $i ];
        }
    }

    return $maskared;
}

/**
 * Return formatted CPF
 *
 * @param string  CPF
 * @return string Formatted CPF
 **/
function mask_cpf( $cpf ) {
    return mask( $cpf, '###.###.###-##' );
}

/**
 * Return teacher level by specialization time
 *
 * @param int     Time in years
 * @return string Teacher level
 **/
function get_level_by_time_spe( $time ) {
    if ( $time >= 1 ) {
        $level = 'Básico';
    }
    if ( $time >= 3 ) {
        $level = 'Intermediário';
    }
    if ( $time >= 5) {
        $level = 'Avançado';
    }
    return $level;
}

/**
 * Outputs the html checked attribute.
 *
 * Compares the first two arguments and if identical marks as checked
 *
 * @param mixed $checked One of the values to compare
 * @param mixed $current (true) The other value to compare if not just true
 * @param bool  $echo    Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function checked( $checked, $current = true, $echo = true ) {
    return __checked_selected_helper( $checked, $current, $echo, 'checked' );
}

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected
 *
 * @param mixed $selected One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function selected( $selected, $current = true, $echo = true ) {
    return __checked_selected_helper( $selected, $current, $echo, 'selected' );
}

/**
 * Outputs the html disabled attribute.
 *
 * Compares the first two arguments and if identical marks as disabled
 *
 * @param mixed $disabled One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function disabled( $disabled, $current = true, $echo = true ) {
    return __checked_selected_helper( $disabled, $current, $echo, 'disabled' );
}

/**
 * Private helper function for checked, selected, and disabled.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @since 2.8.0
 * @access private
 *
 * @param mixed  $helper  One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled we are doing
 * @return string html attribute or empty string
 */
function __checked_selected_helper( $helper, $current, $echo, $type ) {
    if ( (string) $helper === (string) $current )
        $result = " $type='$type'";
    else
        $result = '';

    if ( $echo )
        echo $result;

    return $result;
}

/**
 * Redirect function
 *
 * @param string $page   Page to redirect
 * @param string $params Query args
 * @return void
 **/
function redirect( $page = '', $params = '' ) {
    if ( $page ) {
        $page = '?page=' . $page;
    }
    header( 'Location: ' . $_SERVER['PHP_SELF'] . $page . $params );
    exit;
}