<?php 

// Message to show?
$has_message = $_GET['message'] ?: null;
$message_type = $_GET['type'] ?: null;

// Remove note
$note_to_remove = (int) $_GET['remove-note'] ?: null;
if ( $note_to_remove > 0 ) {
    if ( $current_user->type >= 1 ) {
        $note_to_remove = $db->query( "DELETE FROM notas WHERE ID = :ID", array( 'ID' => $note_to_remove ) );
        $has_message = 'success';
        $message_type = 'note-removed';
    }
}

if ( $current_user->type == '0' ) {
	$user_id = $current_user->ID;
} else {
	$users = $db->query( "SELECT ID, nome FROM usuarios WHERE tipo = :tipo", array( 'tipo' => '0' ) );
	$user_id = $_GET['user_id'] ?: null;
}

$notes = $db->query( "SELECT * FROM notas WHERE usuario_id = :usuario_id", array( 'usuario_id' => $user_id ) );

get_header(); ?>

<div class="main">
	<div class="header">
		<h1>Notas</h1>
	    <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>

	    <?php 
            if ( $has_message ) {
                switch ( $message_type ) {
                    case 'note-created':
                        $message = 'Nota criada com sucesso!';
                        break;
                    case 'note-updated':
                        $message = 'Nota editada com sucesso!';
                        break;
                    case 'note-removed':
                        $message = 'Nota removida com sucesso!';
                        break;
                    default:
                        $message = 'Sucesso!';
                        break;
                }
                echo "<p class='message {$has_message}'>{$message}</p>";
            }
        ?>
	</div>

	<?php if ( $current_user->type >= 1 ) { ?>
	<form action="" method="get" class="form-two">
        <p>
            <label for="user_id">Selecione o Aluno<br>
                <select name="user_id" id="user_id" onchange="this.form.submit()">
                    <option value="">Selecione</option>
                    <?php foreach ( $users as $user ) { ?>
                    <option value="<?php echo $user['ID'] ?>" <?php selected( $_GET['user_id'], $user['ID'] ); ?>><?php echo $user['nome']; ?></option>
                    <?php } ?>
                </select>
            </label>
            <input type="hidden" name="page" value="view-notes">
        </p>
	</form>
	<?php } ?>

	<table class="table-list">
		<thead>
			<tr>
				<th>Curso</th>
				<th>Data da Avaliação</th>
				<th>Nota</th>
				<?php if ( $current_user->type >= 1 ) { ?>
				<th class="action">Alterar</th>
				<th class="action">Excluir</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $notes as $note ) : ?>
			<tr <?php echo $note['nota'] < 7 ? ' class="red-row"' : ''; ?>>
				<td>
					<?php 
						$course = new Course( $note['curso_id'] );
						echo $course->data['nome'];
					?>
				</td>
				<td><?php echo datefrommysql( $note['data_avaliacao'] ); ?></td>
				<td><strong><?php echo $note['nota']; ?></strong></td>
				<?php if ( $current_user->type >= 1 ) { ?>
				<td class="action"><a href="?page=edit-note&note_id=<?php echo $note['ID']; ?>" class="edit"><i class="fa fa-pencil"></i></a></td>
				<td class="action"><a href="?page=view-notes&user_id=<?php echo $user_id; ?>&remove-note=<?php echo $note['ID']; ?>" class="remove"><i class="fa fa-times"></i></a></td>
				<?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php get_footer(); ?>