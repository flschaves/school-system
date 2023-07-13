<?php 

$course_id = (int) $_GET['course_id'] ?: null;

if ( $course_id > 0 ) {
	$course = new Course( $course_id );
}

$users = $db->query( "SELECT ID, nome FROM usuarios WHERE tipo = :tipo", array( 'tipo' => '1' ) );

$required_fields = array(
	'nome', 'professor'
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

	// If there's no errors, update or insert course
	if ( ! $error ) {
		$course_data = $_POST;

		if ( $course_id > 0 ) {
			$course_data['ID'] = $course_id;
		}

		$new_course_id = insert_course( $course_data );

		if ( $new_course_id > 0 ) {
			$type = 'course-created';
			if ( $course_data['ID'] > 0 ) {
				$type = 'course-updated';
			}
			redirect( 'view-courses', '&message=success&type=' . $type );
		}
	}
}

get_header(); ?>

<div class="main">
	<div class="header">
		<h1><?php echo $course_id > 0 ? 'Editar' : 'Cadastrar' ?> Curso</h1>
	    <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>
	</div>
	<form action="" method="post" class="form-two">
        <p>
            <label for="nome">Nome <?php echo $required_fields['nome']; ?><br>
                <input type="text" name="nome" id="nome" class="input" value="<?php echo ( $_POST['nome'] ?: $course->data['nome'] ) ?: ''; ?>" size="45">
            </label>
        </p>
		<p>
			<label for="professor">Professor <?php echo $required_fields['professor']; ?><br>
				<select name="professor" id="professor">
					<option value="">Selecione</option>
					<?php foreach ( $users as $user ) { ?>
					<option value="<?php echo $user['ID'] ?>" <?php selected( ( $_POST['professor'] ?: $course->data['professor'] ), $user['ID'] ); ?>><?php echo $user['nome']; ?></option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p>
			<label for="numero_sala">Sala<br>
				<input type="text" name="numero_sala" id="numero_sala" class="input" value="<?php echo ( $_POST['numero_sala'] ?: $course->data['numero_sala'] ) ?: ''; ?>" size="20">
			</label>
		</p>
		<p>
			<label for="valor_mensalidade">Mensalidade<br>
				<input type="text" name="valor_mensalidade" id="valor_mensalidade" class="input" value="<?php echo ( $_POST['valor_mensalidade'] ?: $course->data['valor_mensalidade'] ) ?: ''; ?>" size="20">
			</label>
		</p>
		<p>
			<label for="descricao">Descrição<br>
				<textarea name="descricao" id="descricao" class="input"><?php echo ( $_POST['descricao'] ?: $course->data['descricao'] ) ?: ''; ?></textarea>
			</label>
		</p>
		<p class="submit">
			<button class="button button-primary">Salvar</button>
		</p>
		<input type="hidden" name="action" value="<?php echo $course_id > 0 ? 'update' : 'create'; ?>">
	</form>
</div>

<?php get_footer(); ?>