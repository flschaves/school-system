<?php 

$note_id = (int) $_GET['note_id'] ?: null;

if ( $note_id > 0 ) {
    $note = new Note( $note_id );
}

$users = $db->query( "SELECT ID, nome FROM usuarios WHERE tipo = :tipo", array( 'tipo' => '0' ) );
$courses = $db->query( "SELECT * FROM cursos" );

$required_fields = array(
    'usuario_id', 'curso_id',
    'data_avaliacao', 'nota'
);
if ( isset( $_POST['action'] ) ) {

    // Validate required fields
    $error = false;
    foreach ( $required_fields as $field ) {

        if ( empty( $_POST[ $field] ) ) {
            $required_fields[ $field ] =  '<span class="single-error">Preencha este campo!</span>';
            $error = true;
        }
    }
    // Just insert note if selected users is from selected course
    $user = new User( $_POST['usuario_id'] );
    if ( ! empty( $_POST['usuario_id'] ) and $_POST['curso_id'] != $user->data['curso'] ) {
        $required_fields['usuario_id'] = '<span class="single-error">O aluno não pertence a disciplina</span>';
        $error = true;
    }

    // If there's no errors, update or insert note
    if ( ! $error ) {
        $note_data = $_POST;

        if ( $note_id > 0 ) {
            $note_data['ID'] = $note_id;
        }

        $note_data['data_avaliacao'] = date2mysql( $note_data['data_avaliacao'] );

        $new_note_id = insert_note( $note_data );

        if ( $new_note_id > 0 ) {
            $type = 'note-created';
            if ( $note_data['ID'] > 0 ) {
                $type = 'note-updated';
            }
            redirect( 'view-notes', '&message=success&type=' . $type . '&user_id=' . $note_data['usuario_id'] );
        }
    }
}

get_header(); ?>

<div class="main">
    <div class="header">
        <h1><?php echo $note_id > 0 ? 'Editar' : 'Inserir' ?> Nota</h1>
        <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>
    </div>
    <form action="" method="post" class="form-two">
        <p>
            <label for="curso_id">Curso <?php echo $required_fields['curso_id']; ?><br>
                <select name="curso_id" id="curso_id">
                    <option value="">Selecione</option>
                    <?php foreach ( $courses as $course ) { ?>
                    <option value="<?php echo $course['ID'] ?>" <?php selected( ( $_POST['curso_id'] ?: $note->data['curso_id'] ), $course['ID'] ); ?>><?php echo $course['nome']; ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>
        <p>
            <label for="usuario_id">Aluno <?php echo $required_fields['usuario_id']; ?><br>
                <select name="usuario_id" id="usuario_id">
                    <option value="">Selecione</option>
                    <?php foreach ( $users as $user ) { ?>
                    <option value="<?php echo $user['ID'] ?>" <?php selected( ( $_POST['usuario_id'] ?: $note->data['usuario_id'] ), $user['ID'] ); ?>><?php echo $user['nome']; ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>
        <p>
            <label for="data_avaliacao">Data <?php echo $required_fields['data_avaliacao']; ?><br>
                <input type="text" name="data_avaliacao" id="data_avaliacao" class="input" value="<?php echo ( $_POST['data_avaliacao'] ?: $note->data['data_avaliacao'] ) ? datefrommysql( ( $_POST['data_avaliacao'] ?: $note->data['data_avaliacao'] ) ) : ''; ?>" size="20">
            </label>
        </p>
        <p>
            <label for="nota">Nota <?php echo $required_fields['nota']; ?><br>
                <input type="text" name="nota" id="nota" class="input" value="<?php echo ( $_POST['nota'] ?: $note->data['nota'] ) ?: ''; ?>" size="3">
            </label>
        </p>
        <p class="submit">
            <button class="button button-primary">Salvar</button>
        </p>
        <input type="hidden" name="action" value="<?php echo $note_id > 0 ? 'update' : 'create'; ?>">
    </form>
</div>

<?php get_footer(); ?>